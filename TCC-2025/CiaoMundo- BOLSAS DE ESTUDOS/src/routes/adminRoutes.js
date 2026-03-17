const express = require('express');
const router = express.Router();
const adminController = require('../controllers/adminController');
const authMiddleware = require('../middleware/authMiddleware'); 

// ==================== USUÁRIOS ====================
router.get('/usuarios', authMiddleware('administrador'), adminController.listar);
router.put('/usuarios/:id/tipo', authMiddleware('administrador'), adminController.promover);
router.delete('/usuarios/:id', authMiddleware('administrador'), adminController.excluir);


module.exports = router;

