// backend/index.js
const express = require('express');
const cors = require('cors');
const path = require('path');
const fs = require('fs');
const cookieParser = require('cookie-parser');
require('dotenv').config();
const hbs = require('hbs');
const MulterLib = require('multer');
const db = require('./config/db');

// Importação de rotas
const routes = require('./routes'); // API router principal
const colaboradorRoutes = require('./routes/colaboradorRoutes');
const uploadRoutes = require('./routes/uploadRoutes');
const usuarioRoutes = require('./routes/usuarioRoutes');
const gestorApiRoutes = require('./routes/gestorApiRoutes');
const gestorViewsRoutes = require('./routes/gestorViewsRoutes');
const setorRoutes = require('./routes/setorRoutes');
const analysisRoutes = require('./routes/analysisRoutes');
const pdfRoutes = require('./routes/pdfRoutes');
const authRoutes = require('./routes/authRoutes');

const app = express();

// ==========================================
// CONFIGURAÇÕES GLOBAIS
// ==========================================
const PORT = process.env.PORT || 3001;
const UPLOADS_DIR = process.env.UPLOADS_DIR || path.join(__dirname, 'uploads');
const backendViewsPath = path.join(__dirname, 'views');
const frontendViewsPath = path.join(__dirname, '..', 'frontend', 'views');
const frontendPublicPath = path.join(__dirname, '..', 'frontend', 'public');

// Criar pasta uploads se não existir
if (!fs.existsSync(UPLOADS_DIR)) {
  fs.mkdirSync(UPLOADS_DIR, { recursive: true });
  console.log(' Pasta uploads criada:', UPLOADS_DIR);
}

// ==========================================
// SECURITY & CORS
// ==========================================
try {
  const helmet = require('helmet');

  app.use(helmet({
    contentSecurityPolicy: {
      directives: {
        defaultSrc: ["'self'"],
        // Aceita scripts externos + permite inline (DEV only)
        scriptSrc: [
          "'self'",
          "'unsafe-inline'",
          "https://cdn.jsdelivr.net",
          "https://cdnjs.cloudflare.com",
          "https://unpkg.com"
        ],
        scriptSrcElem: [
          "'self'",
          "'unsafe-inline'",
          "https://cdn.jsdelivr.net",
          "https://cdnjs.cloudflare.com",
          "https://unpkg.com"
        ],

        // Styles: aceita links externos e inline (DEV only)
        styleSrc: [
          "'self'",
          "'unsafe-inline'",
          "https://cdn.jsdelivr.net",
          "https://cdnjs.cloudflare.com",
          "https://fonts.googleapis.com"
        ],
        styleSrcElem: [
          "'self'",
          "'unsafe-inline'",
          "https://cdn.jsdelivr.net",
          "https://cdnjs.cloudflare.com",
          "https://fonts.googleapis.com"
        ],

        fontSrc: [
          "'self'",
          "https://fonts.gstatic.com",
          "https://cdn.jsdelivr.net",
          "https://cdnjs.cloudflare.com"
        ],

        // imagens
        imgSrc: [
          "'self'",
          "data:",
          "blob:",
          "http://localhost:3001",
          process.env.BACKEND_URL || 'http://localhost:3001'
        ],

        // permite conexões XHR / fetch / sourcemaps para cdn hosts
        connectSrc: [
          "'self'",
          "http://localhost:3000",
          "http://localhost:3001",
          "https://cdnjs.cloudflare.com",
          "https://cdn.jsdelivr.net"
        ],

        workerSrc: ["'self'", "blob:"],
        objectSrc: ["'none'"],
        frameAncestors: ["'self'"],
        manifestSrc: ["'self'"]
      }
    }
  }));

  console.log(' helmet ativado (CSP dev)');
} catch (err) {
  console.warn(' helmet não encontrado — rodando sem ele (dev).');
}


app.use(cors({
  origin: ['http://localhost:3000', 'http://127.0.0.1:3000'],
  methods: ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'],
  allowedHeaders: ['Content-Type', 'Authorization', 'X-Requested-With'],
  credentials: true,
  preflightContinue: false,
  optionsSuccessStatus: 204
}));

// ==========================================
// MIDDLEWARES DE PARSING
// ==========================================
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(cookieParser());

// ==========================================
// MIDDLEWARE GLOBAL - BACKEND_URL
// ==========================================
app.use((req, res, next) => {
  res.locals.BACKEND_URL = process.env.BACKEND_URL || 'http://localhost:3001';
  res.locals.currentPath = req.path;
  next();
});

// ==========================================
// CORS HEADERS ADICIONAIS
// ==========================================
app.use((req, res, next) => {
  res.header('Access-Control-Allow-Origin', 'http://localhost:3000');
  res.header('Access-Control-Allow-Credentials', 'true');
  res.header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
  res.header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
  
  if (req.method === 'OPTIONS') {
    return res.status(200).end();
  }
  next();
});

// ==========================================
// STATIC FILES - CRÍTICO: ANTES DAS ROTAS!
// ==========================================

// 1. Uploads do backend (fotos de usuários)
app.use('/uploads', express.static(UPLOADS_DIR, {
  dotfiles: 'ignore',
  index: false,
  maxAge: '1d',
  setHeaders: (res, filePath) => {
    res.setHeader('Cross-Origin-Resource-Policy', 'cross-origin');
    res.setHeader('Access-Control-Allow-Origin', '*');
  }
}));
console.log(' Servindo uploads de:', UPLOADS_DIR);

// 2. Imagens do frontend (fundofoda.png, etc)
app.use('/img', express.static(path.join(frontendPublicPath, 'img'), {
  dotfiles: 'ignore',
  index: false,
  maxAge: '1d',
  setHeaders: (res, filePath) => {
    res.setHeader('Cross-Origin-Resource-Policy', 'cross-origin');
    res.setHeader('Access-Control-Allow-Origin', '*');
  }
}));
console.log(' Servindo imagens de:', path.join(frontendPublicPath, 'img'));

// 3. CSS do frontend
app.use('/css', express.static(path.join(frontendPublicPath, 'css'), {
  maxAge: '1d',
  setHeaders: (res) => {
    res.setHeader('Cross-Origin-Resource-Policy', 'cross-origin');
    res.setHeader('Access-Control-Allow-Origin', '*');
  }
}));
console.log(' Servindo CSS de:', path.join(frontendPublicPath, 'css'));

// 4. JavaScript do frontend
app.use('/js', express.static(path.join(frontendPublicPath, 'js'), {
  maxAge: '1d',
  setHeaders: (res) => {
    res.setHeader('Cross-Origin-Resource-Policy', 'cross-origin');
    res.setHeader('Access-Control-Allow-Origin', '*');
  }
}));
console.log(' Servindo JS de:', path.join(frontendPublicPath, 'js'));

// ==========================================
// VIEWS CONFIGURATION (HANDLEBARS)
// ==========================================
app.set('views', [backendViewsPath, frontendViewsPath]);
app.engine('hbs', hbs.__express);
app.engine('handlebars', hbs.__express);
app.set('view engine', 'handlebars');
console.log('✔ View engines registradas: .handlebars, .hbs (padrão: .handlebars)');

// ==========================================
// PARTIALS REGISTRATION (RECURSIVO)
// ==========================================
function registerPartialsRecursively(dirPath) {
  const exts = ['.hbs', '.handlebars'];
  if (!fs.existsSync(dirPath)) return;
  
  const walk = (dir) => {
    const items = fs.readdirSync(dir);
    items.forEach(item => {
      const full = path.join(dir, item);
      const stat = fs.statSync(full);
      if (stat.isDirectory()) {
        walk(full);
        return;
      }
      const ext = path.extname(item).toLowerCase();
      if (!exts.includes(ext)) return;
      const name = path.basename(item, ext);
      try {
        const content = fs.readFileSync(full, 'utf8');
        hbs.registerPartial(name, content);
        console.log(` Partial registrado: "${name}"`);
      } catch (err) {
        console.warn(` Falha ao registrar partial ${name}:`, err.message);
      }
    });
  };
  walk(dirPath);
}

registerPartialsRecursively(path.join(backendViewsPath, 'partials'));
registerPartialsRecursively(path.join(frontendViewsPath, 'partials'));

// ==========================================
// HANDLEBARS HELPERS
// ==========================================

// Helper: ifActive
hbs.registerHelper('ifActive', function () {
  const args = Array.from(arguments);
  const options = args.pop();
  let match, currentPath;

  if (args.length === 2) {
    currentPath = args[0] || (options.data?.root?.currentPath) || '';
    match = args[1];
  } else if (args.length === 1) {
    currentPath = (options.data?.root?.currentPath) || this.currentPath || '';
    match = args[0];
  } else {
    currentPath = (options.data?.root?.currentPath) || this.currentPath || '';
    match = '';
  }

  if (!match) return options.inverse(this);

  let isRegex = false;
  let regex = null;
  if (typeof match === 'string') {
    const maybe = match.trim();
    if ((maybe.startsWith('/') && maybe.endsWith('/') && maybe.length > 1) || 
        maybe.startsWith('^') || /[.*+?()[\]{}|\\]/.test(maybe)) {
      try {
        const body = (maybe.startsWith('/') && maybe.endsWith('/')) ? maybe.slice(1, -1) : maybe;
        regex = new RegExp(body);
        isRegex = true;
      } catch (e) {
        isRegex = false;
        regex = null;
      }
    }
  } else if (match instanceof RegExp) {
    isRegex = true;
    regex = match;
  }

  let matched = false;
  if (isRegex && regex) {
    matched = regex.test(currentPath);
  } else {
    matched = String(currentPath || '').startsWith(String(match));
  }

  return matched ? options.fn(this) : options.inverse(this);
});

// Helper: activeClass
hbs.registerHelper('activeClass', function () {
  const args = Array.from(arguments);
  const options = args.pop();
  let match, currentPath;

  if (args.length === 2) {
    currentPath = args[0] || (options.data?.root?.currentPath) || '';
    match = args[1];
  } else if (args.length === 1) {
    currentPath = (options.data?.root?.currentPath) || this.currentPath || '';
    match = args[0];
  } else {
    return '';
  }

  let isRegex = false;
  let regex = null;
  if (typeof match === 'string') {
    const maybe = match.trim();
    if ((maybe.startsWith('/') && maybe.endsWith('/') && maybe.length > 1) || 
        maybe.startsWith('^') || /[.*+?()[\]{}|\\]/.test(maybe)) {
      try {
        const body = (maybe.startsWith('/') && maybe.endsWith('/')) ? maybe.slice(1, -1) : maybe;
        regex = new RegExp(body);
        isRegex = true;
      } catch (e) {
        isRegex = false;
        regex = null;
      }
    }
  } else if (match instanceof RegExp) {
    isRegex = true;
    regex = match;
  }

  let matched = false;
  if (isRegex && regex) {
    matched = regex.test(currentPath);
  } else {
    matched = String(currentPath || '').startsWith(String(match));
  }

  return matched ? 'active' : '';
});

// Helper: formatDate
hbs.registerHelper('formatDate', function (dateInput, format) {
  try {
    const d = new Date(dateInput);
    if (isNaN(d)) return '';
    const dd = String(d.getDate()).padStart(2, '0');
    const mm = String(d.getMonth() + 1).padStart(2, '0');
    const yy = d.getFullYear();
    const hh = String(d.getHours()).padStart(2, '0');
    const min = String(d.getMinutes()).padStart(2, '0');
    return `${dd}/${mm}/${yy} ${hh}:${min}`;
  } catch (e) {
    return String(dateInput);
  }
});

// Helper: json
hbs.registerHelper('json', function (context) {
  return JSON.stringify(context);
});

// ==========================================
// SAFE RENDER WRAPPER (TRY MULTIPLE EXTENSIONS)
// ==========================================
(function patchResponseRender() {
  const originalRender = app.response.render;
  app.response.render = function (view) {
    let args = Array.prototype.slice.call(arguments, 1);
    let options = {};
    let callback = null;
    
    if (args.length === 1) {
      if (typeof args[0] === 'function') callback = args[0];
      else options = args[0] || {};
    } else if (args.length >= 2) {
      options = args[0] || {};
      callback = args[1];
    }

    const tryExts = ['handlebars', 'hbs'];
    let idx = 0;
    const self = this;

    const tryNext = () => {
      if (idx >= tryExts.length) {
        return originalRender.call(self, view, options, callback);
      }
      const ext = tryExts[idx++];
      const tryView = `${view}.${ext}`;
      return originalRender.call(self, tryView, options, (err, html) => {
        if (!err) {
          if (typeof callback === 'function') return callback(null, html);
          return self.send(html);
        }
        const msg = String(err?.message || err).toLowerCase();
        const isNotFound = msg.includes('failed to lookup view') || 
                          msg.includes('enoent') || 
                          msg.includes('not found');
        if (isNotFound) {
          return tryNext();
        }
        if (typeof callback === 'function') return callback(err);
        return self.req ? self.req.next(err) : self.next(err);
      });
    };

    return tryNext();
  };
})();

// ==========================================
// ROTAS DE API
// ==========================================
// ROTAS DE API
app.use('/api/setores', setorRoutes);
app.use('/api/usuarios', usuarioRoutes);
app.use('/api/usuario', usuarioRoutes);
app.use('/api/upload', uploadRoutes);
app.use('/api/analysis', analysisRoutes);
app.use('/api/pdf', pdfRoutes);
app.use('/api/colaborador', colaboradorRoutes);


app.use('/api/gestor', gestorApiRoutes);
app.use('/auth', authRoutes);

app.use('/api', routes);
// ==========================================
// ROTAS DE VIEWS DO GESTOR
// ==========================================
app.use('/gestor', gestorViewsRoutes);

// Rota específica: Análise de dados
app.get('/gestor/analise', (req, res) => {
  res.render('analise', {
    title: 'Análise de Dados',
    BACKEND_URL: process.env.BACKEND_URL || 'http://localhost:3001'
  });
});

// Rota específica: Folha de pagamento por ID
app.get('/gestor/folhapaga/:id', async (req, res) => {
  const id = parseInt(req.params.id, 10);
  if (!id) return res.status(400).send('ID inválido');

  try {
    const result = await minhaQueryHoleritePorId(id);
    if (!result || !result.usuario) {
      return res.status(404).send('Usuário não encontrado');
    }

    console.log(' Render holerite id=', id, 'usuario=', result.usuario.nome);

    return res.render('gestor/folhapaga', {
      title: 'Holerite',
      usuario: result.usuario,
      arquivos: result.uploads
    });
  } catch (err) {
    console.error(' Erro /gestor/folhapaga/:id ->', err);
    return res.status(500).send('Erro interno');
  }
});

// --- Início da alteração da rota /colaborador/holerites ---
app.get('/colaborador/holerites', async (req, res) => {
  let usuarioData = req.usuario || {};
  let empresaData = {};
  
  // Tenta obter o ID do usuário (do middleware ou do cookie token)
  let userId = usuarioData.id || usuarioData.userId;
  
  // Se não veio pelo middleware, tenta ler do cookie manualmente
  if (!userId && req.cookies && req.cookies.token) {
    try {
      // Tenta importar jwt caso não esteja no topo, ou usa require se já tiver
      const jwt = require('jsonwebtoken'); 
      const secret = process.env.JWT_SECRET || 'seu_segredo_seguro'; // Ajuste conforme seu .env
      const decoded = jwt.verify(req.cookies.token, secret);
      userId = decoded.id || decoded.userId;
    } catch (e) {
      console.log('Aviso: Não foi possível decodificar token do cookie na rota de holerites:', e.message);
    }
  }

  if (userId) {
    try {
      // Busca dados completos: Usuario + Nome da Empresa
      // [cite: 72] Usa a conexão 'db' que já está importada no index.js
      const [rows] = await db.execute(`
        SELECT u.id, u.nome, u.cargo, u.cpf, u.empresa_id,
               e.nome_empresa, e.cnpj
        FROM usuario u
        LEFT JOIN empresa e ON u.empresa_id = e.id
        WHERE u.id = ?
      `, [userId]);

      if (rows && rows.length > 0) {
        const fullUser = rows[0];
        
        // Prepara o objeto usuario com dados do banco (que tem CPF e Cargo)
        usuarioData = {
            ...usuarioData,
            id: fullUser.id,
            nome: fullUser.nome,
            cargo: fullUser.cargo,
            cpf: fullUser.cpf
        };

        // Prepara o objeto empresa com dados do JOIN
        empresaData = {
            id: fullUser.empresa_id,
            nome_empresa: fullUser.nome_empresa,
            cnpj: fullUser.cnpj
        };
      }
    } catch (err) {
      console.error('Erro ao buscar dados completos do colaborador:', err);
    }
  }

  res.render('holerites', {
    title: 'Meus Holerites',
    BACKEND_URL: process.env.BACKEND_URL || 'http://localhost:3001',
    usuario: usuarioData, // Agora contém CPF e Cargo do banco
    empresa: empresaData  // Agora contém Nome e CNPJ do banco
  });
});
// --- Fim da alteração ---
// ==========================================
// ROTA DE DOCUMENTAÇÃO
// ==========================================
app.get('/documentacao', (req, res) => {
  const categoria = req.query.categoria || null;
  res.render('documentacao', { categoria });
});

// ==========================================
// HEALTH CHECK
// ==========================================
app.get('/health', (req, res) => res.json({ success: true, status: 'ok' }));

// ==========================================
// ERROR HANDLER - MULTER
// ==========================================
let MULTER_MAX_BYTES = 15 * 1024 * 1024;
try {
  const uploadMw = require('./middlewares/uploadMiddleware');
  if (uploadMw?.MAX_FILE_SIZE_BYTES) {
    MULTER_MAX_BYTES = uploadMw.MAX_FILE_SIZE_BYTES;
  }
} catch (e) {
  // ignore
}

app.use((err, req, res, next) => {
  if (err instanceof MulterLib.MulterError) {
    // Remove arquivo parcial
    try {
      if (req.file?.path && fs.existsSync(req.file.path)) {
        fs.unlinkSync(req.file.path);
        console.log('Arquivo parcial removido:', req.file.path);
      }
    } catch (e) {
      console.warn(' Falha ao remover arquivo parcial:', e.message);
    }

    let message = err.message || 'Erro no upload';
    if (err.code === 'LIMIT_FILE_SIZE') {
      message = `Arquivo muito grande. Tamanho máximo: ${Math.round(MULTER_MAX_BYTES / 1024 / 1024)} MB.`;
      return res.status(413).json({ erro: message, code: err.code });
    }
    if (err.code === 'LIMIT_UNEXPECTED_FILE') {
      message = err.message || 'Tipo de arquivo não suportado.';
      return res.status(400).json({ erro: message, code: err.code });
    }
    return res.status(400).json({ erro: message, code: err.code });
  }
  return next(err);
});

// ==========================================
// FUNÇÕES AUXILIARES
// ==========================================

async function minhaQueryHoleritePorId(id) {
  if (!id) throw new Error('ID inválido');
  
  if (typeof db !== 'undefined' && typeof db.execute === 'function') {
    const [rows] = await db.execute('SELECT * FROM usuario WHERE id = ?', [id]);
    const [uploads] = await db.execute('SELECT * FROM realizarupload WHERE usuario_id = ?', [id]);
    return { 
      usuario: rows?.[0] || null, 
      uploads: uploads || [] 
    };
  }
  
  throw new Error('Cliente DB não disponível');
}

function listarTemplatesDisponiveis() {
  const exts = ['.handlebars', '.hbs'];
  const dirs = [backendViewsPath, frontendViewsPath];
  console.log('--- TEMPLATES DISPONÍVEIS ---');
  dirs.forEach(dir => {
    try {
      if (!fs.existsSync(dir)) {
        console.log(`(não existe) ${dir}`);
        return;
      }
      const walk = (d, prefix = '') => {
        const items = fs.readdirSync(d);
        items.forEach(it => {
          const full = path.join(d, it);
          const stat = fs.statSync(full);
          if (stat.isDirectory()) return walk(full, path.join(prefix, it));
          const ext = path.extname(it).toLowerCase();
          if (exts.includes(ext)) {
            console.log(path.join(prefix, it));
          }
        });
      };
      walk(dir);
    } catch (e) {
      console.warn(' Erro ao listar templates em', dir, e.message);
    }
  });
  console.log('-----------------------------');
}

function listarRotas(appInstance) {
  console.log('--- ROTAS REGISTRADAS (Express) ---');
  if (!appInstance?._router) {
    console.log('Nenhuma rota encontrada');
    return;
  }
  appInstance._router.stack.forEach((m) => {
    if (m.route?.path) {
      const methods = Object.keys(m.route.methods).join(',').toUpperCase();
      console.log(`${methods}  ${m.route.path}`);
    } else if (m.name === 'router' && m.handle?.stack) {
      const mountPath = (m.regexp?.fast_slash) ? '/' : (m.regexp?.toString()) || '<mount>';
      m.handle.stack.forEach((r) => {
        if (r.route?.path) {
          const methods = Object.keys(r.route.methods).join(',').toUpperCase();
          console.log(`${methods}  MOUNTED_AT: ${mountPath}  -->  ${r.route.path}`);
        }
      });
    }
  });
  console.log('-----------------------------------');
}

// ==========================================
// START SERVER
// ==========================================
async function startServer() {
  try {
    await db.query('SELECT 1');
    console.log('Conexão com o banco de dados OK.');

    const server = app.listen(PORT, () => {
      console.log(` Servidor backend rodando em http://localhost:${PORT}`);
      console.log(` API base: /api`);
      console.log(` Frontend public: ${frontendPublicPath}`);
      console.log(` Uploads: ${UPLOADS_DIR}`);
      
      // Debug info
      listarTemplatesDisponiveis();
      listarRotas(app);
    });

    return server;
  } catch (err) {
    console.error(' Falha ao conectar ao banco:', err);
    process.exit(1);
  }
}

startServer();

module.exports = app;