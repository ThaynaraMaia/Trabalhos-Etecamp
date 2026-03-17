// frontend/public/js/holeriteColaborador.js
(function() {
  'use strict';

  const BACKEND_URL = (typeof window !== 'undefined' && window.BACKEND_URL && window.BACKEND_URL !== '{{BACKEND_URL}}')
    ? window.BACKEND_URL
    : (window.location.hostname === 'localhost' && window.location.port === '3000')
      ? 'http://localhost:3001'
      : window.location.origin;

  console.log('🎯 BACKEND_URL configurado:', BACKEND_URL);

  let holeritesCacheados = [];
  let holeriteAtualPreview = null;

  /* ============================
     FUNÇÕES AUXILIARES
     ============================ */
  
  function formatarMoeda(valor) {
    try {
      return parseFloat(valor || 0).toLocaleString('pt-BR', { 
        style: 'currency', 
        currency: 'BRL' 
      });
    } catch (e) {
      return 'R$ 0,00';
    }
  }

  function formatarMesReferencia(dataString) {
    try {
      if (!dataString) return 'Data não informada';
      
      // Se já estiver no formato YYYY-MM
      if (typeof dataString === 'string' && dataString.match(/^\d{4}-\d{2}$/)) {
        const [ano, mes] = dataString.split('-');
        const meses = [
          'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
          'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
        ];
        return `${meses[parseInt(mes) - 1]}/${ano}`;
      }
      
      // Se vier como Date do MySQL (YYYY-MM-DD)
      const partes = dataString.split('-');
      if (partes.length >= 2) {
        const ano = partes[0];
        const mes = parseInt(partes[1]) - 1;
        const meses = [
          'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
          'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
        ];
        return `${meses[mes]}/${ano}`;
      }
      
      return dataString;
    } catch (error) {
      console.error('❌ Erro ao formatar data:', error, dataString);
      return dataString;
    }
  }

  function obterUsuarioId() {
    console.log('🔍 Tentando obter ID do usuário...');
    
    // 1. Tenta pegar do elemento hidden no HTML
    const hiddenInput = document.getElementById('debug-user-id');
    if (hiddenInput && hiddenInput.textContent) {
      const id = hiddenInput.textContent.trim();
      if (id && id !== '{{usuario.id}}' && id !== 'Carregando...' && id !== '') {
        console.log('✅ ID encontrado no elemento hidden:', id);
        return id;
      }
    }
    console.log('⚠️ ID não encontrado no elemento hidden');

    // 2. Tenta do data attribute
    const container = document.getElementById('holerites-container');
    if (container && container.dataset.userId) {
      const id = container.dataset.userId.trim();
      if (id && id !== '' && id !== '{{usuario.id}}') {
        console.log('✅ ID encontrado no data attribute:', id);
        return id;
      }
    }
    console.log('⚠️ ID não encontrado no data attribute');

    // 3. Tenta extrair do token
    try {
      const token = localStorage.getItem('token') || sessionStorage.getItem('token');
      if (token) {
        console.log('🔑 Token encontrado, decodificando...');
        const payload = JSON.parse(atob(token.split('.')[1]));
        const id = payload.id || payload.userId || payload.sub || payload.user_id;
        if (id) {
          console.log('✅ ID encontrado no token:', id);
          return id;
        }
      }
    } catch (e) {
      console.warn('⚠️ Erro ao decodificar token:', e);
    }

    // 4. Tenta do objeto window.usuario (se o template injetou)
    if (typeof window.usuario !== 'undefined' && window.usuario && window.usuario.id) {
      console.log('✅ ID encontrado em window.usuario:', window.usuario.id);
      return window.usuario.id;
    }

    console.error('❌ ID do usuário não encontrado em nenhum lugar');
    return null;
  }

  function mostrarNotificacao(mensagem, tipo = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${tipo}`;
    toast.textContent = mensagem;
    toast.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 15px 20px;
      background: ${tipo === 'success' ? '#10b981' : tipo === 'error' ? '#ef4444' : '#3b82f6'};
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

  function mostrarLoading(mostrar) {
    let loading = document.getElementById('loading-overlay-holerites');
    
    if (mostrar) {
      if (!loading) {
        loading = document.createElement('div');
        loading.id = 'loading-overlay-holerites';
        loading.style.cssText = `
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(0, 0, 0, 0.7);
          display: flex;
          justify-content: center;
          align-items: center;
          z-index: 9998;
        `;
        loading.innerHTML = `
          <div style="text-align: center; color: white;">
            <div class="spinner-border" style="width: 3rem; height: 3rem;"></div>
            <div style="margin-top: 10px;">Carregando...</div>
          </div>
        `;
        document.body.appendChild(loading);
      }
      loading.style.display = 'flex';
    } else {
      if (loading) {
        loading.style.display = 'none';
      }
    }
  }

  /* ============================
     RENDERIZAÇÃO DE HOLERITES
     ============================ */
  
  function renderizarHolerites(holerites) {
    console.log('📋 Renderizando holerites:', holerites);
    
    const container = document.getElementById('holerites-container');
    const mensagemVazio = document.getElementById('mensagem-vazio');
    const debugStatus = document.getElementById('debug-status');

    if (!holerites || holerites.length === 0) {
      if (container) container.innerHTML = '';
      if (mensagemVazio) mensagemVazio.style.display = 'block';
      if (debugStatus) debugStatus.textContent = 'Nenhum holerite encontrado';
      return;
    }

    if (mensagemVazio) mensagemVazio.style.display = 'none';
    if (debugStatus) debugStatus.textContent = `${holerites.length} holerite(s) encontrado(s)`;
    
    if (!container) {
      console.error('❌ Container de holerites não encontrado');
      return;
    }

    container.innerHTML = holerites.map(h => {
      const salarioLiquido = h.salario_liquido || 0;
      const mesRef = formatarMesReferencia(h.mes_referencia);
      const dataCriacao = h.data_criacao 
        ? new Date(h.data_criacao).toLocaleDateString('pt-BR')
        : 'Data não disponível';

      return `
        <div class="holerite-card" data-id="${h.id}">
          <h4>
            <i class="bi bi-file-earmark-text"></i>
            Holerite
          </h4>
          <div class="mes-ref">
            <i class="bi bi-calendar3"></i> ${mesRef}
          </div>
          <div class="valor">
            ${formatarMoeda(salarioLiquido)}
          </div>
          <div style="font-size: 12px; opacity: 0.8; margin-top: 10px;">
            <i class="bi bi-clock"></i> Enviado em ${dataCriacao}
          </div>
          <div class="acoes">
            <button class="btn btn-visualizar" onclick="window.visualizarHolerite(${h.id})">
              <i class="bi bi-eye"></i> Ver
            </button>
            <button class="btn btn-download" onclick="window.baixarHoleritePDF(${h.id})">
              <i class="bi bi-download"></i> Baixar
            </button>
          </div>
        </div>
      `;
    }).join('');

    console.log('✅ Holerites renderizados com sucesso');
  }

  /* ============================
     CARREGAR HOLERITES
     ============================ */
  
  async function carregarHolerites() {
    try {
      console.log('🔄 Iniciando carregamento de holerites...');
      mostrarLoading(true);

      const usuarioId = obterUsuarioId();
      
      if (!usuarioId || usuarioId === 'undefined' || usuarioId === '') {
        console.error('❌ ID do usuário não encontrado');
        const debugStatus = document.getElementById('debug-status');
        if (debugStatus) {
          debugStatus.textContent = 'ERRO: ID do usuário não encontrado. Faça login novamente.';
        }
        mostrarNotificacao('Erro: Não foi possível identificar o usuário', 'error');
        return;
      }

      console.log('👤 ID do usuário:', usuarioId);
      
      const debugUserId = document.getElementById('debug-user-id');
      if (debugUserId) {
        debugUserId.textContent = usuarioId;
      }
      
      const debugStatus = document.getElementById('debug-status');
      if (debugStatus) {
        debugStatus.textContent = 'Buscando no servidor...';
      }

      const url = `${BACKEND_URL}/api/colaborador/${usuarioId}`;
      console.log('🌐 URL da requisição:', url);

      const response = await fetch(url, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json'
        }
      });

      console.log('📡 Status da resposta:', response.status);

      if (!response.ok) {
        const errorText = await response.text();
        console.error('❌ Erro na resposta:', errorText);
        throw new Error(`Erro ${response.status}: ${errorText}`);
      }

      let resultado = null;
      try {
        resultado = await response.json();
      } catch (err) {
        console.error('❌ Não foi possível parsear JSON:', err);
        const debugStatus = document.getElementById('debug-status');
        if (debugStatus) {
          debugStatus.textContent = 'ERRO: resposta inválida do servidor';
        }
        mostrarNotificacao('Erro ao processar resposta do servidor', 'error');
        return;
      }

      console.log('📦 Resultado completo:', resultado);

      // Normaliza formato: aceita vários formatos de resposta
      let holerites = [];
      if (Array.isArray(resultado)) {
        holerites = resultado;
      } else if (resultado.holerites && Array.isArray(resultado.holerites)) {
        holerites = resultado.holerites;
      } else if (resultado.data && Array.isArray(resultado.data)) {
        holerites = resultado.data;
      } else if (resultado.success && resultado.holerites) {
        holerites = Array.isArray(resultado.holerites) ? resultado.holerites : [resultado.holerites];
      } else if (resultado.holerite && !Array.isArray(resultado.holerite)) {
        // Backend retornou UM objeto holerite
        holerites = [resultado.holerite];
        console.warn('⚠️ Resposta continha um único objeto holerite — convertendo para array.');
      }

      holeritesCacheados = holerites || [];
      renderizarHolerites(holeritesCacheados);
      
      console.log(`✅ ${holeritesCacheados.length} holerite(s) carregado(s)`);
      mostrarNotificacao(`${holeritesCacheados.length} holerite(s) encontrado(s)`, 'success');

    } catch (error) {
      console.error('❌ Erro ao carregar holerites:', error);
      const debugStatus = document.getElementById('debug-status');
      if (debugStatus) {
        debugStatus.textContent = `ERRO: ${error.message}`;
      }
      const mensagemVazio = document.getElementById('mensagem-vazio');
      if (mensagemVazio) {
        mensagemVazio.style.display = 'block';
      }
      mostrarNotificacao('Erro ao carregar holerites: ' + error.message, 'error');
    } finally {
      mostrarLoading(false);
    }
  }

  /* ============================
     PREVIEW DE HOLERITE
     ============================ */
  
  function visualizarHolerite(id) {
    console.log('👁️ Visualizando holerite:', id);
    
    const holerite = holeritesCacheados.find(h => h.id === id);
    
    if (!holerite) {
      console.error('❌ Holerite não encontrado:', id);
      mostrarNotificacao('Holerite não encontrado', 'error');
      return;
    }

    holeriteAtualPreview = holerite;
    
    // Parse dos dados JSON
    let proventos = [];
    let descontos = [];
    
    try {
      proventos = typeof holerite.proventos_detalhe === 'string' 
        ? JSON.parse(holerite.proventos_detalhe) 
        : (holerite.proventos_detalhe || []);
    } catch (e) {
      console.warn('⚠️ Erro ao parsear proventos:', e);
    }
    
    try {
      descontos = typeof holerite.descontos_detalhe === 'string' 
        ? JSON.parse(holerite.descontos_detalhe) 
        : (holerite.descontos_detalhe || []);
    } catch (e) {
      console.warn('⚠️ Erro ao parsear descontos:', e);
    }

    // Gerar HTML do preview
    const htmlPreview = gerarHTMLPreview(holerite, proventos, descontos);
    
    // Inserir no modal
    const previewContainer = document.getElementById('preview-holerite-content');
    if (previewContainer) {
      previewContainer.innerHTML = htmlPreview;
    }
    
    // Mostrar modal
    const modal = document.getElementById('modal-preview-holerite');
    if (modal) {
      modal.style.display = 'flex';
    }
  }

  function gerarHTMLPreview(holerite, proventos, descontos) {
    const mesRef = formatarMesReferencia(holerite.mes_referencia);
    const totalProventos = proventos.reduce((sum, p) => sum + (parseFloat(p.valor) || 0), 0);
    const totalDescontos = descontos.reduce((sum, d) => sum + (parseFloat(d.valor) || 0), 0);

    let htmlProventos = '';
    proventos.forEach(p => {
      htmlProventos += `
        <tr>
          <td style="border: 1px solid #ddd; padding: 8px;">${p.codigo || '-'}</td>
          <td style="border: 1px solid #ddd; padding: 8px;">${p.descricao || '-'}</td>
          <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">${p.referencia || '-'}</td>
          <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"><strong>${formatarMoeda(p.valor)}</strong></td>
          <td style="border: 1px solid #ddd; padding: 8px;"></td>
        </tr>
      `;
    });

    let htmlDescontos = '';
    descontos.forEach(d => {
      htmlDescontos += `
        <tr>
          <td style="border: 1px solid #ddd; padding: 8px;">${d.codigo || '-'}</td>
          <td style="border: 1px solid #ddd; padding: 8px;">${d.descricao || '-'}</td>
          <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">${d.referencia || '-'}</td>
          <td style="border: 1px solid #ddd; padding: 8px;"></td>
          <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"><strong>${formatarMoeda(d.valor)}</strong></td>
        </tr>
      `;
    });

    return `
      <div style="background: white; padding: 30px; color: #000; font-family: Arial, sans-serif;">
        <div style="text-align: center; border-bottom: 2px solid #667eea; padding-bottom: 15px; margin-bottom: 20px;">
          <h2 style="margin: 0; color: #667eea;">RECIBO DE PAGAMENTO DE SALÁRIO</h2>
          <p style="margin: 5px 0;"><strong>Mês de Referência:</strong> ${mesRef}</p>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
          <thead>
            <tr style="background: #667eea; color: white;">
              <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Código</th>
              <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Descrição</th>
              <th style="border: 1px solid #ddd; padding: 10px; text-align: center;">Referência</th>
              <th style="border: 1px solid #ddd; padding: 10px; text-align: right;">Proventos</th>
              <th style="border: 1px solid #ddd; padding: 10px; text-align: right;">Descontos</th>
            </tr>
          </thead>
          <tbody>
            ${htmlProventos}
            ${htmlDescontos}
            <tr style="background: #f0f0f0; font-weight: bold;">
              <td colspan="3" style="border: 1px solid #ddd; padding: 10px;">TOTAIS</td>
              <td style="border: 1px solid #ddd; padding: 10px; text-align: right;">${formatarMoeda(totalProventos)}</td>
              <td style="border: 1px solid #ddd; padding: 10px; text-align: right;">${formatarMoeda(totalDescontos)}</td>
            </tr>
            <tr style="background: #667eea; color: white; font-weight: bold; font-size: 1.1em;">
              <td colspan="4" style="border: 1px solid #ddd; padding: 12px; text-align: right;">SALÁRIO LÍQUIDO</td>
              <td style="border: 1px solid #ddd; padding: 12px; text-align: right;">${formatarMoeda(holerite.salario_liquido)}</td>
            </tr>
          </tbody>
        </table>

        <div style="margin-top: 30px; text-align: center; color: #666; font-size: 0.9em;">
          <p>Documento gerado em ${new Date().toLocaleDateString('pt-BR')}</p>
        </div>
      </div>
    `;
  }

  function fecharModalPreview() {
    const modal = document.getElementById('modal-preview-holerite');
    if (modal) {
      modal.style.display = 'none';
    }
    holeriteAtualPreview = null;
  }

  /* ============================
     DOWNLOAD DE HOLERITE EM PDF
     ============================ */
  
  async function baixarHoleritePDF(id) {
    try {
      console.log('⬇️ Baixando holerite PDF:', id);
      mostrarLoading(true);

      const holerite = holeritesCacheados.find(h => h.id === id);
      
      if (!holerite) {
        throw new Error('Holerite não encontrado');
      }

      // Parse dos dados
      let proventos = [];
      let descontos = [];
      
      try {
        proventos = typeof holerite.proventos_detalhe === 'string' 
          ? JSON.parse(holerite.proventos_detalhe) 
          : (holerite.proventos_detalhe || []);
      } catch (e) {
        console.warn('⚠️ Erro ao parsear proventos:', e);
      }
      
      try {
        descontos = typeof holerite.descontos_detalhe === 'string' 
          ? JSON.parse(holerite.descontos_detalhe) 
          : (holerite.descontos_detalhe || []);
      } catch (e) {
        console.warn('⚠️ Erro ao parsear descontos:', e);
      }

      // Gerar HTML completo para PDF
      const htmlCompleto = gerarHTMLCompletoPDF(holerite, proventos, descontos);
      
      // Criar Blob e fazer download
      const blob = new Blob([htmlCompleto], { type: 'text/html' });
      const url = URL.createObjectURL(blob);
      
      // Tentar abrir para impressão
      const janela = window.open(url, '_blank');
      
      if (!janela) {
        // Se bloqueado, fazer download direto
        const a = document.createElement('a');
        a.href = url;
        const mesRef = formatarMesReferencia(holerite.mes_referencia).replace('/', '-');
        a.download = `Holerite_${mesRef}.html`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        mostrarNotificacao('Download iniciado!', 'success');
      } else {
        mostrarNotificacao('Holerite aberto para impressão', 'success');
      }

    } catch (error) {
      console.error('❌ Erro ao baixar holerite:', error);
      mostrarNotificacao('Erro ao baixar holerite: ' + error.message, 'error');
    } finally {
      mostrarLoading(false);
    }
  }

  function gerarHTMLCompletoPDF(holerite, proventos, descontos) {
    const htmlPreview = gerarHTMLPreview(holerite, proventos, descontos);
    
    return `
      <!DOCTYPE html>
      <html>
      <head>
        <meta charset="UTF-8">
        <title>Holerite - ${formatarMesReferencia(holerite.mes_referencia)}</title>
        <style>
          @media print {
            body { margin: 0; padding: 0; }
            @page { margin: 15mm; }
          }
          body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            padding: 20px;
          }
        </style>
      </head>
      <body>
        ${htmlPreview}
        <script>
          window.onload = function() {
            try { window.focus(); } catch(e){}
            window.print();
          };
        </script>
      </body>
      </html>
    `;
  }

  /* ============================
     FILTRO POR MÊS
     ============================ */
  
  function filtrarPorMes() {
    const mesInput = document.getElementById('buscaMes');
    if (!mesInput) return;
    
    const mesValor = mesInput.value;
    console.log('🔍 Filtrando por mês:', mesValor);
    
    if (!mesValor) {
      renderizarHolerites(holeritesCacheados);
      return;
    }

    const holeriteFiltrado = holeritesCacheados.filter(h => {
      const mesRef = h.mes_referencia || '';
      return mesRef.startsWith(mesValor);
    });

    console.log('📋 Holerites filtrados:', holeriteFiltrado.length);
    renderizarHolerites(holeriteFiltrado);
  }

  /* ============================
     EVENTOS
     ============================ */
  
  function configurarEventos() {
    // Botão atualizar
    const btnAtualizar = document.getElementById('btnAtualizar');
    if (btnAtualizar) {
      btnAtualizar.addEventListener('click', () => {
        console.log('🔄 Botão atualizar clicado');
        carregarHolerites();
      });
    }
    
    // Filtro por mês
    const buscaMes = document.getElementById('buscaMes');
    if (buscaMes) {
      buscaMes.addEventListener('change', filtrarPorMes);
    }

    // Fechar modal
    const btnFecharModal = document.getElementById('btn-fechar-preview-holerite');
    if (btnFecharModal) {
      btnFecharModal.addEventListener('click', fecharModalPreview);
    }

    // Clicar fora do modal
    const modal = document.getElementById('modal-preview-holerite');
    if (modal) {
      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          fecharModalPreview();
        }
      });
    }

    // Botão baixar PDF do preview
    const btnBaixarPreview = document.getElementById('btn-baixar-pdf-preview');
    if (btnBaixarPreview) {
      btnBaixarPreview.addEventListener('click', () => {
        if (holeriteAtualPreview) {
          baixarHoleritePDF(holeriteAtualPreview.id);
        }
      });
    }

    console.log('✅ Eventos configurados');
  }

  /* ============================
     AUTO-ATUALIZAÇÃO
     ============================ */
  
  function iniciarAutoAtualizacao() {
    setInterval(() => {
      console.log('⏰ Auto-atualização...');
      carregarHolerites();
    }, 30000); // 30 segundos
  }

  /* ============================
     EXPOR FUNÇÕES GLOBAIS
     ============================ */
  
  window.visualizarHolerite = visualizarHolerite;
  window.baixarHoleritePDF = baixarHoleritePDF;
  window.fecharModalPreviewHolerite = fecharModalPreview;

  /* ============================
     INICIALIZAÇÃO
     ============================ */
  
  document.addEventListener('DOMContentLoaded', () => {
    console.log('🚀 Página carregada, iniciando sistema de holerites...');
    
    // Verificar se os elementos necessários existem
    const elementosNecessarios = [
      'holerites-container',
      'mensagem-vazio',
      'debug-user-id',
      'debug-status'
    ];
    
    const elementosFaltando = [];
    elementosNecessarios.forEach(id => {
      if (!document.getElementById(id)) {
        elementosFaltando.push(id);
        console.warn(`⚠️ Elemento não encontrado: #${id}`);
      }
    });
    
    if (elementosFaltando.length > 0) {
      console.error('❌ Elementos HTML faltando:', elementosFaltando.join(', '));
      mostrarNotificacao('Erro: Estrutura HTML incompleta', 'error');
    }
    
    // Verificar se conseguimos obter o ID do usuário
    const usuarioId = obterUsuarioId();
    if (!usuarioId) {
      console.error('❌ Não foi possível obter ID do usuário no carregamento');
      mostrarNotificacao('Erro: ID do usuário não encontrado. Faça login novamente.', 'error');
      return;
    }
    
    console.log('✅ ID do usuário detectado:', usuarioId);
    
    // Configurar eventos e iniciar
    configurarEventos();
    carregarHolerites();
    iniciarAutoAtualizacao();
  });

})();