// backend/routes/cargoRoutes.js
const express = require('express');
const router = express.Router();
const cargoController = require('../controllers/cargoController');
const { verificarToken, autorizarTipoUsuario } = require('../middlewares/authMiddleware');

// Todas as rotas requerem autenticação de gestor
router.use(verificarToken, autorizarTipoUsuario(['gestor']));

// Criar cargo
router.post('/criar', cargoController.criar);

// Listar todos os cargos da empresa
router.get('/listar', cargoController.listar);

// Listar cargos por setor
router.get('/setor/:setor_id', cargoController.listarPorSetor);

// Deletar cargo
router.delete('/:id', cargoController.deletar);

module.exports = router;