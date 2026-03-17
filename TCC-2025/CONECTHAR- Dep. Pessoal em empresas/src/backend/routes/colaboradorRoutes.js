// backend/routes/colaboradorRoutes.js - VERSÃO CORRIGIDA
const express = require('express');
const router = express.Router();
const colaboradorController = require('../controllers/colaboradorController');
const authController = require('../controllers/authController');
const Colaborador = require('../models/colaboradorModel');
const multer = require('multer');
const path = require('path');
const { verificarToken, autorizarTipoUsuario } = require('../middlewares/authMiddleware');
const db = require('../config/db');
const holeriteController = require('../controllers/holeriteController');

/* ==========================
   Multer (upload de foto)
   ========================== */
const storage = multer.diskStorage({
  destination: (req, file, cb) => cb(null, 'uploads/'),
  filename: (req, file, cb) => cb(null, Date.now() + path.extname(file.originalname))
});
const upload = multer({ storage });

/* ==========================
   ROTAS PÚBLICAS (sem token)
   ========================== */
router.post('/register', upload.single('foto'), colaboradorController.register);
router.post('/login', authController.login);

/* ==========================
   ROTAS ESTÁTICAS - VÊM PRIMEIRO
   (precisam vir ANTES de /:id)
   ========================== */

// Listar colaboradores
router.get('/listar', verificarToken, colaboradorController.listar);

// Próximo número de registro
router.get('/nextRegistro', async (req, res) => {
  try {
    const { empresa_id } = req.query;
    if (!empresa_id) return res.status(400).json({ message: 'empresa_id é obrigatório' });
    const proximo = await Colaborador.proximoRegistro(empresa_id);
    const numeroFormatado = 'C' + String(proximo).padStart(3, '0');
    return res.json({ proximoRegistro: numeroFormatado });
  } catch (err) { 
    console.error(err); 
    return res.status(500).json({ message: 'Erro interno' }); 
  }
});

// Listar setores
router.get('/setores', verificarToken, colaboradorController.listarSetores);

// Criar setor
router.post('/setores', verificarToken, colaboradorController.criarSetor);

// Listar benefícios por cargo
router.get('/beneficios/cargo', verificarToken, colaboradorController.listarBeneficiosPorCargo);

/* ==========================
   ROTAS DE VIEWS (páginas)
   ========================== */

// Página: ponto do colaborador
router.get('/pontoColaborador', verificarToken, autorizarTipoUsuario(['colaborador']), (req, res) => {
  return res.render('colaborador/pontoColaborador',  { title: 'Meu Ponto', usuario: req.usuario  });
});

// Página: dados do colaborador
router.get('/dados', verificarToken, autorizarTipoUsuario(['colaborador']), (req, res) => {
  return res.render('colaborador/dados', { title: 'Meus Dados', usuario: req.usuario });
});

// Compatibilidade: redirecionar /meusDados para /dados
router.get('/meusDados', (req, res) => res.redirect('/colaborador/dados'));

// Página: documentação
router.get('/documentacaoCola', verificarToken, autorizarTipoUsuario(['colaborador']), (req, res) => {
  return res.render('colaborador/documentacaoCola',  { title: 'Documentação', usuario: req.usuario });
});

// Página: solicitações
router.get('/solicitacoesCola', verificarToken, autorizarTipoUsuario(['colaborador']), (req, res) => {
  return res.render('colaborador/solicitacoesCola',  { title: 'Solicitações', usuario: req.usuario });
});


// Página: holerites
router.get('/holerites', verificarToken, autorizarTipoUsuario(['colaborador']), async (req, res) => {
    try {
        console.log('Carregando página de holerites...');
        const usuarioId = req.usuario.id;
        
        // Buscar dados completos do usuário
        const [usuarios] = await db.query(
            'SELECT id, nome, email, cpf, cargo FROM usuario WHERE id = ?',
            [usuarioId]
        );

        if (!usuarios || usuarios.length === 0) {
            return res.status(404).send('Usuário não encontrado');
        }

        const usuario = usuarios[0];
        console.log(' Dados do usuário carregados:', usuario);

        res.render('colaborador/holerites', {
            usuario: usuario,
            title: 'Meus Holerites',
            layout: 'main',

        });
    } catch (error) {
        console.error(' Erro ao carregar página de holerites:', error);
        res.status(500).send('Erro ao carregar holerites');
    }
});
router.get('/api/holerites/download/:id', verificarToken, holeriteController.download);


/* ==========================
   ROTAS DE API COM TOKEN
   ========================== */

// Perfil do colaborador
router.get('/perfil', verificarToken, colaboradorController.getProfile);

// Atualizar colaborador (com foto opcional)
router.put('/atualizar', verificarToken, upload.single('foto'), (req,res,next) => { 
  console.log('REQ.UPDATE', req.body); 
  next(); 
}, colaboradorController.update);

/* ==========================
   ROTAS DINÂMICAS - VÊM POR ÚLTIMO
   (/:id precisa vir DEPOIS de todas rotas estáticas)
   ========================== */

// Holerites do colaborador por ID
router.get('/colaborador/:id', 
  verificarToken,
  autorizarTipoUsuario(['colaborador', 'gestor']), 
  holeriteController.listarPorColaborador
);

// Benefícios do colaborador
router.get('/:id/beneficios', verificarToken, colaboradorController.getBeneficios);

// Buscar benefícios do usuário
router.get('/:id/beneficios', colaboradorController.buscarBeneficiosUsuario);

// Atualizar benefícios
router.put('/:id/beneficios', verificarToken, colaboradorController.updateBeneficios);

// Remover benefício específico
router.delete('/:id/beneficios/:beneficioId', verificarToken, async (req,res) => {
  try {
    const { beneficioId } = req.params;
    const UsuarioBeneficios = require('../models/usuariosBeneficiosModel');
    await UsuarioBeneficios.removeBeneficio(beneficioId);
    return res.json({ success: true, message: 'Benefício removido' });
  } catch (err) { 
    console.error(err); 
    return res.status(500).json({ success: false, message: 'Erro' }); 
  }
});

// Atualizar salário
router.put('/:id/salario', verificarToken, colaboradorController.updateSalario);

// Deletar colaborador
router.delete('/:id', verificarToken, colaboradorController.excluir);

// Buscar por ID específico - ÚLTIMA ROTA
router.get('/:id', 
  verificarToken,
  holeriteController.buscarPorId
);

module.exports = router;