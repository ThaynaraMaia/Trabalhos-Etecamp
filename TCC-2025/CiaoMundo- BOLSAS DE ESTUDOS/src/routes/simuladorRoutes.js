const express = require("express");
const router = express.Router();
const simuladorController = require("../controllers/simuladorController");
const authMiddleware = require("../middleware/authMiddleware");

// Rotas do simulador
router.post("/simulador/calcular", authMiddleware("usuario"), simuladorController.calcular);
router.get("/simulador/historico", authMiddleware("usuario"), simuladorController.historico);

// Rotas de exclusão
router.delete("/simulador/:id", authMiddleware("usuario"), simuladorController.excluir);
router.delete("/simulador", authMiddleware("usuario"), simuladorController.excluirTodos);

module.exports = router;
