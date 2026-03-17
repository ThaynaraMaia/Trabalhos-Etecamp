// backend/routes/analysisRoutes.js
const express = require('express');
const router = express.Router();
const controller = require('../controllers/analysisController');

// Autenticação: se você usa middleware auth, adicione aqui: e.g. router.use(authMiddleware);

// Definitions
router.get('/definitions', controller.listDefinitions);
router.get('/definition/:id', controller.getDefinition);

// Runs
router.post('/run', controller.createRun);
router.get('/run/:id', controller.getRun);
router.post('/run/:id/status', controller.updateRunStatus);

// Exports
router.post('/export', controller.saveExport);

// Alerts
router.get('/alerts', controller.listAlerts);
router.post('/alerts', controller.createAlert);

module.exports = router;
