const express = require('express');
const router = express.Router();
const multer = require('multer');
const path = require('path');
const userController = require('../controllers/userController');
const authMiddleware = require('../middleware/authMiddleware'); // seu middleware

// Configuração do multer
const storage = multer.diskStorage({
  destination: function (req, file, cb) {
    cb(null, 'public/uploads/'); // pasta onde as fotos serão salvas
  },
  filename: function (req, file, cb) {
    const ext = path.extname(file.originalname);
    cb(null, 'user_' + req.usuario.id_usuario + Date.now() + ext);
  }
});
const upload = multer({ storage });

// Rota para obter dados do perfil do usuário logado
router.get('/perfil', authMiddleware(), userController.getPerfil);

// Rota para atualizar dados do perfil
router.put('/atualizar', authMiddleware(), userController.atualizarPerfil);

// Rota para alterar foto do usuário (usar o nome correto)
router.post('/alterar-foto', authMiddleware(), upload.single('foto'), userController.alterarFotoUsuario);

// Rota para listar bolsas para o usuário
router.get('/bolsas-publicas', userController.listarBolsasPublicas);

module.exports = router;










