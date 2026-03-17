const express = require('express');
const router = express.Router();
const authMiddleware = require('../middleware/authMiddleware');
const avaliacaoController = require('../controllers/avaliacaoController');

//avaliar bolsas

router.get('/:id_bolsa', authMiddleware(), avaliacaoController.listar);
router.post('/:id_bolsa', authMiddleware(), avaliacaoController.avaliar);
router.delete('/:id_bolsa', authMiddleware(), avaliacaoController.remover);

module.exports = router;





