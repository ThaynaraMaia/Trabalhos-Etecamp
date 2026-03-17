// frontend/public/js/folhaGestor.js - Versão Refatorada e Corrigida
(function () {
  "use strict";

  /* ============================
     CONFIGURAÇÃO GLOBAL
     ============================ */
const INJECTED_BACKEND_URL = (typeof window !== 'undefined' && window.BACKEND_URL && window.BACKEND_URL !== '{{BACKEND_URL}}')
  ? window.BACKEND_URL
  : null;

// Se não injetado, tenta detectar backend automaticamente em dev:
// - se estamos em localhost:3000 (frontend dev), assume backend em localhost:3001
// - caso contrário usa window.location.origin
const BACKEND_BASE = INJECTED_BACKEND_URL
  ? String(INJECTED_BACKEND_URL).replace(/\/$/, '')
  : (window.location.hostname === 'localhost' && (window.location.port === '3000' || window.location.port === '')) 
      ? 'http://localhost:3001' 
      : window.location.origin;
const tokenKey = () => localStorage.getItem('token') || sessionStorage.getItem('token');
let tokenCache = null;
function getToken() {
  try {
    // sempre consulta o storage (mantém cache também)
    const t = tokenKey();
    if (t) tokenCache = t;
  } catch (e) {
    tokenCache = null;
  }
  return tokenCache;
}

const CONFIG = {
  BACKEND_BASE: BACKEND_BASE,
  API_BASE: BACKEND_BASE.replace(/\/$/, '') + '/api',
  ENDPOINTS: {
    setores: [
      "/api/gestor/setores",
      "/api/setores/listar",
      "/api/setores/empresa",
      "/gestor/setores",
      "/setores/listar",
      "/setores/empresa"
    ],
    colaboradores: [
      "/api/gestor/colaboradores",
      "/api/colaborador/listar",
      "/gestor/colaboradores",
      "/colaborador/listar"
    ],
    configuracoes: [
      "/api/folha/configuracoes/pagamento",
      "/folha/configuracoes/pagamento"
    ],
    auth: [
      "/api/auth/me",
      "/auth/me",
      "/api/usuario/me",
      "/usuario/me",
      "/me"
    ],
    stats: [
      "/api/gestor/stats",
      "/api/stats/gestor",
      "/gestor/stats",
      "/gestor/estatisticas",
      "/api/gestor/estatisticas"
    ],
    holerites: [
      "/api/holerites/empresa",
      "/holerites/empresa",
      "/api/holerite/empresa"
    ]
  }
};

/* ===== token cache e getter ===== */
let token = null;


  /* ============================
     VARIÁVEIS GLOBAIS
     ============================ */

  let empresaId = null;
  let todosColaboradores = [];
  let colaboradoresAtuais = [];
  let configuracoesPagamento = null;
  let setorAtual = "Todos";

  // Elementos DOM
  const elementos = {
    setoresContainer: document.getElementById("setores-list") || document.getElementById("lista-setores"),
    tbody: document.getElementById("employees-tbody"),
    statProximoPagamento: document.getElementById("stat-proximo-pagamento"),
    statTotal: document.getElementById("stat-total-func"),
    inputBusca: document.getElementById("input-busca") || document.querySelector('.glass-input')
  };

  /* ============================
     UTILITÁRIOS
     ============================ */
  function getToken() {
    if (!token) {
      try {
        token = localStorage.getItem("token") || sessionStorage.getItem("token");
      } catch (e) {
        token = null;
      }
    }
    return token;
  }

  function escapeHtml(str) {
    if (str == null) return "";
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;")
      .replace(/`/g, "&#96;");
  }

  function obterIniciais(nome) {
    if (!nome) return "";
    const partes = nome.trim().split(/\s+/);
    if (partes.length === 1) return (partes[0].charAt(0) || "").toUpperCase();
    return ((partes[0].charAt(0) || "") + (partes[partes.length - 1].charAt(0) || "")).toUpperCase();
  }

  function formatCurrencyBRL(v) {
    try {
      return Number(v).toLocaleString("pt-BR", { style: "currency", currency: "BRL" });
    } catch (e) {
      return "R$ 0,00";
    }
  }

  function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.textContent = message;
    toast.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 15px 20px;
      background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
      color: white;
      border-radius: 8px;
      z-index: 10000;
      animation: slideIn 0.3s ease;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    `;
    document.body.appendChild(toast);
    setTimeout(() => {
      toast.style.animation = 'slideOut 0.3s ease';
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }

  /* ============================
     TENTATIVA DE MÚLTIPLOS ENDPOINTS
     ============================ */
async function tryEndpoints(paths, opts = {}) {
  // opts.fetchOptions pode conter { credentials: 'include' } se precisar
  for (const path of paths) {
    let url;
    if (typeof path === 'string' && path.match(/^https?:\/\//i)) {
      url = path;
    } else if (typeof path === 'string' && path.startsWith('/')) {
      // se o path já começa com /api..., junta com BACKEND_BASE
      url = CONFIG.BACKEND_BASE.replace(/\/$/, '') + path;
    } else {
      url = CONFIG.API_BASE.replace(/\/$/, '') + '/' + String(path).replace(/^\/+/, '');
    }

    try {
      const headers = Object.assign({ 'Accept': 'application/json' }, opts.headers || {});
      const currentToken = getToken();
      if (currentToken) headers['Authorization'] = `Bearer ${currentToken}`;

      const fetchOptions = Object.assign({
        method: opts.method || 'GET',
        headers,
        // Por padrão NÃO forçamos credentials. Se backend usar cookie, passe fetchOptions: { credentials: 'include' }.
        // credentials: 'same-origin'
      }, opts.fetchOptions || {});

      console.log(` Tentando: ${url}`);
      console.log('    -> Authorization header presente?', !!headers['Authorization']);
      const res = await fetch(url, fetchOptions);
      const contentType = (res.headers.get('content-type') || '').toLowerCase();

      if (contentType.includes('application/json')) {
        const data = await res.json();
        if (res.ok) {
          console.log(`Sucesso em: ${url}`, data);
          return { url, res, data };
        } else {
          console.warn(` Status ${res.status} em: ${url}`, data);
          continue;
        }
      } else {
        // resposta não JSON (HTML de erro/404) — pega texto pra debug e continua
        const text = await res.text();
        console.warn(` Resposta não-JSON de ${url} (status=${res.status}). Trecho:`, text.slice(0, 500));
        continue;
      }
    } catch (err) {
      console.warn(` Erro fetch em ${url}:`, err && err.message);
    }
  }

  console.error(' Nenhum endpoint respondeu com sucesso para os paths:', paths);
  return null;
}

function decodeJwtPayload(token) {
  if (!token) return null;
  try {
    const parts = token.split('.');
    if (parts.length < 2) return null;
    const payload = parts[1].replace(/-/g, '+').replace(/_/g, '/');
    const json = decodeURIComponent(atob(payload).split('').map(function(c){
      return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
    return JSON.parse(json);
  } catch (e) {
    return null;
  }
}

  /* ============================
     CÁLCULO DE PRÓXIMO PAGAMENTO
     ============================ */
  function getNextPaymentDateForDay(diaPagamento, fromDate) {
    fromDate = fromDate || new Date();
    const hoje = new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate());
    let ano = hoje.getFullYear();
    let mes = hoje.getMonth();

    if (hoje.getDate() > diaPagamento) {
      mes += 1;
      if (mes > 11) {
        mes = 0;
        ano += 1;
      }
    }

    const ultimoDia = new Date(ano, mes + 1, 0).getDate();
    const diaReal = Math.min(diaPagamento, ultimoDia);
    return new Date(ano, mes, diaReal);
  }

  function calcularProximoPagamentoText() {
    if (!configuracoesPagamento || !configuracoesPagamento.diaPagamento1) {
      return "Não configurado";
    }

    const hoje = new Date();
    const dia1 = parseInt(configuracoesPagamento.diaPagamento1, 10);
    const qtd = parseInt(configuracoesPagamento.qtdPagamentos || "1", 10);
    const candidates = [];

    if (!isNaN(dia1)) candidates.push(getNextPaymentDateForDay(dia1, hoje));

    if (qtd === 2 && configuracoesPagamento.diaPagamento2) {
      const dia2 = parseInt(configuracoesPagamento.diaPagamento2, 10);
      if (!isNaN(dia2)) candidates.push(getNextPaymentDateForDay(dia2, hoje));
    }

    if (candidates.length === 0) return "Não configurado";

    let best = candidates[0];
    for (let i = 1; i < candidates.length; i++) {
      if (candidates[i] < best) best = candidates[i];
    }

    const hojeMid = new Date(hoje.getFullYear(), hoje.getMonth(), hoje.getDate());
    const diffMs = best - hojeMid;
    const diffDias = Math.ceil(diffMs / (1000 * 60 * 60 * 24));

    if (diffDias === 0) return "Hoje";
    if (diffDias === 1) return "1 dia";
    return diffDias + " dias";
  }

  function atualizarProximoPagamento() {
    if (elementos.statProximoPagamento) {
      elementos.statProximoPagamento.textContent = calcularProximoPagamentoText();
    }
  }

  /* ============================
     OBTER EMPRESA_ID
     ============================ */
  async function getEmpresaId(opts = {}) {
  if (empresaId) return empresaId;

  // 1) localStorage
  try {
    const userJSON = localStorage.getItem("user");
    if (userJSON) {
      const u = JSON.parse(userJSON);
      if (u && (u.empresa_id || u.empresaId || u.empresa)) {
        empresaId = u.empresa_id || u.empresaId || u.empresa;
        console.log("Empresa ID do localStorage:", empresaId);
        return empresaId;
      }
    }
  } catch (e) {
    console.warn(" Erro ao ler empresa_id do localStorage:", e);
  }

  // 2) tentar extrair do token JWT
  const tokenNow = getToken();
  if (tokenNow) {
    const claims = decodeJwtPayload(tokenNow);
    if (claims) {
      empresaId = claims.empresa_id || claims.empresaId || claims.empresa || claims.company_id || claims.cid || null;
      if (empresaId) {
        console.log(" Empresa ID extraído do token:", empresaId);
        // salva no localStorage para próxima vez
        try {
          const userData = JSON.parse(localStorage.getItem("user") || "{}");
          userData.empresa_id = empresaId;
          localStorage.setItem("user", JSON.stringify(userData));
        } catch (e) { /* ignore */ }
        return empresaId;
      }
    }
  }

  // 3) chamar /auth/me (várias rotas) — passar credentials se solicitado
  const fetchOpts = {};
  if (opts.credentialsInclude) {
    fetchOpts.fetchOptions = { credentials: 'include' };
  }
  const tryRes = await tryEndpoints(CONFIG.ENDPOINTS.auth, fetchOpts);
  if (tryRes && tryRes.data) {
    const d = tryRes.data;
    const usuario = d.usuario || d.user || d.data || d;
    empresaId = usuario && (usuario.empresa_id || usuario.empresaId || usuario.empresa || null);
    if (empresaId) {
      console.log(" Empresa ID do /auth/me:", empresaId);
      try {
        const userData = JSON.parse(localStorage.getItem("user") || "{}");
        userData.empresa_id = empresaId;
        localStorage.setItem("user", JSON.stringify(userData));
      } catch (e) { /* ignore */ }
      return empresaId;
    }
  }

  console.error(" Não foi possível obter empresa_id por nenhum método");
  return null;
}


  /* ============================
     CARREGAR CONFIGURAÇÕES PAGAMENTO
     ============================ */
  async function carregarConfiguracoesPagamento() {
    const tryRes = await tryEndpoints(CONFIG.ENDPOINTS.configuracoes);
    if (tryRes && tryRes.data) {
      configuracoesPagamento = tryRes.data.data || tryRes.data;
    } else {
      configuracoesPagamento = {
        qtdPagamentos: '1',
        diaPagamento1: '5',
        diaPagamento2: ''
      };
    }
    atualizarProximoPagamento();
    console.log(" Configurações carregadas:", configuracoesPagamento ? "" : "");
  }

  /* ============================
     CARREGAR SETORES
     ============================ */
  async function carregarSetores() {
    if (!elementos.setoresContainer) {
      console.warn(" Container de setores não encontrado");
      return;
    }

    const empId = await getEmpresaId();
    if (!empId) {
      console.warn(" Empresa ID não disponível para carregar setores");
      elementos.setoresContainer.innerHTML = "<li><span class='text-muted'>Erro: Empresa não identificada</span></li>";
      return;
    }

    const tryRes = await tryEndpoints(CONFIG.ENDPOINTS.setores);

    if (!tryRes || !tryRes.data) {
      console.error(" Erro ao carregar setores");
      elementos.setoresContainer.innerHTML = "<li><span class='text-muted'>Erro ao carregar setores</span></li>";
      return;
    }

    const setores = Array.isArray(tryRes.data)
      ? tryRes.data
      : (tryRes.data.setores || tryRes.data.data || []);

    console.log(" Setores encontrados:", setores.length);
    renderSetores(setores);
  }

  function renderSetores(setores) {
    if (!elementos.setoresContainer) return;

    elementos.setoresContainer.innerHTML = "";

    // Adiciona "Todos"
    const liTodos = document.createElement("li");
    const activeTodos = setorAtual === "Todos" ? "sidebar-link-active" : "sidebar-link-default";
    liTodos.innerHTML = `<a href="#" data-setor="Todos" class="sidebar-link ${activeTodos}"><i class="bi bi-people"></i> Todos</a>`;
    elementos.setoresContainer.appendChild(liTodos);

    // Adiciona setores
    setores.forEach(s => {
      const nome = s.nome_setor || s.nome || String(s);
      const li = document.createElement("li");
      const isActive = setorAtual === nome ? "sidebar-link-active" : "sidebar-link-default";
      li.innerHTML = `<a href="#" data-setor="${escapeHtml(nome)}" class="sidebar-link ${isActive}"><i class="bi bi-diagram-3"></i> ${escapeHtml(nome)}</a>`;
      elementos.setoresContainer.appendChild(li);
    });

    // Event listeners
    elementos.setoresContainer.querySelectorAll('a[data-setor]').forEach(a => {
      a.addEventListener("click", async (ev) => {
        ev.preventDefault();
        
        // Atualiza classes
        elementos.setoresContainer.querySelectorAll('.sidebar-link').forEach(link => {
          link.classList.remove('sidebar-link-active');
          link.classList.add('sidebar-link-default');
        });
        a.classList.remove('sidebar-link-default');
        a.classList.add('sidebar-link-active');

        const setor = a.getAttribute("data-setor");
        setorAtual = setor || "Todos";
        await carregarColaboradores(setor === "Todos" ? null : setor);
        atualizarProximoPagamento();
      });
    });

    console.log(" Setores renderizados:", setores.length);
  }

  /* ============================
     CARREGAR COLABORADORES
     ============================ */
  async function carregarColaboradores(setorFiltro) {
    if (!elementos.tbody) {
      console.warn("Tbody não encontrado");
      return;
    }

    const empId = await getEmpresaId();

    if (!empId) {
      console.error(" Empresa não encontrada");
      elementos.tbody.innerHTML = "<tr><td colspan='6' class='text-center'> Empresa não configurada. Faça login novamente.</td></tr>";
      showToast('Erro: Empresa não identificada', 'error');
      return;
    }

    console.log(" Carregando colaboradores - Empresa:", empId, "Setor:", setorFiltro || "Todos");

    // Monta query params
    let queryParams = `empresa_id=${encodeURIComponent(empId)}`;
    if (setorFiltro) {
      queryParams += `&setor=${encodeURIComponent(setorFiltro)}`;
    }

    // Adiciona query params aos paths
    const paths = CONFIG.ENDPOINTS.colaboradores.map(path => {
      const separator = path.includes('?') ? '&' : '?';
      return `${path}${separator}${queryParams}`;
    });

    const tryRes = await tryEndpoints(paths);

    if (!tryRes || !tryRes.data) {
      console.error(" Nenhum endpoint de colaboradores respondeu");
      elementos.tbody.innerHTML = "<tr><td colspan='6' class='text-center'> Erro ao carregar colaboradores</td></tr>";
      showToast('Erro ao carregar colaboradores', 'error');
      return;
    }

    let list = Array.isArray(tryRes.data)
      ? tryRes.data
      : (tryRes.data.colaboradores || tryRes.data.data || tryRes.data.rows || []);

    // Normaliza dados
    list = list.map(c => {
      c = c || {};

      // Normaliza salário
      if (typeof c.salario === "string") {
        const parsed = parseFloat(c.salario.replace(",", "."));
        c.salario = isNaN(parsed) ? 0 : parsed;
      } else if (typeof c.salario !== "number") {
        c.salario = c.salario_bruto || 0;
      }

      // Normaliza campos
      c.nome = c.nome || c.nome_completo || c.name || "Nome não informado";
      c.cargo = c.cargo || c.funcao || c.role || "Cargo não informado";
      c.setor = c.setor || c.nome_setor || "Setor não informado";
      c.numero_registro = c.numero_registro || c.matricula || c.registro || "";
      c.status = c.status || c.situacao || "Ativo";
      c.id = c.id || c.usuario_id || c.user_id;

      return c;
    });

    todosColaboradores = list.slice();
    colaboradoresAtuais = list.slice();

    console.log(` Colaboradores carregados: ${list.length}`);
    renderizarColaboradores(colaboradoresAtuais);

    // Atualiza estatística total
    if (elementos.statTotal) {
      elementos.statTotal.textContent = list.length;
    }
  }

  /* ============================
     RENDERIZAR COLABORADORES
     ============================ */
  function renderizarColaboradores(list) {
    list = list || colaboradoresAtuais;
    if (!elementos.tbody) return;

    elementos.tbody.innerHTML = "";

    if (!list || list.length === 0) {
      elementos.tbody.innerHTML = `
        <tr>
          <td colspan='6' class='text-center' style='padding: 40px; color: rgba(255,255,255,0.6);'>
            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
            <p style="margin-top: 10px;">Nenhum colaborador encontrado</p>
          </td>
        </tr>
      `;
      return;
    }

    // Helper para detectar campo de avatar
    function detectAvatarField(obj) {
      if (!obj || typeof obj !== 'object') return null;
      const prefer = ['foto', 'avatar', 'avatar_url', 'imagem', 'imagem_url', 'photo', 'picture'];
      
      for (const k of prefer) {
        if (obj[k] && typeof obj[k] === 'string' && obj[k].trim() !== '') {
          return { key: k, val: obj[k] };
        }
      }
      return null;
    }

    function buildAvatarSrc(val) {
      if (!val) return null;
      if (/^data:image\//i.test(val)) return val;
      if (/^https?:\/\//i.test(val)) return val;

      if (/^[^\/\\]+\.(jpe?g|png|gif|webp|svg)$/i.test(val.trim())) {
        return CONFIG.BACKEND_BASE.replace(/\/$/, '') + '/uploads/' + val.replace(/^\//, '');
      }
      if (val.startsWith('/')) return CONFIG.BACKEND_BASE.replace(/\/$/, '') + val;
      return CONFIG.BACKEND_BASE.replace(/\/$/, '') + '/' + val;
    }

    async function fetchImageObjectUrl(src) {
      if (!src) return null;
      try {
        const headers = {};
        if (getToken()) headers['Authorization'] = `Bearer ${getToken()}`;
        
        const res = await fetch(src, { headers });
        if (!res.ok) throw new Error("fetch failed " + res.status);
        const blob = await res.blob();
        if (!blob || !blob.type || blob.type.indexOf("image/") !== 0) throw new Error("not image blob");
        return URL.createObjectURL(blob);
      } catch (e) {
        return null;
      }
    }

    // Agrupa por setor
    const grupos = {};
    list.forEach(c => {
      const setor = c.setor || "Sem setor";
      if (!grupos[setor]) grupos[setor] = [];
      grupos[setor].push(c);
    });

    const frag = document.createDocumentFragment();
    const setoresKeys = Object.keys(grupos).sort();

    setoresKeys.forEach(setor => {
      grupos[setor].forEach(c => {
        const row = document.createElement("tr");
        const iniciais = obterIniciais(c.nome);
        const salarioTexto = c.salario > 0 ? formatCurrencyBRL(c.salario) : "R$ 0,00";

        // Detecta avatar
        const detected = detectAvatarField(c);
        let avatarHtml = "";

        if (detected && detected.val) {
          const src = buildAvatarSrc(detected.val);
          avatarHtml = `
            <div class='employee-avatar-wrapper'>
              <img data-src='${escapeHtml(src)}' alt='${escapeHtml(c.nome)}' class='employee-avatar-img' style='display:none;'/>
              <div class='employee-avatar-initials' style='display:flex;'>${escapeHtml(iniciais)}</div>
            </div>
          `;
        } else {
          avatarHtml = `<div class='employee-avatar'>${escapeHtml(iniciais)}</div>`;
        }

        // Determina classe de status
        const statusLower = c.status.toLowerCase();
        let statusClass = 'status-active';
        if (statusLower.includes('férias') || statusLower.includes('ferias')) {
          statusClass = 'status-vacation';
        } else if (statusLower.includes('inativo') || statusLower.includes('desligado')) {
          statusClass = 'status-inativo';
        }

        const holeriteUrl = `${CONFIG.BACKEND_BASE}/gestor/folhapaga/${encodeURIComponent(c.id || "")}`;

        row.innerHTML = `
          <td>
            <div class='d-flex align-items-center gap-3'>
              ${avatarHtml}
              <div>
                <span style='font-weight:500;'>${escapeHtml(c.nome)}</span><br>
                <small class='text-muted'>${escapeHtml(c.cargo)}</small>
              </div>
            </div>
          </td>
          <td>${escapeHtml(c.numero_registro)}</td>
          <td>${escapeHtml(setor)}</td>
          <td>${salarioTexto}</td>
          <td><span class='status-badge ${statusClass}'>${escapeHtml(c.status)}</span></td>
          <td>
            <div class='btn-group' role='group'>
              
              <a class='action-icon btn-edit' href='${escapeHtml(holeriteUrl)}' data-id='${escapeHtml(String(c.id))}' title='Editar Holerite'>
                <i class='bi bi-pencil'></i>
              </a>
            </div>
          </td>
        `;

        frag.appendChild(row);
      });
    });

    elementos.tbody.appendChild(frag);

    // Hydrate images
    (async function hydrateImages() {
      const imgs = Array.from(elementos.tbody.querySelectorAll("img[data-src]"));
      for (const img of imgs) {
        const src = img.getAttribute("data-src");
        if (!src) continue;

        const objectUrl = await fetchImageObjectUrl(src);
        if (objectUrl) {
          img.src = objectUrl;
          img.style.display = "block";
          const initials = img.parentNode.querySelector('.employee-avatar-initials');
          if (initials) initials.style.display = "none";
          continue;
        }

        // Fallback direto
        img.src = src;
        img.style.display = "block";
        img.onerror = function () {
          this.style.display = "none";
          const e = this.parentNode.querySelector('.employee-avatar-initials');
          if (e) e.style.display = "flex";
        };
      }
    })();

    // Event listeners para botões
    elementos.tbody.querySelectorAll(".btn-view").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-id");
        if (id) window.location.href = `/gestor/colaborador/${encodeURIComponent(id)}`;
      });
    });
  }

  /* ============================
     BUSCA LOCAL
     ============================ */
  function configurarBusca() {
    if (!elementos.inputBusca) {
      console.warn(" Input de busca não encontrado");
      return;
    }

    elementos.inputBusca.addEventListener('input', (e) => {
      const termo = e.target.value.toLowerCase().trim();

      if (!termo) {
        renderizarColaboradores(todosColaboradores);
        return;
      }

      const filtrados = todosColaboradores.filter(c => {
        const nome = (c.nome || "").toLowerCase();
        const cargo = (c.cargo || "").toLowerCase();
        const setor = (c.setor || "").toLowerCase();
        const registro = (c.numero_registro || "").toLowerCase();
        return nome.includes(termo) || cargo.includes(termo) || setor.includes(termo) || registro.includes(termo);
      });

      renderizarColaboradores(filtrados);
    });

    console.log(" Busca configurada");
  }

  /* ============================
     MODAL DE HOLERITES
     ============================ */
  function criarModalHolerites() {
    if (document.getElementById('modal-holerites')) return;

    const modalHTML = `
      <div id="modal-holerites" class="modal-overlay" style="display: none;">
        <div class="modal-content" style="max-width: 900px; max-height: 80vh; overflow-y: auto;">
          <div class="modal-header">
            <h3><i class="bi bi-file-earmark-text"></i> Últimos Holerites</h3>
            <button class="btn-fechar-modal" onclick="window.fecharModalHolerites()">&times;</button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Filtrar por Setor:</label>
              <select id="filtro-setor-holerite" class="glass-input">
                <option value="Todos">Todos os Setores</option>
              </select>
            </div>
            <div id="lista-holerites">
              <div class="text-center" style="padding: 40px; color: rgba(255,255,255,0.6);">
                <div class="spinner-border" role="status"></div>
                <p style="margin-top: 15px;">Carregando holerites...</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);
    console.log(" Modal de holerites criado");
  }

  async function abrirModalHolerites() {
    console.log(" Abrindo modal de holerites");
    criarModalHolerites();
    const modal = document.getElementById('modal-holerites');
    modal.style.display = 'flex';

    await carregarSetoresFiltro();
    await carregarHolerites();

    const filtroSetor = document.getElementById('filtro-setor-holerite');
    if (filtroSetor) {
      filtroSetor.removeEventListener('change', carregarHolerites);
      filtroSetor.addEventListener('change', carregarHolerites);
    }
  }

  function fecharModalHolerites() {
    const modal = document.getElementById('modal-holerites');
    if (modal) modal.style.display = 'none';
  }

  async function carregarSetoresFiltro() {
    const tryRes = await tryEndpoints(CONFIG.ENDPOINTS.setores);
    const setores = tryRes && tryRes.data
      ? (Array.isArray(tryRes.data) ? tryRes.data : (tryRes.data.data || tryRes.data.setores || []))
      : [];

    const select = document.getElementById('filtro-setor-holerite');
    if (!select) return;

    select.innerHTML = '<option value="Todos">Todos os Setores</option>';
    setores.forEach(setor => {
      const option = document.createElement('option');
      const nome = setor.nome_setor || setor.nome;
      option.value = nome;
      option.textContent = nome;
      select.appendChild(option);
    });

    console.log(" Setores do filtro carregados");
  }

  async function carregarHolerites() {
    const setorFiltro = document.getElementById('filtro-setor-holerite')?.value || 'Todos';
    console.log(" Carregando holerites para setor:", setorFiltro);
    
    // Recarrega colaboradores do setor selecionado
    await carregarColaboradores(setorFiltro === 'Todos' ? null : setorFiltro);
    
    const container = document.getElementById('lista-holerites');
    if (!container) return;

    renderizarListaHolerites(colaboradoresAtuais, container);
  }

  function renderizarListaHolerites(colaboradores, container) {
    if (!colaboradores || colaboradores.length === 0) {
      container.innerHTML = `
        <div class="text-center" style="padding: 40px; color: rgba(255,255,255,0.6);">
          <i class="bi bi-inbox" style="font-size: 3rem;"></i>
          <p style="margin-top: 15px;">Nenhum holerite encontrado</p>
        </div>
      `;
      return;
    }

    container.innerHTML = '';

    colaboradores.forEach(colab => {
      const holeriteCard = document.createElement('div');
      holeriteCard.className = 'holerite-card';
      holeriteCard.style.cssText = `
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s;
        cursor: pointer;
      
      `;

      holeriteCard.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
          <div >
            <h5 class="text-white mb-1">${escapeHtml(colab.nome)}</h5>
           <p class="mb-0" style="font-size: 0.875rem; color: white !important;">

              <i class="bi bi-briefcase" ></i > ${escapeHtml(colab.cargo)}
              | <i class="bi bi-building"></i> ${escapeHtml(colab.setor)}
            </p>
          </div>
          <div class="d-flex gap-2">
            <button class="glass-button" onclick="window.visualizarHolerite(${colab.id})" title="Visualizar Holerite">
              <i class="bi bi-eye"></i> Ver
            </button>
           
          </div>
        </div>
        <div class="mt-3" style="font-size: 0.875rem; color: rgba(255,255,255,0.7);">
          <i class="bi bi-calendar3"></i> Último holerite: ${new Date().toLocaleDateString('pt-BR')}
        </div>
      `;

      holeriteCard.addEventListener('mouseenter', () => {
        holeriteCard.style.background = 'rgba(255,255,255,0.08)';
        holeriteCard.style.borderColor = 'rgba(102,126,234,0.3)';
      });
      holeriteCard.addEventListener('mouseleave', () => {
        holeriteCard.style.background = 'rgba(255,255,255,0.05)';
        holeriteCard.style.borderColor = 'rgba(255,255,255,0.1)';
      });

      container.appendChild(holeriteCard);
    });

    console.log(" Holerites renderizados:", colaboradores.length);
  }

  /* ============================
     FUNÇÕES DE AÇÃO
     ============================ */
  function visualizarColaborador(id) {
    window.location.href = `/gestor/colaborador/${id}`;
  }

  function abrirHolerite(id) {
    window.location.href = `/gestor/folhapaga/${id}`;
  }

  function visualizarHolerite(id) {
    window.location.href = `/gestor/folhapaga/${id}`;
  }

  async function baixarHolerite(id) {
    try {
      showToast('Preparando download...', 'info');
      
      // Tenta buscar o holerite via API
      const paths = CONFIG.ENDPOINTS.holerites.map(p => `${p}/${id}`);
      const tryRes = await tryEndpoints(paths);
      
      if (tryRes && tryRes.data) {
        // TODO: Implementar download real do PDF quando disponível
        showToast('Funcionalidade de download em desenvolvimento', 'info');
      } else {
        showToast('Holerite não encontrado', 'error');
      }
    } catch (err) {
      console.error('Erro ao baixar holerite:', err);
      showToast('Erro ao baixar holerite', 'error');
    }
  }

  async function excluirColaborador(id) {
    if (!confirm('Confirma exclusão deste colaborador?')) return;

    try {
      const response = await fetch(`${CONFIG.API_BASE}/usuario/${id}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${getToken()}`,
          'Content-Type': 'application/json'
        }
      });

      const json = await response.json();

      if (json && (json.success || json.affectedRows)) {
        showToast('Colaborador excluído com sucesso', 'success');
        await carregarColaboradores(setorAtual === 'Todos' ? null : setorAtual);
      } else {
        showToast('Não foi possível excluir o colaborador', 'error');
        console.warn('Resposta exclusão:', json);
      }
    } catch (err) {
      console.error('Erro ao excluir:', err);
      showToast('Erro ao excluir colaborador', 'error');
    }
  }

  /* ============================
     CARREGAR ESTATÍSTICAS (DASHBOARD)
     ============================ */
/* ============================
     CARREGAR ESTATÍSTICAS (DASHBOARD)
     ============================ */
async function carregarEstatisticas() {
  // Use os IDs corretos do seu HTML
  const statElements = {
    totalFunc: document.getElementById('stat-total-func'),
    folhaTotal: document.getElementById('stat-folha-mensal'), // ID correto do HTML
    mediaSalarial: document.getElementById('stat-media-salarial'),
    proximoPagamento: document.getElementById('stat-proximo-pagamento')
  };

  // Se não encontrar nenhum elemento, não precisa carregar
  if (!statElements.totalFunc && !statElements.folhaTotal && !statElements.mediaSalarial) {
    console.log("ℹ Elementos de estatística não encontrados - pulando carregamento");
    return;
  }

  try {
    const empId = await getEmpresaId();
    if (!empId) {
      console.warn(" Não é possível carregar estatísticas sem empresa_id");
      return;
    }

    console.log(" Carregando estatísticas para empresa:", empId);

    // Atualize os endpoints para incluir o caminho correto
    const statsEndpoints = [
      "/api/gestor/dashboard/stats",
      "/api/folha/dashboard/stats", 
      "/api/stats/dashboard",
      "/gestor/dashboard/stats",
      "/folha/dashboard/stats"
    ];

    const tryRes = await tryEndpoints(statsEndpoints);
    
    if (tryRes && tryRes.data) {
      const stats = tryRes.data.data || tryRes.data;
      console.log(" Dados de estatísticas recebidos:", stats);
      
      // Atualizar total de funcionários
      if (statElements.totalFunc) {
        const total = stats.total || stats.total_funcionarios || 0;
        statElements.totalFunc.textContent = total;
        console.log(" Total de funcionários:", total);
      }
      
      // Atualizar folha salarial total - CORREÇÃO PRINCIPAL
      if (statElements.folhaTotal) {
        let folhaValue = stats.total_salarios || stats.folha_total || 0;
        
        // Se for string formatada, use diretamente, senão formate
        if (stats.total_salarios_formatted) {
          statElements.folhaTotal.textContent = stats.total_salarios_formatted;
        } else {
          statElements.folhaTotal.textContent = formatCurrencyBRL(folhaValue);
        }
        console.log(" Folha salarial total:", folhaValue);
      }
      
      // Atualizar média salarial
      if (statElements.mediaSalarial) {
        let mediaValue = stats.media_salarios || stats.media_salarial || 0;
        
        if (stats.media_salarios_formatted) {
          statElements.mediaSalarial.textContent = stats.media_salarios_formatted;
        } else {
          statElements.mediaSalarial.textContent = formatCurrencyBRL(mediaValue);
        }
        console.log(" Média salarial:", mediaValue);
      }

      // Atualizar próximo pagamento se existir no elemento
      if (statElements.proximoPagamento && stats.proximo_pagamento) {
        statElements.proximoPagamento.textContent = stats.proximo_pagamento;
      }
      
    } else {
      console.warn("Não foi possível carregar estatísticas da API, usando cálculo local");
      
      // Fallback: calcular estatísticas localmente com base nos colaboradores
      await calcularEstatisticasLocais();
    }
  } catch (err) {
    console.error(" Erro ao carregar estatísticas:", err);
    // Fallback em caso de erro
    await calcularEstatisticasLocais();
  }
}

/* ============================
     CALCULAR ESTATÍSTICAS LOCAIS (FALLBACK)
     ============================ */
async function calcularEstatisticasLocais() {
  console.log(" Calculando estatísticas localmente...");
  
  const statElements = {
    totalFunc: document.getElementById('stat-total-func'),
    folhaTotal: document.getElementById('stat-folha-mensal'),
    mediaSalarial: document.getElementById('stat-media-salarial')
  };

  // Aguarda carregar colaboradores se necessário
  if (todosColaboradores.length === 0) {
    await carregarColaboradores();
  }

  const colaboradoresComSalario = todosColaboradores.filter(c => c.salario > 0);
  const total = colaboradoresComSalario.length;
  const totalSalarios = colaboradoresComSalario.reduce((sum, c) => sum + (c.salario || 0), 0);
  const mediaSalarios = total > 0 ? totalSalarios / total : 0;

  console.log(" Estatísticas locais calculadas:", {
    total,
    totalSalarios, 
    mediaSalarios
  });

  // Atualizar elementos
  if (statElements.totalFunc) {
    statElements.totalFunc.textContent = total;
  }
  
  if (statElements.folhaTotal) {
    statElements.folhaTotal.textContent = formatCurrencyBRL(totalSalarios);
  }
  
  if (statElements.mediaSalarial) {
    statElements.mediaSalarial.textContent = formatCurrencyBRL(mediaSalarios);
  }
}

/* ============================
     MELHORAR A FUNÇÃO formatCurrencyBRL
     ============================ */
function formatCurrencyBRL(v) {
  try {
    // Converte para número se for string
    let value = v;
    if (typeof v === 'string') {
      // Remove caracteres não numéricos exceto ponto e vírgula
      value = v.replace(/[^\d,.-]/g, '').replace(',', '.');
    }
    
    const numberValue = parseFloat(value);
    
    if (isNaN(numberValue)) {
      console.warn(" Valor inválido para formatação monetária:", v);
      return "R$ 0,00";
    }
    
    return numberValue.toLocaleString("pt-BR", { 
      style: "currency", 
      currency: "BRL",
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
  } catch (e) {
    console.error(" Erro na formatação monetária:", e, "Valor:", v);
    return "R$ 0,00";
  }
}
  /* ============================
     CONFIGURAR LINKS
     ============================ */
  function configurarLinkBeneficios() {
    const linkBeneficios = document.querySelector('a[href="#beneficios"]');
    if (linkBeneficios) {
      linkBeneficios.addEventListener('click', (e) => {
        e.preventDefault();
        window.location.href = '/gestor/beneficios';
      });
      console.log(" Link benefícios configurado");
    }
  }

  function configurarLinkHolerite() {
    // Procura por múltiplos seletores possíveis
    const selectors = [
      'a[href="#holerite"]',
      'a[href="#holerites"]',
      '.nav-link[href="#holerite"]',
      '.sidebar-link[href="#holerite"]'
    ];

    let linkHolerite = null;
    for (const selector of selectors) {
      linkHolerite = document.querySelector(selector);
      if (linkHolerite) break;
    }

    if (linkHolerite) {
      console.log(' Link holerite encontrado');
      linkHolerite.addEventListener('click', (e) => {
        e.preventDefault();
        console.log(' Clique no link de holerites detectado');
        abrirModalHolerites();
      });
    } else {
      console.warn(' Link holerite não encontrado, tentando alternativa por texto');
      // Busca por texto
      const allLinks = document.querySelectorAll('a');
      allLinks.forEach(link => {
        const texto = link.textContent.toLowerCase();
        if (texto.includes('holerite')) {
          const href = link.getAttribute('href');
          if (href === '#holerite' || href === '#holerites') {
            link.addEventListener('click', (e) => {
              e.preventDefault();
              abrirModalHolerites();
            });
            console.log(' Link alternativo de holerite configurado');
          }
        }
      });
    }
  }

  /* ============================
     ESTILOS ADICIONAIS
     ============================ */
  function injetarEstilosAdicionais() {
    if (document.getElementById('folha-gestor-styles')) return;

    const style = document.createElement('style');
    style.id = 'folha-gestor-styles';
    style.textContent = `
      /* Employee Avatar Styles */
      .employee-avatar,
      .employee-avatar-initials {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
        flex-shrink: 0;
      }

      .employee-avatar-wrapper {
        position: relative;
        width: 40px;
        height: 40px;
      }

      .employee-avatar-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
      }

      /* Status Badges */
      .status-badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
      }

      .status-active,
      .status-ativo {
        background: rgba(16, 185, 129, 0.2);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
      }

      .status-vacation,
      .status-ferias,
      .status-férias {
        background: rgba(251, 191, 36, 0.2);
        color: #fbbf24;
        border: 1px solid rgba(251, 191, 36, 0.3);
      }

      .status-inativo {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
      }

      /* Action Buttons */
      .action-icon {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.9);
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
      }

      .action-icon:hover {
        background: rgba(255,255,255,0.1);
        border-color: rgba(102,126,234,0.5);
        transform: translateY(-2px);
        color: #667eea;
      }

      .btn-group {
        display: flex;
        gap: 8px;
      }

      /* Modal Styles */
      .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        animation: fadeIn 0.3s ease;
      }

      .modal-content {
        background: linear-gradient(135deg, #1a1d35 0%, #2b2f48 100%);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        width: 90%;
        max-width: 800px;
        animation: slideUp 0.3s ease;
      }

      .modal-header {
        padding: 24px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .modal-header h3 {
        margin: 0;
        color: white;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .btn-fechar-modal {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        font-size: 1.5rem;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
      }

      .btn-fechar-modal:hover {
        background: rgba(239, 68, 68, 0.2);
        border-color: rgba(239, 68, 68, 0.5);
        transform: rotate(90deg);
      }

      .modal-body {
        padding: 24px;
        max-height: 60vh;
        overflow-y: auto;
      }

      /* Glass Button */
      .glass-button {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
      }

      .glass-button:hover {
        background: rgba(102,126,234,0.2);
        border-color: rgba(102,126,234,0.5);
        transform: translateY(-2px);
      }

      /* Glass Input */
      .glass-input {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: white;
        padding: 10px 16px;
        border-radius: 8px;
        width: 100%;
        transition: all 0.3s;
      }

      .glass-input:focus {
        outline: none;
        border-color: rgba(102,126,234,0.5);
        background: rgba(255,255,255,0.08);
      }

      .glass-input option {
        background: #1a1d35;
        color: white;
      }

      /* Spinner */
      .spinner-border {
        display: inline-block;
        width: 2rem;
        height: 2rem;
        border: 3px solid rgba(255,255,255,0.2);
        border-top-color: #667eea;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
      }

      /* Sidebar Links */
      .sidebar-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 8px;
        color: rgba(255,255,255,0.7);
        text-decoration: none;
        transition: all 0.3s;
      }

      .sidebar-link:hover {
        background: rgba(255,255,255,0.05);
        color: white;
      }

      .sidebar-link-active {
        background: rgba(102,126,234,0.2);
        color: white;
        border-left: 3px solid #667eea;
      }

      .sidebar-link-default {
        border-left: 3px solid transparent;
      }

      /* Holerite Card */
      .holerite-card:hover {
        box-shadow: 0 8px 24px rgba(102,126,234,0.2);
      }

      /* Toast Animations */
      @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
      }

      @keyframes slideUp {
        from {
          transform: translateY(30px);
          opacity: 0;
        }
        to {
          transform: translateY(0);
          opacity: 1;
        }
      }

      @keyframes slideIn {
        from {
          transform: translateX(100%);
          opacity: 0;
        }
        to {
          transform: translateX(0);
          opacity: 1;
        }
      }

      @keyframes slideOut {
        from {
          transform: translateX(0);
          opacity: 1;
        }
        to {
          transform: translateX(100%);
          opacity: 0;
        }
      }

      @keyframes spin {
        to { transform: rotate(360deg); }
      }

      /* Form Label */
      .form-label {
        display: block;
        margin-bottom: 8px;
        color: rgba(255,255,255,0.8);
        font-weight: 500;
      }

      /* Utilities */
      .mb-1 { margin-bottom: 0.25rem; }
      .mb-3 { margin-bottom: 1rem; }
      .mt-3 { margin-top: 1rem; }
      .d-flex { display: flex; }
      .gap-2 { gap: 0.5rem; }
      .gap-3 { gap: 1rem; }
      .align-items-center { align-items: center; }
      .justify-content-between { justify-content: space-between; }
      .text-center { text-align: center; }
      .text-white { color: white; }
      .text-muted { color: rgba(255,255,255,0.6); }

      /* Responsive */
      @media (max-width: 768px) {
        .modal-content {
          width: 95%;
          max-height: 90vh;
        }

        .employee-avatar,
        .employee-avatar-initials,
        .employee-avatar-img {
          width: 32px;
          height: 32px;
          font-size: 0.75rem;
        }

        .action-icon {
          padding: 6px 10px;
          font-size: 0.875rem;
        }

        .glass-button {
          padding: 6px 12px;
          font-size: 0.8rem;
        }
      }
    `;
    document.head.appendChild(style);
    console.log(" Estilos injetados");
  }

  /* ============================
     INICIALIZAÇÃO
     ============================ */
/* ============================
     INICIALIZAÇÃO
     ============================ */
async function init() {
  console.log(' Inicializando folhaGestor.js Refatorado v2.0');

  try {
    // Injeta estilos primeiro
    injetarEstilosAdicionais();

    // Carrega configurações básicas primeiro
    await carregarConfiguracoesPagamento();
    
    // Carrega setores e colaboradores em sequência
    await carregarSetores();
    await carregarColaboradores();

    // AGORA carrega estatísticas (depois de ter os dados dos colaboradores)
    await carregarEstatisticas();

    // Configura funcionalidades
    configurarBusca();
    configurarLinkBeneficios();
    configurarLinkHolerite();

    console.log(' folhaGestor.js inicializado com sucesso');
  } catch (err) {
    console.error(' Erro na inicialização:', err);
    showToast('Erro ao inicializar sistema', 'error');
  }
}
  /* ============================
     EXPOR FUNÇÕES GLOBAIS
     ============================ */
  window.todosColaboradores = todosColaboradores;
  window.colaboradoresAtuais = colaboradoresAtuais;
  window.visualizarColaborador = visualizarColaborador;
  window.abrirHolerite = abrirHolerite;
  window.visualizarHolerite = visualizarHolerite;
  window.baixarHolerite = baixarHolerite;
  window.excluirColaborador = excluirColaborador;
  window.abrirModalHolerites = abrirModalHolerites;
  window.fecharModalHolerites = fecharModalHolerites;
  window.carregarColaboradores = carregarColaboradores;
  window.renderizarColaboradores = renderizarColaboradores;
  window.carregarEstatisticas = carregarEstatisticas;

  /* ============================
     AUTO-INICIALIZAÇÃO
     ============================ */
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();