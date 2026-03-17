// backend/controllers/analysisController.js
const analyticsModel = require('../models/analyticsModel');

/**
 * Controller unificado para endpoints /api/analysis
 * Foco inicial: abrir popups / listar reports / gerenciar runs / exports.
 */

const analysisController = {
  // GET /api/analysis/definitions?empresa_id=1
  async listDefinitions(req, res) {
    try {
      const empresaId = req.query.empresa_id || req.user?.empresa_id || 1;
      const defs = await analyticsModel.listReportDefinitions(empresaId);
      res.json({ ok: true, definitions: defs });
    } catch (err) {
      console.error('listDefinitions error', err);
      res.status(500).json({ ok: false, error: 'Erro ao listar definições' });
    }
  },

  // GET /api/analysis/definition/:id
  async getDefinition(req, res) {
    try {
      const def = await analyticsModel.getReportDefinitionById(req.params.id);
      if (!def) return res.status(404).json({ ok: false, error: 'Não encontrado' });
      res.json({ ok: true, definition: def });
    } catch (err) {
      console.error('getDefinition', err);
      res.status(500).json({ ok: false, error: 'Erro interno' });
    }
  },

  // POST /api/analysis/run  { report_definition_id, parametros }
  async createRun(req, res) {
    try {
      const { report_definition_id, parametros } = req.body;
      if (!report_definition_id) return res.status(400).json({ ok: false, error: 'report_definition_id obrigatório' });
      const runId = await analyticsModel.createReportRun(report_definition_id, parametros || {});
      res.json({ ok: true, runId });
    } catch (err) {
      console.error('createRun', err);
      res.status(500).json({ ok: false, error: 'Erro criando run' });
    }
  },

  // GET /api/analysis/run/:id
  async getRun(req, res) {
    try {
      const run = await analyticsModel.getReportRun(req.params.id);
      if (!run) return res.status(404).json({ ok: false, error: 'Run não encontrado' });
      res.json({ ok: true, run });
    } catch (err) {
      console.error('getRun', err);
      res.status(500).json({ ok: false, error: 'Erro ao buscar run' });
    }
  },

  // POST /api/analysis/run/:id/status  { status, resultado_data?, error_text? }
  async updateRunStatus(req, res) {
    try {
      const runId = req.params.id;
      const { status, resultado_data, error_text } = req.body;
      if (!status) return res.status(400).json({ ok: false, error: 'status obrigatório' });
      await analyticsModel.updateReportRunStatus(runId, status, { resultado_data, error_text });
      res.json({ ok: true });
    } catch (err) {
      console.error('updateRunStatus', err);
      res.status(500).json({ ok: false, error: 'Erro atualizando status' });
    }
  },

  // POST /api/analysis/export (salva meta info de export)
  async saveExport(req, res) {
    try {
      const { runId, tipo_export, caminho, tamanho } = req.body;
      const empresaId = req.body.empresa_id || req.user?.empresa_id || 1;
      const usuarioId = req.user?.id || null;
      const id = await analyticsModel.saveExport(runId || null, empresaId, usuarioId, tipo_export || 'pdf', caminho || '', tamanho || null);
      res.json({ ok: true, exportId: id });
    } catch (err) {
      console.error('saveExport', err);
      res.status(500).json({ ok: false, error: 'Erro salvando export' });
    }
  },

  // alerts: list and create
  async listAlerts(req, res) {
    try {
      const empresaId = req.query.empresa_id || req.user?.empresa_id || 1;
      const onlyUnread = req.query.unread === '1';
      const rows = await analyticsModel.listAlerts(empresaId, onlyUnread);
      res.json({ ok: true, alerts: rows });
    } catch (err) {
      console.error('listAlerts', err);
      res.status(500).json({ ok: false, error: 'Erro listando alerts' });
    }
  },

  async createAlert(req, res) {
    try {
      const empresaId = req.body.empresa_id || req.user?.empresa_id || 1;
      const id = await analyticsModel.createAlert(empresaId, req.body.key, req.body.nivel || 'warning', req.body.referencia_id || null, req.body.contexto || {});
      res.json({ ok: true, id });
    } catch (err) {
      console.error('createAlert', err);
      res.status(500).json({ ok: false, error: 'Erro criando alerta' });
    }
  }
};

module.exports = analysisController;
