const express = require('express');
const router = express.Router();
const bolsaController = require('../controllers/bolsaController');
const authMiddleware = require('../middleware/authMiddleware'); 

// ==================== BOLSAS ====================
router.get("/bolsas", authMiddleware('administrador'), bolsaController.listar);
router.post("/bolsas", authMiddleware('administrador'), bolsaController.criar);
router.put("/bolsas/:id", authMiddleware('administrador'), bolsaController.atualizar);
router.delete("/bolsas/:id", authMiddleware('administrador'), bolsaController.excluir);

module.exports = router;

