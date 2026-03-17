const express = require('express');
const router = express.Router();
const authMiddleware = require('../middleware/authMiddleware'); 
const favoritoController = require('../controllers/favoritoController');

//favoritar bolsas

router.post('/:id_bolsa', authMiddleware(), favoritoController.toggleFavorito);
router.get('/', authMiddleware(), favoritoController.listarFavoritos);

module.exports = router;



