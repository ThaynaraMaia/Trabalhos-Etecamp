// backend/controllers/pdfController.js - VERSÃO COM HELPERS CORRIGIDA
const puppeteer = require('puppeteer');
const path = require('path');
const fs = require('fs').promises;
const handlebars = require('handlebars');

class PdfController {
  constructor() {
    this.templatesPath = path.join(__dirname, '..', '..', 'frontend', 'views', 'templates');
    this.cssPath = path.join(__dirname, '..', '..', 'frontend', 'public', 'css');

    // Registrar helpers do Handlebars
    this.registerHelpers();
  }

  /**
   * Registra helpers customizados do Handlebars
   */
  registerHelpers() {
    // Helper para formatação de moeda
    handlebars.registerHelper('formatCurrency', (value) => {
      const num = parseFloat(value) || 0;
      return num.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    });

    // Helper para formatação de percentual
    handlebars.registerHelper('formatPercent', (value, decimals = 2) => {
      const num = parseFloat(value) || 0;
      return num.toFixed(decimals) + '%';
    });

    // Helper para formatação de data
    handlebars.registerHelper('formatDate', (date, includeTime = false) => {
      if (!date) return '';
      const d = new Date(date);
      const dateStr = d.toLocaleDateString('pt-BR');
      return includeTime ? `${dateStr} ${d.toLocaleTimeString('pt-BR')}` : dateStr;
    });

    // Helper para condicionais
    handlebars.registerHelper('eq', (a, b) => a === b);
    handlebars.registerHelper('gt', (a, b) => a > b);
    handlebars.registerHelper('lt', (a, b) => a < b);
    handlebars.registerHelper('gte', (a, b) => a >= b);
    handlebars.registerHelper('lte', (a, b) => a <= b);

    // Helper para operações matemáticas (CRÍTICO - estava faltando)
    handlebars.registerHelper('add', (a, b) => {
      const numA = parseFloat(a) || 0;
      const numB = parseFloat(b) || 0;
      return numA + numB;
    });

    handlebars.registerHelper('subtract', (a, b) => {
      const numA = parseFloat(a) || 0;
      const numB = parseFloat(b) || 0;
      return numA - numB;
    });

    handlebars.registerHelper('multiply', (a, b) => {
      const numA = parseFloat(a) || 0;
      const numB = parseFloat(b) || 0;
      return numA * numB;
    });

    handlebars.registerHelper('divide', (a, b) => {
      const numA = parseFloat(a) || 0;
      const numB = parseFloat(b) || 0;
      return numB !== 0 ? numA / numB : 0;
    });

    // Helper para cálculo de percentual
    handlebars.registerHelper('calcPercent', (part, total) => {
      const numPart = parseFloat(part) || 0;
      const numTotal = parseFloat(total) || 0;
      return numTotal !== 0 ? ((numPart / numTotal) * 100).toFixed(2) : 0;
    });

    // Helper para verificar se é array
    handlebars.registerHelper('isArray', (value) => {
      return Array.isArray(value);
    });

    // Helper para verificar se está definido
    handlebars.registerHelper('defined', (value) => {
      return value !== undefined && value !== null;
    });

    // Helper para contar elementos
    handlebars.registerHelper('count', (array) => {
      return Array.isArray(array) ? array.length : 0;
    });

    console.log(' Helpers do Handlebars registrados (incluindo add)');
  }

  /**
   * Carrega um template HBS
   */
  async loadTemplate(templateName) {
    try {
      const templatePath = path.join(this.templatesPath, `${templateName}.hbs`);
      const templateContent = await fs.readFile(templatePath, 'utf8');
      return handlebars.compile(templateContent);
    } catch (error) {
      console.error(` Erro ao carregar template ${templateName}:`, error);
      
      // Fallback: template básico sem helpers complexos
      const fallbackTemplate = `
        <!DOCTYPE html>
        <html>
        <head>
          <meta charset="utf-8"/>
          <title>{{title}}</title>
          <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f5f5f5; }
            .total { font-weight: bold; background-color: #f0f0f0; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
            .text-right { text-align: right; }
          </style>
        </head>
        <body>
          <div class="header">
            <h1>{{title}}</h1>
            <p>{{subtitle}}</p>
            <p><strong>Empresa:</strong> {{companyName}} | <strong>Período:</strong> {{periodStart}} a {{periodEnd}}</p>
          </div>
          
          {{#if resumo}}
          <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3>Resumo Financeiro</h3>
            <table>
              <tr>
                <td><strong>Total Bruto:</strong></td>
                <td class="text-right">R$ {{resumo.totalBruto}}</td>
                <td><strong>Total Líquido:</strong></td>
                <td class="text-right">R$ {{resumo.totalLiquido}}</td>
              </tr>
            </table>
          </div>
          {{/if}}

          {{#if rows.length}}
          <table>
            <thead>
              <tr>
                <th>Nome</th>
                <th class="text-right">Salário Bruto</th>
                <th class="text-right">Descontos</th>
                <th class="text-right">Benefícios</th>
                <th class="text-right">Líquido</th>
              </tr>
            </thead>
            <tbody>
              {{#each rows}}
              <tr>
                <td>{{this.nome}}</td>
                <td class="text-right">R$ {{this.salarioBruto}}</td>
                <td class="text-right">R$ {{this.totalDescontos}}</td>
                <td class="text-right">R$ {{this.beneficios}}</td>
                <td class="text-right"><strong>R$ {{this.salarioLiquido}}</strong></td>
              </tr>
              {{/each}}
            </tbody>
          </table>
          {{/if}}

          <div class="footer">
            <p>Gerado por {{generatedBy}} em {{generatedAt}}</p>
            <p>{{footerText}}</p>
          </div>
        </body>
        </html>
      `;
      
      return handlebars.compile(fallbackTemplate);
    }
  }

  /**
   * Gera HTML a partir do template e dados
   */
  async generateHTML(templateName, data) {
    try {
      console.log(`Gerando HTML para template: ${templateName}`);
      
      // Carregar template
      const template = await this.loadTemplate(templateName);

      // Preparar dados com informações adicionais
      const enrichedData = {
        ...data,
        generatedAt: new Date().toLocaleString('pt-BR'),
        generatedBy: data.generatedBy || 'Sistema',
        companyName: data.companyName || 'Minha Empresa',
        footerText: data.footerText || 'Documento gerado automaticamente',
        // Garantir que resumo tenha valores numéricos
        resumo: data.resumo ? {
          totalBruto: parseFloat(data.resumo.totalBruto || 0).toFixed(2),
          totalLiquido: parseFloat(data.resumo.totalLiquido || 0).toFixed(2),
          totalBeneficios: parseFloat(data.resumo.totalBeneficios || 0).toFixed(2),
          totalDescontos: parseFloat(data.resumo.totalDescontos || 0).toFixed(2),
          totalINSS: parseFloat(data.resumo.totalINSS || 0).toFixed(2),
          totalIRRF: parseFloat(data.resumo.totalIRRF || 0).toFixed(2)
        } : null
      };

      // Preparar rows para evitar problemas com helpers
      if (enrichedData.rows && Array.isArray(enrichedData.rows)) {
        enrichedData.rows = enrichedData.rows.map(row => ({
          ...row,
          salarioBruto: parseFloat(row.salarioBruto || 0).toFixed(2),
          beneficios: parseFloat(row.beneficios || 0).toFixed(2),
          inss: parseFloat(row.inss || 0).toFixed(2),
          irrf: parseFloat(row.irrf || 0).toFixed(2),
          totalDescontos: (parseFloat(row.inss || 0) + parseFloat(row.irrf || 0)).toFixed(2),
          salarioLiquido: parseFloat(row.salarioLiquido || 0).toFixed(2)
        }));
      }

      console.log(' Dados preparados para template:', {
        rowsCount: enrichedData.rows ? enrichedData.rows.length : 0,
        hasResumo: !!enrichedData.resumo,
        template: templateName
      });

      // Compilar template com dados
      const html = template(enrichedData);
      console.log(' HTML gerado com sucesso');

      return html;
    } catch (error) {
      console.error(' Erro ao gerar HTML:', error);
      throw error;
    }
  }

  // ... o resto do código permanece igual (generatePdfFromHTML, generatePdf, etc.)
  /**
   * Gera PDF a partir do HTML (VERSÃO OTIMIZADA)
   */
  async generatePdfFromHTML(html, options = {}) {
    let browser = null;

    try {
      console.log(' Iniciando geração de PDF...');

      // Configurações otimizadas do Puppeteer
      const puppeteerOptions = {
        headless: true,
        args: [
          '--no-sandbox',
          '--disable-setuid-sandbox',
          '--disable-dev-shm-usage',
          '--disable-gpu',
          '--disable-features=VizDisplayCompositor',
          '--disable-background-timer-throttling',
          '--disable-backgrounding-occluded-windows',
          '--disable-renderer-backgrounding'
        ],
        timeout: 60000 // 60 segundos
      };

      // Iniciar browser
      browser = await puppeteer.launch(puppeteerOptions);
      const page = await browser.newPage();

      // Configurar timeout da página
      await page.setDefaultNavigationTimeout(60000);
      await page.setDefaultTimeout(60000);

      // Configurar viewport
      await page.setViewport({ width: 1200, height: 800 });

      console.log(' Configurando conteúdo HTML...');

      // Usar approach mais robusto: criar arquivo temporário
      const tempDir = path.join(__dirname, '..', 'temp');
      try {
        await fs.access(tempDir);
      } catch {
        await fs.mkdir(tempDir, { recursive: true });
      }

      const tempFile = path.join(tempDir, `temp_${Date.now()}.html`);
      await fs.writeFile(tempFile, html, 'utf8');

      // Navegar para o arquivo local
      const fileUrl = `file://${tempFile}`;
      await page.goto(fileUrl, {
        waitUntil: 'networkidle0',
        timeout: 60000
      });

      // Esperar um pouco extra para garantir que tudo carregou
      await page.waitForTimeout(2000);

      console.log('🖨️ Gerando PDF...');

      // Configurações do PDF
      const pdfOptions = {
        format: options.format || 'A4',
        printBackground: true,
        margin: {
          top: options.margin?.top || '15mm',
          right: options.margin?.right || '15mm',
          bottom: options.margin?.bottom || '20mm',
          left: options.margin?.left || '15mm'
        },
        displayHeaderFooter: false,
        preferCSSPageSize: true
      };

      // Gerar PDF
      const pdfBuffer = await page.pdf(pdfOptions);

      // Limpar arquivo temporário
      try {
        await fs.unlink(tempFile);
      } catch (cleanupError) {
        console.warn('⚠️ Não foi possível limpar arquivo temporário:', cleanupError);
      }

      console.log(' PDF gerado com sucesso!');
      return pdfBuffer;

    } catch (error) {
      console.error(' Erro ao gerar PDF:', error);
      
      // Tentar abordagem alternativa se a primeira falhar
      try {
        if (browser) {
          console.log(' Tentando abordagem alternativa...');
          const page = await browser.newPage();
          
          // Approach direto com setContent
          await page.setContent(html, {
            waitUntil: 'domcontentloaded',
            timeout: 30000
          });

          const pdfBuffer = await page.pdf({
            format: 'A4',
            printBackground: true,
            margin: { top: '15mm', right: '15mm', bottom: '20mm', left: '15mm' }
          });

          console.log(' PDF gerado com abordagem alternativa!');
          return pdfBuffer;
        }
      } catch (fallbackError) {
        console.error(' Abordagem alternativa também falhou:', fallbackError);
      }

      throw error;
    } finally {
      if (browser) {
        await browser.close();
        console.log('🔒 Browser fechado');
      }
    }
  }

  /**
   * Controller principal: POST /api/pdf/generate
   */
  async generatePdf(req, res) {
    try {
      const { template, data, options, html: customHtml } = req.body;

      console.log(' Gerando PDF...');
      console.log('Template:', template);
      console.log('Dados recebidos:', Object.keys(data || {}));

      // Validar entrada
      if (!customHtml && (!template || !data)) {
        return res.status(400).json({
          success: false,
          error: 'É necessário fornecer "template" e "data" ou "html" diretamente'
        });
      }

      let html;

      // Modo 1: HTML customizado direto
      if (customHtml) {
        html = customHtml;
        console.log(' Usando HTML customizado');
      }
      // Modo 2: Template HBS + dados
      else {
        html = await this.generateHTML(template, data);
        console.log(' HTML gerado a partir do template:', template);
      }

      // Log do tamanho do HTML para debug
      console.log(` Tamanho do HTML: ${html.length} caracteres`);

      // Gerar PDF
      const pdfBuffer = await this.generatePdfFromHTML(html, options);

      // Definir headers para download
      const filename = options?.filename || `relatorio_${new Date().toISOString().slice(0,10)}.pdf`;

      res.setHeader('Content-Type', 'application/pdf');
      res.setHeader('Content-Disposition', `attachment; filename="${filename}"`);
      res.setHeader('Content-Length', pdfBuffer.length);

      console.log(' PDF enviado com sucesso:', filename);

      return res.send(pdfBuffer);

    } catch (error) {
      console.error(' Erro ao gerar PDF:', error);

      // Resposta de erro mais informativa
      return res.status(500).json({
        success: false,
        error: 'Erro ao gerar PDF',
        message: error.message,
        details: process.env.NODE_ENV === 'development' ? error.stack : 'Entre em contato com o administrador'
      });
    }
  }

  // ... manter as outras funções (previewHtml, listTemplates) iguais
  /**
   * Gera preview HTML (útil para debugging)
   */
  async previewHtml(req, res) {
    try {
      const { template, data } = req.body;

      if (!template || !data) {
        return res.status(400).json({
          success: false,
          error: 'É necessário fornecer "template" e "data"'
        });
      }

      const html = await this.generateHTML(template, data);

      res.setHeader('Content-Type', 'text/html');
      return res.send(html);

    } catch (error) {
      console.error(' Erro ao gerar preview HTML:', error);

      return res.status(500).json({
        success: false,
        error: 'Erro ao gerar preview',
        message: error.message
      });
    }
  }

  /**
   * Lista templates disponíveis
   */
  async listTemplates(req, res) {
    try {
      const files = await fs.readdir(this.templatesPath);
      const templates = files
        .filter(f => f.endsWith('.hbs'))
        .map(f => f.replace('.hbs', ''));

      return res.json({
        success: true,
        templates
      });
    } catch (error) {
      console.error(' Erro ao listar templates:', error);

      return res.status(500).json({
        success: false,
        error: 'Erro ao listar templates',
        message: error.message
      });
    }
  }
}

// Exportar instância única
const pdfController = new PdfController();

module.exports = {
  generatePdf: (req, res) => pdfController.generatePdf(req, res),
  previewHtml: (req, res) => pdfController.previewHtml(req, res),
  listTemplates: (req, res) => pdfController.listTemplates(req, res)
};