// models/Punicao.js
const db = require("../config/db");

class Punicao {
  /**
   * Cria nova punição
   */
  static async create(data) {
    const {
      Fk_usuario,
      Fk_denuncia,
      Tipo,
      Motivo,
      Duracao,
      Data_Inicio,
      Data_fim,
      Aplicado_por,
      Status = "ativa",
    } = data;

    const [result] = await db.query(
      `INSERT INTO punicao 
        (Fk_usuario, Fk_denuncia, Tipo, Motivo, Duracao, Data_Inicio, Data_fim, Aplicado_por, Status)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        Fk_usuario,
        Fk_denuncia,
        Tipo,
        Motivo,
        Duracao,
        Data_Inicio,
        Data_fim,
        Aplicado_por,
        Status,
      ]
    );

    return result.insertId;
  }

  /**
   * Busca punição por ID (com joins úteis)
   */
  static async findById(id) {
    const [rows] = await db.query(
      `SELECT p.*, 
              u.Apelido AS Usuario_Apelido, u.nametag AS Usuario_Nametag,
              a.Apelido AS Admin_Apelido, a.nametag AS Admin_Nametag,
              d.Motivo AS Denuncia_Motivo, d.Alvo AS Denuncia_Alvo
       FROM punicao p
       JOIN usuario u ON p.Fk_usuario = u.Id_usuario
       JOIN usuario a ON p.Aplicado_por = a.Id_usuario
       JOIN denuncia d ON p.Fk_denuncia = d.Id_denuncia
       WHERE p.Id_punicao = ?`,
      [id]
    );
    return rows[0] || null;
  }

  /**
   * Lista todas as punições (com paginação)
   */
  static async findAll(page = 1, limit = 20) {
    const offset = (page - 1) * limit;

    const [rows] = await db.query(
      `SELECT p.*, 
              u.Apelido AS Usuario_Apelido,
              a.Apelido AS Admin_Apelido,
              d.Alvo AS Denuncia_Alvo
       FROM punicao p
       JOIN usuario u ON p.Fk_usuario = u.Id_usuario
       JOIN usuario a ON p.Aplicado_por = a.Id_usuario
       JOIN denuncia d ON p.Fk_denuncia = d.Id_denuncia
       ORDER BY p.Data_Inicio DESC
       LIMIT ? OFFSET ?`,
      [limit, offset]
    );

    const [count] = await db.query(`SELECT COUNT(*) AS total FROM punicao`);
    return {
      punicoes: rows,
      total: count[0].total,
      pagina: page,
      totalPaginas: Math.ceil(count[0].total / limit),
    };
  }

  /**
   * Lista punições de um usuário
   */
  static async findByUserId(userId) {
    const [rows] = await db.query(
      `SELECT p.*, a.Apelido AS Admin_Apelido, a.nametag AS Admin_Nametag
       FROM punicao p
       JOIN usuario a ON p.Aplicado_por = a.Id_usuario
       WHERE p.Fk_usuario = ?
       ORDER BY p.Data_Inicio DESC`,
      [userId]
    );
    return rows;
  }

  /**
   * Lista punições ativas de um usuário
   * Considera também punições permanentes (Data_fim IS NULL)
   */
  static async findActiveByUserId(userId) {
    const [rows] = await db.query(
      `SELECT * FROM punicao
       WHERE Fk_usuario = ?
         AND Status = 'ativa'
         AND (Data_fim IS NULL OR Data_fim > NOW())
       ORDER BY Data_Inicio DESC`,
      [userId]
    );
    return rows;
  }

  /**
   * Atualiza status (ativa, expirada, retirada)
   */
  static async updateStatus(id, Status) {
    const valid = ["ativa", "expirada", "retirada"];
    if (!valid.includes(Status)) throw new Error("Status inválido.");

    const [result] = await db.query(
      `UPDATE punicao SET Status = ? WHERE Id_punicao = ?`,
      [Status, id]
    );

    return result.affectedRows > 0;
  }

  /**
   * Atualiza dados de uma punição
   */
  static async update(id, data) {
    const campos = [];
    const valores = [];

    for (const [key, value] of Object.entries(data)) {
      campos.push(`${key} = ?`);
      valores.push(value);
    }

    valores.push(id);

    const [result] = await db.query(
      `UPDATE punicao SET ${campos.join(", ")} WHERE Id_punicao = ?`,
      valores
    );

    return result.affectedRows > 0;
  }

  /**
   * Remove punição
   */
  static async delete(id) {
    const [result] = await db.query(
      `DELETE FROM punicao WHERE Id_punicao = ?`,
      [id]
    );
    return result.affectedRows > 0;
  }

  /**
   * Revoga punição (muda status para retirada)
   */
  static async revoke(id) {
    const [result] = await db.query(
      `UPDATE punicao 
       SET Status = 'retirada' 
       WHERE Id_punicao = ? AND Status = 'ativa'`,
      [id]
    );
    return result.affectedRows > 0;
  }

  /**
   * Atualiza punições expiradas automaticamente
   */
/**
 * Atualiza punições expiradas e reativa usuários suspensos/banidos
 */
static async updateExpired() {
  try {
    // 🔹 1. Expira punições cujo prazo acabou
    const [result] = await db.query(
      `UPDATE punicao 
       SET Status = 'expirada'
       WHERE Status = 'ativa'
         AND Data_fim IS NOT NULL
         AND Data_fim <= NOW()`
    );

    if (result.affectedRows > 0) {
      console.log(`⏰ ${result.affectedRows} punição(ões) expiradas automaticamente.`);
    }

    // 🔹 2. Reativa usuários suspensos/banidos que não têm mais punições ativas
    const [updatedUsers] = await db.query(`
      UPDATE usuario u
      SET u.Status = 'ativo'
      WHERE u.Status IN ('suspenso', 'banido')
        AND NOT EXISTS (
          SELECT 1 FROM punicao p
          WHERE p.Fk_usuario = u.Id_usuario
            AND p.Status = 'ativa'
            AND (p.Data_fim IS NULL OR p.Data_fim > NOW())
        )
    `);

    if (updatedUsers.affectedRows > 0) {
      console.log(`✅ ${updatedUsers.affectedRows} usuário(s) reativado(s) automaticamente.`);
    }

    // Retorna contagem total de punições expiradas
    return result.affectedRows;

  } catch (err) {
    console.error('❌ Erro ao atualizar punições expiradas:', err);
    throw err;
  }
}


  /**
   * Verifica se usuário possui punição ativa (ainda válida)
   */
 /**
 * Verifica se usuário possui punição ativa (inclui suspensões permanentes)
 */
static async hasActivePunishment(userId) {
  const [rows] = await db.query(
    `SELECT * FROM punicao
     WHERE Fk_usuario = ?
       AND Status = 'ativa'
       AND (Data_fim IS NULL OR Data_fim > NOW())
     ORDER BY Data_Inicio DESC
     LIMIT 1`,
    [userId]
  );

  if (!rows.length) return null;

  const punicao = rows[0];
  return {
    ...punicao,
    Tipo: punicao.Tipo ? punicao.Tipo.toLowerCase() : ''
  };
}
}

module.exports = Punicao;
