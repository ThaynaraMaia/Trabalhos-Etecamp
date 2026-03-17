// models/setorModel.js
const db = require('../config/db');// ajuste para o seu módulo de DB (mysql2 / pool)

const SetorModel = {
  async listarPorEmpresa(empresaId) {
    if (!empresaId) return [];
    const sql = `
      SELECT id, empresa_id, nome_setor, descricao
      FROM setores
      WHERE empresa_id = ?
      ORDER BY nome_setor ASC
    `;
    const [rows] = await db.query(sql, [empresaId]);
    return rows || [];
  }
};

module.exports = SetorModel;
