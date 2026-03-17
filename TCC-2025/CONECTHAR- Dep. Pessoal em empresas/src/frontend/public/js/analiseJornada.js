// frontend/public/js/analiseJornada.js
// Sistema de Relatórios de Jornada e Ponto - Versão 1.0
// Integração completa com backend e geração de PDF

(function() {
  'use strict';

  /* ============================
     CONFIGURAÇÃO E CONSTANTES
     ============================ */
  const CONFIG = {
    BACKEND_URL: (typeof window !== 'undefined' && window.BACKEND_URL && window.BACKEND_URL !== '{{BACKEND_URL}}')
      ? window.BACKEND_URL.replace(/\/$/, '')
      : (window.location.hostname === 'localhost' && window.location.port === '3000'
          ? 'http://localhost:3001'
          : window.location.origin),
    API_BASE: null,
    ENDPOINTS: {
      pontoRecentes: '/api/ponto/recentes',
      pontoEmpresa: '/api/ponto/empresa',
      colaboradores: '/api/gestor/colaboradores',
      setores: '/api/gestor/setores',
      stats: '/api/gestor/stats'
    }
  };
  CONFIG.API_BASE = CONFIG.BACKEND_URL;

  console.log(' CONFIG Jornada:', CONFIG);

  /* ============================
     UTILITÁRIOS
     ============================ */
  const Utils = {
    getToken() {
      return localStorage.getItem('token') || sessionStorage.getItem('token');
    },

    formatTime(horas) {
      if (!horas) return '0h';
      const h = Math.floor(horas);
      const m = Math.round((horas - h) * 60);
      return m > 0 ? `${h}h ${m}m` : `${h}h`;
    },

    formatDate(date, includeTime = false) {
      if (!date) return '';
      const d = new Date(date);
      const dateStr = d.toLocaleDateString('pt-BR');
      return includeTime ? `${dateStr} ${d.toLocaleTimeString('pt-BR')}` : dateStr;
    },

    formatPercent(value, decimals = 1) {
      const num = parseFloat(value) || 0;
      return num.toFixed(decimals) + '%';
    },

    getWeekday(date) {
      const days = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
      return days[new Date(date).getDay()];
    },

    showToast(message, type = 'info') {
      const toast = document.createElement('div');
      toast.className = `toast-notification toast-${type}`;
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
      toast.textContent = message;
      document.body.appendChild(toast);
      setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
      }, 3000);
    },

    showLoading(show = true) {
      let overlay = document.getElementById('loading-overlay-jornada');
      
      if (show) {
        if (!overlay) {
          overlay = document.createElement('div');
          overlay.id = 'loading-overlay-jornada';
          overlay.style.cssText = `
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.7); z-index: 9999;
            display: flex; justify-content: center; align-items: center;
          `;
          overlay.innerHTML = `
            <div style="text-align:center;color:#fff;">
              <div class="spinner-border" role="status" style="width:3rem;height:3rem;">
                <span class="visually-hidden">Carregando...</span>
              </div>
              <div style="margin-top:8px;">Processando relatório...</div>
            </div>
          `;
          document.body.appendChild(overlay);
        }
        overlay.style.display = 'flex';
      } else {
        if (overlay) overlay.style.display = 'none';
      }
    },

    async fetchAPI(endpoint, options = {}) {
      const token = this.getToken();
      const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...(token && { 'Authorization': `Bearer ${token}` }),
        ...(options.headers || {})
      };

      const url = endpoint.startsWith('http') 
        ? endpoint 
        : CONFIG.BACKEND_URL + endpoint;

      console.log(' Requisição Jornada:', url);

      try {
        const response = await fetch(url, {
          ...options,
          headers,
          credentials: 'include'
        });

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
          throw new Error('Resposta inválida do servidor');
        }

        const data = await response.json();

        if (!response.ok) {
          throw new Error(data.message || data.erro || data.error || 'Erro na requisição');
        }

        console.log(' Resposta Jornada:', data);
        return data;
      } catch (error) {
        console.error(' Erro na requisição:', error);
        throw error;
      }
    },

    calcularDiasUteis(dataInicio, dataFim) {
      let count = 0;
      const inicio = new Date(dataInicio);
      const fim = new Date(dataFim);
      
      for (let d = new Date(inicio); d <= fim; d.setDate(d.getDate() + 1)) {
        const dia = d.getDay();
        if (dia !== 0 && dia !== 6) count++; // Não é domingo nem sábado
      }
      
      return count;
    }
  };

  /* ============================
     GERENCIADOR DE DADOS
     ============================ */
  const DataManager = {
    cache: {
      registrosPonto: null,
      colaboradores: null,
      setores: null,
      timestamp: null
    },

    async getRegistrosPonto(forceRefresh = false) {
      if (!forceRefresh && this.cache.registrosPonto && this.isCacheValid()) {
        console.log(' Usando cache de registros de ponto');
        return this.cache.registrosPonto;
      }

      try {
        console.log(' Buscando registros de ponto do backend...');
        const data = await Utils.fetchAPI(CONFIG.ENDPOINTS.pontoEmpresa);
        
        this.cache.registrosPonto = Array.isArray(data) ? data : (data.registros || data.data || []);
        this.cache.timestamp = Date.now();
        
        console.log(` ${this.cache.registrosPonto.length} registros de ponto carregados`);
        return this.cache.registrosPonto;
      } catch (error) {
        console.error(' Erro ao buscar registros de ponto:', error);
        return [];
      }
    },

    async getColaboradores(forceRefresh = false) {
      if (!forceRefresh && this.cache.colaboradores && this.isCacheValid()) {
        return this.cache.colaboradores;
      }

      try {
        const data = await Utils.fetchAPI(CONFIG.ENDPOINTS.colaboradores);
        this.cache.colaboradores = Array.isArray(data) ? data : (data.colaboradores || data.data || []);
        console.log(` ${this.cache.colaboradores.length} colaboradores carregados`);
        return this.cache.colaboradores;
      } catch (error) {
        console.error(' Erro ao buscar colaboradores:', error);
        return [];
      }
    },

    async getSetores(forceRefresh = false) {
      if (!forceRefresh && this.cache.setores && this.isCacheValid()) {
        return this.cache.setores;
      }

      try {
        const data = await Utils.fetchAPI(CONFIG.ENDPOINTS.setores);
        this.cache.setores = Array.isArray(data) ? data : (data.setores || data.data || []);
        return this.cache.setores;
      } catch (error) {
        console.error(' Erro ao buscar setores:', error);
        return [];
      }
    },

    isCacheValid() {
      if (!this.cache.timestamp) return false;
      const CACHE_DURATION = 3 * 60 * 1000; // 3 minutos
      return (Date.now() - this.cache.timestamp) < CACHE_DURATION;
    },

    clearCache() {
      this.cache = {
        registrosPonto: null,
        colaboradores: null,
        setores: null,
        timestamp: null
      };
      console.log(' Cache de jornada limpo');
    }
  };

  /* ============================
     PROCESSADOR DE REGISTROS
     ============================ */
  const ProcessadorRegistros = {
    filtrarPorPeriodo(registros, dataInicio, dataFim) {
      const inicio = new Date(dataInicio);
      const fim = new Date(dataFim);
      fim.setHours(23, 59, 59, 999);

      return registros.filter(r => {
        const data = new Date(r.data_registro);
        return data >= inicio && data <= fim;
      });
    },

    agruparPorColaborador(registros) {
      const grupos = {};

      registros.forEach(r => {
        const key = r.usuario_id || r.id;
        if (!grupos[key]) {
          grupos[key] = {
            colaborador_id: r.usuario_id,
            nome: r.nome,
            setor: r.setor,
            registros: [],
            total_horas: 0,
            dias_trabalhados: new Set(),
            entradas: 0,
            saidas: 0,
            intervalos: 0
          };
        }

        grupos[key].registros.push(r);
        grupos[key].total_horas += parseFloat(r.horas) || 0;
        grupos[key].dias_trabalhados.add(new Date(r.data_registro).toDateString());

        if (r.tipo_registro === 'entrada') grupos[key].entradas++;
        if (r.tipo_registro === 'saida') grupos[key].saidas++;
        if (r.tipo_registro.includes('intervalo')) grupos[key].intervalos++;
      });

      return Object.values(grupos).map(g => ({
        ...g,
        dias_trabalhados: g.dias_trabalhados.size
      }));
    },

    agruparPorSetor(registros) {
      const grupos = {};

      registros.forEach(r => {
        const setor = r.setor || 'Não informado';
        if (!grupos[setor]) {
          grupos[setor] = {
            setor,
            registros: [],
            total_horas: 0,
            colaboradores: new Set(),
            dias_trabalhados: new Set()
          };
        }

        grupos[setor].registros.push(r);
        grupos[setor].total_horas += parseFloat(r.horas) || 0;
        grupos[setor].colaboradores.add(r.usuario_id || r.nome);
        grupos[setor].dias_trabalhados.add(new Date(r.data_registro).toDateString());
      });

      return Object.values(grupos).map(g => ({
        ...g,
        colaboradores_count: g.colaboradores.size,
        dias_trabalhados_count: g.dias_trabalhados.size
      }));
    },

    detectarAnomalias(registros) {
      const anomalias = [];

      registros.forEach(r => {
        const horas = parseFloat(r.horas) || 0;
        
        // Jornada muito longa
        if (horas > 12) {
          anomalias.push({
            tipo: 'jornada_excessiva',
            registro: r,
            descricao: `Jornada de ${Utils.formatTime(horas)} excede 12 horas`
          });
        }

        // Horários suspeitos (madrugada)
        const hora = new Date(r.data_registro).getHours();
        if (hora < 5 || hora > 23) {
          anomalias.push({
            tipo: 'horario_atipico',
            registro: r,
            descricao: `Registro às ${hora}h (horário atípico)`
          });
        }
      });

      return anomalias;
    },

    calcularHorasExtras(totalHoras, horasDiarias, diasTrabalhados) {
      const horasEsperadas = horasDiarias * diasTrabalhados;
      const extras = Math.max(0, totalHoras - horasEsperadas);
      return {
        total: extras,
        percentual: horasEsperadas > 0 ? (extras / horasEsperadas) * 100 : 0
      };
    }
  };

  /* ============================
     GERADOR DE RELATÓRIOS
     ============================ */
  const RelatorioJornada = {
    async gerarRelatorioRegistroPonto(parametros) {
      Utils.showLoading(true);
      
      try {
        const { dataInicio, dataFim, filtroTipo, detalhamentoColaborador } = parametros;
        console.log(' Gerando relatório de registro de ponto:', parametros);

        // Buscar registros REAIS
        let registros = await DataManager.getRegistrosPonto(true);

        if (!registros || registros.length === 0) {
          throw new Error('Nenhum registro de ponto encontrado');
        }

        // Aplicar filtros
        if (dataInicio && dataFim) {
          registros = ProcessadorRegistros.filtrarPorPeriodo(registros, dataInicio, dataFim);
        }

        if (filtroTipo && filtroTipo !== '') {
          registros = registros.filter(r => {
            if (filtroTipo === 'faltantes') {
              // Lógica para detectar batidas faltantes
              return r.tipo_registro === 'entrada' && !registros.find(s => 
                s.usuario_id === r.usuario_id && 
                s.tipo_registro === 'saida' &&
                new Date(s.data_registro).toDateString() === new Date(r.data_registro).toDateString()
              );
            }
            return true;
          });
        }

        console.log(` ${registros.length} registros após filtros`);

        // Agrupar dados
        const dadosAgrupados = detalhamentoColaborador
          ? ProcessadorRegistros.agruparPorColaborador(registros)
          : registros;

        // Detectar anomalias
        const anomalias = ProcessadorRegistros.detectarAnomalias(registros);

        // Calcular resumo
        const resumo = {
          total_registros: registros.length,
          total_colaboradores: new Set(registros.map(r => r.usuario_id)).size,
          total_horas: registros.reduce((sum, r) => sum + (parseFloat(r.horas) || 0), 0),
          anomalias_encontradas: anomalias.length,
          periodo: `${Utils.formatDate(dataInicio)} a ${Utils.formatDate(dataFim)}`
        };

        // Armazenar para PDF
        window.lastReportResumo = resumo;

        // Renderizar preview
        this.renderizarPreviewRegistroPonto(dadosAgrupados, resumo, anomalias, parametros);

        Utils.showToast('Relatório gerado com sucesso!', 'success');
        return { dadosAgrupados, resumo, anomalias };

      } catch (error) {
        console.error(' Erro ao gerar relatório de ponto:', error);
        Utils.showToast('Erro ao gerar relatório: ' + error.message, 'error');
        throw error;
      } finally {
        Utils.showLoading(false);
      }
    },

    renderizarPreviewRegistroPonto(dados, resumo, anomalias, parametros) {
      const previewContainer = document.getElementById('preview-registro-ponto-tbody');
      if (!previewContainer) return;

      previewContainer.innerHTML = '';

      // Renderizar dados
      if (parametros.detalhamentoColaborador && Array.isArray(dados)) {
        // Dados agrupados por colaborador
        dados.forEach(grupo => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td><strong>${grupo.nome}</strong></td>
            <td>${grupo.setor || '-'}</td>
            <td class="text-center">${grupo.dias_trabalhados}</td>
            <td class="text-end">${Utils.formatTime(grupo.total_horas)}</td>
            <td class="text-center">
              <span class="badge ${grupo.entradas === grupo.saidas ? 'bg-success' : 'bg-warning'}">
                ${grupo.entradas}/${grupo.saidas}
              </span>
            </td>
          `;
          previewContainer.appendChild(tr);
        });
      } else {
        // Dados individuais
        dados.slice(0, 50).forEach(registro => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${Utils.formatDate(registro.data_registro, true)}</td>
            <td>${registro.nome}</td>
            <td><span class="badge bg-info">${registro.tipo_registro}</span></td>
            <td class="text-end">${Utils.formatTime(registro.horas)}</td>
            <td class="text-center">${Utils.getWeekday(registro.data_registro)}</td>
          `;
          previewContainer.appendChild(tr);
        });
      }

      // Atualizar resumo
      const summaryEl = document.querySelector('#preview-registro-ponto .preview-summary');
      if (summaryEl) {
        summaryEl.innerHTML = `
          <strong>Resumo:</strong> ${resumo.total_registros} registros | 
          ${resumo.total_colaboradores} colaboradores | 
          Total: ${Utils.formatTime(resumo.total_horas)} |
          ${anomalias.length > 0 ? `<span class="text-warning">${anomalias.length} anomalias detectadas</span>` : ''}
        `;
      }

      // Gerar gráfico
      this.gerarGraficoRegistroPonto(dados, parametros);
    },

    gerarGraficoRegistroPonto(dados, parametros) {
      const chartContainer = document.querySelector('[data-chart="registro-ponto"]');
      if (!chartContainer) return;

      const dadosGrafico = parametros.detalhamentoColaborador
        ? dados.slice(0, 10)
        : ProcessadorRegistros.agruparPorColaborador(dados).slice(0, 10);

      const maxHoras = Math.max(...dadosGrafico.map(d => d.total_horas || 0));

      chartContainer.innerHTML = `
        <div style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 8px;">
          <h6 style="color: rgba(255,255,255,0.9); margin-bottom: 15px;">
            Horas Trabalhadas por Colaborador - <span class="badge bg-success"></span>
          </h6>
          <div style="display: flex; flex-direction: column; gap: 10px;">
            ${dadosGrafico.map(d => `
              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                  <span style="font-size: 12px; color: rgba(255,255,255,0.7);">${d.nome}</span>
                  <span style="font-size: 12px; color: rgba(255,255,255,0.9);">${Utils.formatTime(d.total_horas)}</span>
                </div>
                <div style="background: rgba(255,255,255,0.1); height: 8px; border-radius: 4px; overflow: hidden;">
                  <div style="
                    background: linear-gradient(90deg, #3b82f6, #2563eb);
                    height: 100%;
                    width: ${(d.total_horas / maxHoras) * 100}%;
                    transition: width 0.3s ease;
                  "></div>
                </div>
              </div>
            `).join('')}
          </div>
        </div>
      `;
    },

    async gerarRelatorioHorasExtras(parametros) {
      Utils.showLoading(true);
      
      try {
        console.log(' Gerando relatório de horas extras:', parametros);

        // Buscar registros e colaboradores
        const registros = await DataManager.getRegistrosPonto(true);
        const colaboradores = await DataManager.getColaboradores(true);

        // Filtrar por período
        let registrosFiltrados = ProcessadorRegistros.filtrarPorPeriodo(
          registros, 
          parametros.dataInicio, 
          parametros.dataFim
        );

        // Agrupar por colaborador ou projeto
        const grupos = ProcessadorRegistros.agruparPorColaborador(registrosFiltrados);

        // Calcular horas extras para cada colaborador
        const dadosComExtras = grupos.map(g => {
          const colab = colaboradores.find(c => c.id === g.colaborador_id);
          const horasDiarias = colab?.horas_diarias || 8;
          const diasUteis = Utils.calcularDiasUteis(parametros.dataInicio, parametros.dataFim);
          
          const extras = ProcessadorRegistros.calcularHorasExtras(
            g.total_horas,
            horasDiarias,
            g.dias_trabalhados
          );

          return {
            ...g,
            horas_extras: extras.total,
            percentual_extras: extras.percentual,
            horas_esperadas: horasDiarias * g.dias_trabalhados
          };
        });

        // Filtrar apenas quem tem horas extras
        const comHorasExtras = dadosComExtras.filter(d => d.horas_extras > 0);

        // Resumo
        const resumo = {
          total_colaboradores: comHorasExtras.length,
          total_horas_extras: comHorasExtras.reduce((sum, d) => sum + d.horas_extras, 0),
          media_por_colaborador: comHorasExtras.length > 0 
            ? comHorasExtras.reduce((sum, d) => sum + d.horas_extras, 0) / comHorasExtras.length 
            : 0
        };

        window.lastReportResumo = resumo;

        // Renderizar
        this.renderizarPreviewHorasExtras(comHorasExtras, resumo, parametros);

        Utils.showToast('Relatório de horas extras gerado!', 'success');
        return { dadosComExtras: comHorasExtras, resumo };

      } catch (error) {
        console.error(' Erro ao gerar relatório de horas extras:', error);
        Utils.showToast('Erro: ' + error.message, 'error');
        throw error;
      } finally {
        Utils.showLoading(false);
      }
    },

    renderizarPreviewHorasExtras(dados, resumo, parametros) {
      const tbody = document.getElementById('preview-horas-extras-tbody');
      if (!tbody) return;

      tbody.innerHTML = dados.map(d => `
        <tr>
          <td>${d.nome}</td>
          <td class="text-end">${Utils.formatTime(d.horas_extras)}</td>
          <td class="text-end">${Utils.formatPercent(d.percentual_extras)}</td>
          <td class="text-center">${d.dias_trabalhados}</td>
        </tr>
      `).join('');

      const summary = document.querySelector('#preview-horas-extras .preview-summary');
      if (summary) {
        summary.innerHTML = `
          <strong>Resumo:</strong> ${resumo.total_colaboradores} colaboradores | 
          Total: ${Utils.formatTime(resumo.total_horas_extras)} | 
          Média: ${Utils.formatTime(resumo.media_por_colaborador)}
        `;
      }

      this.gerarGraficoHorasExtras(dados);
    },

    gerarGraficoHorasExtras(dados) {
      const chart = document.querySelector('[data-chart="horas-extras"]');
      if (!chart) return;

      const top10 = dados.slice(0, 10);
      const maxExtras = Math.max(...top10.map(d => d.horas_extras));

      chart.innerHTML = `
        <div style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 8px;">
          <h6 style="color: rgba(255,255,255,0.9); margin-bottom: 15px;">
            Top 10 - Horas Extras
          </h6>
          <div style="display: flex; flex-direction: column; gap: 10px;">
            ${top10.map(d => `
              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                  <span style="font-size: 12px; color: rgba(255,255,255,0.7);">${d.nome}</span>
                  <span style="font-size: 12px; color: rgba(255,255,255,0.9);">${Utils.formatTime(d.horas_extras)}</span>
                </div>
                <div style="background: rgba(255,255,255,0.1); height: 8px; border-radius: 4px; overflow: hidden;">
                  <div style="
                    background: linear-gradient(90deg, #f59e0b, #d97706);
                    height: 100%;
                    width: ${(d.horas_extras / maxExtras) * 100}%;
                    transition: width 0.3s ease;
                  "></div>
                </div>
              </div>
            `).join('')}
          </div>
        </div>
      `;
    },

    async gerarRelatorioAbsenteismo(parametros) {
      Utils.showLoading(true);
      
      try {
        console.log(' Gerando relatório de absenteísmo:', parametros);

        const registros = await DataManager.getRegistrosPonto(true);
        const colaboradores = await DataManager.getColaboradores(true);

        // Filtrar período
        const registrosFiltrados = ProcessadorRegistros.filtrarPorPeriodo(
          registros,
          parametros.dataInicio,
          parametros.dataFim
        );

        // Calcular dias úteis no período
        const diasUteis = Utils.calcularDiasUteis(parametros.dataInicio, parametros.dataFim);

        // Agrupar por colaborador
        const grupos = ProcessadorRegistros.agruparPorColaborador(registrosFiltrados);

        // Calcular absenteísmo
        const dadosAbsenteismo = colaboradores.map(colab => {
          const grupo = grupos.find(g => g.colaborador_id === colab.id);
          const diasPresentes = grupo?.dias_trabalhados || 0;
          const diasAusentes = Math.max(0, diasUteis - diasPresentes);
          const taxaAbsenteismo = diasUteis > 0 ? (diasAusentes / diasUteis) * 100 : 0;

          return {
            colaborador_id: colab.id,
            nome: colab.nome,
            setor: colab.setor,
            dias_esperados: diasUteis,
            dias_presentes: diasPresentes,
            dias_ausentes: diasAusentes,
            taxa_absenteismo: taxaAbsenteismo
          };
        });

        // Agrupar por setor se solicitado
        let dadosFinais = dadosAbsenteismo;
        if (parametros.segmentarPor === 'setor') {
          const porSetor = {};
          dadosAbsenteismo.forEach(d => {
            const setor = d.setor || 'Não informado';
            if (!porSetor[setor]) {
              porSetor[setor] = {
                setor,
                colaboradores: [],
                total_ausencias: 0,
                total_presencas: 0
              };
            }
            porSetor[setor].colaboradores.push(d);
            porSetor[setor].total_ausencias += d.dias_ausentes;
            porSetor[setor].total_presencas += d.dias_presentes;
          });

          dadosFinais = Object.values(porSetor).map(s => ({
            ...s,
            colaboradores_count: s.colaboradores.length,
            taxa_absenteismo: s.total_ausencias > 0 
              ? (s.total_ausencias / (s.total_ausencias + s.total_presencas)) * 100 
              : 0
          }));
        }

        // Resumo
        const totalAusencias = dadosAbsenteismo.reduce((sum, d) => sum + d.dias_ausentes, 0);
        const totalPresencas = dadosAbsenteismo.reduce((sum, d) => sum + d.dias_presentes, 0);
        const resumo = {
          total_colaboradores: dadosAbsenteismo.length,
          total_ausencias: totalAusencias,
          taxa_geral: totalPresencas > 0 ? (totalAusencias / (totalAusencias + totalPresencas)) * 100 : 0,
          periodo_dias: diasUteis
        };

        window.lastReportResumo = resumo;

        // Renderizar
        this.renderizarPreviewAbsenteismo(dadosFinais, resumo, parametros);

        Utils.showToast('Relatório de absenteísmo gerado!', 'success');
        return { dadosFinais, resumo };

      } catch (error) {
        console.error(' Erro ao gerar relatório de absenteísmo:', error);
        Utils.showToast('Erro: ' + error.message, 'error');
        throw error;
      } finally {
        Utils.showLoading(false);
      }
    },

    renderizarPreviewAbsenteismo(dados, resumo, parametros) {
      const tbody = document.getElementById('preview-absenteismo-tbody');
      if (!tbody) return;

      tbody.innerHTML = '';

      if (parametros.segmentarPor === 'setor') {
        // Dados por setor
        dados.forEach(setor => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td><strong>${setor.setor}</strong> <span class="badge bg-secondary">${setor.colaboradores_count}</span></td>
            <td class="text-center">${setor.total_ausencias}</td>
            <td class="text-end">${Utils.formatPercent(setor.taxa_absenteismo)}</td>
            <td class="text-center">${setor.total_presencas}</td>
          `;
          tbody.appendChild(tr);
        });
      } else {
        // Dados individuais
        dados.filter(d => d.taxa_absenteismo > 0).slice(0, 50).forEach(d => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${d.nome}</td>
            <td>${d.setor || '-'}</td>
            <td class="text-center">${d.dias_ausentes}</td>
            <td class="text-end">
              <span class="badge ${d.taxa_absenteismo > 10 ? 'bg-danger' : d.taxa_absenteismo > 5 ? 'bg-warning' : 'bg-success'}">
                ${Utils.formatPercent(d.taxa_absenteismo)}
              </span>
            </td>
          `;
          tbody.appendChild(tr);
        });
      }

      const summary = document.querySelector('#preview-absenteismo .preview-summary');
      if (summary) {
        summary.innerHTML = `
          <strong>Resumo:</strong> ${resumo.total_colaboradores} colaboradores | 
          ${resumo.total_ausencias} ausências | 
          Taxa Geral: <span class="badge ${resumo.taxa_geral > 10 ? 'bg-danger' : resumo.taxa_geral > 5 ? 'bg-warning' : 'bg-success'}">${Utils.formatPercent(resumo.taxa_geral)}</span>
        `;
      }

      this.gerarGraficoAbsenteismo(dados, parametros);
    },

    gerarGraficoAbsenteismo(dados, parametros) {
      const chart = document.querySelector('[data-chart="absenteismo"]');
      if (!chart) return;

      const dadosGrafico = parametros.segmentarPor === 'setor' 
        ? dados.slice(0, 10)
        : dados.filter(d => d.taxa_absenteismo > 0).slice(0, 10);

      const maxTaxa = Math.max(...dadosGrafico.map(d => d.taxa_absenteismo || 0));

      chart.innerHTML = `
        <div style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 8px;">
          <h6 style="color: rgba(255,255,255,0.9); margin-bottom: 15px;">
            Taxa de Absenteísmo - ${parametros.segmentarPor === 'setor' ? 'Por Setor' : 'Por Colaborador'}
          </h6>
          <div style="display: flex; flex-direction: column; gap: 10px;">
            ${dadosGrafico.map(d => {
              const nome = d.setor || d.nome;
              const taxa = d.taxa_absenteismo || 0;
              const cor = taxa > 10 ? '#ef4444' : taxa > 5 ? '#f59e0b' : '#10b981';
              
              return `
                <div>
                  <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                    <span style="font-size: 12px; color: rgba(255,255,255,0.7);">${nome}</span>
                    <span style="font-size: 12px; color: rgba(255,255,255,0.9);">${Utils.formatPercent(taxa)}</span>
                  </div>
                  <div style="background: rgba(255,255,255,0.1); height: 8px; border-radius: 4px; overflow: hidden;">
                    <div style="
                      background: ${cor};
                      height: 100%;
                      width: ${maxTaxa > 0 ? (taxa / maxTaxa) * 100 : 0}%;
                      transition: width 0.3s ease;
                    "></div>
                  </div>
                </div>
              `;
            }).join('')}
          </div>
        </div>
      `;
    }
  };

  /* ============================
     GERENCIADOR DE UI
     ============================ */
  const UIManager = {
    inicializar() {
      console.log(' Inicializando UI Manager - Jornada');
      this.configurarEventos();
      this.carregarDadosIniciais();
    },

    configurarEventos() {
      // Botões de geração de relatórios
      const btnRegistroPonto = document.querySelector('[data-action="preview-registro-ponto"]');
      const btnHorasExtras = document.querySelector('[data-action="preview-horas-extras"]');
      const btnAbsenteismo = document.querySelector('[data-action="preview-absenteismo"]');

      if (btnRegistroPonto) {
        btnRegistroPonto.addEventListener('click', () => this.handleGerarRelatorioRegistroPonto());
      }

      if (btnHorasExtras) {
        btnHorasExtras.addEventListener('click', () => this.handleGerarRelatorioHorasExtras());
      }

      if (btnAbsenteismo) {
        btnAbsenteismo.addEventListener('click', () => this.handleGerarRelatorioAbsenteismo());
      }

      console.log(' Eventos de jornada configurados');
    },

    async carregarDadosIniciais() {
      try {
        console.log(' Carregando dados iniciais de jornada...');
        
        // Pré-carregar setores para filtros
        const setores = await DataManager.getSetores(true);
        this.popularFiltrosSetores(setores);

        // Pré-carregar colaboradores
        await DataManager.getColaboradores(true);

        console.log(' Dados iniciais de jornada carregados');
      } catch (error) {
        console.error(' Erro ao carregar dados iniciais:', error);
      }
    },

    popularFiltrosSetores(setores) {
      const selects = document.querySelectorAll('select[name="setor"], select[name="segmento"]');
      
      selects.forEach(select => {
        if (!select) return;
        
        // Limpar opções (exceto primeira)
        while (select.options.length > 1) {
          select.remove(1);
        }
        
        // Adicionar setores
        setores.forEach(setor => {
          const option = document.createElement('option');
          option.value = setor.nome_setor || setor.nome;
          option.textContent = setor.nome_setor || setor.nome;
          select.appendChild(option);
        });
      });
    },

    async handleGerarRelatorioRegistroPonto() {
      try {
        const form = document.getElementById('form-registro-ponto');
        if (!form) return;

        const formData = new FormData(form);
        
        // Datas padrão se não fornecidas
        const hoje = new Date();
        const primeiroDiaMes = new Date(hoje.getFullYear(), hoje.getMonth(), 1);
        
        const parametros = {
          dataInicio: formData.get('inicio') || primeiroDiaMes.toISOString().slice(0, 10),
          dataFim: formData.get('fim') || hoje.toISOString().slice(0, 10),
          filtroTipo: formData.get('filtro_tipo') || '',
          detalhamentoColaborador: formData.get('detalhar_colaborador') === 'on',
          detalhamentoSetor: formData.get('detalhar_setor') === 'on'
        };

        await RelatorioJornada.gerarRelatorioRegistroPonto(parametros);
        
        // Mostrar preview
        const preview = document.getElementById('preview-registro-ponto');
        if (preview) {
          preview.style.display = 'block';
          preview.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

      } catch (error) {
        console.error(' Erro ao gerar relatório de ponto:', error);
        Utils.showToast('Erro ao gerar relatório', 'error');
      }
    },

    async handleGerarRelatorioHorasExtras() {
      try {
        const form = document.getElementById('form-horas-extras');
        if (!form) return;

        const formData = new FormData(form);
        
        const hoje = new Date();
        const primeiroDiaMes = new Date(hoje.getFullYear(), hoje.getMonth(), 1);
        
        const parametros = {
          dataInicio: formData.get('inicio') || primeiroDiaMes.toISOString().slice(0, 10),
          dataFim: formData.get('fim') || hoje.toISOString().slice(0, 10),
          agruparPor: formData.get('agrupar_por') || 'funcionario',
          apenasAprovadas: formData.get('apenas_aprovadas') === 'on',
          incluirCompensadas: formData.get('incluir_compensadas') === 'on'
        };

        await RelatorioJornada.gerarRelatorioHorasExtras(parametros);
        
        const preview = document.getElementById('preview-horas-extras');
        if (preview) {
          preview.style.display = 'block';
          preview.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

      } catch (error) {
        console.error(' Erro ao gerar relatório de horas extras:', error);
        Utils.showToast('Erro ao gerar relatório', 'error');
      }
    },

    async handleGerarRelatorioAbsenteismo() {
      try {
        const form = document.getElementById('form-absenteismo');
        if (!form) return;

        const formData = new FormData(form);
        
        const hoje = new Date();
        const primeiroDiaMes = new Date(hoje.getFullYear(), hoje.getMonth(), 1);
        
        const parametros = {
          dataInicio: formData.get('inicio') || primeiroDiaMes.toISOString().slice(0, 10),
          dataFim: formData.get('fim') || hoje.toISOString().slice(0, 10),
          segmentarPor: formData.get('segmentar_por') || 'colaborador',
          causasClassificadas: formData.get('causas_classificadas') === 'on',
          compararPeriodo: formData.get('comparar_periodo') === 'on'
        };

        await RelatorioJornada.gerarRelatorioAbsenteismo(parametros);
        
        const preview = document.getElementById('preview-absenteismo');
        if (preview) {
          preview.style.display = 'block';
          preview.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

      } catch (error) {
        console.error(' Erro ao gerar relatório de absenteísmo:', error);
        Utils.showToast('Erro ao gerar relatório', 'error');
      }
    }
  };

  /* ============================
     INICIALIZAÇÃO
     ============================ */
  document.addEventListener('DOMContentLoaded', () => {
    console.log(' Iniciando Análise de Jornada e Ponto - v1.0');
    
    try {
      UIManager.inicializar();
      console.log(' Sistema de jornada inicializado com sucesso');
    } catch (error) {
      console.error(' Erro na inicialização:', error);
      Utils.showToast('Erro ao inicializar sistema', 'error');
    }
  });

  // Exportar para uso global
  if (typeof window !== 'undefined') {
    window.AnaliseJornada = {
      Utils,
      DataManager,
      ProcessadorRegistros,
      RelatorioJornada,
      UIManager,
      version: '1.0'
    };
    
    console.log(' API pública exposta em window.AnaliseJornada');
  }

})();