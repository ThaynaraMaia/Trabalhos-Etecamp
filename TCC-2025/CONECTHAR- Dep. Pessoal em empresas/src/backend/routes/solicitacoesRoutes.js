// backend/routes/solicitacoesRoutes.js
'use strict';

const express = require('express');
const router = express.Router();

const solicitacoesController = require('../controllers/solicitacoesController');
const { verificarToken, autorizarTipoUsuario } = require('../middlewares/authMiddleware');

// Upload middleware
const uploadMiddleware = solicitacoesController.uploadMiddleware;

// ==========================================
// ROTAS PÚBLICAS (SEM AUTENTICAÇÃO)
// ==========================================

/**
 * @route   GET /api/solicitacoes/anexo/:filename
 * @desc    Servir arquivo anexo (público)
 * @access  Público
 */
router.get('/anexo/:filename', solicitacoesController.serveAnexo);

// ==========================================
// MIDDLEWARE: AUTENTICAÇÃO OBRIGATÓRIA
// ==========================================
router.use(verificarToken);

// ==========================================
// ROTAS DO COLABORADOR (USUÁRIO AUTENTICADO)
// ==========================================

/**
 * @route   GET /api/solicitacoes/me
 * @desc    Listar minhas solicitações (COLABORADOR)
 * @access  Privado (Colaborador autenticado)
 */
router.get('/me', solicitacoesController.listarMe);

/**
 * @route   GET /api/solicitacoes/minhas
 * @desc    Alias para /me
 * @access  Privado (Colaborador autenticado)
 */
router.get('/minhas', solicitacoesController.listarMe);

/**
 * @route   GET /api/solicitacoes/usuario
 * @desc    Alias para /me
 * @access  Privado (Colaborador autenticado)
 */
router.get('/usuario', solicitacoesController.listarMe);

/**
 * @route   POST /api/solicitacoes
 * @desc    Criar nova solicitação (COLABORADOR)
 * @access  Privado (Colaborador autenticado)
 */
router.post('/', uploadMiddleware, solicitacoesController.criar);

/**
 * @route   POST /api/solicitacoes/:id/anexos
 * @desc    Adicionar anexos a uma solicitação
 * @access  Privado (Dono ou Gestor)
 */
router.post('/:id/anexos', uploadMiddleware, solicitacoesController.adicionarAnexos);

/**
 * @route   DELETE /api/solicitacoes/:id/anexos/:anexoId
 * @desc    Remover anexo de uma solicitação
 * @access  Privado (Dono ou Gestor)
 */
router.delete('/:id/anexos/:anexoId', solicitacoesController.removerAnexo);

// ==========================================
// ROTAS DO GESTOR
// ==========================================

/**
 * @route   GET /api/solicitacoes/gestor
 * @desc    🔥 NOVO: Listar solicitações dos colaboradores do GESTOR
 * @access  Privado (Gestor autenticado)
 */
router.get('/gestor', 
  autorizarTipoUsuario(['gestor']), 
  solicitacoesController.listarSolicitacoesGestor
);

/**
 * @route   GET /api/solicitacoes/gestor/todas
 * @desc    Alias para /gestor
 * @access  Privado (Gestor autenticado)
 */
router.get('/gestor/todas', 
  autorizarTipoUsuario(['gestor']), 
  solicitacoesController.listarSolicitacoesGestor
);

/**
 * @route   PUT /api/solicitacoes/:id/status
 * @desc    Atualizar status da solicitação (APENAS GESTOR)
 * @access  Privado (Gestor autenticado)
 */
router.put('/:id/status', 
  autorizarTipoUsuario(['gestor']), 
  solicitacoesController.atualizarStatus
);

/**
 * @route   PATCH /api/solicitacoes/:id/status
 * @desc    Alias PATCH para atualizar status
 * @access  Privado (Gestor autenticado)
 */
router.patch('/:id/status', 
  autorizarTipoUsuario(['gestor']), 
  solicitacoesController.atualizarStatus
);

// ==========================================
// ROTAS COMPARTILHADAS (GESTOR E COLABORADOR)
// ==========================================

/**
 * @route   GET /api/solicitacoes/:id
 * @desc    Buscar solicitação por ID
 * @access  Privado (Dono ou Gestor)
 */
router.get('/:id', solicitacoesController.getById);

/**
 * @route   PUT /api/solicitacoes/:id
 * @desc    Atualizar solicitação
 * @access  Privado (Dono ou Gestor)
 */
router.put('/:id', solicitacoesController.atualizar);

/**
 * @route   PATCH /api/solicitacoes/:id
 * @desc    Alias PATCH para atualizar
 * @access  Privado (Dono ou Gestor)
 */
router.patch('/:id', solicitacoesController.atualizar);

/**
 * @route   DELETE /api/solicitacoes/:id
 * @desc    Deletar solicitação
 * @access  Privado (Dono ou Gestor)
 */
router.delete('/:id', solicitacoesController.deletar);

/**
 * @route   GET /api/solicitacoes
 * @desc    Listar TODAS as solicitações (FALLBACK - usa listarSolicitacoesGestor se for gestor)
 * @access  Privado
 * @note    Esta rota DEVE vir por ÚLTIMO para não interferir nas outras rotas GET
 */
router.get('/', (req, res) => {
  // Se for gestor, redireciona para listarSolicitacoesGestor
  if (req.usuario && req.usuario.tipo_usuario === 'gestor') {
    return solicitacoesController.listarSolicitacoesGestor(req, res);
  }
  
  // Se for colaborador, lista apenas suas solicitações
  return solicitacoesController.listarMe(req, res);
});

module.exports = router;