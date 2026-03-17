const express = require('express');
const router = express.Router();
const authMiddleware = require('../middleware/authMiddleware');
const {
  getLembretesPersonalizados,
  criarLembretePessoal,
  removerLembretePessoal
} = require('../controllers/lembretePessoalController');

// lembrar usuários
router.use(authMiddleware());

router.get('/', getLembretesPersonalizados);
router.post('/', criarLembretePessoal);
router.delete('/:id', removerLembretePessoal);

module.exports = router;