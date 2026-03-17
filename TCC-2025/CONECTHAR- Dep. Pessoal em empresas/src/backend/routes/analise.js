// backend/routes/analise.js
const express = require('express');
const router = express.Router();

// Se você já criou controller unificado use-o; caso contrário, coloque handlers simples por enquanto.
// Exemplo usando controller (ajuste o caminho caso controller esteja em outro local)
let analysisController;
try {
  analysisController = require('../controllers/analysisController');
} catch (err) {
  // fallback: handlers simples que retornam 501 até você colocar o controller
  analysisController = {
    listDefinitions: (req, res) => res.status(501).json({ ok: false, msg: 'controller não implementado' }),
    getDefinition: (req, res) => res.status(501).json({ ok: false, msg: 'controller não implementado' }),
    createRun: (req, res) => res.status(501).json({ ok: false, msg: 'controller não implementado' }),
    getRun: (req, res) => res.status(501).json({ ok: false, msg: 'controller não implementado' }),
    updateRunStatus: (req, res) => res.status(501).json({ ok: false, msg: 'controller não implementado' }),
    saveExport: (req, res) => res.status(501).json({ ok: false, msg: 'controller não implementado' }),
    listAlerts: (req, res) => res.status(501).json({ ok: false, msg: 'controller não implementado' }),
    createAlert: (req, res) => res.status(501).json({ ok: false, msg: 'controller não implementado' })
  };
}

// Definitions
router.get('/definitions', analysisController.listDefinitions);
router.get('/definition/:id', analysisController.getDefinition);

// Runs
router.post('/run', analysisController.createRun);
router.get('/run/:id', analysisController.getRun);
router.post('/run/:id/status', analysisController.updateRunStatus);

// Exports
router.post('/export', analysisController.saveExport);

// Alerts
router.get('/alerts', analysisController.listAlerts);
router.post('/alerts', analysisController.createAlert);

module.exports = router;
