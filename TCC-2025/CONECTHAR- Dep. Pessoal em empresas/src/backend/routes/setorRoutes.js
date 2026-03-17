// backend/routes/setorRoutes.js
const express = require("express");
const router = express.Router();
const setorController = require("../controllers/setorController");
const { verificarToken, autorizarTipoUsuario } = require('../middlewares/authMiddleware');
const uploadController = require("../controllers/uploadController");

// Todas as rotas requerem autenticação de gestor
router.use(verificarToken, autorizarTipoUsuario(['gestor']));

// Cadastro de setor
router.post("/register", setorController.register);

// Listagem de setores
router.get("/listar", setorController.listar);

// Atualizar setor
router.put("/:id", setorController.atualizar);

// Deletar setor
router.delete("/:id", setorController.deletar);

// Listar por setores na sidbar
router.get('/empresa', setorController.listarPorEmpresa);

router.get(
  '/setor/:setorId',
  verificarToken,
  uploadController.listarUploadsPorSetor
);
module.exports = router;