// backend/models/analyticsModel.js
const db = require('../config/db');

/**
 * Modelo unificado para operações relacionadas a analytics / reports.
 * Cada função usa prepared statements e retorna Promises.
 */

const analyticsModel = {
  // Report definitions (analytics_report_definitions)
  async listReportDefinitions(empresaId) {
    const q = `SELECT * FROM analytics_report_definitions WHERE empresa_id = ? AND ativo = 1 ORDER BY nome`;
    const [rows] = await db.query(q, [empresaId]);
    return rows;
  },

  async getReportDefinitionById(id) {
    const q = `SELECT * FROM analytics_report_definitions WHERE id = ? LIMIT 1`;
    const [rows] = await db.query(q, [id]);
    return rows[0] || null;
  },

  async createReportDefinition(payload) {
    const q = `INSERT INTO analytics_report_definitions 
      (empresa_id,nome,slug,tipo,descricao,parametros_default,owner_usuario_id,publico,ativo)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`;
    const params = [
      payload.empresa_id,
      payload.nome,
      payload.slug,
      payload.tipo || 'custom',
      payload.descricao || null,
      JSON.stringify(payload.parametros_default || {}),
      payload.owner_usuario_id || null,
      payload.publico ? 1 : 0,
      payload.ativo !== undefined ? (payload.ativo ? 1 : 0) : 1
    ];
    const [res] = await db.query(q, params);
    return res.insertId;
  },

  // Report runs
  async createReportRun(reportDefinitionId, parametrosUsed = {}, meta = {}) {
    const q = `INSERT INTO analytics_report_runs 
      (report_definition_id, status, parametros_used, resultado_data, created_at)
      VALUES (?, 'pending', ?, ?, NOW())`;
    const [res] = await db.query(q, [reportDefinitionId, JSON.stringify(parametrosUsed), JSON.stringify(meta)]);
    return res.insertId;
  },

  async updateReportRunStatus(runId, status, extra = {}) {
    const q = `UPDATE analytics_report_runs
      SET status = ?, finished_at = CASE WHEN ? IN ('success','failed','cancelled') THEN NOW() ELSE finished_at END,
          duration_ms = IFNULL(duration_ms, NULL),
          resultado_data = COALESCE(resultado_data, ?),
          error_text = COALESCE(error_text, ?)
      WHERE id = ?`;
    // Note: we keep a flexible approach: pass resultado_data / error_text as strings
    const [res] = await db.query(q, [
      status,
      status,
      extra.resultado_data ? JSON.stringify(extra.resultado_data) : null,
      extra.error_text || null,
      runId
    ]);
    return res.affectedRows;
  },

  async getReportRun(runId) {
    const q = `SELECT * FROM analytics_report_runs WHERE id = ?`;
    const [rows] = await db.query(q, [runId]);
    return rows[0] || null;
  },

  // Exports
  async saveExport(runId, empresaId, usuarioId, tipoExport, caminhoArquivo, tamanhoBytes = null) {
    const q = `INSERT INTO analytics_exports (run_id, empresa_id, usuario_id, tipo_export, caminho_arquivo, tamanho_bytes, criado_em)
      VALUES (?, ?, ?, ?, ?, ?, NOW())`;
    const [res] = await db.query(q, [runId, empresaId, usuarioId, tipoExport, caminhoArquivo, tamanhoBytes]);
    return res.insertId;
  },

  // Metric cache (basic)
  async upsertMetricCache(empresaId, metricKey, dimension = null, periodStart = null, periodEnd = null, valueDouble = null, payload = {}) {
    const q = `INSERT INTO analytics_metric_cache
      (empresa_id, metric_key, dimension, period_start, period_end, value_double, payload, ttl_seconds, computed_at)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
      ON DUPLICATE KEY UPDATE value_double = VALUES(value_double), payload = VALUES(payload), computed_at = NOW(), ttl_seconds = VALUES(ttl_seconds)`;
    const [res] = await db.query(q, [
      empresaId,
      metricKey,
      dimension ? JSON.stringify(dimension) : null,
      periodStart,
      periodEnd,
      valueDouble,
      JSON.stringify(payload || {}),
      86400
    ]);
    return res.insertId || res.affectedRows;
  },

  async getMetricCache(empresaId, metricKey, periodStart = null, periodEnd = null, dimension = null) {
    const q = `SELECT * FROM analytics_metric_cache WHERE empresa_id = ? AND metric_key = ? AND period_start = ? AND period_end = ?`;
    const [rows] = await db.query(q, [empresaId, metricKey, periodStart, periodEnd]);
    return rows[0] || null;
  },

  // timeseries insertion (append)
  async insertTimeseries(empresaId, metricName, tsDate, dimensionKey = null, value = 0, meta = {}) {
    const q = `INSERT INTO analytics_timeseries (empresa_id, metric_name, ts_date, dimension_key, value, meta, created_at)
      VALUES (?, ?, ?, ?, ?, ?, NOW())
      ON DUPLICATE KEY UPDATE value = VALUES(value), meta = VALUES(meta), created_at = NOW()`;
    const [res] = await db.query(q, [empresaId, metricName, tsDate, dimensionKey, value, JSON.stringify(meta || {})]);
    return res.insertId || res.affectedRows;
  },

  // alerts (simple)
  async createAlert(empresaId, key, nivel = 'warning', referenciaId = null, contexto = {}) {
    const q = `INSERT INTO analytics_alerts (empresa_id, alerta_key, nivel, referencia_id, contexto, disparado_em, lido)
      VALUES (?, ?, ?, ?, ?, NOW(), 0)`;
    const [res] = await db.query(q, [empresaId, key, nivel, referenciaId, JSON.stringify(contexto || {})]);
    return res.insertId;
  },

  async listAlerts(empresaId, onlyUnread = false) {
    const q = `SELECT * FROM analytics_alerts WHERE empresa_id = ? ${onlyUnread ? 'AND lido = 0' : ''} ORDER BY disparado_em DESC LIMIT 200`;
    const [rows] = await db.query(q, [empresaId]);
    return rows;
  }
};

module.exports = analyticsModel;
