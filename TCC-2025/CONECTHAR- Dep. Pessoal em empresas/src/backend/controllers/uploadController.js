// backend/controllers/uploadController.js
const db = require('../config/db');
const UploadModel = require('../models/uploadModel');
const path = require('path');
const fs = require('fs');

const uploadController = {
  // Função para realizar upload
 async realizarUpload(req, res) {
  console.log('=== INICIANDO UPLOAD ===');
  console.log('Usuario completo do token:', req.usuario);

  try {
    if (!req.usuario) {
      return res.status(401).json({ success: false, message: 'Usuário não autenticado.' });
    }

    // BUSCAR O USUÁRIO NO BANCO (mesma lógica sua)
    let usuarioId;
    if (req.usuario.id) {
      usuarioId = req.usuario.id;
    } else if (req.usuario.numero_registro) {
      const [usuarios] = await db.query(
        'SELECT id FROM usuario WHERE numero_registro = ?',
        [req.usuario.numero_registro]
      );
      if (usuarios.length === 0) {
        return res.status(404).json({ success: false, message: 'Usuário não encontrado no banco.' });
      }
      usuarioId = usuarios[0].id;
    } else if (req.usuario.cnpj) {
      const [usuarios] = await db.query(
        'SELECT id FROM usuario WHERE cnpj = ?',
        [req.usuario.cnpj]
      );
      if (usuarios.length === 0) {
        return res.status(404).json({ success: false, message: 'Usuário não encontrado no banco.' });
      }
      usuarioId = usuarios[0].id;
    } else {
      return res.status(401).json({ success: false, message: 'Dados do usuário insuficientes.' });
    }

    if (!req.file) {
      console.log('ERRO: Nenhum arquivo enviado');
      return res.status(400).json({ success: false, message: 'Nenhum arquivo enviado.' });
    }

    // Detecta tipo_documento: se enviado no body usa, se não e rota for /usuario/upload assume 'avatar'
    let tipoDocumento = req.body.tipo_documento ? String(req.body.tipo_documento).toLowerCase() : null;
    const url = (req.originalUrl || req.url || '').toLowerCase();

    if (!tipoDocumento) {
      if (/usuario\/upload/i.test(url)) {
        tipoDocumento = 'avatar';
      }
    }

    // Se ainda não houver tipoDocumento e não for avatar, rejeita (comportamento anterior)
    if (!tipoDocumento) {
      // remove arquivo salvo pelo multer para não acumular
      try { fs.unlinkSync(req.file.path); } catch (e) { /* silent */ }
      console.log('ERRO: Tipo de documento não informado e não é upload de usuário');
      return res.status(400).json({ success: false, message: 'O tipo do documento é obrigatório.' });
    }

    // Nome original do arquivo e nome salvo pelo multer
    const nomeArquivoOriginal = req.file.originalname;
    const nomeGerado = req.file.filename; // nome no disco (storage.filename)
    const caminhoParaSalvar = path.join('uploads', nomeGerado).replace(/\\/g, '/');

    console.log('Caminho para salvar no BD:', caminhoParaSalvar, 'tipo:', tipoDocumento);

    // ==== Determinar setor_id a partir do usuário, se possível ====
    let setorId = null;
    try {
      // obtem usuario com setor e empresa_id
      const [rowsUser] = await db.query('SELECT setor, empresa_id FROM usuario WHERE id = ?', [usuarioId]);
      const usuarioDb = rowsUser && rowsUser[0] ? rowsUser[0] : null;
      if (usuarioDb && usuarioDb.setor) {
        // tenta mapear nome do setor (case-insensitive, trim) para setores.id
        const [rowsSetor] = await db.query(
          'SELECT id FROM setores WHERE LOWER(TRIM(nome_setor)) = LOWER(TRIM(?)) AND empresa_id = ? LIMIT 1',
          [usuarioDb.setor, usuarioDb.empresa_id]
        );
        if (rowsSetor && rowsSetor[0]) {
          setorId = rowsSetor[0].id;
          console.log('Setor identificado para upload -> setorId=', setorId);
        } else {
          console.log('Setor do usuário não mapeado para setores (nome):', usuarioDb.setor);
        }
      }
    } catch (e) {
      console.warn('Falha ao identificar setor do usuário (não bloqueante):', e && e.message ? e.message : e);
    }

    // Registrar no banco de dados (passando setorId, pode ser null)
    const resultado = await UploadModel.registrarUpload(
      usuarioId,
      tipoDocumento,
      caminhoParaSalvar,
      nomeArquivoOriginal,
      setorId
    );
    console.log('Registro no BD realizado:', resultado && resultado.insertId);

    // Se for avatar, atualiza campo usuario.foto com o nome do arquivo salvo (apenas o filename)
    if (tipoDocumento === 'avatar') {
      try {
        const [upd] = await db.query('UPDATE usuario SET foto = ? WHERE id = ?', [nomeGerado, usuarioId]);
        console.log('Atualizado usuario.foto:', upd);
      } catch (err) {
        console.warn('Falha ao atualizar usuario.foto (mas upload persiste):', err && err.message ? err.message : err);
      }
    }

    res.status(201).json({
      success: true,
      message: 'Upload realizado com sucesso.',
      filename: nomeGerado,
      filePath: caminhoParaSalvar,
      uploadId: (resultado && (resultado.insertId || resultado.insert_id)) || null
    });
  } catch (error) {
    console.error('Erro no controlador ao realizar upload:', error && error.message ? error.message : error);
    if (req.file && req.file.path && fs.existsSync(req.file.path)) {
      try { fs.unlinkSync(req.file.path); console.log('Arquivo removido devido ao erro'); } catch(e){/*silent*/ }
    }
    res.status(500).json({ success: false, message: 'Erro interno no servidor ao processar upload.', error: error.message || String(error) });
  }
},

async listarUploadsPorCnpjGestor(req, res) {
  try {
    // req.usuario deve ser populado pelo middleware verificarToken
    const usuario = req.usuario;
    if (!usuario) {
      return res.status(401).json({ success: false, message: 'Usuário não autenticado.' });
    }

    // opcional: garantir que só gestores acessam essa rota
    if (usuario.tipo_usuario && String(usuario.tipo_usuario).toLowerCase() !== 'gestor') {
      return res.status(403).json({ success: false, message: 'Acesso negado: rota apenas para gestores.' });
    }

    // preferimos usar cnpj do usuário (gestor)
    const cnpj = usuario.cnpj;
    const empresaId = usuario.empresa_id || null;

    if (!cnpj && !empresaId) {
      return res.status(400).json({ success: false, message: 'CNPJ/empresa do gestor não encontrado.' });
    }

    // 1) Se o model possui método, tenta usá-lo (centraliza lógica se implementado)
    if (UploadModel && typeof UploadModel.listarPorCnpj === 'function') {
      try {
        const uploadsFromModel = await UploadModel.listarPorCnpj(cnpj || empresaId);
        const safe = Array.isArray(uploadsFromModel) ? uploadsFromModel : [];
        // garantir que os objetos incluam setor_id/setor_nome quando possível
        return res.status(200).json({ success: true, uploads: safe });
      } catch (modelErr) {
        console.warn('UploadModel.listarPorCnpj falhou, fallback para query raw:', modelErr && modelErr.message ? modelErr.message : modelErr);
      }
    }

    // 2) Fallback: consulta direta ao DB com JOIN para trazer dados do usuário e do setor
    const sql = `
      SELECT
        r.id,
        r.usuario_id,
        r.tipo_documento,
        r.caminho_arquivo,
        r.data_upload,
        r.status,
        r.nome_arquivo,
        r.setor_id,
        s.nome_setor AS setor_nome,
        u.id AS usuario_id,
        u.nome AS usuario_nome,
        u.setor AS usuario_setor,
        u.empresa_id AS usuario_empresa_id,
        u.cnpj AS usuario_cnpj
      FROM realizarupload r
      LEFT JOIN usuario u ON r.usuario_id = u.id
      LEFT JOIN setores s ON r.setor_id = s.id
      WHERE ${cnpj ? 'u.cnpj = ?' : 'u.empresa_id = ?'}
      ORDER BY r.data_upload DESC
    `;

    const param = cnpj || empresaId;
    const [rows] = await db.query(sql, [param]);

    const uploads = Array.isArray(rows) ? rows : [];
    return res.status(200).json({ success: true, uploads });
  } catch (err) {
    console.error('Erro listarUploadsPorCnpjGestor:', err && err.message ? err.message : err);
    return res.status(500).json({ success: false, message: 'Erro ao listar uploads por CNPJ.' });
  }
},


  // Função para download de arquivo
  async downloadArquivo(req, res) {
    try {
      const { id } = req.params;
      console.log('Download solicitado para ID:', id);

      const [arquivos] = await db.query("SELECT * FROM realizarupload WHERE id = ?", [id]);
      if (!arquivos || arquivos.length === 0) {
        return res.status(404).json({ success: false, message: "Arquivo não encontrado no banco de dados." });
      }

      const arquivo = arquivos[0];
      const filePath = path.resolve(arquivo.caminho_arquivo);
      console.log('Caminho do arquivo:', filePath);

      if (!fs.existsSync(filePath)) {
        return res.status(404).json({ success: false, message: "Arquivo não existe no servidor." });
      }

      return res.download(filePath, err => {
        if (err) {
          console.error("Erro ao enviar arquivo:", err);
          res.status(500).json({ success: false, message: "Erro ao fazer download do arquivo." });
        }
      });
    } catch (error) {
      console.error("Erro no controlador ao baixar arquivo:", error);
      res.status(500).json({ success: false, message: error.message });
    }
  },

  // Função para listar uploads do usuário logado
  async listarMeusUploads(req, res) {
    try {
      console.log('Usuario completo do token:', req.usuario);

      if (!req.usuario) {
        return res.status(401).json({ success: false, message: 'Usuário não autenticado.' });
      }

      let usuarioId;
      if (req.usuario.id) {
        usuarioId = req.usuario.id;
      } else if (req.usuario.numero_registro) {
        const [usuarios] = await db.query('SELECT id FROM usuario WHERE numero_registro = ?', [req.usuario.numero_registro]);
        if (usuarios.length === 0) {
          return res.status(404).json({ success: false, message: 'Usuário não encontrado no banco.' });
        }
        usuarioId = usuarios[0].id;
      } else if (req.usuario.cnpj) {
        const [usuarios] = await db.query('SELECT id FROM usuario WHERE cnpj = ?', [req.usuario.cnpj]);
        if (usuarios.length === 0) {
          return res.status(404).json({ success: false, message: 'Usuário não encontrado no banco.' });
        }
        usuarioId = usuarios[0].id;
      } else {
        return res.status(401).json({ success: false, message: 'Dados do usuário insuficientes.' });
      }

      console.log('Buscando uploads para usuarioId:', usuarioId);
      const uploads = await UploadModel.listarUploadsPorUsuario(usuarioId);
      res.status(200).json({ success: true, uploads });
    } catch (error) {
      console.error("Erro ao listar uploads:", error);
      res.status(500).json({ success: false, message: error.message });
    }
  },

  // Função para listar todos os documentos (adicionada)
  async listarTodos(req, res) {
    try {
      const documentos = await UploadModel.listarTodos();
      res.status(200).json({ success: true, documentos });
    } catch (error) {
      console.error('Erro ao listar documentos:', error);
      res.status(500).json({ success: false, message: 'Erro ao listar documentos.' });
    }
  }
  // controllers/uploadController.js
// Assumindo que já existe `const db = require('../config/db');` no topo do arquivo
,
async listarUploadsPorSetor(req, res) {
  try {
    const { setorId } = req.params;
    if (!setorId) return res.status(400).json({ success: false, message: 'Setor ID obrigatório.' });

    // Query ajustada para o schema da sua tabela `realizarupload`
    const sql = `
      SELECT 
        r.id,
        r.usuario_id,
        r.setor_id,
        r.tipo_documento,
        r.caminho_arquivo,
        r.data_upload,
        r.status,
        r.nome_arquivo,
        u.nome AS usuario_nome
      FROM realizarupload r
      LEFT JOIN usuario u ON u.id = r.usuario_id
      WHERE r.setor_id = ?
      ORDER BY r.data_upload DESC
    `;

    const [rows] = await db.query(sql, [setorId]);

    // Normaliza resposta: sempre retorna { success: true, uploads: [...] }
    return res.json({ success: true, uploads: rows || [] });
  } catch (err) {
    console.error('[listarUploadsPorSetor] Erro:', err);
    return res.status(500).json({ success: false, message: 'Erro ao listar uploads deste setor.' });
  }
}

};

module.exports = uploadController;
