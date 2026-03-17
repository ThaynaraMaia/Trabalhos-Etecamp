const express = require('express');
const router = express.Router();

const authController = require('../controllers/authController');
const empresaController = require('../controllers/empresaController');
const { verificarToken } = require('../middlewares/authMiddleware');

// Login de usuário (gestor - requer CNPJ)
router.post('/login', authController.login);

// Login de colaborador (sem CNPJ obrigatório)
router.post('/colaborador/login', authController.loginColaborador);

// Registro de usuário comum
router.post('/register', authController.register);

// Registro de empresa (gestor inicial)
router.post('/registerEmpresa', empresaController.cadastrarEmpresa);

// Rota para obter dados do usuário logado
router.get('/me', verificarToken, authController.me);

// Logout
router.post('/logout', authController.logout);

module.exports = router;