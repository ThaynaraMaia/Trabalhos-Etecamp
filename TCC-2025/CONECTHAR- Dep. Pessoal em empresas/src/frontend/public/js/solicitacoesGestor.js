
(() => {
  'use strict';

  const CONFIG = {
    API_BASE: window.BACKEND_URL || 'http://localhost:3001',
    ENDPOINTS: {
      SOLICITACOES_GESTOR: '/api/solicitacoes/gestor',
      SOLICITACOES_ALT: '/api/gestor/solicitacoes',
      SOLICITACAO_DETALHE: '/api/solicitacoes',
      SOLICITACAO_STATUS: '/api/solicitacoes',
      SETORES: '/api/setores/empresa',
      COLABORADORES: '/api/gestor/colaboradores',
      AUTH_ME: '/api/gestor/me'
    },
    VIEW_MODES: {
      CARDS: 'cards',
      LIST: 'list'
    },
    STATUS_MAP: {
      'pendente': { label: 'Pendente', class: 'warning', icon: '' },
      'aprovada': { label: 'Aprovada', class: 'success', icon: '' },
      'reprovada': { label: 'Reprovada', class: 'danger', icon: '' },
      'em_analise': { label: 'Em Análise', class: 'info', icon: '' }
    },
    TIPO_LABELS: {
      'ferias': 'Férias',
      'alteracao_dados': 'Alteração de Dados',
      'consulta_banco_horas': 'Banco de Horas',
      'banco_horas': 'Banco de Horas',
      'reajuste_salarial': 'Reajuste Salarial',
      'desligamento': 'Desligamento',
      'reembolso': 'Reembolso',
      'outros': 'Outros'
    },
    DEFAULT_PHOTO: '/img/fundofoda.png'
  };


  const STATE = {
    currentUser: null,
    empresaId: null,
    cnpjGestor: null,
    allSolicitacoes: [],
    filteredSolicitacoes: [],
    setores: [],
    colaboradores: [],
    filters: {
      status: 'all',
      setor: null,
      search: ''
    },
    viewMode: 'cards',
    isLoading: false,
    currentSolicitacaoId: null
  };


  const DOM = {
    setoresWrapper: document.getElementById('setoresWrapper'),
    buscaInput: document.getElementById('buscaSolicitacoes'),
    filtrosBtns: document.querySelectorAll('.filtro-btn'),
    btnRefresh: document.getElementById('btnRefreshSolicitacoes'),
    setorFiltersWrap: document.getElementById('setorFilters'),
    btnToggleView: document.getElementById('btnToggleView'),
    btnFilterTodos: document.getElementById('btnFilterTodos'),
    
    // Modal de Solicitação
    modalSolicitacao: document.getElementById('modalSolicitacao'),
    modalSolicitacaoTitulo: document.getElementById('modalSolicitacaoTitulo'),
    modalSolicitacaoTipo: document.getElementById('modalSolicitacaoTipo'),
    modalSolicitacaoStatus: document.getElementById('modalSolicitacaoStatus'),
    modalSolicitacaoData: document.getElementById('modalSolicitacaoData'),
    modalSolicitacaoDescricao: document.getElementById('modalSolicitacaoDescricao'),
    modalSolicitacaoCamposEspecificos: document.getElementById('modalSolicitacaoCamposEspecificos'),
    modalSolicitacaoAnexos: document.getElementById('modalSolicitacaoAnexos'),
    modalSolicitacaoFoto: document.getElementById('modalSolicitacaoFoto'),
    modalSolicitacaoColabNome: document.getElementById('modalSolicitacaoColabNome'),
    modalSolicitacaoColabSetor: document.getElementById('modalSolicitacaoColabSetor'),
    modalSolicitacaoColabCargo: document.getElementById('modalSolicitacaoColabCargo'),
    modalSolicitacaoColabExtra: document.getElementById('modalSolicitacaoColabExtra'),
    btnAprovar: document.getElementById('btnAprovar'),
    btnReprovar: document.getElementById('btnReprovar'),
    
    // Modal de Colaborador
    modalColaborador: document.getElementById('modalColaboradorGestor'),
    colabModalFoto: document.getElementById('colabModalFoto'),
    colabModalNome: document.getElementById('colabModalNome'),
    colabModalCargoSetor: document.getElementById('colabModalCargoSetor'),
    colabModalInfoExtra: document.getElementById('colabModalInfoExtra'),
    colabModalSolicitacoes: document.getElementById('colabModalSolicitacoes')
  };

  // ==========================================
  // UTILITÁRIOS
  // ==========================================
  const Utils = {
    getToken() {
      try {
        return localStorage.getItem('token') || sessionStorage.getItem('token');
      } catch (e) {
        console.error('Erro ao acessar storage:', e);
        return null;
      }
    },

    buildPhotoUrl(fotoPath) {
  if (!fotoPath || fotoPath === 'null' || fotoPath === 'undefined') {
    return CONFIG.DEFAULT_PHOTO;
  }

  const normalized = String(fotoPath).trim();

  // Se for fundofoda.png, usar recurso local
  if (normalized === 'fundofoda.png' || normalized.includes('fundofoda')) {
    return CONFIG.DEFAULT_PHOTO;
  }

  // URL absoluta (
  if (/^https?:\/\//i.test(normalized)) {
    return normalized;
  }

  // : Remove prefixo 'uploads/' se existir
  let cleanPath = normalized;
  if (cleanPath.startsWith('uploads/')) {
    cleanPath = cleanPath.replace('uploads/', '');
  }
  if (cleanPath.startsWith('/uploads/')) {
    cleanPath = cleanPath.replace('/uploads/', '');
  }

  //  Se tem extensão de imagem ou 'doc-', é upload do servidor
  if (cleanPath.includes('doc-') || cleanPath.match(/\.(jpg|jpeg|png|gif)$/i)) {
    return `${CONFIG.API_BASE}/uploads/${cleanPath}`;
  }

  // Fallback para imagens em /img
  return `/img/${cleanPath}`;
},

    /**
     * Cria elemento de imagem com fallback
     */
    createImageWithFallback(src, alt = 'Foto') {
      const img = document.createElement('img');
      img.alt = alt;
      img.crossOrigin = 'anonymous';
      
      // Tenta carregar a imagem
      img.onerror = () => {
        console.warn(`Falha ao carregar imagem: ${src}`);
        img.src = CONFIG.DEFAULT_PHOTO;
        img.onerror = null; // Previne loop infinito
      };
      
      img.src = src;
      return img;
    },

    async apiCall(endpoint, options = {}) {
      const url = `${CONFIG.API_BASE}${endpoint}`;
      const token = this.getToken();
      
      const headers = {
        'Content-Type': 'application/json',
        ...options.headers
      };

      if (token) {
        headers['Authorization'] = `Bearer ${token}`;
      }

      try {
        console.log(` API Call: ${options.method || 'GET'} ${url}`);
        
        const response = await fetch(url, {
          credentials: 'include',
          headers,
          ...options
        });

        if (response.status === 401) {
          this.redirecionarLogin("Sessão expirada");
          return { success: false, status: 401, data: null };
        }

        if (!response.ok) {
          console.error(` HTTP ${response.status} para ${url}`);
          
          let errorMessage = `HTTP ${response.status}`;
          try {
            const errorData = await response.json();
            errorMessage = errorData.message || errorData.error || errorMessage;
          } catch (e) {
            // Ignora erro de parse
          }
          
          throw new Error(errorMessage);
        }

        const data = await response.json();
        console.log(` Resposta recebida de ${url}:`, data);
        return { success: true, data };
        
      } catch (error) {
        console.error(` Erro na API ${url}:`, error);
        return { 
          success: false, 
          data: null, 
          error: error.message 
        };
      }
    },

    redirecionarLogin(mensagem) {
      console.warn(`🔐 ${mensagem} - Redirecionando...`);
      localStorage.removeItem('token');
      sessionStorage.removeItem('token');
      window.location.href = '/login';
    },

    escapeHtml(text) {
      if (text == null) return '';
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    },

    formatDate(dateString) {
      if (!dateString) return 'N/A';
      try {
        const date = new Date(dateString);
        return date.toLocaleDateString('pt-BR', {
          day: '2-digit',
          month: '2-digit',
          year: 'numeric'
        });
      } catch (e) {
        return dateString;
      }
    },

    formatDateTime(dateString) {
      if (!dateString) return 'N/A';
      try {
        const date = new Date(dateString);
        return date.toLocaleString('pt-BR', {
          day: '2-digit',
          month: '2-digit',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        });
      } catch (e) {
        return dateString;
      }
    },

    formatCurrency(value) {
      if (value == null) return 'R$ 0,00';
      const num = parseFloat(value);
      if (isNaN(num)) return 'R$ 0,00';
      return num.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    },

    debounce(func, wait) {
      let timeout;
      return function executedFunction(...args) {
        const later = () => {
          clearTimeout(timeout);
          func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
      };
    },

    showLoading(show = true) {
      STATE.isLoading = show;
      if (DOM.setoresWrapper) {
        DOM.setoresWrapper.setAttribute('aria-busy', show.toString());
        if (show) {
          DOM.setoresWrapper.innerHTML = '<div class="placeholder">⏳ Carregando solicitações...</div>';
        }
      }
    },

    mostrarMensagem(mensagem, tipo = "info") {
      document.querySelectorAll('.alert-custom').forEach(alert => alert.remove());
      
      const alertDiv = document.createElement('div');
      alertDiv.className = `alert alert-${tipo} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 alert-custom`;
      alertDiv.style.zIndex = '9999';
      alertDiv.style.minWidth = '300px';
      alertDiv.innerHTML = `
        ${mensagem}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      `;
      document.body.appendChild(alertDiv);
      
      setTimeout(() => {
        if (alertDiv.parentNode) {
          alertDiv.remove();
        }
      }, 5000);
    },

    getStatusInfo(status) {
      const normalized = (status || 'pendente').toString().toLowerCase();
      return CONFIG.STATUS_MAP[normalized] || CONFIG.STATUS_MAP['pendente'];
    },

    getTipoLabel(tipo) {
      const normalized = (tipo || 'outros').toString().toLowerCase();
      return CONFIG.TIPO_LABELS[normalized] || 'Solicitação';
    }
  };

  // ==========================================
  // SERVIÇOS DE API
  // ==========================================
  const ApiService = {
    async fetchCurrentUser() {
      try {
        console.log('👤 Buscando dados do gestor...');
        
        const result = await Utils.apiCall(CONFIG.ENDPOINTS.AUTH_ME);
        
        if (!result.success || !result.data) {
          console.error(' Falha ao buscar dados do gestor');
          return null;
        }

        const userData = result.data.usuario || result.data;
        STATE.currentUser = userData;
        STATE.empresaId = userData.empresa_id;
        STATE.cnpjGestor = userData.cnpj;
        
        console.log(' Gestor carregado:', {
          nome: userData.nome,
          empresa_id: STATE.empresaId,
          cnpj: STATE.cnpjGestor
        });
        
        return userData;
        
      } catch (error) {
        console.error(' Erro ao buscar gestor:', error);
        return null;
      }
    },

    async fetchSolicitacoes() {
      try {
        console.log(' Buscando solicitações do gestor...');
        
        let result = await Utils.apiCall(CONFIG.ENDPOINTS.SOLICITACOES_GESTOR);
        
        if (!result.success) {
          console.log('Tentando endpoint alternativo...');
          result = await Utils.apiCall(CONFIG.ENDPOINTS.SOLICITACOES_ALT);
        }

        if (!result.success) {
          console.warn(' Não foi possível carregar solicitações');
          STATE.allSolicitacoes = [];
          return [];
        }

        const solicitacoes = this.extractSolicitacoes(result.data);
        STATE.allSolicitacoes = solicitacoes;
        
        console.log(` ${solicitacoes.length} solicitações carregadas`);
        return solicitacoes;
        
      } catch (error) {
        console.error(' Erro ao buscar solicitações:', error);
        STATE.allSolicitacoes = [];
        return [];
      }
    },

    extractSolicitacoes(responseData) {
      if (!responseData) return [];
      
      let rawSolicitacoes = [];
      
      if (Array.isArray(responseData.solicitacoes)) {
        rawSolicitacoes = responseData.solicitacoes;
      } else if (Array.isArray(responseData.data)) {
        rawSolicitacoes = responseData.data;
      } else if (Array.isArray(responseData)) {
        rawSolicitacoes = responseData;
      } else if (Array.isArray(responseData.rows)) {
        rawSolicitacoes = responseData.rows;
      }
      
      return rawSolicitacoes
        .map(item => this.normalizeSolicitacao(item))
        .filter(Boolean);
    },

    normalizeSolicitacao(raw) {
      if (!raw) return null;

      const colaborador = raw.colaborador || {};
      
      // Processa a foto do colaborador
      const fotoUrl = Utils.buildPhotoUrl(colaborador.foto);

      console.debug(' Foto processada:', {
  original: colaborador.foto,
  processada: fotoUrl
});

      return {
        id: raw.id,
        tipo: (raw.tipo_solicitacao || raw.tipo || 'outros').toLowerCase(),
        titulo: raw.titulo || Utils.getTipoLabel(raw.tipo_solicitacao || raw.tipo),
        descricao: raw.descricao || '',
        status: (raw.status || 'pendente').toLowerCase(),
        createdAt: raw.created_at || raw.data_solicitacao || raw.createdAt,
        updatedAt: raw.updated_at,
        observacao_gestor: raw.observacao_gestor || '',
        
        // Dados do colaborador
        colaborador: {
          id: colaborador.id || raw.usuario_id,
          nome: colaborador.nome || 'Colaborador',
          cargo: colaborador.cargo || 'Cargo não definido',
          setor: colaborador.setor || 'Setor não definido',
          foto: fotoUrl,
          email: colaborador.email || '',
          telefone: colaborador.telefone || '',
          salario: colaborador.salario || 0
        },
        
        // Campos específicos por tipo
        data_inicio: raw.data_inicio,
        data_fim: raw.data_fim,
        salario_solicitado: raw.salario_solicitado,
        justificativa: raw.justificativa,
        campo: raw.campo,
        novo_valor: raw.novo_valor,
        periodo_inicio: raw.periodo_inicio,
        periodo_fim: raw.periodo_fim,
        valor_reembolso: raw.valor_reembolso,
        categoria_reembolso: raw.categoria_reembolso,
        data_desligamento: raw.data_desligamento,
        motivo_desligamento: raw.motivo_desligamento,
        
        // Anexos
        anexos: this.normalizeAnexos(raw.anexos || [])
      };
    },

    normalizeAnexos(rawAnexos) {
      if (!Array.isArray(rawAnexos)) return [];
      
      return rawAnexos
        .filter(anexo => anexo && (anexo.url || anexo.path || anexo.filename))
        .map(anexo => ({
          id: anexo.id,
          nome: anexo.nome || anexo.name || anexo.filename || 'Anexo',
          url: anexo.url || anexo.path || '',
          mime: anexo.mime || anexo.mime_type || '',
          size: anexo.size || 0
        }));
    },

    async fetchSolicitacaoDetalhes(id) {
      try {
        console.log(` Buscando detalhes da solicitação ${id}...`);
        
        const result = await Utils.apiCall(`${CONFIG.ENDPOINTS.SOLICITACAO_DETALHE}/${id}`);
        
        if (!result.success) {
          console.error(' Falha ao buscar detalhes');
          return null;
        }

        const solicitacao = result.data.solicitacao || result.data.data || result.data;
        return this.normalizeSolicitacao(solicitacao);
        
      } catch (error) {
        console.error(' Erro ao buscar detalhes:', error);
        return null;
      }
    },

    async atualizarStatus(id, novoStatus, observacao = '') {
      try {
        console.log(` Atualizando status da solicitação ${id} para ${novoStatus}...`);
        
        const result = await Utils.apiCall(
          `${CONFIG.ENDPOINTS.SOLICITACAO_STATUS}/${id}/status`,
          {
            method: 'PUT',
            body: JSON.stringify({
              status: novoStatus,
              observacao: observacao
            })
          }
        );
        
        if (!result.success) {
          throw new Error(result.error || 'Falha ao atualizar status');
        }

        console.log(' Status atualizado com sucesso');
        return true;
        
      } catch (error) {
        console.error(' Erro ao atualizar status:', error);
        throw error;
      }
    }
  };

  // ==========================================
  // FILTROS E BUSCA
  // ==========================================
  const FilterService = {
    applyFilters() {
      let filtered = [...STATE.allSolicitacoes];

      // Filtro por status
      if (STATE.filters.status !== 'all') {
        filtered = filtered.filter(s => s.status === STATE.filters.status);
      }

      // Filtro por setor
      if (STATE.filters.setor) {
        filtered = filtered.filter(s => 
          s.colaborador.setor === STATE.filters.setor
        );
      }

      // Filtro por busca
      if (STATE.filters.search) {
        const searchTerm = STATE.filters.search.toLowerCase();
        filtered = filtered.filter(s =>
          s.colaborador.nome.toLowerCase().includes(searchTerm) ||
          s.tipo.toLowerCase().includes(searchTerm) ||
          (s.descricao && s.descricao.toLowerCase().includes(searchTerm)) ||
          (s.titulo && s.titulo.toLowerCase().includes(searchTerm))
        );
      }

      STATE.filteredSolicitacoes = filtered;
      return filtered;
    },

    groupBySetor(solicitacoes) {
      const grouped = {};
      
      solicitacoes.forEach(solicitacao => {
        const setor = solicitacao.colaborador.setor || 'Sem setor';
        if (!grouped[setor]) {
          grouped[setor] = [];
        }
        grouped[setor].push(solicitacao);
      });

      return Object.keys(grouped)
        .sort()
        .reduce((acc, setor) => {
          acc[setor] = grouped[setor];
          return acc;
        }, {});
    },

    groupByColaborador(solicitacoes) {
      const grouped = {};
      
      solicitacoes.forEach(solicitacao => {
        const colaboradorId = solicitacao.colaborador.id || 'unknown';
        if (!grouped[colaboradorId]) {
          grouped[colaboradorId] = {
            info: solicitacao.colaborador,
            solicitacoes: []
          };
        }
        grouped[colaboradorId].solicitacoes.push(solicitacao);
      });

      return grouped;
    }
  };

  // ==========================================
  // RENDERIZAÇÃO
  // ==========================================
  const Renderer = {
    renderAll() {
      if (!DOM.setoresWrapper) {
        console.error(' Elemento setoresWrapper não encontrado');
        return;
      }

      const filtered = FilterService.applyFilters();
      
      if (filtered.length === 0) {
        this.renderEmptyState();
        return;
      }
      
      if (STATE.viewMode === CONFIG.VIEW_MODES.CARDS) {
        this.renderCardsView(filtered);
      } else {
        this.renderListView(filtered);
      }
    },

    renderEmptyState() {
      DOM.setoresWrapper.innerHTML = `
        <div class="placeholder">
          <p>Nenhuma solicitação encontrada</p>
          <small>Ajuste os filtros ou verifique se há solicitações pendentes</small>
        </div>
      `;
    },

    renderCardsView(solicitacoes) {
      const groupedBySetor = FilterService.groupBySetor(solicitacoes);
      let html = '';

      Object.entries(groupedBySetor).forEach(([setorNome, setorSolicitacoes]) => {
        const groupedByColaborador = FilterService.groupByColaborador(setorSolicitacoes);
        
        html += `
          <article class="setor-container" data-setor="${Utils.escapeHtml(setorNome)}">
            <div class="setor-header">
              <h3>${Utils.escapeHtml(setorNome)}</h3>
              <div class="count">${setorSolicitacoes.length} solicitação(ões)</div>
            </div>
            <div class="colaboradores-carousel">
              ${this.renderColaboradoresCards(groupedByColaborador)}
            </div>
          </article>
        `;
      });

      DOM.setoresWrapper.innerHTML = html;
      this.attachCardsEventListeners();
    },

    renderColaboradoresCards(colaboradoresMap) {
      return Object.values(colaboradoresMap).map(colaboradorData => {
        const { info, solicitacoes } = colaboradorData;
        const fotoSrc = info.foto;

        return `
          <div class="colab-card" data-colab-id="${info.id}" tabindex="0">
            <img src="${fotoSrc}" 
                 alt="Foto de ${Utils.escapeHtml(info.nome)}"
                 class="colab-foto" 
                 crossorigin="anonymous"
                 onerror="this.onerror=null; this.src='${CONFIG.DEFAULT_PHOTO}';"
                 loading="lazy">
            <div class="nome">${Utils.escapeHtml(info.nome)}</div>
            <div class="cargo">${Utils.escapeHtml(info.cargo)}</div>
            <div class="colab-solicitacoes">
              ${solicitacoes.map(solic => this.renderMiniSolicitacao(solic)).join('')}
            </div>
          </div>
        `;
      }).join('');
    },

    renderMiniSolicitacao(solicitacao) {
      const statusInfo = Utils.getStatusInfo(solicitacao.status);
      const descricao = solicitacao.descricao ? 
        (solicitacao.descricao.length > 60 ? 
          solicitacao.descricao.substring(0, 60) + '...' : 
          solicitacao.descricao) : 
        Utils.getTipoLabel(solicitacao.tipo);

      return `
        <div class="solicitacao-mini status-${statusInfo.class}" 
             data-solicitacao-id="${solicitacao.id}" 
             tabindex="0">
          <div class="tipo">${statusInfo.icon} ${Utils.escapeHtml(Utils.getTipoLabel(solicitacao.tipo))}</div>
          <div class="resumo">${Utils.escapeHtml(descricao)}</div>
          <div class="status ${statusInfo.class}">${Utils.escapeHtml(statusInfo.label)}</div>
        </div>
      `;
    },

    renderListView(solicitacoes) {
      let html = `
        <div class="table-responsive">
          <table class="table table-hover align-middle" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(10px); border-radius: 12px; overflow: hidden;">
          
            <tbody>
              ${solicitacoes.map(solicitacao => this.renderSolicitacaoListRow(solicitacao)).join('')}
            </tbody>
          </table>
        </div>
      `;
      
      DOM.setoresWrapper.innerHTML = html;
      this.attachListEventListeners();
    },

    renderSolicitacaoListRow(solicitacao) {
  const statusInfo = Utils.getStatusInfo(solicitacao.status);
  const descricao = solicitacao.descricao ? 
    (solicitacao.descricao.length > 80 ? 
      solicitacao.descricao.substring(0, 80) + '...' : 
      solicitacao.descricao) : 
    'Sem descrição';

  return `
    <div class="list-solicitacao" data-solicitacao-id="${solicitacao.id}">
      <div class="d-flex justify-content-between align-items-start">
        <div class="left">
          <img src="${solicitacao.colaborador.foto}"
               alt="${Utils.escapeHtml(solicitacao.colaborador.nome)}"
               crossorigin="anonymous"
               onerror="this.onerror=null; this.src='${CONFIG.DEFAULT_PHOTO}';"
               loading="lazy">
          <div class="meta">
            <div class="titulo">${Utils.escapeHtml(solicitacao.colaborador.nome)}</div>
            <div class="sub">
              ${Utils.escapeHtml(solicitacao.colaborador.cargo)} • ${Utils.escapeHtml(solicitacao.colaborador.setor)}
            </div>
            <div style="margin-top: 8px;">
              <strong style="color: #fff;">${Utils.escapeHtml(Utils.getTipoLabel(solicitacao.tipo))}</strong>
            </div>
            <div class="descricao">${Utils.escapeHtml(descricao)}</div>
            ${solicitacao.anexos.length > 0 ? 
              `<div class="anexos"><i class="bi bi-paperclip"></i> ${solicitacao.anexos.length} anexo(s)</div>` : 
              ''}
            <div class="anexos">
              <i class="bi bi-calendar"></i> ${Utils.formatDateTime(solicitacao.createdAt)}
            </div>
          </div>
        </div>
        <div class="acoes">
          <span class="status-badge status-${solicitacao.status}">${statusInfo.icon} ${Utils.escapeHtml(statusInfo.label)}</span>
          <button class="btn btn-success btn-sm" data-action="aprovar" data-id="${solicitacao.id}" title="Aprovar">
            Aprovar
          </button>
          <button class="btn btn-danger btn-sm" data-action="reprovar" data-id="${solicitacao.id}" title="Reprovar">
            Reprovar
          </button>
          <button class="btn btn-outline btn-sm" data-action="detalhes" data-id="${solicitacao.id}" title="Ver Detalhes">
            Ver
          </button>
        </div>
      </div>
    </div>
  `;
},

    renderSolicitacaoListItem(solicitacao) {
  const statusInfo = Utils.getStatusInfo(solicitacao.status);
  const descricao = solicitacao.descricao ? 
    (solicitacao.descricao.length > 140 ? 
      solicitacao.descricao.substring(0, 140) + '...' : 
      solicitacao.descricao) : 
    'Sem descrição';

  return `
    <div class="list-solicitacao" data-solicitacao-id="${solicitacao.id}">
      <div class="d-flex justify-content-between align-items-start">
        <div class="left">
          <img src="${Utils.buildUploadUrl(solicitacao.colaborador.foto)}"
               alt="${Utils.escapeHtml(solicitacao.colaborador.nome)}"
               crossorigin="anonymous"
               onerror="this.onerror=null; this.src='${CONFIG.API_BASE}/img/fundofoda.png';">
          <div class="meta">
            <div class="titulo">${Utils.escapeHtml(solicitacao.colaborador.nome)}</div>
            <div class="sub">
              ${Utils.escapeHtml(solicitacao.colaborador.cargo)} • ${Utils.escapeHtml(solicitacao.colaborador.setor)}
            </div>
            <div style="margin-top: 8px;">
              <strong style="color: #fff;">${Utils.escapeHtml(Utils.getTipoLabel(solicitacao.tipo))}</strong>
            </div>
            <div class="descricao">${Utils.escapeHtml(descricao)}</div>
            <div class="anexos">
              <i class="bi bi-calendar"></i> ${Utils.formatDateTime(solicitacao.createdAt)}
              ${solicitacao.anexos.length > 0 ? 
                ` • <i class="bi bi-paperclip"></i> ${solicitacao.anexos.length} anexo(s)` : 
                ''}
            </div>
          </div>
        </div>
        <div class="acoes">
          <span class="status-badge status-${solicitacao.status}">${statusInfo.icon} ${Utils.escapeHtml(statusInfo.label)}</span>
          <button class="btn btn-success btn-sm" data-action="aprovar" data-id="${solicitacao.id}">
            <i class="bi bi-check-lg"></i> Aprovar
          </button>
          <button class="btn btn-danger btn-sm" data-action="reprovar" data-id="${solicitacao.id}">
            <i class="bi bi-x-lg"></i> Reprovar
          </button>
          <button class="btn btn-outline btn-sm" data-action="detalhes" data-id="${solicitacao.id}">
            <i class="bi bi-eye"></i> Detalhes
          </button>
        </div>
      </div>
    </div>
  `;
},

    renderSetorFilters() {
      if (!DOM.setorFiltersWrap) return;

      const setorCounts = {};
      STATE.allSolicitacoes.forEach(solicitacao => {
        const setor = solicitacao.colaborador.setor || 'Sem setor';
        setorCounts[setor] = (setorCounts[setor] || 0) + 1;
      });

      const setores = Object.keys(setorCounts).sort();
      
      let html = `
        <button class="setor-item btn small ${!STATE.filters.setor ? 'ativo' : ''}" 
                data-setor="all">
          Todos (${STATE.allSolicitacoes.length})
        </button>
      `;

      setores.forEach(setor => {
        html += `
          <button class="setor-item btn small ${STATE.filters.setor === setor ? 'ativo' : ''}" 
                  data-setor="${Utils.escapeHtml(setor)}">
            ${Utils.escapeHtml(setor)} (${setorCounts[setor]})
          </button>
        `;
      });

      DOM.setorFiltersWrap.innerHTML = html;
    },

    attachCardsEventListeners() {
      // Event listeners para cards de solicitações
      document.querySelectorAll('.solicitacao-mini').forEach(card => {
        card.addEventListener('click', async (e) => {
          e.stopPropagation();
          const id = card.dataset.solicitacaoId;
          if (id) {
            await ModalManager.openSolicitacaoModal(id);
          }
        });

        card.addEventListener('keydown', async (e) => {
          if (e.key === 'Enter') {
            e.stopPropagation();
            const id = card.dataset.solicitacaoId;
            if (id) {
              await ModalManager.openSolicitacaoModal(id);
            }
          }
        });
      });

      // Event listeners para cards de colaboradores
      document.querySelectorAll('.colab-card').forEach(card => {
        card.addEventListener('click', (e) => {
          if (e.target.closest('.solicitacao-mini')) return;
          
          const colaboradorId = card.dataset.colabId;
          if (colaboradorId) {
            ModalManager.openColaboradorModal(colaboradorId);
          }
        });
      });
    },

    attachListEventListeners() {
      // Event listeners para ações nos botões
      document.querySelectorAll('[data-action]').forEach(btn => {
        btn.addEventListener('click', async (e) => {
          e.stopPropagation();
          const action = btn.dataset.action;
          const id = btn.dataset.id;

          if (action === 'detalhes') {
            await ModalManager.openSolicitacaoModal(id);
          } else if (action === 'aprovar') {
            await SolicitacaoActions.aprovar(id);
          } else if (action === 'reprovar') {
            await SolicitacaoActions.reprovar(id);
          }
        });
      });

      // Event listener para clique na linha inteira (exceto botões)
      document.querySelectorAll('.list-solicitacao-row').forEach(row => {
        row.addEventListener('click', async (e) => {
          // Não abrir modal se clicou em um botão
          if (e.target.closest('[data-action]') || e.target.closest('.btn-group')) return;
          
          const id = row.dataset.solicitacaoId;
          if (id) {
            await ModalManager.openSolicitacaoModal(id);
          }
        });
        
        // Efeito hover
        row.addEventListener('mouseenter', function() {
          this.style.background = 'rgba(255,255,255,0.1)';
        });
        
        row.addEventListener('mouseleave', function() {
          this.style.background = '';
        });
      });
    }
  };

  // ==========================================
  // GERENCIAMENTO DE MODAIS
  // ==========================================
  const ModalManager = {
    async openSolicitacaoModal(id) {
      try {
        console.log(` Abrindo modal para solicitação ${id}...`);
        
        const solicitacao = await ApiService.fetchSolicitacaoDetalhes(id);
        
        if (!solicitacao) {
          Utils.mostrarMensagem('Erro ao carregar detalhes da solicitação', 'danger');
          return;
        }

        STATE.currentSolicitacaoId = id;
        
        this.preencherModalSolicitacao(solicitacao);
        
        const modal = new bootstrap.Modal(DOM.modalSolicitacao);
        modal.show();
        
      } catch (error) {
        console.error(' Erro ao abrir modal:', error);
        Utils.mostrarMensagem('Erro ao abrir detalhes', 'danger');
      }
    },

    preencherModalSolicitacao(solicitacao) {
      const statusInfo = Utils.getStatusInfo(solicitacao.status);
      
      DOM.modalSolicitacaoTitulo.textContent = solicitacao.titulo || Utils.getTipoLabel(solicitacao.tipo);
      DOM.modalSolicitacaoTipo.textContent = Utils.getTipoLabel(solicitacao.tipo);
      DOM.modalSolicitacaoStatus.innerHTML = `
        <span class="status-badge ${statusInfo.class}">${statusInfo.icon} ${statusInfo.label}</span>
      `;
      DOM.modalSolicitacaoData.textContent = Utils.formatDateTime(solicitacao.createdAt);
      DOM.modalSolicitacaoDescricao.textContent = solicitacao.descricao || 'Sem descrição';

      DOM.modalSolicitacaoCamposEspecificos.innerHTML = this.renderCamposEspecificos(solicitacao);
      DOM.modalSolicitacaoAnexos.innerHTML = this.renderAnexos(solicitacao.anexos);

      // Foto do modal (garante crossOrigin e fallback absoluto)
     try {
  const fotoUrl = Utils.buildPhotoUrl(solicitacao.colaborador.foto); // ✅ CORREÇÃO
  const fallbackUrl = CONFIG.DEFAULT_PHOTO;
  
  if (DOM.modalSolicitacaoFoto) {
    DOM.modalSolicitacaoFoto.crossOrigin = 'anonymous';
    DOM.modalSolicitacaoFoto.onerror = () => { 
      DOM.modalSolicitacaoFoto.onerror = null; // Previne loop
      DOM.modalSolicitacaoFoto.src = fallbackUrl; 
    };
    DOM.modalSolicitacaoFoto.src = fotoUrl;
  }
} catch (e) {
  console.warn('Erro ao setar foto do modal:', e);
  if (DOM.modalSolicitacaoFoto) {
    DOM.modalSolicitacaoFoto.src = CONFIG.DEFAULT_PHOTO;
  }
}

      DOM.modalSolicitacaoColabNome.textContent = solicitacao.colaborador.nome;
      DOM.modalSolicitacaoColabSetor.textContent = solicitacao.colaborador.setor;
      DOM.modalSolicitacaoColabCargo.textContent = solicitacao.colaborador.cargo;
      
      const extraInfo = [];
      if (solicitacao.colaborador.email) {
        extraInfo.push(` ${solicitacao.colaborador.email}`);
      }
      if (solicitacao.colaborador.telefone) {
        extraInfo.push(` ${solicitacao.colaborador.telefone}`);
      }
      if (solicitacao.colaborador.salario && solicitacao.tipo === 'reajuste_salarial') {
        extraInfo.push(` Salário atual: ${Utils.formatCurrency(solicitacao.colaborador.salario)}`);
      }
      DOM.modalSolicitacaoColabExtra.innerHTML = extraInfo.join('<br>');

      const desabilitar = solicitacao.status !== 'pendente' && solicitacao.status !== 'em_analise';
      DOM.btnAprovar.disabled = desabilitar;
      DOM.btnReprovar.disabled = desabilitar;
    },

    renderCamposEspecificos(solicitacao) {
      let html = '';

      switch (solicitacao.tipo) {
        case 'ferias':
          html = `
            <div class="campo-especifico">
              <strong> Período de Férias:</strong>
              <p>${Utils.formatDate(solicitacao.data_inicio)} até ${Utils.formatDate(solicitacao.data_fim)}</p>
            </div>
          `;
          break;

        case 'reajuste_salarial':
          html = `
            <div class="campo-especifico">
              <strong> Salário Atual:</strong>
              <p>${Utils.formatCurrency(solicitacao.colaborador.salario)}</p>
            </div>
            <div class="campo-especifico">
              <strong> Salário Solicitado:</strong>
              <p style="font-size: 1.2em; color: #4CAF50; font-weight: bold;">
                ${Utils.formatCurrency(solicitacao.salario_solicitado)}
              </p>
              ${this.calcularDiferenca(solicitacao.colaborador.salario, solicitacao.salario_solicitado)}
            </div>
            ${solicitacao.justificativa ? `
              <div class="campo-especifico">
                <strong>Justificativa:</strong>
                <p>${Utils.escapeHtml(solicitacao.justificativa)}</p>
              </div>
            ` : ''}
          `;
          break;

        case 'alteracao_dados':
          html = `
            <div class="campo-especifico">
              <strong> Campo a ser alterado:</strong>
              <p>${Utils.escapeHtml(solicitacao.campo || 'Não especificado')}</p>
            </div>
            <div class="campo-especifico">
              <strong> Novo valor:</strong>
              <p>${Utils.escapeHtml(solicitacao.novo_valor || 'Não especificado')}</p>
            </div>
          `;
          break;

        case 'consulta_banco_horas':
        case 'banco_horas':
          html = `
            <div class="campo-especifico">
              <strong> Período de Consulta:</strong>
              <p>${Utils.formatDate(solicitacao.periodo_inicio || 'Não especificado')} até ${Utils.formatDate(solicitacao.periodo_fim || 'Não especificado')}</p>
            </div>
          `;
          break;

        case 'reembolso':
          html = `
            <div class="campo-especifico">
              <strong> Valor do Reembolso:</strong>
              <p>${Utils.formatCurrency(solicitacao.valor_reembolso)}</p>
            </div>
            ${solicitacao.categoria_reembolso ? `
              <div class="campo-especifico">
                <strong> Categoria:</strong>
                <p>${Utils.escapeHtml(solicitacao.categoria_reembolso)}</p>
              </div>
            ` : ''}
          `;
          break;

        case 'desligamento':
          html = `
            ${solicitacao.data_desligamento ? `
              <div class="campo-especifico">
                <strong> Data do Desligamento:</strong>
                <p>${Utils.formatDate(solicitacao.data_desligamento)}</p>
              </div>
            ` : ''}
            ${solicitacao.motivo_desligamento ? `
              <div class="campo-especifico">
                <strong> Motivo:</strong>
                <p>${Utils.escapeHtml(solicitacao.motivo_desligamento)}</p>
              </div>
            ` : ''}
          `;
          break;

        default:
          html = `<p class="text-muted">Nenhum campo específico para este tipo de solicitação.</p>`;
      }

      return html;
    },

    calcularDiferenca(salarioAtual, salarioNovo) {
      if (!salarioAtual || !salarioNovo) return '';
      
      const diferenca = salarioNovo - salarioAtual;
      const percentual = ((diferenca / salarioAtual) * 100).toFixed(2);
      
      const cor = diferenca > 0 ? '#4CAF50' : '#f44336';
      const sinal = diferenca > 0 ? '+' : '';
      
      return `
        <p style="color: ${cor}; font-size: 0.9em; margin-top: 8px;">
          ${sinal}${Utils.formatCurrency(diferenca)} (${sinal}${percentual}%)
        </p>
      `;
    },

    renderAnexos(anexos) {
      if (!anexos || anexos.length === 0) {
        return '<p class="text-muted">Nenhum anexo enviado</p>';
      }

      return `
        <ul class="anexo-lista">
          ${anexos.map(anexo => `
            <li>
              <a href="${CONFIG.API_BASE}${anexo.url}" 
                 target="_blank" 
                 rel="noopener noreferrer" 
                 class="btn btn-outline btn-sm">
                📎 ${Utils.escapeHtml(anexo.nome)}
                ${anexo.size ? ` (${(anexo.size / 1024).toFixed(2)} KB)` : ''}
              </a>
            </li>
          `).join('')}
        </ul>
      `;
    },

   openColaboradorModal(colaboradorId) {
  const solicitacoesDoColaborador = STATE.allSolicitacoes.filter(
    s => String(s.colaborador.id) === String(colaboradorId)
  );

  if (solicitacoesDoColaborador.length === 0) {
    Utils.mostrarMensagem('Nenhuma solicitação encontrada para este colaborador', 'warning');
    return;
  }

  const colaborador = solicitacoesDoColaborador[0].colaborador;

  
 if (DOM.colabModalFoto) {
  const fotoUrl = Utils.buildPhotoUrl(colaborador.foto); // ✅ CORREÇÃO AQUI
  const fallbackUrl = CONFIG.DEFAULT_PHOTO;
  
  console.log(' Carregando foto do colaborador:', {
    original: colaborador.foto,
    processada: fotoUrl,
    fallback: fallbackUrl
  });
  
  // Remove handler anterior se existir
  DOM.colabModalFoto.onerror = null;
  
  // Define novo handler de erro
  DOM.colabModalFoto.onerror = function() {
    console.warn('⚠️ Falha ao carregar foto, usando fallback');
    this.onerror = null; // Previne loop infinito
    this.src = fallbackUrl;
  };
  
  // Define crossOrigin ANTES de definir src
  DOM.colabModalFoto.crossOrigin = 'anonymous';
  
  // Por último, define o src (isso dispara o carregamento)
  DOM.colabModalFoto.src = fotoUrl;
}

  DOM.colabModalNome.textContent = colaborador.nome;
  DOM.colabModalCargoSetor.textContent = `${colaborador.cargo} • ${colaborador.setor}`;
  
  const infoExtra = [];
  if (colaborador.email) infoExtra.push(` ${colaborador.email}`);
  if (colaborador.telefone) infoExtra.push(` ${colaborador.telefone}`);
  DOM.colabModalInfoExtra.innerHTML = infoExtra.join('<br>');

  DOM.colabModalSolicitacoes.innerHTML = solicitacoesDoColaborador.map(s => {
    const statusInfo = Utils.getStatusInfo(s.status);
    return `
      <div class="colab-solicitacao-item" data-solicitacao-id="${s.id}" style="cursor: pointer;">
        <div class="d-flex justify-content-between">
          <strong>${Utils.getTipoLabel(s.tipo)}</strong>
          <span class="status-badge ${statusInfo.class}">${statusInfo.icon}</span>
        </div>
        <small>${Utils.formatDateTime(s.createdAt)}</small>
      </div>
    `;
  }).join('');

  DOM.colabModalSolicitacoes.querySelectorAll('.colab-solicitacao-item').forEach(item => {
    item.addEventListener('click', async () => {
      const modal = bootstrap.Modal.getInstance(DOM.modalColaborador);
      if (modal) modal.hide();
      
      const id = item.dataset.solicitacaoId;
      await this.openSolicitacaoModal(id);
    });
  });

  const modal = new bootstrap.Modal(DOM.modalColaborador);
  modal.show();
}
  };

  // ==========================================
  // AÇÕES DE SOLICITAÇÃO
  // ==========================================
  const SolicitacaoActions = {
    async aprovar(id) {
      if (!confirm('Tem certeza que deseja aprovar esta solicitação?\n\nEsta ação irá processar automaticamente as alterações no sistema.')) {
        return;
      }

      try {
        Utils.showLoading(true);
        
        await ApiService.atualizarStatus(id, 'aprovada', 'Solicitação aprovada e processada automaticamente pelo gestor');
        
        Utils.mostrarMensagem(' Solicitação aprovada e processada com sucesso!', 'success');
        
        const modalElement = DOM.modalSolicitacao;
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
          modal.hide();
        }
        
        await EventManager.refreshData();
        
      } catch (error) {
        console.error(' Erro ao aprovar:', error);
        Utils.mostrarMensagem('Erro ao aprovar solicitação: ' + error.message, 'danger');
      } finally {
        Utils.showLoading(false);
      }
    },

    async reprovar(id) {
      const motivo = prompt('Motivo da reprovação (opcional):');
      
      if (motivo === null) {
        return;
      }

      try {
        await ApiService.atualizarStatus(
          id, 
          'reprovada', 
          motivo || 'Solicitação reprovada pelo gestor'
        );
        
        Utils.mostrarMensagem(' Solicitação reprovada', 'warning');
        
        const modalElement = DOM.modalSolicitacao;
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
          modal.hide();
        }
        
        await EventManager.refreshData();
        
      } catch (error) {
        console.error(' Erro ao reprovar:', error);
        Utils.mostrarMensagem('Erro ao reprovar solicitação', 'danger');
      }
    }
  };

  // ==========================================
  // GERENCIAMENTO DE EVENTOS
  // ==========================================
  const EventManager = {
    init() {
      this.setupBusca();
      this.setupFiltrosStatus();
      this.setupRefresh();
      this.setupToggleView();
      this.setupFiltrosSetor();
      this.setupModalButtons();
      this.setupFilterTodos();
    },

    setupBusca() {
      if (DOM.buscaInput) {
        DOM.buscaInput.addEventListener('input', Utils.debounce((e) => {
          STATE.filters.search = e.target.value.trim();
          Renderer.renderAll();
        }, 300));
      }
    },

    setupFiltrosStatus() {
      if (DOM.filtrosBtns) {
        DOM.filtrosBtns.forEach(btn => {
          btn.addEventListener('click', () => {
            DOM.filtrosBtns.forEach(b => b.classList.remove('ativo'));
            btn.classList.add('ativo');
            STATE.filters.status = btn.dataset.status;
            Renderer.renderAll();
          });
        });
      }
    },

    setupRefresh() {
      if (DOM.btnRefresh) {
        DOM.btnRefresh.addEventListener('click', () => {
          this.refreshData();
        });
      }
    },

    setupToggleView() {
      if (DOM.btnToggleView) {
        DOM.btnToggleView.addEventListener('click', () => {
          STATE.viewMode = STATE.viewMode === CONFIG.VIEW_MODES.CARDS ? 
            CONFIG.VIEW_MODES.LIST : CONFIG.VIEW_MODES.CARDS;
          
          // Atualizar o texto do botão
          DOM.btnToggleView.textContent = STATE.viewMode === CONFIG.VIEW_MODES.CARDS ? 
            ' Lista' : ' Cards';
          
          // Renderizar novamente
          Renderer.renderAll();
        });
      }
    },

    setupFiltrosSetor() {
      if (DOM.setorFiltersWrap) {
        DOM.setorFiltersWrap.addEventListener('click', (e) => {
          const setorBtn = e.target.closest('.setor-item');
          if (!setorBtn) return;

          const setor = setorBtn.dataset.setor;
          STATE.filters.setor = setor === 'all' ? null : setor;
          Renderer.renderSetorFilters();
          Renderer.renderAll();
        });
      }
    },

    setupFilterTodos() {
      if (DOM.btnFilterTodos) {
        DOM.btnFilterTodos.addEventListener('click', () => {
          STATE.filters.status = 'all';
          STATE.filters.setor = null;
          STATE.filters.search = '';
          
          if (DOM.buscaInput) {
            DOM.buscaInput.value = '';
          }
          
          DOM.filtrosBtns.forEach(btn => {
            btn.classList.toggle('ativo', btn.dataset.status === 'all');
          });
          
          Renderer.renderSetorFilters();
          Renderer.renderAll();
        });
      }
    },

    setupModalButtons() {
      if (DOM.btnAprovar) {
        DOM.btnAprovar.addEventListener('click', () => {
          if (STATE.currentSolicitacaoId) {
            SolicitacaoActions.aprovar(STATE.currentSolicitacaoId);
          }
        });
      }

      if (DOM.btnReprovar) {
        DOM.btnReprovar.addEventListener('click', () => {
          if (STATE.currentSolicitacaoId) {
            SolicitacaoActions.reprovar(STATE.currentSolicitacaoId);
          }
        });
      }
    },

    async refreshData() {
      Utils.showLoading(true);
      
      try {
        await ApiService.fetchSolicitacoes();
        Renderer.renderSetorFilters();
        Renderer.renderAll();
        Utils.mostrarMensagem(' Dados atualizados com sucesso!', 'success');
      } catch (error) {
        console.error(' Erro ao atualizar dados:', error);
        Utils.mostrarMensagem('Erro ao atualizar dados', 'danger');
      } finally {
        Utils.showLoading(false);
      }
    }
  };

  // ==========================================
  // INICIALIZAÇÃO
  // ==========================================
  const App = {
    async init() {
      console.log(' Inicializando aplicação de solicitações do gestor...');

      try {
        // Verificar autenticação
        const user = await ApiService.fetchCurrentUser();
        if (!user) {
          Utils.redirecionarLogin('Usuário não autenticado');
          return;
        }

        // Carregar solicitações
        Utils.showLoading(true);
        await ApiService.fetchSolicitacoes();
        
        // Configurar event listeners
        EventManager.init();
        
        // Renderizar interface
        Renderer.renderSetorFilters();
        Renderer.renderAll();
        
        Utils.showLoading(false);
        
        console.log(' Aplicação inicializada com sucesso!');
        
      } catch (error) {
        console.error(' Erro na inicialização:', error);
        Utils.mostrarMensagem('Erro ao carregar a aplicação', 'danger');
        Utils.showLoading(false);
      }
    }
  };

  // ==========================================
  // INICIAR APLICAÇÃO
  // ==========================================
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => App.init());
  } else {
    App.init();
  }

})();