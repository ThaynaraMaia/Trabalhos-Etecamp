// backend/routes/index.js
const express = require('express');
const router = express.Router();

// Importar rotas
const authRoutes = require('./authRoutes');
const colaboradorRoutes = require('./colaboradorRoutes');
const uploadRoutes = require('./uploadRoutes');
const pontoRoutes = require('./pontoRoutes');
const setorRoutes = require('./setorRoutes');
const solicitacoesRoutes = require('./solicitacoesRoutes');
const folhaRoutes = require('./folhaRoutes');
const cargoRoutes = require('./cargoRoutes');
const beneficioRoutes = require('./beneficioRoutes');
const holeriteRoutes = require('./holeriteRoutes');
const gestorApiRoutes = require('./gestorApiRoutes'); 
const gestorRoutes = require('./gestorRoutes');
const analiseRoutes = require('./analise');
const notificacoesRoutes = require('./notificacoesRoutes');

// Montar rotas
router.use('/auth', authRoutes);
router.use('/colaborador', colaboradorRoutes);
router.use('/upload', uploadRoutes);
router.use('/ponto', pontoRoutes);
router.use('/setores', setorRoutes);
router.use('/solicitacoes', solicitacoesRoutes);
router.use('/realizarsolicitacoes', solicitacoesRoutes);
router.use('/folha', folhaRoutes);
router.use('/cargos', cargoRoutes);
router.use('/beneficios', beneficioRoutes);
router.use('/holerites', holeriteRoutes);


router.use('/gestor', gestorApiRoutes); 

router.use('/gestor', gestorRoutes);
router.use('/analise', analiseRoutes);
router.use('/notificacoes', notificacoesRoutes);

module.exports = router;