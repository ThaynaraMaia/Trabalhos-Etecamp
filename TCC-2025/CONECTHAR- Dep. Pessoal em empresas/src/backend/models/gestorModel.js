// backend/models/gestorModel.js
const db = require('../config/db');
const bcrypt = require('bcrypt');
const SALT_ROUNDS = 10;

const gestorModel = {
  /**
   * Busca gestor por ID (com join para trazer nome da empresa quando disponível)
   */
  async findById(id) {
    try {
      const [rows] = await db.query(
        `SELECT 
           u.id,
           u.empresa_id,
           u.numero_registro,
           u.nome,
           u.cnpj,
           u.tipo_usuario,
           u.cargo,
           u.setor,
           u.tipo_jornada,
           u.horas_diarias,
           u.foto,
           u.senha_hash,
           e.nome_empresa AS empresa_nome  
         FROM usuario u
         LEFT JOIN empresa e ON e.id = u.empresa_id
         WHERE u.id = ? AND u.tipo_usuario = 'gestor'
         LIMIT 1`,
        [id]
      );
      return rows[0] || null;
    } catch (err) {
      console.error('Erro em gestorModel.findById:', err);
      throw err;
    }
  },

   async findByIdSimple(id) {
    try {
      const [rows] = await db.query(
        `SELECT 
           u.id,
           u.empresa_id,
           u.numero_registro,
           u.nome,
           u.cnpj,
           u.tipo_usuario,
           u.cargo,
           u.setor,
           u.tipo_jornada,
           u.horas_diarias,
           u.foto,
           u.senha_hash,
           e.nome_empresa AS empresa_nome  // CORREÇÃO AQUI TAMBÉM
         FROM usuario u
         LEFT JOIN empresa e ON e.id = u.empresa_id
         WHERE u.id = ? AND u.tipo_usuario = 'gestor'
         LIMIT 1`,
        [id]
      );
      return rows[0] || null;
    } catch (err) {
      console.error('Erro em gestorModel.findByIdSimple:', err);
      throw err;
    }
  },


  /**
   * Busca gestor por registro dentro de uma empresa (usado no login)
   */
  async findByRegistro(empresa_id, numero_registro) {
    try {
      const [rows] = await db.query(
        `SELECT * FROM usuario 
         WHERE empresa_id = ? AND numero_registro = ? AND tipo_usuario = 'gestor' 
         LIMIT 1`,
        [empresa_id, numero_registro]
      );
      return rows[0] || null;
    } catch (err) {
      console.error('Erro em gestorModel.findByRegistro:', err);
      throw err;
    }
  },

  /**
   * Cria um novo gestor e retorna o registro completo (findById)
   */
  async create({ empresa_id, numero_registro, nome, cnpj, senha, cargo, setor, tipo_jornada, horas_diarias }) {
    if (!empresa_id || !numero_registro || !nome || !cnpj || !senha) {
      throw new Error('Campos obrigatórios ausentes para criar gestor.');
    }

    try {
      const existing = await this.findByRegistro(empresa_id, numero_registro);
      if (existing) {
        throw new Error('Gestor já existe para este registro e empresa.');
      }

      const senha_hash = await bcrypt.hash(senha, SALT_ROUNDS);

      const [result] = await db.query(
        `INSERT INTO usuario 
          (empresa_id, numero_registro, nome, cnpj, senha_hash, tipo_usuario, cargo, setor, tipo_jornada, horas_diarias, created_at)
         VALUES (?, ?, ?, ?, ?, 'gestor', ?, ?, ?, ?, NOW())`,
        [empresa_id, numero_registro, nome, cnpj, senha_hash, cargo || null, setor || null, tipo_jornada || null, horas_diarias || null]
      );

      // Retorna registro completo
      return await this.findById(result.insertId);
    } catch (err) {
      console.error('Erro em gestorModel.create:', err);
      throw err;
    }
  },

  /**
   * Atualiza gestor por ID e retorna registro atualizado (findById)
   */
  async update(id, updates) {
    try {
      const gestor = await this.findById(id);
      if (!gestor) {
        throw new Error('Gestor não encontrado.');
      }

      // Campos que não permitimos atualizar aqui
      const forbidden = new Set(['senha', 'senha_hash', 'tipo_usuario', 'empresa_id', 'id', 'numero_registro', 'cnpj']);
      const fields = [];
      const values = [];

      Object.keys(updates).forEach(key => {
        if (forbidden.has(key)) return;
        // somente adiciona se diferente de undefined
        if (updates[key] !== undefined) {
          fields.push(`${key} = ?`);
          values.push(updates[key]);
        }
      });

      if (fields.length === 0) {
        // nada para atualizar -> retorna registro atual
        return gestor;
      }

      values.push(id);

      const [result] = await db.query(
        `UPDATE usuario SET ${fields.join(', ')} WHERE id = ? AND tipo_usuario = 'gestor'`,
        values
      );

      if (result.affectedRows === 0) {
        throw new Error('Nenhum gestor atualizado.');
      }

      // Retorna registro atualizado
      return await this.findById(id);
    } catch (err) {
      console.error('Erro em gestorModel.update:', err);
      throw err;
    }
  },

  /**
   * Deleta gestor por ID (hard delete)
   */
  async delete(id) {
    try {
      const [result] = await db.query(
        `DELETE FROM usuario WHERE id = ? AND tipo_usuario = 'gestor'`,
        [id]
      );

      if (result.affectedRows === 0) {
        throw new Error('Gestor não encontrado ou não deletado.');
      }

      return { message: 'Gestor deletado com sucesso.' };
    } catch (err) {
      console.error('Erro em gestorModel.delete:', err);
      throw err;
    }
  }
};

module.exports = gestorModel;
