// backend/routes/gestorRoutes.js
const express = require('express');
const router = express.Router();

// Importação dos controladores
const authController = require('../controllers/authController');
const gestorController = require('../controllers/gestorController');
// const pontoController = require('../controllers/pontoController');
const uploadController = require('../controllers/uploadController');
const folhaPagamentoController = require('../controllers/folhaPagamentoController');
const colaboradorController = require('../controllers/colaboradorController');
const setorController = require('../controllers/setorController');
const cargoController = require('../controllers/cargoController');
const beneficioController = require('../controllers/beneficioController');
const holeriteController = require('../controllers/holeriteController');
const solicitacoesController = require('../controllers/solicitacoesController');
const pontoController = require('../controllers/pontoController')
// Middlewares
const { verificarToken, autorizarTipoUsuario } = require('../middlewares/authMiddleware');
const upload = require('../middlewares/uploadMiddleware');

// --- Rotas Públicas ---
router.post('/login', authController.login);

// --- Middleware de Autenticação para todas as rotas abaixo ---
router.use(verificarToken);
router.use(autorizarTipoUsuario(['gestor']));

// --- Rotas de Perfil do Gestor ---
router.get('/me', gestorController.me);
router.get('/perfil', gestorController.getProfile);
router.put('/perfil', gestorController.update);
router.delete('/perfil', gestorController.delete);

// --- Rotas de Upload ---
router.post('/upload', upload.single('arquivo'), uploadController.realizarUpload);
router.get('/uploads', uploadController.listarMeusUploads);
router.get('/uploads/cnpj', uploadController.listarUploadsPorCnpjGestor);
router.get('/uploads/setor/:setorId', uploadController.listarUploadsPorSetor);
router.get('/download/:id', uploadController.downloadArquivo);

// --- Rotas de Ponto ---
// --- Rotas de Ponto ---
// rotas novas/alias para compatibilidade com frontend antigo/atual
router.get('/ponto/ultimos', pontoController.getMeusRegistros);        // alias -> /ponto/meus-registros
router.get('/ponto/registros', pontoController.getRegistrosEmpresa);   // alias -> /ponto/registros-empresa

// rota original (mantém compatibilidade existente)
router.post('/ponto/registrar', pontoController.registrar);
router.get('/ponto/meus-registros', pontoController.getMeusRegistros);
router.get('/ponto/registros-empresa', pontoController.getRegistrosEmpresa);
router.get('/ponto/me', pontoController.getMe);

// --- Rotas de Colaboradores ---
router.post('/colaboradores', colaboradorController.register);
router.get('/colaboradores', colaboradorController.listar);
router.get('/colaboradores/:id', colaboradorController.getById);
router.put('/colaboradores/:id', colaboradorController.update);
router.delete('/colaboradores/:id', colaboradorController.excluir);
router.get('/colaboradores/:id/beneficios', colaboradorController.getBeneficios);
router.put('/colaboradores/:id/beneficios', colaboradorController.updateBeneficios);
router.get('/colaboradores/:id/beneficios/cargo', colaboradorController.listarBeneficiosPorCargo);

// --- Rotas de Setores ---
router.post('/setores', setorController.register);
router.get('/setores', setorController.listar);
router.get('/setores/empresa', setorController.listarPorEmpresa);
router.put('/setores/:id', setorController.atualizar);
router.delete('/setores/:id', setorController.deletar);

// --- Rotas de Cargos ---
router.post('/cargos', cargoController.criar);
router.get('/cargos', cargoController.listar);
router.get('/cargos/setor/:setor_id', cargoController.listarPorSetor);
router.delete('/cargos/:id', cargoController.deletar);

// --- Rotas de Benefícios ---
router.post('/beneficios', beneficioController.criar);
router.get('/beneficios', beneficioController.listar);
router.get('/beneficios/cargo/:cargo_id', beneficioController.listarPorCargo);
router.put('/beneficios/:id', beneficioController.atualizar);
router.delete('/beneficios/:id', beneficioController.deletar);

// --- Rotas de Folha de Pagamento ---
router.get('/folha-pagamento/stats', folhaPagamentoController.obterEstatisticasDashboard);
router.get('/folha-pagamento/calcular/:usuarioId', folhaPagamentoController.calcularFolhaUsuario);
router.post('/folha-pagamento/processar/:usuarioId', folhaPagamentoController.processarFolhaUsuario);
router.get('/folha-pagamento/empresa', folhaPagamentoController.calcularFolhaEmpresa);
router.post('/folha-pagamento/calcular', folhaPagamentoController.calcularComDadosUsuario);

// --- Rotas de Holerites ---
router.get('/holerites', holeriteController.listarPorEmpresa);
router.get('/holerites/colaborador/:id', holeriteController.listarPorColaborador);
router.get('/holerites/:id', holeriteController.buscarPorId);
router.post('/holerites', holeriteController.criar);
router.put('/holerites/:id', holeriteController.atualizar);
router.delete('/holerites/:id', holeriteController.excluir);
router.get('/holerites/download/:id', holeriteController.download);

// --- Rotas de Solicitações ---
router.get('/solicitacoes', solicitacoesController.listarTodos);
router.get('/solicitacoes/minhas', solicitacoesController.listarMe);
router.post('/solicitacoes', 
    solicitacoesController.uploadMiddleware, 
    solicitacoesController.criar
);
router.get('/solicitacoes/:id', solicitacoesController.getById);
router.put('/solicitacoes/:id/status', solicitacoesController.atualizarStatus);
router.put('/solicitacoes/:id', solicitacoesController.atualizar);
router.delete('/solicitacoes/:id', solicitacoesController.deletar);
router.post('/solicitacoes/:id/anexos',
    solicitacoesController.uploadMiddleware,
    solicitacoesController.adicionarAnexos
);
router.delete('/solicitacoes/:id/anexos/:anexoId', solicitacoesController.removerAnexo);

// --- Rota de Logout ---
router.post('/logout', authController.logout);

module.exports = router;