// backend/routes/beneficioRoutes.js
const express = require('express');
const router = express.Router();
const beneficioController = require('../controllers/beneficioController');
const { verificarToken, autorizarTipoUsuario } = require('../middlewares/authMiddleware');

// Todas as rotas requerem autenticação de gestor
router.use(verificarToken, autorizarTipoUsuario(['gestor']));

// Criar benefício
router.post('/criar', beneficioController.criar);

// Listar benefícios
router.get('/listar', beneficioController.listar);

// Atualizar benefício
router.put('/:id', beneficioController.atualizar);

// Deletar benefício
router.delete('/:id', beneficioController.deletar);

// Beneficio por cargo

router.get('/cargo/:cargo_id', beneficioController.listarPorCargo);

module.exports = router;