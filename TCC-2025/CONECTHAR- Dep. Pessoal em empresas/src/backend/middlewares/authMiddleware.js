// backend/middlewares/authMiddleware.js
const jwt = require('jsonwebtoken');
require('dotenv').config();

const JWT_SECRET = process.env.JWT_SECRET;

if (!JWT_SECRET) {
  console.warn('AVISO: JWT_SECRET não está definida no .env. Recomenda-se configurar para produção.');
}
let UsuarioModel = null;
try {
  UsuarioModel = require('../models/usuarioModel'); // ou '../models/userModel' — ajuste se necessário
} catch (e) {
  // não é fatal: continuamos usando apenas o payload do token
  UsuarioModel = null;
}

async function verificarToken(req, res, next) {
  try {
    // captura do header/cookie/query
    const authHeader = req.headers['authorization'] || req.headers['Authorization'] || '';
    let token = null;

    // header Authorization pode ser:
    // "Bearer <token>", "<token>", "Token token=\"<token>\"", "token=<token>"
    if (authHeader && typeof authHeader === 'string') {
      // formatos comuns
      const bearerMatch = authHeader.match(/Bearer\s+(.+)/i);
      if (bearerMatch) {
        token = bearerMatch[1].trim();
      } else {
        // token=xxx ou Token token="xxx"
        const tokenMatch = authHeader.match(/(?:token=|token\s+["']?)([A-Za-z0-9\-_\.=]+)/i);
        if (tokenMatch) token = tokenMatch[1];
        else {
          // fallback: se header só tem um valor, use-o
          const parts = authHeader.trim().split(/\s+/);
          if (parts.length === 1) token = parts[0];
        }
      }
    }

    // x-access-token header
    if (!token && req.headers['x-access-token']) {
      token = req.headers['x-access-token'];
    }

    // cookie HttpOnly 'token'
    if (!token && req.cookies && req.cookies.token) {
      token = req.cookies.token;
    }

    // query string ?token=...
    if (!token && req.query && req.query.token) {
      token = req.query.token;
    }

    // se ainda não houver token -> resposta apropriada
    const wantsHtml = req.accepts && req.accepts('html') && !req.xhr && !(req.headers['accept'] && req.headers['accept'].includes('application/json'));
    if (!token) {
      if (wantsHtml) return res.redirect('/');
      return res.status(401).json({ success: false, message: 'Token não fornecido' });
    }

    const JWT_SECRET = process.env.JWT_SECRET || global.JWT_SECRET; // tenta env ou global
    if (!JWT_SECRET) {
      console.error('JWT_SECRET não configurado no servidor');
      if (wantsHtml) return res.redirect('/');
      return res.status(500).json({ success: false, message: 'JWT_SECRET não configurado no servidor' });
    }

    // verificar token (síncrono para simplificar controle de erros)
    let decoded;
    try {
      decoded = jwt.verify(token, JWT_SECRET);
    } catch (err) {
      console.warn('Falha ao verificar JWT:', err && err.name ? `${err.name}: ${err.message}` : err);
      // se for token expirado -> 401
      if (wantsHtml) return res.redirect('/');
      return res.status(401).json({ success: false, message: 'Token inválido ou expirado' });
    }

    // Normalização do payload
    const normalized = Object.assign({}, decoded);

    // garantir campos básicos
    // tipo_usuario em lowercase (se existir)
    const tipoRaw = (decoded.tipo_usuario || decoded.tipo || decoded.role || '').toString().trim().toLowerCase();
    if (tipoRaw) normalized.tipo_usuario = tipoRaw;

    // montar roles array coerente
    const rolesSet = new Set();
    if (Array.isArray(decoded.roles)) decoded.roles.forEach(r => { if (r) rolesSet.add(String(r).toLowerCase()); });
    if (Array.isArray(decoded.papeis)) decoded.papeis.forEach(r => { if (r) rolesSet.add(String(r).toLowerCase()); });
    if (decoded.role) rolesSet.add(String(decoded.role).toLowerCase());
    if (decoded.tipo) rolesSet.add(String(decoded.tipo).toLowerCase());
    if (decoded.tipo_usuario) rolesSet.add(String(decoded.tipo_usuario).toLowerCase());
    normalized.roles = Array.from(rolesSet);

    // padronizar id
    const possibleId = normalized.id || normalized.sub || normalized.usuario_id || normalized.user_id || normalized.uid || null;
    normalized.id = (typeof possibleId === 'number' || (typeof possibleId === 'string' && /^\d+$/.test(possibleId))) ? Number(possibleId) : possibleId;

    // Se possível, completar dados do usuário via DB (empresa_id, nome, cnpj, setor, etc.)
    if (UsuarioModel && normalized.id) {
      try {
        // vários nomes possíveis para as funções do model
        let userFromDb = null;
        if (typeof UsuarioModel.findById === 'function') {
          userFromDb = await UsuarioModel.findById(normalized.id);
        } else if (typeof UsuarioModel.getById === 'function') {
          userFromDb = await UsuarioModel.getById(normalized.id);
        } else if (typeof UsuarioModel.buscarPorId === 'function') {
          userFromDb = await UsuarioModel.buscarPorId(normalized.id);
        } else if (typeof UsuarioModel.findOne === 'function') {
          userFromDb = await UsuarioModel.findOne({ id: normalized.id });
        } else if (typeof UsuarioModel.get === 'function') {
          userFromDb = await UsuarioModel.get(normalized.id);
        } else {
          // tentativa genérica: se model exporta uma query direta
          userFromDb = null;
        }

        // se encontrou usuário, mescla campos importantes
        if (userFromDb) {
          // alguns ORMs retornam array [rows], outras retornam objeto. normalize:
          const u = Array.isArray(userFromDb) && userFromDb.length ? userFromDb[0] : userFromDb;

          // mescla somente campos úteis, sem sobrescrever id
          if (u.empresa_id) normalized.empresa_id = u.empresa_id;
          if (u.empresaId) normalized.empresa_id = u.empresaId;
          if (u.nome) normalized.nome = normalized.nome || u.nome;
          if (u.name) normalized.nome = normalized.nome || u.name;
          if (u.cnpj) normalized.cnpj = normalized.cnpj || u.cnpj;
          if (u.tipo_usuario) normalized.tipo_usuario = normalized.tipo_usuario || u.tipo_usuario.toString().toLowerCase();
          if (u.tipo) normalized.tipo_usuario = normalized.tipo_usuario || u.tipo.toString().toLowerCase();
          // opcional: setor / cargo
          if (u.setor) normalized.setor = u.setor;
          if (u.cargo) normalized.cargo = u.cargo;
        }
      } catch (errUser) {
        // não falhar por causa do DB — apenas logar
        console.warn('Aviso: não foi possível buscar dados completos do usuário no DB:', errUser);
      }
    }

    // anexar no req e res.locals
    req.usuario = normalized;
    req.user = normalized;
    if (res && res.locals) {
      res.locals.usuario = normalized;
      res.locals.user = normalized;
    }

    // continuar fluxo
    return next();
  } catch (err) {
    console.error('Erro em verificarToken middleware:', err);
    const wantsHtml = req.accepts && req.accepts('html') && !req.xhr;
    if (wantsHtml) return res.redirect('/');
    return res.status(500).json({ success: false, message: 'Erro interno no middleware de autenticação' });
  }
}


function autorizarTipoUsuario(permitidos = []) {
  // permitidos pode ser ['gestor'] ou []
  const permitidosLower = (Array.isArray(permitidos) ? permitidos : []).map(p => String(p).toLowerCase());

  return (req, res, next) => {
    try {
      const usuarioObj = req.user || req.usuario;
      if (!usuarioObj) {
        if (req.accepts && req.accepts('html')) return res.redirect('/');
        return res.status(401).json({ success: false, message: 'Não autenticado' });
      }

      // coletar possíveis fontes de papel/role em várias chaves
      const candidates = new Set();

      // campos simples
      if (usuarioObj.tipo_usuario) candidates.add(String(usuarioObj.tipo_usuario).toLowerCase());
      if (usuarioObj.tipo) candidates.add(String(usuarioObj.tipo).toLowerCase());
      if (usuarioObj.role) candidates.add(String(usuarioObj.role).toLowerCase());
      if (usuarioObj.cargo) candidates.add(String(usuarioObj.cargo).toLowerCase());

      // arrays
      if (Array.isArray(usuarioObj.roles)) usuarioObj.roles.forEach(r => { if (r) candidates.add(String(r).toLowerCase()); });
      if (Array.isArray(usuarioObj.papeis)) usuarioObj.papeis.forEach(r => { if (r) candidates.add(String(r).toLowerCase()); });

      // se o token não contiver roles explícitos, aceitar 'gestor' em outras propriedades como fallback:
      // (ex.: usuarioObj.permissao = 'gestor' — opcional)
      if (usuarioObj.permissao) candidates.add(String(usuarioObj.permissao).toLowerCase());

      // padronizações comuns: tratar 'admin' e 'manager' como gestores também
      const extraAliases = ['admin', 'manager'];
      extraAliases.forEach(a => {
        if (candidates.has(a)) candidates.add('gestor');
      });

      // se permitidos vazios -> liberar
      if (permitidosLower.length === 0) return next();

      // se alguma interseção entre permitidos e candidates => ok
      const ok = Array.from(candidates).some(c => permitidosLower.includes(c));
      if (ok) return next();

      // fallback extra: se roles array existir e conter qualquer dos permitidos
      if (Array.isArray(usuarioObj.roles) && usuarioObj.roles.some(r => permitidosLower.includes(String(r).toLowerCase()))) {
        return next();
      }

      if (req.accepts && req.accepts('html')) return res.status(403).send('Acesso negado');
      return res.status(403).json({ success: false, message: 'Acesso negado: requer papel gestor' });
    } catch (err) {
      console.error('Erro em autorizarTipoUsuario:', err);
      if (req.accepts && req.accepts('html')) return res.status(500).send('Erro interno');
      return res.status(500).json({ success: false, message: 'Erro interno de autorização' });
    }
  };
}

module.exports = { verificarToken, autorizarTipoUsuario };
