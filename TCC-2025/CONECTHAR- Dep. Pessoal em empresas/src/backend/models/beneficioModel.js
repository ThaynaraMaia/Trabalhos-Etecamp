// backend/models/beneficioModel.js
const db = require('../config/db');

const beneficioModel = {
  // Listar por cargo
  // backend/models/beneficioModel.js - Adicione este método
// backend/models/beneficioModel.js - Adicione este método
async listarPorCargo(cargo_id) {
    try {
        const sql = `
            SELECT 
                id,
                nome_do_beneficio,
                descricao_beneficio,
                valor_aplicado,
                data_inicio,
                data_fim,
                ativo
            FROM gerenciarbeneficios 
            WHERE cargo_id = ? AND ativo = 1
            ORDER BY nome_do_beneficio
        `;
        const [beneficios] = await db.query(sql, [cargo_id]);
        return beneficios;
    } catch (error) {
        console.error('Erro no modelo ao listar benefícios por cargo:', error);
        throw error;
    }
}, 

// Método para listar benefícios por cargo E setor
async listarPorCargoESetor(cargo_id, setor_id) {
    try {
        const sql = `
            SELECT 
                gb.id,
                gb.nome_do_beneficio,
                gb.descricao_beneficio,
                gb.valor_aplicado,
                gb.data_inicio,
                gb.data_fim,
                gb.ativo,
                c.nome_cargo,
                s.nome_setor
            FROM gerenciarbeneficios gb
            LEFT JOIN cargos c ON gb.cargo_id = c.id
            LEFT JOIN setores s ON gb.setor_id = s.id
            WHERE (gb.cargo_id = ? OR gb.setor_id = ?)
                AND gb.ativo = 1
                AND gb.usuario_id IS NULL
            ORDER BY gb.nome_do_beneficio
        `;
        const [beneficios] = await db.query(sql, [cargo_id, setor_id]);
        return beneficios;
    } catch (error) {
        console.error('Erro no modelo ao listar benefícios por cargo e setor:', error);
        throw error;
    }
},

  // Criar benefício
  async criar({ gestor_id, cargo_id, setor_id, nome_beneficio, descricao, valor, data_inicio, data_fim }) {
    try {
      const [result] = await db.query(`
        INSERT INTO gerenciarbeneficios 
        (gestor_id, cargo_id, setor_id, nome_do_beneficio, descricao_beneficio, valor_aplicado, data_inicio, data_fim, ativo)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)
      `, [gestor_id, cargo_id, setor_id, nome_beneficio, descricao, valor, data_inicio, data_fim]);
      
      return { success: true, id: result.insertId };
    } catch (err) {
      console.error('Erro ao criar benefício:', err);
      throw err;
    }
  },

  // Listar benefícios por empresa (através do gestor)
  async listarPorEmpresa(empresa_id) {
    try {
      const [rows] = await db.query(`
        SELECT 
          b.id,
          b.nome_do_beneficio,
          b.descricao_beneficio,
          b.valor_aplicado,
          b.data_inicio,
          b.data_fim,
          b.ativo,
          c.nome_cargo,
          c.id as cargo_id,
          s.nome_setor,
          s.id as setor_id,
          (SELECT COUNT(*) FROM usuario_beneficios ub WHERE ub.beneficio_id = b.id) as total_funcionarios
        FROM gerenciarbeneficios b
        INNER JOIN usuario g ON b.gestor_id = g.id
        LEFT JOIN cargos c ON b.cargo_id = c.id
        LEFT JOIN setores s ON b.setor_id = s.id
        WHERE g.empresa_id = ?
        ORDER BY b.ativo DESC, b.data_inicio DESC
      `, [empresa_id]);
      return rows;
    } catch (err) {
      console.error('Erro ao listar benefícios:', err);
      throw err;
    }
  },

  // Buscar benefício por ID
  async buscarPorId(id) {
    try {
      const [rows] = await db.query(`
        SELECT 
          b.*,
          c.nome_cargo,
          s.nome_setor
        FROM gerenciarbeneficios b
        LEFT JOIN cargos c ON b.cargo_id = c.id
        LEFT JOIN setores s ON b.setor_id = s.id
        WHERE b.id = ?
      `, [id]);
      return rows[0] || null;
    } catch (err) {
      console.error('Erro ao buscar benefício:', err);
      throw err;
    }
  },

  // Atualizar benefício
  async atualizar(id, dados) {
    try {
      const campos = [];
      const valores = [];

      if (dados.nome_beneficio) {
        campos.push('nome_do_beneficio = ?');
        valores.push(dados.nome_beneficio);
      }
      if (dados.descricao) {
        campos.push('descricao_beneficio = ?');
        valores.push(dados.descricao);
      }
      if (dados.valor !== undefined) {
        campos.push('valor_aplicado = ?');
        valores.push(dados.valor);
      }
      if (dados.ativo !== undefined) {
        campos.push('ativo = ?');
        valores.push(dados.ativo);
      }

      if (campos.length === 0) return { success: false, message: 'Nenhum campo para atualizar' };

      valores.push(id);
      const query = `UPDATE gerenciarbeneficios SET ${campos.join(', ')} WHERE id = ?`;
      
      const [result] = await db.query(query, valores);
      return { success: result.affectedRows > 0 };
    } catch (err) {
      console.error('Erro ao atualizar benefício:', err);
      throw err;
    }
  },

  // Deletar benefício
  async deletar(id) {
    try {
      const [result] = await db.query('DELETE FROM gerenciarbeneficios WHERE id = ?', [id]);
      return result.affectedRows > 0;
    } catch (err) {
      console.error('Erro ao deletar benefício:', err);
      throw err;
    }
  },

  // Estatísticas
  async obterEstatisticas(empresa_id) {
    try {
      const [stats] = await db.query(`
        SELECT 
          COUNT(DISTINCT b.id) as total_beneficios,
          SUM(b.valor_aplicado) as valor_total_mensal,
          COUNT(DISTINCT ub.usuario_id) as funcionarios_com_beneficios
        FROM gerenciarbeneficios b
        INNER JOIN usuario g ON b.gestor_id = g.id
        LEFT JOIN usuario_beneficios ub ON b.id = ub.beneficio_id
        WHERE g.empresa_id = ? AND b.ativo = 1
      `, [empresa_id]);
      
      return stats[0] || { total_beneficios: 0, valor_total_mensal: 0, funcionarios_com_beneficios: 0 };
    } catch (err) {
      console.error('Erro ao obter estatísticas:', err);
      throw err;
    }
  }
};

module.exports = beneficioModel;