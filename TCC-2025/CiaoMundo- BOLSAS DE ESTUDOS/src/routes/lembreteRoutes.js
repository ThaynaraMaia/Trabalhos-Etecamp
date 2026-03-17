const express = require('express');
const router = express.Router();
const { getLembretes } = require('../controllers/lembreteController');

router.get('/', getLembretes);

module.exports = router;


