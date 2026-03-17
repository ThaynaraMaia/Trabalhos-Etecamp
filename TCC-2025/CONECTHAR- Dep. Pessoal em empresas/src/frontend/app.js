// frontend/app.js
const express = require('express');
const exphbs = require('express-handlebars');
const path = require('path');
const cookieParser = require('cookie-parser');
require('dotenv').config();

const app = express();

const FRONT_PORT = process.env.FRONT_PORT || 3000;
const BACKEND_URL = process.env.BACKEND_URL || 'http://localhost:3001';

// --- Middlewares globais ---
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(cookieParser());

// disponibiliza BACKEND_URL e currentPath para todas as views
app.use((req, res, next) => {
  res.locals.BACKEND_URL = process.env.BACKEND_URL || 'http://localhost:3001';
  res.locals.currentPath = req.path;
  next();
});

// 🔥 CORREÇÃO: Rota de solicitações do gestor com BACKEND_URL
app.get('/gestor/solicitacoes', (req, res) => {
  res.render('solicitacoes', { 
    title: 'Solicitações',
    BACKEND_URL: process.env.BACKEND_URL || 'http://localhost:3001'
  });
});
// --- Handlebars + helpers ---
const hbs = exphbs.create({
  extname: '.handlebars',
  layoutsDir: path.join(__dirname, 'views', 'layouts'),
  defaultLayout: 'main',
  partialsDir: path.join(__dirname, 'views', 'partials'),
  helpers: {
    // compara valores básicos com operador
    ifCond: function (v1, operator, v2, options) {
      switch (operator) {
        case '==': return (v1 == v2) ? options.fn(this) : options.inverse(this);
        case '===': return (v1 === v2) ? options.fn(this) : options.inverse(this);
        case '!=': return (v1 != v2) ? options.fn(this) : options.inverse(this);
        case '<': return (v1 < v2) ? options.fn(this) : options.inverse(this);
        case '<=': return (v1 <= v2) ? options.fn(this) : options.inverse(this);
        case '>': return (v1 > v2) ? options.fn(this) : options.inverse(this);
        case '>=': return (v1 >= v2) ? options.fn(this) : options.inverse(this);
        default: return options.inverse(this);
      }
    },
    eq: (a, b) => a === b,
    // ifActive: adiciona classe 'active' quando a rota atual começa com o path passado
    ifActive: function (expectedPath, options) {
      // res.locals.currentPath está disponível por middleware
      const current = (options && options.data && options.data.root && options.data.root.currentPath) || this.currentPath || '';
      if (!expectedPath) return '';
      // aceita arrays (se quiser) ou string
      if (Array.isArray(expectedPath)) {
        for (const p of expectedPath) {
          if (current === p || current.startsWith(p)) return options.fn ? options.fn(this) : 'active';
        }
      } else {
        if (current === expectedPath || current.startsWith(expectedPath)) {
          return options.fn ? options.fn(this) : 'active';
        }
      }
      return options.inverse ? options.inverse(this) : '';
    },
    // ADICIONE ESTES HELPERS:
    // Helper simples para data atual
    now: function() {
      return new Date().toLocaleDateString('pt-BR');
    },
    // Helper básico para formatação de moeda
    formatCurrency: function(value) {
      if (!value) return 'R$ 0,00';
      const number = typeof value === 'string' ? parseFloat(value.replace(',', '.')) : value;
      if (isNaN(number)) return 'R$ 0,00';
      return 'R$ ' + number.toFixed(2).replace('.', ',');
    }
  }
});

app.engine('handlebars', hbs.engine);
app.set('view engine', 'handlebars');
app.set('views', path.join(__dirname, 'views'));

// --- Servir estáticos ---
app.use('/css', express.static(path.join(__dirname, 'public', 'css')));
app.use('/js', express.static(path.join(__dirname, 'public', 'js')));
app.use('/img', express.static(path.join(__dirname, 'public', 'img')));
// app.use('/uploads', express.static(path.join(__dirname, '..', 'backend', 'uploads')));

// --- Rotas front-end (render HBS) ---
// Home
app.get('/', (req, res) => res.render('home', { title: 'CONECTHAR — Home' }));

// Colaborador: páginas renderizadas pelo front-end
// Colaborador: páginas renderizadas pelo front-end
app.get('/colaborador/dados', (req, res) => res.render('colaborador/dados', { title: 'Meus Dados', BACKEND_URL }));
app.get('/colaborador/holerites', (req, res) => {
  const fake = [
    { id: 1, mes_referencia: '2025-08', salario: 2500.00, arquivo_pdf: '/uploads/exemplo1.pdf' },
    { id: 2, mes_referencia: '2025-07', salario: 2400.00, arquivo_pdf: '/uploads/exemplo2.pdf' }
  ];
  res.render('colaborador/holerites', { holerites: fake, title: 'Holerites', BACKEND_URL });
});

// ROTA CORRIGIDA: renderiza corretamente o template 'colaborador/documentacaoCola'
app.get('/colaborador/documentacaoCola', (req, res) => {
  return res.render('colaborador/documentacaoCola', { title: 'Documentação', BACKEND_URL });
});

// Solicitações -> renderiza o template certo
app.get('/colaborador/solicitacoesCola', (req, res) => res.render('colaborador/solicitacoesCola', { title: 'Solicitações', BACKEND_URL }));

// Perfil -> usa o template 'perfil' (corrigido)
app.get('/colaborador/perfil', (req, res) => res.render('colaborador/perfil', { title: 'Meu Perfil', BACKEND_URL }));

// Ponto do colaborador (já correto)
app.get('/colaborador/pontoColaborador', (req, res) => {
  res.render('colaborador/pontoColaborador', { title: 'Meu Ponto', BACKEND_URL });
});

// Rotas do gestor (exemplos)
app.get('/gestor/documentacao', (req, res) => res.render('documentacao', { title: 'Documentação' }));
app.get('/gestor/folhadepagamento', (req, res) => res.render('folhadepagamento', { title: 'Folha de pagamento' }));
// Rota para folhapaga com ID - APENAS UMA VEZ
app.get('/gestor/folhapaga/:id', async (req, res) => {
  const id = parseInt(req.params.id, 10);
  console.log(' Redirecionando para backend...');
  
  // Redireciona para o backend que já tem a rota funcionando
  res.redirect(`http://localhost:3001/gestor/folhapaga/${id}`);
});

app.get('/gestor/beneficios', (req, res) => res.render('beneficios', { title: 'Gerenciar Benefícios' }));
app.get('/gestor/solicitacoes', (req, res) => res.render('solicitacoes', { title: 'Solicitações' }));
app.get('/gestor/gerenciamento', (req, res) => res.render('gerenciamento', { title: 'Gerenciamento' }));
app.get('/gestor/gerenciarPonto', (req, res) => res.render('gerenciarPonto', { title: 'Gerenciar Ponto' }));
// Rota para análise de dados
app.get('/gestor/analise', (req, res) => res.render('analise', { title: 'Análise de Dados' }));
// Start
if (require.main === module) {
  app.listen(FRONT_PORT, () => {
    console.log(`Frontend rodando em http://localhost:${FRONT_PORT}`);
    console.log(`Backend esperado em: ${BACKEND_URL}`);
  });
}

module.exports = app;
