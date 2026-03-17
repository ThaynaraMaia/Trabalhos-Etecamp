// backend/models/uploadModel.js
const db = require('../config/db');

const VALID_TIPOS = new Set(['contrato','holerite','atestado','recibo','declaracao','outros','avatar']);

const UploadModel = {
  // registrarUpload: agora recebe opcionalmente setorId
async registrarUpload(usuarioId, tipoDocumento, caminhoArquivo, nomeArquivo, setorId = null) {
  try {
    if (!usuarioId) throw new Error('usuarioId obrigatório');

    const tipo = tipoDocumento ? String(tipoDocumento).toLowerCase() : 'outros';
    if (!VALID_TIPOS.has(tipo)) {
      throw new Error('tipoDocumento inválido: ' + tipo);
    }

    if (!caminhoArquivo) throw new Error('caminhoArquivo obrigatório');

    const caminho = String(caminhoArquivo).substr(0, 255);
    const nome = String(nomeArquivo || '').substr(0, 100);

    // se setorId for fornecido, garante que seja number ou null
    const sId = (typeof setorId === 'number' || (typeof setorId === 'string' && /^\d+$/.test(setorId)))
      ? Number(setorId)
      : null;

    const [result] = await db.query(
      'INSERT INTO realizarupload (usuario_id, setor_id, tipo_documento, caminho_arquivo, nome_arquivo, data_upload) VALUES (?, ?, ?, ?, ?, NOW())',
      [usuarioId, sId, tipo, caminho, nome]
    );

    console.info('[UploadModel] registrarUpload -> insertId=', result && result.insertId);
    return result;
  } catch (error) {
    console.error('[UploadModel] erro registrarUpload:', error && error.message ? error.message : error);
    throw error;
  }
},

  // LISTAGEM: agora traz também o nome do usuário (usuario_nome)
 async listarUploadsPorUsuario(usuarioId) {
  try {
    if (!usuarioId) return [];
    const [rows] = await db.query(
      `SELECT r.id, r.usuario_id, r.tipo_documento, r.caminho_arquivo,
              r.nome_arquivo, r.data_upload, u.nome AS usuario_nome
       FROM realizarupload r
       LEFT JOIN usuario u ON u.id = r.usuario_id
       WHERE r.usuario_id = ?
       ORDER BY r.data_upload DESC`,
      [usuarioId]
    );
    return rows || [];
  } catch (error) {
    console.error('[UploadModel] erro listarUploadsPorUsuario:', error);
    throw error;
  }
},
// backend/models/uploadModel.js
// --- adicione ao objeto UploadModel ---

/**
 * Retorna uploads feitos por usuários cujo cnpj é igual ao fornecido.
 * Retorna campos do upload e também u.nome AS usuario_nome e u.cnpj.
 */
async listarPorCnpj(cnpj) {
  try {
    if (!cnpj) return [];
    const sql = `
      SELECT
        r.id,
        r.usuario_id,
        r.tipo_documento,
        r.caminho_arquivo,
        r.nome_arquivo,
        r.data_upload,
        r.status,
        u.nome AS usuario_nome,
        u.cnpj AS usuario_cnpj,
        u.id AS usuario_id_full
      FROM realizarupload r
      LEFT JOIN usuario u ON u.id = r.usuario_id
      WHERE u.cnpj = ?
      ORDER BY r.data_upload DESC, r.id DESC
    `;
    const [rows] = await db.query(sql, [cnpj]);
    return rows || [];
  } catch (err) {
    console.error('[UploadModel] erro listarPorCnpj:', err);
    throw err;
  }
},


  // LISTAR TODOS: também incluindo usuario_nome para cada documento
 async listarTodos() {
  try {
    const [rows] = await db.query(
      `SELECT r.id, r.usuario_id, r.tipo_documento, r.caminho_arquivo,
              r.nome_arquivo, r.data_upload, u.nome AS usuario_nome
       FROM realizarupload r
       LEFT JOIN usuario u ON u.id = r.usuario_id
       ORDER BY r.data_upload DESC`
    );
    return rows || [];
  } catch (error) {
    console.error('[UploadModel] erro listarTodos:', error);
    throw error;
  }
},
  async buscarPorId(uploadId) {
    try {
      if (!uploadId) return null;
      const [rows] = await db.query(
        'SELECT id, usuario_id, tipo_documento, caminho_arquivo, nome_arquivo, data_upload FROM realizarupload WHERE id = ? LIMIT 1',
        [uploadId]
      );
      return rows.length > 0 ? rows[0] : null;
    } catch (error) {
      console.error('[UploadModel] erro buscarPorId:', error);
      throw error;
    }
  }
};

module.exports = UploadModel;
