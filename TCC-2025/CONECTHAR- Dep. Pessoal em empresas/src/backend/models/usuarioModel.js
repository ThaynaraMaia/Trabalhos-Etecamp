// backend/models/usuarioModel.js
const db = require('../config/db');

const usuarioModel = {
  /**
   * Busca usuário por ID (campos públicos)
   */
 async findById(id) {
    const [rows] = await db.query(
      `SELECT id, empresa_id, numero_registro, nome, cpf, email, cnpj,
              tipo_usuario, cargo, setor, salario, tipo_jornada, horas_diarias, foto
       FROM usuario
       WHERE id = ? LIMIT 1`,
      [id]
    );
    return rows && rows[0] ? rows[0] : null;
  },

  // alias em português
  async buscarPorId(id) {
    return this.findById(id);
  },

  /**
   * Busca por número de registro + (opcional) cnpj
   * Se cnpj fornecido, filtra por ambos; caso contrário retorna o primeiro registro que bater com numero_registro
   */
 async findByRegistro(numero_registro, cnpj = null) {
    if (cnpj) {
      const [rows] = await db.query(
        `SELECT id, empresa_id, numero_registro, nome, cpf, email, cnpj,
                tipo_usuario, cargo, setor, salario, tipo_jornada, horas_diarias, foto
         FROM usuario
         WHERE numero_registro = ? AND cnpj = ? LIMIT 1`,
        [numero_registro, cnpj]
      );
      return rows && rows[0] ? rows[0] : null;
    } else {
      const [rows] = await db.query(
        `SELECT id, empresa_id, numero_registro, nome, cpf, email, cnpj,
                tipo_usuario, cargo, setor, salario, tipo_jornada, horas_diarias, foto
         FROM usuario
         WHERE numero_registro = ? LIMIT 1`,
        [numero_registro]
      );
      return rows && rows[0] ? rows[0] : null;
    }
  },

  /**
   * Busca por registro + tipo (caso precise)
   */
 async findByRegistroAndTipo(numero_registro, tipo_usuario, cnpj = null) {
    if (cnpj) {
      const [rows] = await db.query(
        `SELECT * FROM usuario WHERE numero_registro = ? AND tipo_usuario = ? AND cnpj = ? LIMIT 1`,
        [numero_registro, tipo_usuario, cnpj]
      );
      return rows && rows[0] ? rows[0] : null;
    } else {
      const [rows] = await db.query(
        `SELECT * FROM usuario WHERE numero_registro = ? AND tipo_usuario = ? LIMIT 1`,
        [numero_registro, tipo_usuario]
      );
      return rows && rows[0] ? rows[0] : null;
    }
  },

async buscarPorRegistroAndTipo(numero_registro, tipo_usuario, cnpj = null) {
    return this.findByRegistroAndTipo(numero_registro, tipo_usuario, cnpj);
  },

  /**
   * Cria usuário (padrão usado pelo authController.register)
   */
   async create(usuarioObj) {
    const q = `INSERT INTO usuario
      (empresa_id, numero_registro, nome, cpf, email, cnpj, senha_hash, tipo_usuario,
       cargo, setor, tipo_jornada, horas_diarias, foto, salario)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`;
    const params = [
      usuarioObj.empresa_id || null,
      usuarioObj.numero_registro,
      usuarioObj.nome,
      usuarioObj.cpf || null,
      usuarioObj.email || null,
      usuarioObj.cnpj || null,
      usuarioObj.senha_hash,
      usuarioObj.tipo_usuario,
      usuarioObj.cargo || null,
      usuarioObj.setor || null,
      usuarioObj.tipo_jornada || '6x1',
      usuarioObj.horas_diarias || 8,
      usuarioObj.foto || null,
      // garantir decimal padrão
      typeof usuarioObj.salario !== 'undefined' ? Number(usuarioObj.salario) : 0.00
    ];
    const [result] = await db.query(q, params);
    return result;
  },

  // alias em português
  async criar(usuarioObj) {
    return this.create(usuarioObj);
  },

  /**
   * Lista usuários por empresa (inclui salario)
   */
  async listarPorEmpresa(empresaId) {
    const [rows] = await db.query(
      `SELECT id, empresa_id, numero_registro, nome, cpf, email, cnpj,
              tipo_usuario, cargo, setor, salario, tipo_jornada, horas_diarias, foto
       FROM usuario
       WHERE empresa_id = ?`,
      [empresaId]
    );
    return rows || [];
  },

  /**
   * Atualiza usuário por id com um objeto de updates (ex.: { nome, cargo, salario })
   */
  async atualizar(id, updatesObj = {}) {
    const allowed = ['numero_registro','nome','cpf','email','cnpj','senha_hash','tipo_usuario','cargo','setor','tipo_jornada','horas_diarias','foto','salario'];
    const sets = [];
    const params = [];

    for (const key of Object.keys(updatesObj)) {
      if (allowed.includes(key)) {
        sets.push(`${key} = ?`);
        // se for salario, garantir Number
        params.push(key === 'salario' ? Number(updatesObj[key]) : updatesObj[key]);
      }
    }

    if (sets.length === 0) {
      return { affectedRows: 0 };
    }

    params.push(id);
    const q = `UPDATE usuario SET ${sets.join(', ')} WHERE id = ?`;
    const [result] = await db.query(q, params);
    return result;
  },

  /**
   * Excluir usuário (garantindo empresa)
   */
  async excluir(id, empresaId) {
    const [result] = await db.query('DELETE FROM usuario WHERE id = ? AND empresa_id = ?', [id, empresaId]);
    return result;
  }

};

module.exports = usuarioModel;
