// /js/analiseGestor.js
// VERSÃO CORRIGIDA: Integração completa com geração de PDF via templates Handlebars
// Gerencia modais de relatórios, previews com dados reais e export robusto

(function () {
  'use strict';

  // -------------------------
  // Helpers DOM & util
  // -------------------------
  const qs = (sel, ctx = document) => ctx.querySelector(sel);
  const qsa = (sel, ctx = document) => Array.from((ctx || document).querySelectorAll(sel));

  function isOverlay(el) {
    return el && el.classList && el.classList.contains('overlay-modal');
  }

  // Ui utils fallback (usa window.AnaliseFinanceira.Utils se disponível)
  function getUiUtils() {
    const possible = (window.AnaliseFinanceira && window.AnaliseFinanceira.Utils) 
      ? window.AnaliseFinanceira.Utils 
      : null;
    
    return possible || {
      showLoading: (show) => {
        const overlay = document.getElementById('loading-overlay-financeiro');
        if (show && !overlay) {
          const div = document.createElement('div');
          div.id = 'loading-overlay-financeiro';
          div.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:9999;display:flex;justify-content:center;align-items:center;';
          div.innerHTML = '<div style="color:#fff;text-align:center;"><div class="spinner-border"></div><div>Processando...</div></div>';
          document.body.appendChild(div);
        } else if (!show && overlay) {
          overlay.remove();
        }
      },
      showToast: (msg, type) => {
        const toast = document.createElement('div');
        toast.style.cssText = `position:fixed;top:20px;right:20px;padding:15px 20px;background:${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};color:#fff;border-radius:8px;z-index:10000;`;
        toast.textContent = msg;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
      },
      formatCurrency: (value) => {
        const num = parseFloat(value) || 0;
        return num.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
      },
      getToken: () => localStorage.getItem('token') || sessionStorage.getItem('token')
    };
  }

  // -------------------------
  // Coleta dados estruturados do preview
  // -------------------------
  async function gatherPreviewData(preview, tipo) {
    const table = preview.querySelector('table') || preview.querySelector('.glass-table');
    
    if (!table) {
      return { headers: [], rows: [], rawHtml: preview.innerHTML };
    }

    // Extrair headers
    const headers = Array.from(table.querySelectorAll('thead th'))
      .map(th => th.textContent.trim())
      .filter(h => h);

    // Extrair rows com conversão numérica automática
    const rows = Array.from(table.querySelectorAll('tbody tr')).map(tr => {
      const cells = Array.from(tr.querySelectorAll('th, td'));
      const obj = {};
      
      cells.forEach((cell, i) => {
        const key = headers[i] || `col${i + 1}`;
        const text = cell.textContent.trim();
        
        // Tentar converter badges para números
        const badge = cell.querySelector('.badge');
        if (badge) {
          const badgeNum = parseInt(badge.textContent);
          if (!isNaN(badgeNum)) obj[`${key}_badge`] = badgeNum;
        }
        
        obj[key] = text;
        
        // Tentar conversão numérica
        const num = parseLocaleNumber(text);
        if (num !== null) {
          obj[`${key}__number`] = num;
        }
      });
      
      return obj;
    });

    // Extrair footer (totais)
    const tfoot = table.querySelector('tfoot');
    const footerData = tfoot ? Array.from(tfoot.querySelectorAll('td, th')).map(td => ({
      text: td.textContent.trim(),
      number: parseLocaleNumber(td.textContent.trim())
    })) : [];

    return { 
      headers, 
      rows, 
      footerData,
      rawHtml: table.outerHTML 
    };
  }

  // Parse de número em notação local
  function parseLocaleNumber(str) {
    if (!str || typeof str !== 'string') return null;
    const cleaned = str.replace(/\s/g, '').replace(/[R$€£]/g, '').replace(/%/g, '');
    const hasComma = cleaned.indexOf(',') !== -1;
    let normalized;
    if (hasComma) {
      normalized = cleaned.replace(/\./g, '').replace(',', '.');
    } else {
      normalized = cleaned.replace(/,/g, '');
    }
    const num = parseFloat(normalized);
    return isFinite(num) ? num : null;
  }

  // -------------------------
  // Extrai filtros e metadata do overlay
  // -------------------------
  function extractMetadataFromOverlay(overlay) {
    const metadata = {
      filters: {},
      summary: '',
      title: '',
      tipo: ''
    };

    // Extrair título do modal
    const titleEl = overlay.querySelector('[id$="-title"]');
    if (titleEl) metadata.title = titleEl.textContent.trim();

    // Extrair tipo do relatório do ID do modal
    const modalId = overlay.id || '';
    metadata.tipo = modalId.replace('modal-', '').replace('overlay-', '');

    // Extrair filtros dos inputs
    const filterSelectors = [
      '#data-inicio-folha', '#data-fim-folha',
      '#departamento-folha', '#filial-folha', '#agrupar-folha',
      '#periodo-custo', '#agrupar-custo',
      '#periodo-beneficios', '#tipo-beneficio',
      'input[name="dataInicio"]', 'input[name="dataFim"]',
      'input[name="inicio"]', 'input[name="fim"]',
      'select[name="departamento"]', 'select[name="setor"]',
      'select[name="agrupar_por"]', 'select[name="periodo"]'
    ];

    filterSelectors.forEach(sel => {
      try {
        const el = overlay.querySelector(sel);
        if (el && el.value) {
          const key = (el.id || el.name || sel.replace(/[^a-zA-Z0-9_-]/g, '')).replace(/-/g, '_');
          const label = el.labels && el.labels[0] ? el.labels[0].textContent.trim() : key;
          
          // Para selects, pegar o texto da opção selecionada
          if (el.tagName === 'SELECT') {
            const selectedOption = el.options[el.selectedIndex];
            metadata.filters[label || key] = selectedOption ? selectedOption.textContent : el.value;
          } else {
            metadata.filters[label || key] = el.value;
          }
        }
      } catch (e) {
        // ignore selector errors
      }
    });

    // Extrair resumo
    const summaryEl = overlay.querySelector('.preview-summary');
    if (summaryEl) {
      metadata.summary = summaryEl.textContent.trim();
    }

    return metadata;
  }

  // -------------------------
  // Formatar dados para o template Handlebars
  // -------------------------
  function formatDataForTemplate(previewData, metadata, resumoCalculado, tipo) {
    const Ui = getUiUtils();
    
    // Função helper para formatar valores monetários
    const formatMoney = (val) => {
      if (typeof val === 'number') return Ui.formatCurrency(val);
      if (typeof val === 'string') {
        const num = parseLocaleNumber(val);
        return num !== null ? Ui.formatCurrency(num) : val;
      }
      return val;
    };

    // Processar rows adicionando versões formatadas
    const processedRows = (previewData.rows || []).map(row => {
      const processed = { ...row };
      
      Object.keys(row).forEach(key => {
        if (key.endsWith('__number')) {
          const baseKey = key.replace('__number', '');
          processed[`${baseKey}Formatted`] = formatMoney(row[key]);
        }
      });
      
      return processed;
    });

    // Calcular resumo se não foi fornecido
    let resumo = resumoCalculado || {};
    
    if (!resumo.totalBruto && processedRows.length > 0) {
      // Tentar calcular totais automaticamente
      const numericalKeys = Object.keys(processedRows[0])
        .filter(k => k.endsWith('__number'))
        .map(k => k.replace('__number', ''));
      
      numericalKeys.forEach(key => {
        const total = processedRows.reduce((sum, row) => {
          return sum + (row[`${key}__number`] || 0);
        }, 0);
        resumo[`total${key.charAt(0).toUpperCase() + key.slice(1)}`] = total;
        resumo[`total${key.charAt(0).toUpperCase() + key.slice(1)}Formatted`] = formatMoney(total);
      });
    } else {
      // Adicionar versões formatadas do resumo fornecido
      Object.keys(resumo).forEach(key => {
        if (!key.endsWith('Formatted') && typeof resumo[key] === 'number') {
          resumo[`${key}Formatted`] = formatMoney(resumo[key]);
        }
      });
    }

    // Determinar template baseado no tipo de relatório
    let templateName = 'financial_report'; // default
    if (tipo === 'custo' || tipo === 'custo_pessoal') {
      templateName = 'custo_report';
    } else if (tipo === 'beneficios' || tipo === 'gastos_beneficios') {
      templateName = 'beneficios_report';
    }

    // Estruturar dados para o template
    const templateData = {
      template: templateName,
      title: metadata.title || 'Relatório Financeiro',
      subtitle: metadata.summary || '',
      companyName: window.APP_COMPANY_NAME || window.COMPANY_NAME || 'Minha Empresa',
      companyTaxId: window.APP_COMPANY_TAXID || '',
      periodStart: metadata.filters['Data início'] || metadata.filters.dataInicio || '',
      periodEnd: metadata.filters['Data fim'] || metadata.filters.dataFim || '',
      filters: metadata.filters,
      resumo: resumo,
      rows: processedRows,
      groups: [], // Será populado se houver agrupamento
      generatedBy: window?.USER?.name || window?.CURRENT_USER_NAME || 'Sistema',
      generatedAt: new Date().toLocaleString('pt-BR'),
      footerText: 'Documento gerado automaticamente — confidencial',
      logoUrl: window.APP_LOGO_URL || '',
      notes: ''
    };

    // Se os dados estão agrupados (detectar por estrutura)
    if (processedRows.length > 0 && processedRows[0].grupo) {
      templateData.groups = processedRows.map(grupo => ({
        grupo: grupo.grupo || grupo.agrupamento || 'Grupo',
        quantidade: grupo.quantidade || grupo.total_colaboradores || 0,
        colaboradores: grupo.colaboradores || [],
        ...grupo
      }));
      templateData.rows = []; // Limpar rows se estamos usando groups
    }

    return templateData;
  }

  // -------------------------
  // Download PDF via servidor (robusto)
  // -------------------------
  async function downloadPdfFromServer(templateData, filename = 'relatorio.pdf') {
    const Ui = getUiUtils();
    
    // Determinar URL do backend
    const backendUrl = (window.BACKEND_URL && window.BACKEND_URL !== '{{BACKEND_URL}}')
      ? window.BACKEND_URL.replace(/\/$/, '')
      : (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1')
        ? `${window.location.protocol}//${window.location.hostname}:3001`
        : window.location.origin;

    const url = `${backendUrl}/api/pdf/generate`;
    
    console.log(' Gerando PDF via servidor:', url);
    console.log(' Dados do template:', templateData);

    // Pegar token de autenticação
    const token = Ui.getToken?.() || localStorage.getItem('token') || sessionStorage.getItem('token');

    const headers = { 'Content-Type': 'application/json' };
    if (token) headers['Authorization'] = `Bearer ${token}`;

    try {
      const response = await fetch(url, {
        method: 'POST',
        headers,
        body: JSON.stringify({
          template: templateData.template,
          data: templateData,
          options: {
            format: 'A4',
            printBackground: true,
            preferCSSPageSize: true,
            margin: {
              top: '15mm',
              right: '15mm',
              bottom: '20mm',
              left: '15mm'
            }
          }
        }),
        credentials: token ? 'omit' : 'include'
      });

      if (!response.ok) {
        let errorMsg = response.statusText;
        try {
          const contentType = response.headers.get('content-type') || '';
          if (contentType.includes('application/json')) {
            const errorData = await response.json();
            errorMsg = errorData.error || errorData.message || JSON.stringify(errorData);
          } else {
            errorMsg = await response.text();
          }
        } catch (e) {
          // ignore parse error
        }
        throw new Error(`Erro ao gerar PDF: ${response.status} - ${errorMsg}`);
      }

      const blob = await response.blob();
      
      // Verificar se realmente é um PDF
      if (blob.type !== 'application/pdf' && !blob.type.includes('pdf')) {
        console.warn('⚠️ Resposta não é PDF:', blob.type);
        // Tentar ler como texto para debug
        const text = await blob.text();
        console.error('Resposta do servidor:', text);
        throw new Error('Servidor não retornou um PDF válido');
      }

      downloadBlob(filename, blob);
      Ui.showToast('PDF gerado e baixado com sucesso!', 'success');
      
      return true;
    } catch (error) {
      console.error(' Erro ao gerar PDF:', error);
      throw error;
    }
  }

  // -------------------------
  // Util: download blob
  // -------------------------
  function downloadBlob(filename, blob) {
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    a.style.display = 'none';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    setTimeout(() => URL.revokeObjectURL(url), 1000);
  }

  // -------------------------
  // Util: table -> CSV
  // -------------------------
  function tableToCSV(tableEl) {
    const rows = [];
    const trs = tableEl.querySelectorAll('thead tr, tbody tr');
    trs.forEach(tr => {
      const cols = Array.from(tr.querySelectorAll('th, td')).map(td => {
        let text = td.textContent.trim();
        if (text.includes('"') || text.includes(',') || text.includes('\n')) {
          text = `"${text.replace(/"/g, '""')}"`;
        }
        return text;
      });
      rows.push(cols.join(','));
    });
    return rows.join('\n');
  }

  // -------------------------
  // Modal manager
  // -------------------------
  const openModals = new Set();
  let lastFocusedElement = null;

  function openModalById(modalId, opts = {}) {
    const overlay = document.getElementById(modalId);
    if (!overlay) {
      console.warn(`[analiseGestor] modal ${modalId} not found`);
      return;
    }

    const popup = overlay.querySelector('.modal-popup') || overlay.querySelector('.report-modal') || overlay;

    overlay.style.display = 'flex';
    setTimeout(() => popup.classList.add('show'), 10);

    lastFocusedElement = document.activeElement;
    const focusable = popup.querySelectorAll('a[href], button:not([disabled]), input, select, textarea, [tabindex]:not([tabindex="-1"])');
    if (focusable.length) focusable[0].focus();

    document.documentElement.classList.add('modal-open');
    document.body.style.overflow = 'hidden';

    openModals.add(overlay);

    if (opts.reportId) overlay.dataset.reportId = opts.reportId;
  }

  function closeModalByOverlay(overlay) {
    if (!overlay) return;
    const popup = overlay.querySelector('.modal-popup') || overlay.querySelector('.report-modal') || overlay;
    popup.classList.remove('show');

    setTimeout(() => {
      overlay.style.display = 'none';
      openModals.delete(overlay);
      try { if (lastFocusedElement) lastFocusedElement.focus(); } catch (e) {}
      if (openModals.size === 0) {
        document.documentElement.classList.remove('modal-open');
        document.body.style.overflow = '';
      }
    }, 210);
  }

  // Event listeners para fechar modal
  document.addEventListener('click', (e) => {
    const overlay = e.target.closest('.overlay-modal');
    if (overlay && e.target === overlay) {
      closeModalByOverlay(overlay);
    }
  });

  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-close-modal]');
    if (!btn) return;
    e.preventDefault();
    const modalId = btn.getAttribute('data-close-modal') || btn.closest('.overlay-modal')?.id;
    if (modalId) {
      const overlay = document.getElementById(modalId);
      if (overlay) closeModalByOverlay(overlay);
    } else {
      const overlay = btn.closest('.overlay-modal');
      if (overlay) closeModalByOverlay(overlay);
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      const overlays = Array.from(openModals);
      if (overlays.length) {
        const top = overlays[overlays.length - 1];
        closeModalByOverlay(top);
      }
    }
  });

  // -------------------------
  // Wire buttons
  // -------------------------
  function wireOpenModalButtons() {
    qsa('[data-open-modal]').forEach(btn => {
      btn.addEventListener('click', (ev) => {
        ev.preventDefault();
        const modalId = btn.getAttribute('data-open-modal');
        if (!modalId) return;
        const card = btn.closest('.report-card');
        const reportId = card?.dataset?.relatorio || modalId;
        openModalById(modalId, { reportId });
      });
    });
  }

  function wireResetButtons() {
    qsa('[data-action^="reset-"]').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const targetForm = btn.closest('.report-modal')?.querySelector('form.report-form');
        if (targetForm) {
          targetForm.reset();
          const overlay = btn.closest('.overlay-modal');
          if (overlay) {
            const preview = overlay.querySelector('.report-preview');
            if (preview) {
              preview.style.display = 'none';
              qsa('tbody', preview).forEach(tb => tb.innerHTML = '');
              const ps = preview.querySelector('.preview-summary');
              if (ps) ps.textContent = '';
              const chart = preview.querySelector('[data-chart]');
              if (chart) chart.innerHTML = '';
            }
          }
        }
      });
    });
  }

  // -------------------------
  // Download/export handler UNIFICADO
  // -------------------------
  function wireDownloadButtons() {
    qsa('[data-action^="download-preview-"]').forEach(btn => {
      btn.addEventListener('click', async (e) => {
        e.preventDefault();
        
        const tipo = btn.dataset.tipo; // 'folha', 'custo', 'beneficios'
        const overlay = btn.closest('.overlay-modal');
        
        if (!overlay) {
          alert('Erro: overlay não encontrado');
          return;
        }

        const formatSelect = overlay.querySelector(`#export-format-${tipo}`);
        const formato = formatSelect?.value || 'csv';

        await performExport(overlay, formato, tipo);
      });
    });
  }

  // -------------------------
  // Perform Export (MELHORADO)
  // -------------------------
  async function performExport(overlay, format, tipo) {
    const Ui = getUiUtils();

    if (!overlay) {
      alert('Erro: overlay não encontrado');
      return;
    }

    const preview = overlay.querySelector('.report-preview') || overlay;
    if (!preview) {
      alert('Erro: preview não encontrado');
      return;
    }

    const table = preview.querySelector('.glass-table') || preview.querySelector('table');

    // CSV
    if (format === 'csv') {
      if (!table) {
        alert('Nenhum dado disponível para exportar');
        return;
      }
      const csv = tableToCSV(table);
      const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' }); // BOM para Excel
      const filename = `relatorio_${tipo}_${new Date().toISOString().slice(0,10)}.csv`;
      downloadBlob(filename, blob);
      Ui.showToast('CSV exportado com sucesso!', 'success');
      return;
    }

    // Excel (SheetJS se disponível)
    if (format === 'excel') {
      if (!table) {
        alert('Nenhum dado disponível para exportar');
        return;
      }
      
      if (typeof XLSX !== 'undefined') {
        try {
          const wb = XLSX.utils.table_to_book(table, { sheet: "Relatório" });
          const filename = `relatorio_${tipo}_${new Date().toISOString().slice(0,10)}.xlsx`;
          XLSX.writeFile(wb, filename);
          Ui.showToast('Excel exportado com sucesso!', 'success');
        } catch (err) {
          console.error('Erro ao gerar Excel:', err);
          Ui.showToast('Erro ao gerar Excel. Tentando CSV...', 'error');
          const csv = tableToCSV(table);
          const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
          const filename = `relatorio_${tipo}_${new Date().toISOString().slice(0,10)}.csv`;
          downloadBlob(filename, blob);
        }
      } else {
        // Fallback para CSV
        Ui.showToast('Biblioteca Excel não disponível. Exportando como CSV...', 'info');
        const csv = tableToCSV(table);
        const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
        const filename = `relatorio_${tipo}_${new Date().toISOString().slice(0,10)}.csv`;
        downloadBlob(filename, blob);
      }
      return;
    }

    // PDF (via servidor com template Handlebars)
    if (format === 'pdf') {
      try {
        Ui.showLoading(true);

        // Coletar dados do preview
        const previewData = await gatherPreviewData(preview, tipo);
        const metadata = extractMetadataFromOverlay(overlay);

        // Buscar resumo calculado se disponível (window.AnaliseFinanceira)
        let resumoCalculado = null;
        if (window.AnaliseFinanceira && window.AnaliseFinanceira.RelatorioFinanceiro) {
          // Tentar obter último resumo calculado
          resumoCalculado = window.lastReportResumo || null;
        }

        // Formatar dados para o template
        const templateData = formatDataForTemplate(previewData, metadata, resumoCalculado, tipo);

        // Gerar nome do arquivo
        const filename = `relatorio_${tipo}_${new Date().toISOString().slice(0,10)}.pdf`;

        // Chamar servidor para gerar PDF
        await downloadPdfFromServer(templateData, filename);

      } catch (err) {
        console.error(' Erro ao gerar PDF:', err);
        Ui.showToast(`Erro ao gerar PDF: ${err.message}`, 'error');

        // Fallback: tentar geração local com jsPDF
        try {
          if (typeof jspdf !== 'undefined' && table) {
            const { jsPDF } = jspdf;
            const doc = new jsPDF();
            
            doc.setFontSize(16);
            doc.text(`Relatório: ${tipo.toUpperCase()}`, 14, 15);
            
            doc.setFontSize(10);
            doc.text(`Data: ${new Date().toLocaleDateString('pt-BR')}`, 14, 22);
            
            if (typeof doc.autoTable === 'function') {
              doc.autoTable({ 
                html: table, 
                startY: 30,
                styles: { fontSize: 8, cellPadding: 2 },
                headStyles: { fillColor: [59, 130, 246] }
              });
            }
            
            doc.save(`relatorio_${tipo}_${new Date().toISOString().slice(0,10)}.pdf`);
            Ui.showToast('PDF gerado localmente (fallback)', 'success');
          } else {
            // Último fallback: print
            window.print();
          }
        } catch (fallbackErr) {
          console.error('Fallback também falhou:', fallbackErr);
          alert('Não foi possível gerar o PDF. Tente exportar como CSV ou contate o administrador.');
        }
      } finally {
        Ui.showLoading(false);
      }
      return;
    }

    alert('Formato de exportação não suportado: ' + format);
  }

  // -------------------------
  // Preview handlers (mantém compatibilidade com relatórios não-financeiros)
  // -------------------------
  function fillTableRows(tbodyEl, rows = [], columns = []) {
    if (!tbodyEl) return;
    tbodyEl.innerHTML = '';
    rows.forEach(r => {
      const tr = document.createElement('tr');
      columns.forEach(col => {
        const td = document.createElement('td');
        td.textContent = r[col] != null ? r[col] : '';
        tr.appendChild(td);
      });
      tbodyEl.appendChild(tr);
    });
  }

  const previewHandlersComplement = {
    'preview-registro-ponto': (btn) => {
      const overlay = btn.closest('.overlay-modal');
      const preview = overlay.querySelector('#preview-registro-ponto');
      if (preview) preview.style.display = 'block';
      const tbody = preview?.querySelector('#preview-registro-ponto-tbody');
      const rows = [
        { Data: '2025-10-01', Colaborador: 'Ana Silva', Entrada: '09:00', Saída: '18:00', Obs: '' },
        { Data: '2025-10-02', Colaborador: 'Bruno Costa', Entrada: '09:10', Saída: '18:05', Obs: 'Atraso 10m' },
      ];
      fillTableRows(tbody, rows, ['Data', 'Colaborador', 'Entrada', 'Saída', 'Obs']);
    },
    'preview-horas-extras': (btn) => {
      const overlay = btn.closest('.overlay-modal');
      const preview = overlay.querySelector('#preview-horas-extras');
      if (preview) preview.style.display = 'block';
      const tbody = preview?.querySelector('#preview-horas-extras-tbody');
      const rows = [
        { Colaborador: 'Carla Souza', Horas: '12', Projeto: 'Projeto X', Data: '2025-09-15' },
        { Colaborador: 'Bruno Costa', Horas: '6', Projeto: 'Projeto Y', Data: '2025-09-28' }
      ];
      fillTableRows(tbody, rows, ['Colaborador', 'Horas', 'Projeto', 'Data']);
    },
    'preview-absenteismo': (btn) => {
      const overlay = btn.closest('.overlay-modal');
      const preview = overlay.querySelector('#preview-absenteismo');
      if (preview) preview.style.display = 'block';
      const tbody = preview?.querySelector('#preview-absenteismo-tbody');
      const rows = [
        { Segmento: 'TI', Faltas: '12', 'Taxa (%)': '4.2', Período: 'Set/2025' },
        { Segmento: 'Comercial', Faltas: '8', 'Taxa (%)': '3.1', Período: 'Set/2025' }
      ];
      fillTableRows(tbody, rows, ['Segmento', 'Faltas', 'Taxa (%)', 'Período']);
    },
    'preview-turnover': (btn) => {
      const overlay = btn.closest('.overlay-modal');
      const preview = overlay.querySelector('#preview-turnover');
      if (preview) preview.style.display = 'block';
      const tbody = preview?.querySelector('#preview-turnover-tbody');
      const rows = [
        { Segmento: 'Comercial', Admissões: '10', Desligamentos: '6', 'Taxa (%)': '3.2' },
        { Segmento: 'TI', Admissões: '4', Desligamentos: '2', 'Taxa (%)': '1.8' }
      ];
      fillTableRows(tbody, rows, ['Segmento', 'Admissões', 'Desligamentos', 'Taxa (%)']);
    },
    'preview-ferias': (btn) => {
      const overlay = btn.closest('.overlay-modal');
      const preview = overlay.querySelector('#preview-ferias');
      if (preview) preview.style.display = 'block';
      const tbody = preview?.querySelector('#preview-ferias-tbody');
      const rows = [
        { Colaborador: 'Ana Silva', 'Período aquisitivo': '01/2024 - 01/2025', Status: 'Programada', 'Data prevista': '2025-11-05' },
        { Colaborador: 'Carla Souza', 'Período aquisitivo': '03/2024 - 03/2025', Status: 'Pendente', 'Data prevista': '-' }
      ];
      fillTableRows(tbody, rows, ['Colaborador', 'Período aquisitivo', 'Status', 'Data prevista']);
    },
    'preview-contratos': (btn) => {
      const overlay = btn.closest('.overlay-modal');
      const preview = overlay.querySelector('#preview-contratos');
      if (preview) preview.style.display = 'block';
      const tbody = preview?.querySelector('#preview-contratos-tbody');
      const rows = [
        { Colaborador: 'Bruno Costa', Tipo: 'CLT', Vencimento: '2025-12-15', Ação: 'Notificar' },
        { Colaborador: 'Fornecedor X', Tipo: 'PJ', Vencimento: '2025-11-02', Ação: 'Rever' }
      ];
      fillTableRows(tbody, rows, ['Colaborador', 'Tipo', 'Vencimento', 'Ação']);
    }
  };

  function wirePreviewButtons() {
    Object.keys(previewHandlersComplement).forEach(key => {
      qsa(`[data-action="${key}"]`).forEach(btn => {
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          const handler = previewHandlersComplement[key];
          try {
            handler(btn);
          } catch (err) {
            console.error(`[analiseGestor] erro em handler ${key}:`, err);
          }
        });
      });
    });
  }

  /* ============================
     INICIALIZAÇÃO
     ============================ */
  function init() {
    console.log(' Iniciando analiseGestor.js v2.0');
    
    wireOpenModalButtons();
    wireResetButtons();
    wirePreviewButtons();
    wireDownloadButtons();

    // Botões de fechar
    qsa('.modal-close').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const overlay = btn.closest('.overlay-modal');
        if (overlay) closeModalByOverlay(overlay);
      });
    });

    // Accessibility: abrir modal com Enter/Space
    qsa('.report-card[role="button"]').forEach(card => {
      card.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          const modalId = card.querySelector('[data-open-modal]')?.getAttribute('data-open-modal') 
            || (card.dataset.relatorio && `modal-${card.dataset.relatorio}`);
          if (modalId) {
            openModalById(modalId, { reportId: card.dataset.relatorio || modalId });
          }
        }
      });

      card.addEventListener('click', (e) => {
        if (e.target.closest('button') || e.target.closest('a')) return;
        const btn = card.querySelector('[data-open-modal]');
        if (btn) btn.click();
      });
    });

    console.log(' analiseGestor.js inicializado com sucesso');
  }

  // DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // Expor API pública para debugging e uso externo
  window.__analiseGestor = {
    version: '2.0',
    openModalById,
    closeModalByOverlay,
    previewHandlersComplement,
    performExport,
    gatherPreviewData,
    extractMetadataFromOverlay,
    formatDataForTemplate,
    downloadPdfFromServer,
    Utils: getUiUtils()
  };

  console.log(' API pública exposta em window.__analiseGestor');
})();