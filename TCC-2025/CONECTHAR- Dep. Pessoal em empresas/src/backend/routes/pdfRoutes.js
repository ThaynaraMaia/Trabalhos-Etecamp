// backend/routes/pdfRoutes.js
const express = require('express');
const router = express.Router();
const pdfController = require('../controllers/pdfController');


 

router.post('/generate', pdfController.generatePdf);
router.post('/preview', pdfController.previewHtml);
router.get('/templates', pdfController.listTemplates);

module.exports = router;