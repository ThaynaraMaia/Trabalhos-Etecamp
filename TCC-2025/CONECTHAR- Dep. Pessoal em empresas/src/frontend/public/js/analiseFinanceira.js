// frontend/public/js/analiseFinanceira.js
// Script integrada com dados REAIS do backend
// Versão: 3.0 - Integração completa com API e geração de PDF

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
      stats: '/api/gestor/stats',
      colaboradores: '/api/gestor/colaboradores-com-beneficios',
      colaboradoresSimples: '/api/gestor/colaboradores-simples', // Nova rota alternativa
      setores: '/api/gestor/setores',
      beneficios: '/api/beneficios/listar',
      folhaEmpresa: '/api/folha/empresa',
      folhaColaborador: '/api/folha',
      pontoRegistros: '/api/gestor/ponto/registros',
      calcularFolha: '/api/folha/calcular'
    }
  };
  CONFIG.API_BASE = CONFIG.BACKEND_URL;

  console.log(' CONFIG:', CONFIG);

  /* ============================
     UTILITÁRIOS
     ============================ */
  const Utils = {
    getToken() {
      return localStorage.getItem('token') || sessionStorage.getItem('token');
    },

    formatCurrency(value) {
      const num = parseFloat(value) || 0;
      return num.toLocaleString('pt-BR', { 
        style: 'currency', 
        currency: 'BRL',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    },

    formatPercent(value, decimals = 2) {
      const num = parseFloat(value) || 0;
      return num.toFixed(decimals) + '%';
    },

    formatDate(date, includeTime = false) {
      if (!date) return '';
      const d = new Date(date);
      const dateStr = d.toLocaleDateString('pt-BR');
      return includeTime ? `${dateStr} ${d.toLocaleTimeString('pt-BR')}` : dateStr;
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
      let overlay = document.getElementById('loading-overlay-financeiro');
      
      if (show) {
        if (!overlay) {
          overlay = document.createElement('div');
          overlay.id = 'loading-overlay-financeiro';
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

      console.log(' Requisição:', url);

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

        console.log('Resposta:', data);
        return data;
      } catch (error) {
        console.error(' Erro na requisição:', error);
        throw error;
      }
    }
  };

  /* ============================
     GERENCIADOR DE DADOS REAIS
     ============================ */
  const DataManager = {
    cache: {
      colaboradores: null,
      setores: null,
      beneficios: null,
      stats: null,
      timestamp: null
    },


  async getColaboradores(forceRefresh = false) {
  if (!forceRefresh && this.cache.colaboradores && this.isCacheValid()) {
    console.log(' Usando cache de colaboradores');
    return this.cache.colaboradores;
  }

  try {
    console.log(' Buscando colaboradores com benefícios do backend...');
    
    // Tentar a nova rota primeiro
    let data;
    try {
      data = await Utils.fetchAPI('/api/gestor/colaboradores-com-beneficios');
    } catch (error) {
      console.warn(' Rota principal falhou, tentando alternativa...');
      // Fallback para rota alternativa
      data = await Utils.fetchAPI('/api/gestor/colaboradores-simples');
    }
    
    if (data.success && data.colaboradores) {
      this.cache.colaboradores = data.colaboradores;
      this.cache.timestamp = Date.now();
      
      console.log(` ${this.cache.colaboradores.length} colaboradores carregados`);
      
      // Debug: verificar estrutura dos dados
      this.cache.colaboradores.forEach((colab, index) => {
        console.log(`${index + 1}. ${colab.nome}: ${colab.beneficios ? colab.beneficios.length : 0} benefícios, Salário: R$ ${colab.salario}`);
        if (colab.beneficios && colab.beneficios.length > 0) {
          colab.beneficios.forEach(b => {
            const valor = b.valor_personalizado || b.valor_aplicado || b.valor;
            console.log(`   - ${b.nome_do_beneficio}: R$ ${valor}`);
          });
        }
      });
      
      return this.cache.colaboradores;
    } else {
      this.cache.colaboradores = [];
      console.warn('⚠️ Nenhum colaborador encontrado ou estrutura de dados inválida');
      return [];
    }
    
  } catch (error) {
    console.error(' Erro ao buscar colaboradores:', error);
    
    // Último fallback: buscar colaboradores básicos
    try {
      const dataFallback = await Utils.fetchAPI(CONFIG.ENDPOINTS.colaboradores);
      this.cache.colaboradores = dataFallback.colaboradores || dataFallback.data || dataFallback || [];
      
      // Adicionar array vazio de benefícios para cada colaborador
      this.cache.colaboradores = this.cache.colaboradores.map(colab => ({
        ...colab,
        beneficios: [],
        total_beneficios: 0
      }));
      
      console.log(` Fallback: ${this.cache.colaboradores.length} colaboradores carregados sem benefícios`);
      return this.cache.colaboradores;
      
    } catch (fallbackError) {
      console.error(' Erro no fallback de colaboradores:', fallbackError);
      Utils.showToast('Erro ao carregar colaboradores', 'error');
      return [];
    }
  }
},

    async getSetores(forceRefresh = false) {
      if (!forceRefresh && this.cache.setores && this.isCacheValid()) {
        console.log(' Usando cache de setores');
        return this.cache.setores;
      }

      try {
        console.log(' Buscando setores do backend...');
        const data = await Utils.fetchAPI(CONFIG.ENDPOINTS.setores);
        this.cache.setores = data.setores || data.data || data || [];
        console.log(` ${this.cache.setores.length} setores carregados`);
        return this.cache.setores;
      } catch (error) {
        console.error(' Erro ao buscar setores:', error);
        return [];
      }
    },

   async getBeneficios(forceRefresh = false) {
      if (!forceRefresh && this.cache.beneficios && this.isCacheValid()) {
        return this.cache.beneficios;
      }

      try {
        console.log(' Buscando benefícios do backend...');
        const data = await Utils.fetchAPI(CONFIG.ENDPOINTS.beneficios);
        
        if (data.success) {
          this.cache.beneficios = data.data || [];
          console.log(` ${this.cache.beneficios.length} benefícios carregados`);
          
          // Log para debug
          this.cache.beneficios.forEach(beneficio => {
            console.log(`Benefício: ${beneficio.nome_do_beneficio}, Valor: R$ ${beneficio.valor_aplicado}`);
          });
        } else {
          this.cache.beneficios = [];
          console.warn('Estrutura de dados de benefícios inválida');
        }
        
        return this.cache.beneficios;
      } catch (error) {
        console.error(' Erro ao buscar benefícios:', error);
        return [];
      }
    },

    async getStats(forceRefresh = false) {
      if (!forceRefresh && this.cache.stats && this.isCacheValid()) {
        return this.cache.stats;
      }

      try {
        console.log(' Buscando estatísticas do backend...');
        const data = await Utils.fetchAPI(CONFIG.ENDPOINTS.stats);
        this.cache.stats = data.data || data || {};
        console.log(' Estatísticas carregadas:', this.cache.stats);
        return this.cache.stats;
      } catch (error) {
        console.error(' Erro ao buscar estatísticas:', error);
        return {};
      }
    },

    async calcularFolhaEmpresa(mes) {
      try {
        console.log(' Calculando folha da empresa para:', mes);
        const data = await Utils.fetchAPI(
          `${CONFIG.ENDPOINTS.folhaEmpresa}?mes=${mes}`
        );
        return data;
      } catch (error) {
        console.error(' Erro ao calcular folha da empresa:', error);
        throw error;
      }
    },

    async calcularFolhaColaborador(usuarioId, mes) {
      try {
        console.log(' Calculando folha do colaborador:', usuarioId, mes);
        const data = await Utils.fetchAPI(
          `${CONFIG.ENDPOINTS.folhaColaborador}/${usuarioId}?mes=${mes}`
        );
        return data;
      } catch (error) {
        console.error(' Erro ao calcular folha do colaborador:', error);
        throw error;
      }
    },

    isCacheValid() {
      if (!this.cache.timestamp) return false;
      const CACHE_DURATION = 5 * 60 * 1000; // 5 minutos
      return (Date.now() - this.cache.timestamp) < CACHE_DURATION;
    },

    clearCache() {
      this.cache = {
        colaboradores: null,
        setores: null,
        beneficios: null,
        stats: null,
        timestamp: null
      };
      console.log(' Cache limpo');
    }
  };

  /* ============================
     CALCULADORA DE FOLHA (REAL)
     ============================ */
 const CalculadoraFolha = {
    calcularINSS(salarioBruto) {
        if (salarioBruto <= 1412.00) return salarioBruto * 0.075;
        if (salarioBruto <= 2666.68) return (salarioBruto * 0.09) - 21.18;
        if (salarioBruto <= 4000.03) return (salarioBruto * 0.12) - 101.18;
        if (salarioBruto <= 7786.02) return (salarioBruto * 0.14) - 181.18;
        return 908.85;
    },

    calcularIRRF(baseCalculo, dependentes = 0) {
        const deducaoDependente = 189.59 * dependentes;
        const base = Math.max(0, baseCalculo - deducaoDependente);
        
        if (base <= 2259.20) return 0;
        if (base <= 2826.65) return (base * 0.075) - 169.44;
        if (base <= 3751.05) return (base * 0.15) - 381.44;
        if (base <= 4664.68) return (base * 0.225) - 662.77;
        return (base * 0.275) - 896.00;
    },

    calcularFGTS(salarioBruto) {
        return salarioBruto * 0.08;
    },

    calcularEncargosPatronais(salarioBruto) {
        return salarioBruto * 0.368;
    },

    async calcularFolhaCompleta(colaboradores) {
        console.log(` Calculando folha para ${colaboradores.length} colaboradores`);
        
        const resultados = colaboradores.map(colab => {
            const salarioBruto = parseFloat(colab.salario) || 0;
            
            // Como não temos coluna dependentes, usar 0 como padrão
            const dependentes = 0;

            // Processar benefícios do colaborador
            let totalBeneficios = 0;
            const beneficiosDetalhados = [];
            
            if (colab.beneficios && Array.isArray(colab.beneficios)) {
                colab.beneficios.forEach(b => {
                    if (b.ativo !== false && b.ativo !== 0) {
                        const valorBeneficio = parseFloat(b.valor_personalizado || b.valor_aplicado) || 0;
                        totalBeneficios += valorBeneficio;
                        beneficiosDetalhados.push({
                            nome: b.nome_do_beneficio,
                            valor: valorBeneficio
                        });
                    }
                });
            }

            console.log(` ${colab.nome}: Salário R$ ${salarioBruto}, Benefícios: R$ ${totalBeneficios}`);

            const inss = this.calcularINSS(salarioBruto);
            const baseIRRF = Math.max(0, salarioBruto - inss);
            const irrf = this.calcularIRRF(baseIRRF, dependentes);
            const fgts = this.calcularFGTS(salarioBruto);
            const encargosPatronais = this.calcularEncargosPatronais(salarioBruto);
            
            const totalProventos = salarioBruto + totalBeneficios;
            const totalDescontos = inss + irrf;
            const salarioLiquido = totalProventos - totalDescontos;
            const custoTotal = salarioBruto + encargosPatronais + totalBeneficios;

            return {
                id: colab.id,
                nome: colab.nome,
                cargo: colab.cargo,
                setor: colab.setor,
                salarioBruto,
                beneficios: totalBeneficios,
                beneficiosDetalhados,
                inss,
                irrf,
                fgts,
                encargosPatronais,
                custoTotal,
                totalProventos,
                totalDescontos,
                salarioLiquido,
                dependentes
            };
        });

        const resumo = {
            totalBruto: resultados.reduce((sum, r) => sum + r.salarioBruto, 0),
            totalBeneficios: resultados.reduce((sum, r) => sum + r.beneficios, 0),
            totalINSS: resultados.reduce((sum, r) => sum + r.inss, 0),
            totalIRRF: resultados.reduce((sum, r) => sum + r.irrf, 0),
            totalFGTS: resultados.reduce((sum, r) => sum + r.fgts, 0),
            totalEncargos: resultados.reduce((sum, r) => sum + r.encargosPatronais, 0),
            totalDescontos: resultados.reduce((sum, r) => sum + r.totalDescontos, 0),
            totalLiquido: resultados.reduce((sum, r) => sum + r.salarioLiquido, 0),
            totalCusto: resultados.reduce((sum, r) => sum + r.custoTotal, 0)
        };

        console.log(' Folha calculada com sucesso:', resumo);
        
        // Armazenar resumo globalmente para uso no PDF
        window.lastReportResumo = resumo;
        
        return { resultados, resumo };
    }
};
  /* ============================
     GERADOR DE RELATÓRIOS (REAL)
     ============================ */
  const RelatorioFinanceiro = {
    async gerarRelatorioFolha(parametros) {
      Utils.showLoading(true);
      
      try {
        const { dataInicio, dataFim, departamento, filial, agruparPor } = parametros;
        console.log(' Gerando relatório de folha:', parametros);

        // Buscar colaboradores REAIS do backend
        let colaboradores = await DataManager.getColaboradores(true);

        if (!colaboradores || colaboradores.length === 0) {
          throw new Error('Nenhum colaborador encontrado');
        }

        // Aplicar filtros
        if (departamento && departamento !== '') {
          colaboradores = colaboradores.filter(c => c.setor === departamento);
        }

        if (filial && filial !== '') {
          colaboradores = colaboradores.filter(c => c.filial === filial);
        }

        console.log(` ${colaboradores.length} colaboradores após filtros`);

        // Calcular folha com dados reais
        const { resultados, resumo } = await CalculadoraFolha.calcularFolhaCompleta(colaboradores);

        // Agrupar dados
        const dadosAgrupados = this.agruparDados(resultados, agruparPor);

        // Renderizar preview
        this.renderizarPreviewFolha(dadosAgrupados, resumo, parametros);

        Utils.showToast('Relatório gerado com sucesso!', 'success');
        return { dadosAgrupados, resumo };

      } catch (error) {
        console.error(' Erro ao gerar relatório de folha:', error);
        Utils.showToast('Erro ao gerar relatório: ' + error.message, 'error');
        throw error;
      } finally {
        Utils.showLoading(false);
      }
    },

    agruparDados(resultados, agruparPor) {
      if (!agruparPor || agruparPor === 'funcionario') {
        return resultados;
      }

      const grupos = {};
      
      resultados.forEach(r => {
        const chave = r[agruparPor] || 'Não informado';
        
        if (!grupos[chave]) {
          grupos[chave] = {
            grupo: chave,
            colaboradores: [],
            totalBruto: 0,
            totalBeneficios: 0,
            totalINSS: 0,
            totalIRRF: 0,
            totalFGTS: 0,
            totalDescontos: 0,
            totalLiquido: 0,
            quantidade: 0
          };
        }

        grupos[chave].colaboradores.push(r);
        grupos[chave].totalBruto += r.salarioBruto;
        grupos[chave].totalBeneficios += r.beneficios;
        grupos[chave].totalINSS += r.inss;
        grupos[chave].totalIRRF += r.irrf;
        grupos[chave].totalFGTS += r.fgts;
        grupos[chave].totalDescontos += r.totalDescontos;
        grupos[chave].totalLiquido += r.salarioLiquido;
        grupos[chave].quantidade++;
      });

      return Object.values(grupos);
    },

    renderizarPreviewFolha(dados, resumo, parametros) {
      const previewContainer = document.getElementById('preview-folha-tbody');
      if (!previewContainer) return;

      previewContainer.innerHTML = '';

      // Renderizar dados
      if (Array.isArray(dados) && dados[0] && dados[0].grupo) {
        // Dados agrupados
        dados.forEach(grupo => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td><strong>${grupo.grupo}</strong> <span class="badge bg-secondary">${grupo.quantidade}</span></td>
            <td class="text-end">${Utils.formatCurrency(grupo.totalBruto)}</td>
            <td class="text-end">${Utils.formatCurrency(grupo.totalINSS + grupo.totalIRRF)}</td>
            <td class="text-end">${Utils.formatCurrency(grupo.totalBeneficios)}</td>
            <td class="text-end"><strong>${Utils.formatCurrency(grupo.totalLiquido)}</strong></td>
          `;
          previewContainer.appendChild(tr);
        });
      } else {
        // Dados individuais
        dados.forEach(colab => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${colab.nome} <span class="small text-muted">${colab.cargo || ''}</span></td>
            <td class="text-end">${Utils.formatCurrency(colab.salarioBruto)}</td>
            <td class="text-end">${Utils.formatCurrency(colab.inss + colab.irrf)}</td>
            <td class="text-end">${Utils.formatCurrency(colab.beneficios)}</td>
            <td class="text-end"><strong>${Utils.formatCurrency(colab.salarioLiquido)}</strong></td>
          `;
          previewContainer.appendChild(tr);
        });
      }

      // Atualizar resumo
      const summaryEl = document.querySelector('#preview-folha .preview-summary');
      if (summaryEl) {
        summaryEl.innerHTML = `
          <strong>Resumo:</strong> ${dados.length} ${parametros.agruparPor === 'funcionario' ? 'colaboradores' : 'grupos'} | 
          Total Bruto: ${Utils.formatCurrency(resumo.totalBruto)} | 
          Total Líquido: ${Utils.formatCurrency(resumo.totalLiquido)} | 
          FGTS Empresa: ${Utils.formatCurrency(resumo.totalFGTS)}
        `;
      }

      // Gerar gráfico
      this.gerarGraficoFolha(dados, parametros);
    },

    gerarGraficoFolha(dados, parametros) {
      const chartContainer = document.querySelector('[data-chart="folha"]');
      if (!chartContainer) return;

      const dadosGrafico = dados.slice(0, 10);
      const labels = dadosGrafico.map(d => d.nome || d.grupo || 'Item');
      const valores = dadosGrafico.map(d => d.salarioBruto || d.totalBruto || 0);
      const maxValor = Math.max(...valores);

      chartContainer.innerHTML = `
        <div style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 8px;">
          <h6 style="color: rgba(255,255,255,0.9); margin-bottom: 15px;">
            Distribuição de Salários (Top 10) - <span class="badge bg-success"></span>
          </h6>
          <div style="display: flex; flex-direction: column; gap: 10px;">
            ${labels.map((label, i) => `
              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                  <span style="font-size: 12px; color: rgba(255,255,255,0.7);">${label}</span>
                  <span style="font-size: 12px; color: rgba(255,255,255,0.9);">${Utils.formatCurrency(valores[i])}</span>
                </div>
                <div style="background: rgba(255,255,255,0.1); height: 8px; border-radius: 4px; overflow: hidden;">
                  <div style="
                    background: linear-gradient(90deg, #10b981, #059669);
                    height: 100%;
                    width: ${(valores[i] / maxValor) * 100}%;
                    transition: width 0.3s ease;
                  "></div>
                </div>
              </div>
            `).join('')}
          </div>
        </div>
      `;
    },

    async gerarRelatorioCusto(parametros) {
      Utils.showLoading(true);
      
      try {
        console.log(' Gerando relatório de custos:', parametros);

        // Buscar colaboradores REAIS
        let colaboradores = await DataManager.getColaboradores(true);

        // Calcular custos reais com encargos
        const resultados = colaboradores.map(colab => {
          const salarioBruto = parseFloat(colab.salario) || 0;
          const inss = CalculadoraFolha.calcularINSS(salarioBruto);
          const fgts = CalculadoraFolha.calcularFGTS(salarioBruto);
          const encargosPatronais = CalculadoraFolha.calcularEncargosPatronais(salarioBruto);
          const custoTotal = salarioBruto + encargosPatronais;

          return {
            id: colab.id,
            nome: colab.nome,
            cargo: colab.cargo,
            setor: colab.setor,
            salarioBruto,
            encargosPatronais,
            custoTotal,
            custoMensal: custoTotal,
            custoAnual: custoTotal * 13.33 // 12 meses + 13º + 1/3 férias
          };
        });

        // Agrupar
        const dadosAgrupados = this.agruparCustos(resultados, parametros.agruparPor);

        // Armazenar resumo global para PDF
        window.lastReportResumo = {
          totalSalario: dadosAgrupados.reduce((sum, g) => sum + g.totalSalario, 0),
          totalEncargos: dadosAgrupados.reduce((sum, g) => sum + g.totalEncargos, 0),
          totalCusto: dadosAgrupados.reduce((sum, g) => sum + g.totalCusto, 0)
        };

        // Renderizar
        this.renderizarPreviewCusto(dadosAgrupados, parametros);

        Utils.showToast('Relatório de custos gerado com sucesso!', 'success');
        return { dadosAgrupados };

      } catch (error) {
        console.error(' Erro ao gerar relatório de custos:', error);
        Utils.showToast('Erro ao gerar relatório: ' + error.message, 'error');
        throw error;
      } finally {
        Utils.showLoading(false);
      }
    },

    agruparCustos(resultados, agruparPor) {
      const campo = agruparPor === 'centro_custo' ? 'setor' : agruparPor;
      const grupos = {};

      resultados.forEach(r => {
        const chave = r[campo] || 'Não informado';
        
        if (!grupos[chave]) {
          grupos[chave] = {
            agrupamento: chave,
            totalSalario: 0,
            totalEncargos: 0,
            totalCusto: 0,
            quantidade: 0,
            colaboradores: []
          };
        }

        grupos[chave].totalSalario += r.salarioBruto;
        grupos[chave].totalEncargos += r.encargosPatronais;
        grupos[chave].totalCusto += r.custoTotal;
        grupos[chave].quantidade++;
        grupos[chave].colaboradores.push(r);
      });

      return Object.values(grupos);
    },

    renderizarPreviewCusto(dados, parametros) {
      const previewContainer = document.getElementById('preview-custo-tbody');
      if (!previewContainer) return;

      previewContainer.innerHTML = '';

      dados.forEach(grupo => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td><strong>${grupo.agrupamento}</strong> <span class="badge bg-secondary">${grupo.quantidade}</span></td>
          <td class="text-end">${Utils.formatCurrency(grupo.totalSalario)}</td>
          <td class="text-end">${Utils.formatCurrency(grupo.totalEncargos)}</td>
          <td class="text-end"><strong>${Utils.formatCurrency(grupo.totalCusto)}</strong></td>
        `;
        previewContainer.appendChild(tr);
      });

      // Atualizar summary
      const summaryEl = document.querySelector('#preview-custo .preview-summary');
      if (summaryEl) {
        const totalCusto = dados.reduce((sum, g) => sum + g.totalCusto, 0);
        const totalColaboradores = dados.reduce((sum, g) => sum + g.quantidade, 0);
        const custoMedio = totalColaboradores > 0 ? totalCusto / totalColaboradores : 0;

        summaryEl.innerHTML = `
          <strong>Resumo - <span class="badge bg-success"></span>:</strong> ${dados.length} grupos | 
          ${totalColaboradores} colaboradores | 
          Custo Total: ${Utils.formatCurrency(totalCusto)} | 
          Custo Médio: ${Utils.formatCurrency(custoMedio)}
        `;
      }

      this.gerarGraficoCusto(dados);
    },

    gerarGraficoCusto(dados) {
      const chartContainer = document.querySelector('[data-chart="custo"]');
      if (!chartContainer) return;

      const dadosGrafico = dados.slice(0, 8);
      const maxCusto = Math.max(...dadosGrafico.map(g => g.totalCusto));

      chartContainer.innerHTML = `
        <div style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 8px;">
          <h6 style="color: rgba(255,255,255,0.9); margin-bottom: 15px;">
            Distribuição de Custos por Grupo - <span class="badge bg-success"></span>
          </h6>
          <div style="display: flex; flex-direction: column; gap: 10px;">
            ${dadosGrafico.map(grupo => `
              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                  <span style="font-size: 12px; color: rgba(255,255,255,0.7);">
                    ${grupo.agrupamento} (${grupo.quantidade})
                  </span>
                  <span style="font-size: 12px; color: rgba(255,255,255,0.9);">
                    ${Utils.formatCurrency(grupo.totalCusto)}
                  </span>
                </div>
                <div style="background: rgba(255,255,255,0.1); height: 8px; border-radius: 4px; overflow: hidden;">
                  <div style="
                    background: linear-gradient(90deg, #a855f7, #7c3aed);
                    height: 100%;
                    width: ${(grupo.totalCusto / maxCusto) * 100}%;
                    transition: width 0.3s ease;
                  "></div>
                </div>
              </div>
            `).join('')}
          </div>
        </div>
      `;
    },

    async gerarRelatorioBeneficios(parametros) {
      Utils.showLoading(true);
      
      try {
        console.log(' Gerando relatório de benefícios:', parametros);

        // Buscar colaboradores e benefícios REAIS
        const colaboradores = await DataManager.getColaboradores(true);
        const beneficios = await DataManager.getBeneficios(true);

        // Calcular custos reais de benefícios
        const beneficiosPorColaborador = colaboradores.map(colab => {
          const beneficiosUsuario = colab.beneficios || [];
          
          return {
            id: colab.id,
            nome: colab.nome,
            cargo: colab.cargo,
            setor: colab.setor,
            beneficios: beneficiosUsuario.map(b => ({
              id: b.id || b.beneficio_id,
              nome: b.nome_do_beneficio || b.nome,
              valor: parseFloat(b.valor_personalizado || b.valor_aplicado || 0),
              custo_mensal: parseFloat(b.valor_personalizado || b.valor_aplicado || 0)
            })),
            total_beneficios: beneficiosUsuario.reduce((sum, b) => {
              return sum + parseFloat(b.valor_personalizado || b.valor_aplicado || 0);
            }, 0)
          };
        });

        // Agrupar por tipo de benefício
        const beneficiosPorTipo = {};
        
        beneficiosPorColaborador.forEach(colab => {
          colab.beneficios.forEach(b => {
            if (!beneficiosPorTipo[b.nome]) {
              beneficiosPorTipo[b.nome] = {
                nome: b.nome,
                total_colaboradores: 0,
                custo_total: 0,
                custo_medio: 0,
                colaboradores: []
              };
            }
            
            beneficiosPorTipo[b.nome].total_colaboradores++;
            beneficiosPorTipo[b.nome].custo_total += b.valor;
            beneficiosPorTipo[b.nome].colaboradores.push({
              nome: colab.nome,
              valor: b.valor
            });
          });
        });

        // Calcular médias
        Object.values(beneficiosPorTipo).forEach(tipo => {
          tipo.custo_medio = tipo.custo_total / tipo.total_colaboradores;
        });

        const dadosAgrupados = Object.values(beneficiosPorTipo);

        // Armazenar resumo global para PDF
        window.lastReportResumo = {
          tiposDistintos: dadosAgrupados.length,
          totalColaboradores: dadosAgrupados.reduce((sum, b) => sum + b.total_colaboradores, 0),
          totalCusto: dadosAgrupados.reduce((sum, b) => sum + b.custo_total, 0)
        };

        // Renderizar preview
        this.renderizarPreviewBeneficios(dadosAgrupados, parametros);

        Utils.showToast('Relatório de benefícios gerado com sucesso!', 'success');
        return { dadosAgrupados };

      } catch (error) {
        console.error(' Erro ao gerar relatório de benefícios:', error);
        Utils.showToast('Erro ao gerar relatório: ' + error.message, 'error');
        throw error;
      } finally {
        Utils.showLoading(false);
      }
    },

    renderizarPreviewBeneficios(dados, parametros) {
      const previewContainer = document.getElementById('preview-beneficios-tbody');
      if (!previewContainer) return;

      previewContainer.innerHTML = '';

      dados.forEach(beneficio => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td><strong>${beneficio.nome}</strong></td>
          <td class="text-center">${beneficio.total_colaboradores}</td>
          <td class="text-end">${Utils.formatCurrency(beneficio.custo_medio)}</td>
          <td class="text-end"><strong>${Utils.formatCurrency(beneficio.custo_total)}</strong></td>
        `;
        previewContainer.appendChild(tr);
      });

      // Atualizar resumo
      const summaryEl = document.querySelector('#preview-beneficios .preview-summary');
      if (summaryEl) {
        const totalCusto = dados.reduce((sum, b) => sum + b.custo_total, 0);
        const totalColaboradores = dados.reduce((sum, b) => sum + b.total_colaboradores, 0);
        const tiposDistintos = dados.length;

        summaryEl.innerHTML = `
          <strong>Resumo - <span class="badge bg-success"></span>:</strong> 
          ${tiposDistintos} tipos de benefícios | 
          ${totalColaboradores} concessões | 
          Custo Total: ${Utils.formatCurrency(totalCusto)}
        `;
      }

      this.gerarGraficoBeneficios(dados);
    },

    gerarGraficoBeneficios(dados) {
      const chartContainer = document.querySelector('[data-chart="beneficios"]');
      if (!chartContainer) return;

      const dadosGrafico = dados.slice(0, 10);
      const maxCusto = Math.max(...dadosGrafico.map(b => b.custo_total));

      chartContainer.innerHTML = `
        <div style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 8px;">
          <h6 style="color: rgba(255,255,255,0.9); margin-bottom: 15px;">
            Distribuição de Custos com Benefícios - <span class="badge bg-success"></span>
          </h6>
          <div style="display: flex; flex-direction: column; gap: 10px;">
            ${dadosGrafico.map(beneficio => `
              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                  <span style="font-size: 12px; color: rgba(255,255,255,0.7);">
                    ${beneficio.nome} (${beneficio.total_colaboradores})
                  </span>
                  <span style="font-size: 12px; color: rgba(255,255,255,0.9);">
                    ${Utils.formatCurrency(beneficio.custo_total)}
                  </span>
                </div>
                <div style="background: rgba(255,255,255,0.1); height: 8px; border-radius: 4px; overflow: hidden;">
                  <div style="
                    background: linear-gradient(90deg, #f59e0b, #d97706);
                    height: 100%;
                    width: ${(beneficio.custo_total / maxCusto) * 100}%;
                    transition: width 0.3s ease;
                  "></div>
                </div>
              </div>
            `).join('')}
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
      console.log(' Inicializando UI Manager');
      this.configurarEventos();
      this.carregarDadosIniciais();
    },

    configurarEventos() {
      // Eventos dos botões de geração de relatórios
      const btnGerarFolha = document.getElementById('btn-gerar-folha');
      const btnGerarCusto = document.getElementById('btn-gerar-custo');
      const btnGerarBeneficios = document.getElementById('btn-gerar-beneficios');

      if (btnGerarFolha) {
        btnGerarFolha.addEventListener('click', () => this.handleGerarRelatorioFolha());
      }

      if (btnGerarCusto) {
        btnGerarCusto.addEventListener('click', () => this.handleGerarRelatorioCusto());
      }

      if (btnGerarBeneficios) {
        btnGerarBeneficios.addEventListener('click', () => this.handleGerarRelatorioBeneficios());
      }

      console.log(' Eventos configurados');
    },

    async carregarDadosIniciais() {
      try {
        console.log(' Carregando dados iniciais...');
        
        // Carregar setores para filtros
        const setores = await DataManager.getSetores(true);
        this.popularFiltrosSetores(setores);

        console.log(' Dados iniciais carregados');
      } catch (error) {
        console.error(' Erro ao carregar dados iniciais:', error);
        Utils.showToast('Erro ao carregar dados iniciais', 'error');
      }
    },

    popularFiltrosSetores(setores) {
      const selects = document.querySelectorAll('select[name="departamento"], select[name="setor"]');
      
      selects.forEach(select => {
        if (!select) return;
        
        // Limpar opções existentes (exceto a primeira)
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

    async handleGerarRelatorioFolha() {
      try {
        const dataInicio = document.getElementById('data-inicio-folha')?.value;
        const dataFim = document.getElementById('data-fim-folha')?.value;
        const departamento = document.getElementById('departamento-folha')?.value;
        const filial = document.getElementById('filial-folha')?.value;
        const agruparPor = document.getElementById('agrupar-folha')?.value || 'funcionario';

        const parametros = {
          dataInicio: dataInicio || new Date().toISOString().slice(0, 7) + '-01',
          dataFim: dataFim || new Date().toISOString().slice(0, 10),
          departamento,
          filial,
          agruparPor
        };

        await RelatorioFinanceiro.gerarRelatorioFolha(parametros);
        
        // Mostrar preview
        const previewSection = document.getElementById('preview-folha');
        if (previewSection) {
          previewSection.style.display = 'block';
          previewSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

      } catch (error) {
        console.error(' Erro ao gerar relatório de folha:', error);
        Utils.showToast('Erro ao gerar relatório de folha', 'error');
      }
    },

    async handleGerarRelatorioCusto() {
      try {
        const periodo = document.getElementById('periodo-custo')?.value || 'mensal';
        const agruparPor = document.getElementById('agrupar-custo')?.value || 'setor';

        const parametros = {
          periodo,
          agruparPor
        };

        await RelatorioFinanceiro.gerarRelatorioCusto(parametros);
        
        // Mostrar preview
        const previewSection = document.getElementById('preview-custo');
        if (previewSection) {
          previewSection.style.display = 'block';
          previewSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

      } catch (error) {
        console.error(' Erro ao gerar relatório de custos:', error);
        Utils.showToast('Erro ao gerar relatório de custos', 'error');
      }
    },

    async handleGerarRelatorioBeneficios() {
      try {
        const periodo = document.getElementById('periodo-beneficios')?.value || 'mensal';
        const tipoBeneficio = document.getElementById('tipo-beneficio')?.value;

        const parametros = {
          periodo,
          tipoBeneficio
        };

        await RelatorioFinanceiro.gerarRelatorioBeneficios(parametros);
        
        // Mostrar preview
        const previewSection = document.getElementById('preview-beneficios');
        if (previewSection) {
          previewSection.style.display = 'block';
          previewSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

      } catch (error) {
        console.error(' Erro ao gerar relatório de benefícios:', error);
        Utils.showToast('Erro ao gerar relatório de benefícios', 'error');
      }
    }
  };

  /* ============================
     INICIALIZAÇÃO
     ============================ */
  document.addEventListener('DOMContentLoaded', () => {
    console.log(' Iniciando Análise Financeira com dados REAIS - v3.0');
    
    try {
      UIManager.inicializar();
      console.log(' Sistema inicializado com sucesso');
    } catch (error) {
      console.error(' Erro na inicialização:', error);
      Utils.showToast('Erro ao inicializar sistema', 'error');
    }
  });

  // Exportar para uso global se necessário
  if (typeof window !== 'undefined') {
    window.AnaliseFinanceira = {
      Utils,
      DataManager,
      CalculadoraFolha,
      RelatorioFinanceiro,
      UIManager
    };
  }

})();