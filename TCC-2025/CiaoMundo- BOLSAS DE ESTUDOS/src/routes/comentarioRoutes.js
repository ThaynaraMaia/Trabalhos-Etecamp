const express = require('express');
const router = express.Router();
const authMiddleware = require('../middleware/authMiddleware');
const comentarioController = require('../controllers/comentarioController');

//Comentario

router.get('/:id_bolsa', authMiddleware(), comentarioController.listar);
router.post('/:id_bolsa', authMiddleware(), comentarioController.adicionar);
router.delete('/:id_comentario', authMiddleware(), comentarioController.apagar);
router.post('/:id_comentario/curtir', authMiddleware(), comentarioController.curtir);
router.post('/:id_comentario/descurtir', authMiddleware(), comentarioController.descurtir);

module.exports = router;




