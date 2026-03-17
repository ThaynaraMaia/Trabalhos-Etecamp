// backend/models/cargoModel.js
const db = require('../config/db');

const cargoModel = {
  // Criar novo cargo
  async criar({ empresa_id, setor_id, nome_cargo, descricao }) {
    try {
      const [result] = await db.query(
        'INSERT INTO cargos (empresa_id, setor_id, nome_cargo, descricao) VALUES (?, ?, ?, ?)',
        [empresa_id, setor_id, nome_cargo, descricao || null]
      );
      return { success: true, id: result.insertId };
    } catch (err) {
      console.error('Erro ao criar cargo:', err);
      if (err.code === 'ER_DUP_ENTRY') {
        throw new Error('Cargo já existe neste setor');
      }
      throw err;
    }
  },

  // Listar cargos por empresa
  async listarPorEmpresa(empresa_id) {
    try {
      const [rows] = await db.query(`
        SELECT 
          c.id,
          c.nome_cargo,
          c.descricao,
          c.setor_id,
          s.nome_setor,
          c.data_criacao
        FROM cargos c
        INNER JOIN setores s ON c.setor_id = s.id
        WHERE c.empresa_id = ?
        ORDER BY s.nome_setor, c.nome_cargo
      `, [empresa_id]);
      return rows;
    } catch (err) {
      console.error('Erro ao listar cargos:', err);
      throw err;
    }
  },

  // Listar cargos por setor
  async listarPorSetor(setor_id) {
    try {
      const [rows] = await db.query(
        'SELECT id, nome_cargo, descricao FROM cargos WHERE setor_id = ? ORDER BY nome_cargo',
        [setor_id]
      );
      return rows;
    } catch (err) {
      console.error('Erro ao listar cargos por setor:', err);
      throw err;
    }
  },

  // Buscar cargo por ID
  async buscarPorId(id) {
    try {
      const [rows] = await db.query(`
        SELECT 
          c.id,
          c.empresa_id,
          c.setor_id,
          c.nome_cargo,
          c.descricao,
          s.nome_setor
        FROM cargos c
        INNER JOIN setores s ON c.setor_id = s.id
        WHERE c.id = ?
      `, [id]);
      return rows[0] || null;
    } catch (err) {
      console.error('Erro ao buscar cargo:', err);
      throw err;
    }
  },

  // Deletar cargo
  async deletar(id) {
    try {
      const [result] = await db.query('DELETE FROM cargos WHERE id = ?', [id]);
      return result.affectedRows > 0;
    } catch (err) {
      console.error('Erro ao deletar cargo:', err);
      throw err;
    }
  }
};

module.exports = cargoModel;