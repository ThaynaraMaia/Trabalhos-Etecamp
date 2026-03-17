const BACKEND_URL = 'http://localhost:3001';

class CalculadoraFolha {
  static calcularINSS(salarioBruto) {
    if (salarioBruto <= 1412.00) return salarioBruto * 0.075;
    if (salarioBruto <= 2666.68) return (salarioBruto * 0.09) - 21.18;
    if (salarioBruto <= 4000.03) return (salarioBruto * 0.12) - 101.18;
    if (salarioBruto <= 7786.02) return (salarioBruto * 0.14) - 181.18;
    return 908.85;
  }

  static calcularIRRF(salarioBase, dependentes = 0) {
    const deducaoDependente = 189.59 * dependentes;
    const baseCalculo = Math.max(0, salarioBase - deducaoDependente);
    
    if (baseCalculo <= 2259.20) return 0;
    if (baseCalculo <= 2826.65) return (baseCalculo * 0.075) - 169.44;
    if (baseCalculo <= 3751.05) return (baseCalculo * 0.15) - 381.44;
    if (baseCalculo <= 4664.68) return (baseCalculo * 0.225) - 662.77;
    return (baseCalculo * 0.275) - 896.00;
  }

  static calcularFGTS(salarioBruto) {
    return salarioBruto * 0.08;
  }

  static calcularPercentualINSS(salarioBruto) {
    const inss = this.calcularINSS(salarioBruto);
    return ((inss / salarioBruto) * 100).toFixed(2);
  }
}

class GerenciadorHolerite {
  constructor() {
    this.proventos = [];
    this.descontos = [];
    this.dadosColaborador = null;
  }

  adicionarProvento(codigo, descricao, referencia, valor) {
    this.proventos.push({ 
      codigo, 
      descricao, 
      referencia, 
      valor: parseFloat(valor) || 0 
    });
  }

  adicionarDesconto(codigo, descricao, referencia, valor) {
    this.descontos.push({ 
      codigo, 
      descricao, 
      referencia, 
      valor: parseFloat(valor) || 0 
    });
  }

  removerItem(tipo, index) {
    if (tipo === 'provento') this.proventos.splice(index, 1);
    else this.descontos.splice(index, 1);
  }

  getTotalProventos() {
    return this.proventos.reduce((sum, item) => sum + (Number(item.valor) || 0), 0);
  }

  getTotalDescontos() {
    return this.descontos.reduce((sum, item) => sum + (Number(item.valor) || 0), 0);
  }

  getSalarioLiquido() {
    return this.getTotalProventos() - this.getTotalDescontos();
  }

  limpar() {
    this.proventos = [];
    this.descontos = [];
  }
}

function mostrarNotificacao(mensagem, tipo = 'info') {
  const notif = document.createElement('div');
  notif.className = `alert alert-${tipo} position-fixed top-0 end-0 m-3`;
  notif.style.zIndex = '9999';
  notif.style.minWidth = '300px';
  notif.innerHTML = `
    <div class="d-flex align-items-center">
      <i class="bi bi-${tipo === 'success' ? 'check-circle' : tipo === 'danger' ? 'x-circle' : 'info-circle'} me-2"></i>
      <span>${mensagem}</span>
    </div>
  `;
  document.body.appendChild(notif);
  setTimeout(() => { try { notif.remove(); } catch(e){} }, 4000);
}

function mostrarLoading(show = true) {
  try {
    if (typeof window.__holeriteLoadingCount === "undefined") window.__holeriteLoadingCount = 0;
    if (show) {
      window.__holeriteLoadingCount++;
      if (!document.getElementById('loading-overlay')) {
        const loading = document.createElement('div');
        loading.id = 'loading-overlay';
        loading.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center';
        loading.style.cssText = 'background:rgba(0,0,0,0.7);z-index:100000;';
        loading.innerHTML = `
          <div style="text-align:center;color:#fff;">
            <div class="spinner-border" role="status" style="width:3rem;height:3rem;">
              <span class="visually-hidden">Carregando...</span>
            </div>
            <div style="margin-top:8px;">Carregando...</div>
          </div>
        `;
        document.body.appendChild(loading);
      }
    } else {
      window.__holeriteLoadingCount = Math.max(0, window.__holeriteLoadingCount - 1);
      if (window.__holeriteLoadingCount === 0) {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) overlay.remove();
      }
    }
  } catch (e) { console.error('Erro mostrarLoading:', e); }
}

function formatarMoeda(valor) {
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(valor || 0);
}

function formatarCPF(cpf) {
  if (!cpf) return '';
  const numeros = cpf.replace(/\D/g, '');
  return numeros.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
}

function formatarCNPJ(cnpj) {
  if (!cnpj) return '';
  const numeros = cnpj.replace(/\D/g, '');
  return numeros.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
}

// ---------------------------------------------------------------------------
// Início do script principal
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', async function() {
  const gerenciador = new GerenciadorHolerite();
  
  // Elementos da UI
  const tbodyProventos = document.getElementById('tbody-proventos');
  const tbodyDescontos = document.getElementById('tbody-descontos');
  const btnAdicionarProvento = document.getElementById('btn-add-provento');
  const btnAdicionarDesconto = document.getElementById('btn-add-desconto');
  const btnCalcularAuto = document.getElementById('btn-calcular-auto');
  const btnSalvarHolerite = document.getElementById('btn-salvar-holerite');
  const btnGerarPDF = document.getElementById('btn-gerar-pdf');
  const btnEnviarEmail = document.getElementById('btn-enviar-email');

  const totalProventosEl = document.getElementById('total-proventos');
  const totalDescontosEl = document.getElementById('total-descontos');
  const salarioLiquidoEl = document.getElementById('salario-liquido');

  const modalProvento = document.getElementById('modal-provento');
  const modalDesconto = document.getElementById('modal-desconto');

  // Funções de Read-Only
  function isReadOnlyView() {
    try {
      const params = new URLSearchParams(window.location.search);
      if (params.get('view') === '1' || params.get('readonly') === '1' || params.get('mode') === 'view') return true;
      if (/\/folhapaga\/\d+\/view\/?$/.test(window.location.pathname)) return true;
    } catch (e) {}
    return false;
  }

  const _READ_ONLY = isReadOnlyView();
  if (_READ_ONLY) {
    try {
      if (btnAdicionarProvento) btnAdicionarProvento.style.display = 'none';
      if (btnAdicionarDesconto) btnAdicionarDesconto.style.display = 'none';
      const btnsToHide = ['btn-calcular-auto','btn-salvar-holerite','btn-enviar-email'];
      btnsToHide.forEach(id => { const b = document.getElementById(id); if (b) b.style.display = 'none'; });

      if (modalProvento) modalProvento.style.display = 'none';
      if (modalDesconto) modalDesconto.style.display = 'none';
    } catch(e){ console.warn('Erro setando modo read-only:', e); }
  }

  function aplicarReadOnlyNaTela() {
    // ... (nenhuma mudança nesta função interna)
    if (!_READ_ONLY) return;
    document.querySelectorAll('input, select, textarea, button').forEach(el => {
      if (el.id === 'btn-gerar-pdf' || el.id === 'btn-gerar-pdf' || el.classList.contains('btn-pdf')) {
        el.disabled = false;
        el.style.display = el.style.display === 'none' ? '' : el.style.display;
        return;
      }
      if (el.tagName === 'INPUT' || el.tagName === 'SELECT' || el.tagName === 'TEXTAREA' || el.type === 'number' || el.type === 'text') {
        try { el.readOnly = true; el.disabled = true; } catch(e){}
      }
      if (el.matches && (el.classList.contains('btn-remover') || el.matches('button[type="submit"]') || el.classList.contains('btn-adicionar') )) {
        try { el.style.display = 'none'; } catch(e){}
      }
    });
    document.querySelectorAll('table.tabela-holerite th, table.tabela-holerite td').forEach(td => {
      if (td.querySelector && (td.querySelector('.btn-remover') || td.querySelector('.btn-edit') || td.querySelector('.btn-add'))) {
        td.style.display = 'none';
      }
    });
    document.querySelectorAll('.btn-remover, .btn-fechar-modal, .btn-adicionar, .btn-edit').forEach(b => { try { b.style.display = 'none'; } catch(e){} });
  }

  // Helpers
  function obterTotalBaseParaCalculos() {
    const totalProv = gerenciador.getTotalProventos();
    if (totalProv > 0) return totalProv;
    const sb = parseFloat(document.getElementById('salario-base')?.value) || 0;
    return sb;
  }

  function obterColaboradorIdDaPagina() {
    const hidden = document.getElementById('colaborador-id');
    if (hidden && hidden.value) return String(hidden.value);
    const m = window.location.pathname.match(/\/(?:gestor\/)?folhapaga\/(\d+)(?:\/|$)/i);
    if (m && m[1]) return m[1];
    const urlParams = new URLSearchParams(window.location.search);
    const qid = urlParams.get('id') || urlParams.get('usuarioId') || urlParams.get('usuarioID') || urlParams.get('usuario_id');
    if (qid) return qid;
    return null;
  }

  // Obter ID
  const colaboradorId = obterColaboradorIdDaPagina();

  // ==========================================================
  // CORREÇÃO: Listeners dos modais movidos para ANTES
  // da carga de dados. Isso garante que os modais
  // abram mesmo se a API falhar.
  // ==========================================================
  
  // Listeners de Abrir Modal (descomentados)
  btnAdicionarProvento?.addEventListener('click', () => abrirModal('provento'));
  btnAdicionarDesconto?.addEventListener('click', () => abrirModal('desconto'));

  // Listeners de Fechar Modal
  document.querySelectorAll('.btn-fechar-modal').forEach(btn => {
    btn.addEventListener('click', fecharModals);
  });
  modalProvento?.addEventListener('click', (e) => { if (e.target === modalProvento) fecharModals(); });
  modalDesconto?.addEventListener('click', (e) => { if (e.target === modalDesconto) fecharModals(); });

  // Forms dos Modais
  document.getElementById('form-add-provento')?.addEventListener('submit', (e) => {
    e.preventDefault();
    const codigo = document.getElementById('provento-codigo').value || 'MANUAL';
    const descricao = document.getElementById('provento-descricao').value;
    const referencia = document.getElementById('provento-referencia').value;
    let valorRaw = document.getElementById('provento-valor').value;
    valorRaw = String(valorRaw).replace(/\s+/g, '').replace(',', '.');
    const valor = parseFloat(valorRaw);
    if (!descricao || isNaN(valor)) {
      mostrarNotificacao('Preencha todos os campos obrigatórios corretamente', 'warning');
      return;
    }
    gerenciador.adicionarProvento(codigo, descricao, referencia, valor);
    renderizarProventos();
    atualizarTotais();
    fecharModals();
    e.target.reset();
    mostrarNotificacao('Provento adicionado!', 'success');
  });

  document.getElementById('form-add-desconto')?.addEventListener('submit', (e) => {
    e.preventDefault();
    const codigo = (document.getElementById('desconto-codigo').value || 'MANUAL').toString();
    const descricaoEl = document.getElementById('desconto-descricao');
    const referenciaEl = document.getElementById('desconto-referencia');
    const valorEl = document.getElementById('desconto-valor');

    const descricao = descricaoEl ? descricaoEl.value.trim() : '';
    const referencia = referenciaEl ? referenciaEl.value.trim() : '';
    let valorRaw = valorEl ? String(valorEl.value).trim() : '';
    valorRaw = valorRaw.replace(/\s+/g, '').replace(',', '.');
    const valorNum = parseFloat(valorRaw);

    if (!descricao) {
      mostrarNotificacao('Descrição é obrigatória', 'warning');
      if (descricaoEl) descricaoEl.focus();
      return;
    }
    if (isNaN(valorNum)) {
      mostrarNotificacao('Valor inválido. Use números (ex: 1234.56 ou 1.234,56)', 'warning');
      if (valorEl) valorEl.focus();
      return;
    }

    gerenciador.adicionarDesconto(codigo, descricao, referencia, valorNum);
    renderizarDescontos();
    atualizarTotais();
    fecharModals();
    e.target.reset();
    mostrarNotificacao('Desconto adicionado!', 'success');
  });


  // --- Início da Carga de Dados ---

  if (!colaboradorId) {
    mostrarNotificacao('ID do colaborador não encontrado na página (path, hidden input ou query).', 'danger');
    console.warn('Tentativas de localizar ID falharam. pathname=', window.location.pathname, 'search=', window.location.search, 'hidden#colaborador-id=', document.getElementById('colaborador-id')?.value);
    // Não usamos 'return' para permitir que os modais ainda funcionem
  } else {
    console.log('Holerite: ID do colaborador detectado ->', colaboradorId);
    // Carregar dados do colaborador
    await carregarDadosColaborador(colaboradorId);
  }

  // Event Listeners (Restantes, que dependem dos dados)
  btnCalcularAuto?.addEventListener('click', calcularAutomatico);
  btnSalvarHolerite?.addEventListener('click', salvarHolerite);
  btnGerarPDF?.addEventListener('click', gerarPDF);
  btnEnviarEmail?.addEventListener('click', enviarEmail);


  // ---------- principais funções ----------
  async function carregarDadosColaborador(id) {
    try {
      mostrarLoading(true);
      const response = await fetch(`${BACKEND_URL}/api/gestor/colaborador/${id}/dados-completos`);
      const data = await response.json();

      if (!data.success) {
        throw new Error(data.erro || 'Erro ao carregar dados');
      }

      gerenciador.dadosColaborador = data.usuario;

      // Preencher informações na tela
      document.getElementById('nome-colaborador').textContent = data.usuario.nome || '';
      document.getElementById('cpf-colaborador').textContent = formatarCPF(data.usuario.cpf || '');
      document.getElementById('cargo-colaborador').textContent = data.usuario.cargo || '';
      document.getElementById('codigo-colaborador').textContent = data.usuario.numero_registro || '';
      // hidden inputs
      const em = document.getElementById('email-colaborador');
      if (em) em.value = data.usuario.email || '';
      const hid = document.getElementById('colaborador-id');
      if (hid) hid.value = data.usuario.id || '';
      const sbInput = document.getElementById('salario-base');
      const salarioBase = parseFloat(data.usuario.salario) || 0;
      if (sbInput) sbInput.value = salarioBase;
      if (document.getElementById('salario-base-display')) {
        document.getElementById('salario-base-display').textContent = formatarMoeda(salarioBase);
      }

      // Dados da empresa
      if (document.getElementById('nome-empresa')) document.getElementById('nome-empresa').textContent = data.usuario.nomeEmpresa || '';
      if (document.getElementById('cnpj-empresa')) document.getElementById('cnpj-empresa').textContent = formatarCNPJ(data.usuario.cnpjEmpresa || '');

      // mês referência
      const hoje = new Date();
      const mesAtual = hoje.toISOString().substring(0, 7);
      const mesEl = document.getElementById('mes-referencia');
      if (mesEl) mesEl.value = mesAtual;

      // limpar
      gerenciador.limpar();

      if (salarioBase > 0) gerenciador.adicionarProvento('001', 'Salário Base', '30 dias', salarioBase);

      if (data.beneficios && data.beneficios.length > 0) {
        data.beneficios.forEach(b => {
          const valor = parseFloat(b.valor) || 0;
          if (valor > 0) {
            gerenciador.adicionarProvento(`BEN${b.beneficio_id}`, b.nome, b.descricao || '-', valor);
          }
        });
      }

      renderizarProventos();
      renderizarDescontos();
      atualizarTotais();

      mostrarNotificacao('Dados carregados com sucesso!', 'success');
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
      mostrarNotificacao('Erro ao carregar dados: ' + error.message, 'danger');
    } finally {
      mostrarLoading(false);
    }
  }

  // ==========================================================
  // CORREÇÃO: Declarações duplicadas removidas daqui
  // (Elas já foram declaradas no topo do DOMContentLoaded)
  // ==========================================================
  // const modalProvento = ... (REMOVIDO)
  // const modalDesconto = ... (REMOVIDO)

  function abrirModal(tipo) {
    fecharModals();
    if (tipo === 'provento') {
      if (modalProvento) {
        modalProvento.classList.add('d-flex');
        modalProvento.style.display = 'flex'; // Mantém o style para compatibilidade
      }
      preencherSugestoesProvento();
    } else {
      if (modalDesconto) {
        modalDesconto.classList.add('d-flex');
        modalDesconto.style.display = 'flex'; // Mantém o style para compatibilidade
      }
      preencherSugestoesDesconto();
    }
  }

  function fecharModals() {
    if (modalProvento) {
      modalProvento.classList.remove('d-flex');
      modalProvento.style.display = 'none'; // Garante que o style volte para 'none'
    }
    if (modalDesconto) {
      modalDesconto.classList.remove('d-flex');
      modalDesconto.style.display = 'none'; // Garante que o style volte para 'none'
    }
  }

  // preenchimento selects (provento/desconto)
  function preencherSugestoesProvento() {
    const sugestoes = [
      { codigo: '001', descricao: 'Salário Base' },
      { codigo: '002', descricao: 'Hora Extra 50%' },
      { codigo: '003', descricao: 'Hora Extra 100%' },
      { codigo: '004', descricao: 'Adicional Noturno' },
      { codigo: '005', descricao: 'Adicional Periculosidade' },
      { codigo: '006', descricao: 'Adicional Insalubridade' },
      { codigo: '007', descricao: 'Comissão' },
      { codigo: '008', descricao: 'Bonificação' },
      { codigo: '009', descricao: 'Gratificação' },
      { codigo: '010', descricao: 'DSR sobre Horas Extras' },
      { codigo: '011', descricao: '13º Salário' },
      { codigo: '012', descricao: 'Férias' },
      { codigo: 'MANUAL', descricao: 'Outro (digitar manualmente)' }
    ];
    preencherSelect('provento', sugestoes);
  }

  function preencherSugestoesDesconto() {
    const sugestoes = [
      { codigo: '091', descricao: 'INSS' },
      { codigo: '092', descricao: 'IRRF' },
      { codigo: '093', descricao: 'Vale Transporte' },
      { codigo: '094', descricao: 'Vale Refeição' },
      { codigo: '095', descricao: 'Vale Alimentação' },
      { codigo: '096', descricao: 'Plano de Saúde' },
      { codigo: '097', descricao: 'Plano Odontológico' },
      { codigo: '098', descricao: 'Seguro de Vida' },
      { codigo: '099', descricao: 'Pensão Alimentícia' },
      { codigo: '100', descricao: 'Adiantamento Salarial' },
      { codigo: '101', descricao: 'Falta Injustificada' },
      { codigo: '102', descricao: 'Atraso' },
      { codigo: 'MANUAL', descricao: 'Outro (digitar manualmente)' }
    ];
    preencherSelect('desconto', sugestoes, true);
  }

  function preencherSelect(tipo, sugestoes, autoSuggest = false) {
    const select = document.getElementById(`${tipo}-codigo`);
    const inputDescricao = document.getElementById(`${tipo}-descricao`);
    const valorInput = document.getElementById(`${tipo}-valor`);

    if (!select) return;
    select.innerHTML = '<option value="">Selecione uma opção...</option>';
    sugestoes.forEach(s => {
      const option = document.createElement('option');
      option.value = s.codigo;
      option.textContent = `${s.codigo} - ${s.descricao}`;
      option.dataset.descricao = s.descricao;
      select.appendChild(option);
    });

    select.onchange = (e) => {
      const selected = e.target.selectedOptions[0];
      if (!selected) return;
      const codigo = selected.value;
      if (selected && selected.dataset.descricao) {
        inputDescricao.value = selected.dataset.descricao;
      }
      if (selected.value === 'MANUAL') {
        if (inputDescricao) { inputDescricao.value = ''; inputDescricao.focus(); }
        if (valorInput) valorInput.value = '';
        return;
      }

      if (autoSuggest && (codigo === '091' || codigo === '092')) {
        const base = obterTotalBaseParaCalculos();
        const dependentes = parseInt(document.getElementById('num-dependentes')?.value) || 0;
        if (codigo === '091') {
          const sugest = CalculadoraFolha.calcularINSS(base);
          if (valorInput) valorInput.value = sugest.toFixed(2);
          mostrarNotificacao('Valor sugerido para INSS preenchido (edite se necessário).', 'info');
        } else if (codigo === '092') {
          const inssCalc = CalculadoraFolha.calcularINSS(base);
          const baseIR = Math.max(0, base - inssCalc);
          const sugest = CalculadoraFolha.calcularIRRF(baseIR, dependentes);
          if (valorInput) valorInput.value = sugest.toFixed(2);
          mostrarNotificacao('Valor sugerido para IRRF preenchido (edite se necessário).', 'info');
        }
      }
    };
  }

  function renderizarProventos() {
    if (!tbodyProventos) return;
    tbodyProventos.innerHTML = '';
    
    if (gerenciador.proventos.length === 0) {
      tbodyProventos.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Nenhum provento adicionado</td></tr>';
      return;
    }

    gerenciador.proventos.forEach((item, index) => {
      const valorNum = Number(item.valor) || 0;
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><input type="text" class="form-control form-control-sm" value="${item.codigo || ''}" data-tipo="provento" data-index="${index}" data-campo="codigo"></td>
        <td><input type="text" class="form-control form-control-sm" value="${item.descricao || ''}" data-tipo="provento" data-index="${index}" data-campo="descricao"></td>
        <td><input type="text" class="form-control form-control-sm" value="${item.referencia || ''}" data-tipo="provento" data-index="${index}" data-campo="referencia"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm text-end" value="${valorNum.toFixed(2)}" data-tipo="provento" data-index="${index}" data-campo="valor"></td>
        <td class="text-center">
          <button class="btn btn-sm btn-danger btn-remover" data-tipo="provento" data-index="${index}">
            <i class="bi bi-trash"></i>
          </button>
        </td>
      `;
      tbodyProventos.appendChild(tr);
    });

    adicionarListenersEdicao();
  }

  function renderizarDescontos() {
    if (!tbodyDescontos) return;
    tbodyDescontos.innerHTML = '';
    
    if (gerenciador.descontos.length === 0) {
      tbodyDescontos.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Nenhum desconto adicionado</td></tr>';
      return;
    }

    gerenciador.descontos.forEach((item, index) => {
      const valorNum = Number(item.valor) || 0;
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><input type="text" class="form-control form-control-sm" value="${item.codigo || ''}" data-tipo="desconto" data-index="${index}" data-campo="codigo"></td>
        <td><input type="text" class="form-control form-control-sm" value="${item.descricao || ''}" data-tipo="desconto" data-index="${index}" data-campo="descricao"></td>
        <td><input type="text" class="form-control form-control-sm" value="${item.referencia || ''}" data-tipo="desconto" data-index="${index}" data-campo="referencia"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm text-end" value="${valorNum.toFixed(2)}" data-tipo="desconto" data-index="${index}" data-campo="valor"></td>
        <td class="text-center">
          <button class="btn btn-sm btn-danger btn-remover" data-tipo="desconto" data-index="${index}">
            <i class="bi bi-trash"></i>
          </button>
        </td>
      `;
      tbodyDescontos.appendChild(tr);
    });

    adicionarListenersEdicao();
  }

  function adicionarListenersEdicao() {
    document.querySelectorAll('input[data-tipo]').forEach(input => {
      const handler = (e) => {
        const tipo = e.target.dataset.tipo;
        const index = parseInt(e.target.dataset.index, 10);
        const campo = e.target.dataset.campo;
        let valor = e.target.value;

        const lista = tipo === 'provento' ? gerenciador.proventos : gerenciador.descontos;
        if (!lista || !lista[index]) return;

        if (campo === 'valor') {
          valor = String(valor).replace(/\s+/g, '').replace(',', '.');
          lista[index][campo] = isNaN(parseFloat(valor)) ? 0 : parseFloat(valor);
        } else {
          lista[index][campo] = valor;
        }
        atualizarTotais();
      };

      input.removeEventListener('input', input._holeriteHandler);
      input.removeEventListener('change', input._holeriteHandler);
      input._holeriteHandler = handler;
      input.addEventListener('input', handler);
      input.addEventListener('change', handler);
    });

    document.querySelectorAll('.btn-remover').forEach(btn => {
      btn.removeEventListener('click', btn._holeriteRemover);
      const remover = (e) => {
        const tipo = e.currentTarget.dataset.tipo;
        const index = parseInt(e.currentTarget.dataset.index, 10);
        if (confirm('Deseja remover este item?')) {
          gerenciador.removerItem(tipo, index);
          if (tipo === 'provento') renderizarProventos();
          else renderizarDescontos();
          atualizarTotais();
          mostrarNotificacao('Item removido!', 'info');
        }
      };
      btn._holeriteRemover = remover;
      btn.addEventListener('click', remover);
    });
  }

  function atualizarTotais() {
    const totalProv = gerenciador.getTotalProventos();
    const totalDesc = gerenciador.getTotalDescontos();
    const liquido = gerenciador.getSalarioLiquido();

    if (totalProventosEl) totalProventosEl.textContent = formatarMoeda(totalProv);
    if (totalDescontosEl) totalDescontosEl.textContent = formatarMoeda(totalDesc);
    if (salarioLiquidoEl) salarioLiquidoEl.textContent = formatarMoeda(liquido);

    const baseINSS = document.getElementById('base-inss');
    const baseFGTS = document.getElementById('base-fgts');
    const baseIRRF = document.getElementById('base-irrf');
    const valorFGTS = document.getElementById('valor-fgts');

    if (baseINSS) baseINSS.textContent = formatarMoeda(totalProv);
    if (baseFGTS) baseFGTS.textContent = formatarMoeda(totalProv);
    
    const inssDescontado = gerenciador.descontos.find(d => String(d.codigo) === '091');
    const baseIRRFCalc = totalProv - (inssDescontado ? Number(inssDescontado.valor || 0) : 0);
    if (baseIRRF) baseIRRF.textContent = formatarMoeda(baseIRRFCalc);
    
    const fgts = CalculadoraFolha.calcularFGTS(totalProv);
    if (valorFGTS) valorFGTS.textContent = formatarMoeda(fgts);
  }

  function calcularAutomatico() {
    const salarioBruto = gerenciador.getTotalProventos();
    const dependentes = parseInt(document.getElementById('num-dependentes')?.value) || 0;

    if (salarioBruto <= 0) {
      mostrarNotificacao('Adicione proventos antes de calcular', 'warning');
      return;
    }

    gerenciador.descontos = gerenciador.descontos.filter(d => d.codigo !== '091' && d.codigo !== '092');

    const inss = CalculadoraFolha.calcularINSS(salarioBruto);
    const percentualINSS = CalculadoraFolha.calcularPercentualINSS(salarioBruto);
    gerenciador.adicionarDesconto('091', 'INSS', `${percentualINSS}%`, inss);

    const baseIRRF = salarioBruto - inss;
    const irrf = CalculadoraFolha.calcularIRRF(baseIRRF, dependentes);
    if (irrf > 0) {
      const percentualIRRF = ((irrf / baseIRRF) * 100).toFixed(2);
      gerenciador.adicionarDesconto('092', 'IRRF', `${percentualIRRF}%`, irrf);
    }

    renderizarDescontos();
    atualizarTotais();
    mostrarNotificacao('Cálculos automáticos aplicados! Você pode editar os valores manualmente.', 'success');
  }

  async function salvarHolerite() {
    
    try {
      mostrarLoading(true);
      const mesReferencia = document.getElementById('mes-referencia')?.value;
      
      if (!mesReferencia) {
        mostrarNotificacao('Selecione o mês de referência', 'warning');
        return;
      }

      if (gerenciador.proventos.length === 0) {
        mostrarNotificacao('Adicione pelo menos um provento', 'warning');
        return;
      }

      const dados = {
        colaboradorId,
        mesReferencia,
        proventos: gerenciador.proventos,
        descontos: gerenciador.descontos,
        totalProventos: gerenciador.getTotalProventos(),
        totalDescontos: gerenciador.getTotalDescontos(),
        salarioLiquido: gerenciador.getSalarioLiquido()
      };

      const response = await fetch(`${BACKEND_URL}/api/gestor/holerite/salvar`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${authToken}` },
        body: JSON.stringify(dados)
      });

      const result = await response.json();

      if (result.success) {
        mostrarNotificacao('Holerite salvo com sucesso!', 'success');
      } else {
        throw new Error(result.erro || 'Erro ao salvar');
      }
    } catch (error) {
      console.error('Erro:', error);
      mostrarNotificacao('Erro ao salvar holerite: ' + error.message, 'danger');
    } finally {
      mostrarLoading(false);
    }
  }

  function gerarPDF() {
    const nomeColaborador = document.getElementById('nome-colaborador')?.textContent || 'Colaborador';
    const cpf = document.getElementById('cpf-colaborador')?.textContent || '';
    const cargo = document.getElementById('cargo-colaborador')?.textContent || '';
    const codigo = document.getElementById('codigo-colaborador')?.textContent || '';
    const nomeEmpresa = document.getElementById('nome-empresa')?.textContent || 'EMPRESA LTDA';
    const cnpjEmpresa = document.getElementById('cnpj-empresa')?.textContent || '';
    const mesRef = document.getElementById('mes-referencia')?.value || new Date().toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' });

    let htmlProventos = '';
    gerenciador.proventos.forEach(p => {
      htmlProventos += `
        <tr>
          <td>${p.codigo}</td>
          <td>${p.descricao}</td>
          <td class="text-center">${p.referencia}</td>
          <td class="text-right">${formatarMoeda(p.valor)}</td>
          <td></td>
        </tr>
      `;
    });

    let htmlDescontos = '';
    gerenciador.descontos.forEach(d => {
      htmlDescontos += `
        <tr>
          <td>${d.codigo}</td>
          <td>${d.descricao}</td>
          <td class="text-center">${d.referencia}</td>
          <td></td>
          <td class="text-right">${formatarMoeda(d.valor)}</td>
        </tr>
      `;
    });

    const viaHTML = `
      <div class="container-via" style="max-width:800px;margin:0 auto;border:2px solid #000;padding:12px;margin-bottom:18px;">
        <div style="text-align:center;margin-bottom:8px;border-bottom:1px solid #000;padding-bottom:6px;">
          <h3 style="margin:0;font-size:14px;">RECIBO DE PAGAMENTO DE SALÁRIO</h3>
          <div style="font-size:12px;margin-top:6px;"><strong>${nomeEmpresa}</strong> - CNPJ: ${cnpjEmpresa}</div>
          <div style="margin-top:4px;"><strong>Referente ao mês: ${mesRef}</strong></div>
        </div>

        <div style="display:flex;justify-content:space-between;margin-top:8px;">
          <div><strong>Código:</strong> ${codigo}<br><strong>Nome:</strong> ${nomeColaborador}</div>
          <div><strong>CPF:</strong> ${cpf}<br><strong>Função:</strong> ${cargo}</div>
        </div>

        <table style="width:100%;border-collapse:collapse;margin-top:10px;">
          <thead>
            <tr>
              <th style="border:1px solid #000;padding:4px;width:10%;">CÓDIGOS</th>
              <th style="border:1px solid #000;padding:4px;width:45%;">DESCRIÇÕES</th>
              <th style="border:1px solid #000;padding:4px;width:15%;">REFERÊNCIAS</th>
              <th style="border:1px solid #000;padding:4px;width:15%;">PROVENTOS</th>
              <th style="border:1px solid #000;padding:4px;width:15%;">DESCONTOS</th>
            </tr>
          </thead>
          <tbody>
            ${htmlProventos}
            ${htmlDescontos}
            <tr>
              <td colspan="3" style="border:1px solid #000;padding:6px;"><strong>Totais</strong></td>
              <td style="border:1px solid #000;padding:6px;text-align:right;">${formatarMoeda(gerenciador.getTotalProventos())}</td>
              <td style="border:1px solid #000;padding:6px;text-align:right;">${formatarMoeda(gerenciador.getTotalDescontos())}</td>
            </tr>
            <tr>
              <td colspan="4" style="border:1px solid #000;padding:6px;text-align:right;"><strong>SALÁRIO LÍQUIDO</strong></td>
              <td style="border:1px solid #000;padding:6px;text-align:right;"><strong>${formatarMoeda(gerenciador.getSalarioLiquido())}</strong></td>
            </tr>
          </tbody>
        </table>

        <div style="margin-top:12px;display:flex;justify-content:space-between;">
          <div style="width:45%;text-align:center;border-top:1px solid #000;padding-top:8px;">Assinatura do Colaborador</div>
          <div style="width:45%;text-align:center;border-top:1px solid #000;padding-top:8px;">Data: ${new Date().toLocaleDateString('pt-BR')}</div>
        </div>
      </div>
    `;
    
    // **NOVA LÓGICA: Usar um Blob/URL para download e abrir para impressão**

    // 1. Criar um blob com o HTML
    const blob = new Blob([`
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Holerite ${nomeColaborador}</title>
            <style>
                @media print { body { margin:0; } .container-via { page-break-after: avoid; } } 
                body { font-family:Arial,Helvetica,sans-serif; font-size:12px; padding:12px; }
            </style>
        </head>
        <body>
          ${viaHTML}
          ${viaHTML}
          <script>
            window.onload = function() { 
                try { window.focus(); } catch(e){}
                // Chama a janela de impressão automaticamente
                window.print(); 
            };
          </script>
        </body>
        </html>
    `], { type: 'text/html' });
    
    const url = URL.createObjectURL(blob);
    
    // 2. Tentar abrir para impressão
    const janelaImpressao = window.open(url, '_blank');
    
    // 3. Se a janela não abriu (bloqueio de pop-up), forçar o download
    if (!janelaImpressao) {
        mostrarNotificacao('Bloqueio de pop-up impediu a impressão. Tentando download automático...', 'warning');
        const a = document.createElement('a');
        a.href = url;
        a.download = `Holerite_${nomeColaborador.replace(/\s/g, '_')}_${mesRef.replace('/', '-')}.pdf`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
    // Se a janela abriu, ela já chamou window.print() no seu próprio onload
  }

  async function enviarEmail() {
    // ... (nenhuma mudança nesta função interna)
    if (!confirm('Deseja enviar este holerite por e-mail para o colaborador?')) return;

    try {
      mostrarLoading(true);
      const email = document.getElementById('email-colaborador')?.value;
      const mesReferencia = document.getElementById('mes-referencia')?.value;

      if (!email) {
        mostrarNotificacao('E-mail do colaborador não informado', 'warning');
        return;
      }

      const dados = {
        colaboradorId,
        email,
        mesReferencia,
        proventos: gerenciador.proventos,
        descontos: gerenciador.descontos,
        totalProventos: gerenciador.getTotalProventos(),
        totalDescontos: gerenciador.getTotalDescontos(),
        salarioLiquido: gerenciador.getSalarioLiquido(),
        
      };

      const response = await fetch(`${BACKEND_URL}/api/gestor/holerite/enviar-email`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dados)
      });

      const result = await response.json();

      if (result.success) {
        mostrarNotificacao('Holerite enviado por e-mail com sucesso!', 'success');
      } else {
        throw new Error(result.erro || 'Erro ao enviar');
      }
    } catch (error) {
      console.error('Erro:', error);
      mostrarNotificacao('Erro ao enviar holerite: ' + error.message, 'danger');
    } finally {
      mostrarLoading(false);
    }
  }

  // Expor globalmente (útil para debug)
  window.GerenciadorHolerite = gerenciador;
  window.atualizarTotaisHolerite = atualizarTotais;
});