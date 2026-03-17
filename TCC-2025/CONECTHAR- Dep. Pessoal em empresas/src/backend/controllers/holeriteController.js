// backend/controllers/holeriteController.js
const HoleriteModel = require('../models/holeriteModel');
const db = require('../config/db');
const path = require('path');
const fs = require('fs');

const holeriteController = {
  // ... (mantive todas suas outras funções iguais, só a função buscarPorId foi tornada mais resiliente)
  async listarPorColaborador(req, res) {
    try {
      const colaboradorId = parseInt(req.params.id, 10);
      
      if (req.usuario && req.usuario.tipo_usuario === 'colaborador' && req.usuario.id !== colaboradorId) {
        return res.status(403).json({ 
          success: false, 
          message: 'Você não tem permissão para acessar estes holerites' 
        });
      }

      const holerites = await HoleriteModel.visualizarMeusHolerites(colaboradorId);
      
      return res.json({
        success: true,
        holerites: holerites
      });
    } catch (err) {
      console.error(' Erro em listarPorColaborador:', err);
      return res.status(500).json({ 
        success: false, 
        message: 'Erro ao buscar holerites' 
      });
    }
  },

  async listarPorEmpresa(req, res) {
    try {
      const empresaId = req.usuario.empresa_id;
      const setorFiltro = req.query.setor;

      let sql = `
        SELECT 
          h.id,
          h.colaborador_id,
          h.mes_referencia,
          h.salario,
          h.arquivo_pdf,
          h.criado_em,
          u.nome as colaborador_nome,
          u.cargo,
          u.setor,
          u.numero_registro
        FROM visualizarholerites h
        INNER JOIN usuario u ON h.colaborador_id = u.id
        WHERE u.empresa_id = ?
      `;
      
      const params = [empresaId];

      if (setorFiltro && setorFiltro !== 'Todos') {
        sql += ' AND u.setor = ?';
        params.push(setorFiltro);
      }

      sql += ' ORDER BY h.mes_referencia DESC, u.nome ASC';

      const [rows] = await db.query(sql, params);

      const holeritesPorColaborador = {};
      
      rows.forEach(row => {
        if (!holeritesPorColaborador[row.colaborador_id]) {
          holeritesPorColaborador[row.colaborador_id] = {
            colaborador_id: row.colaborador_id,
            colaborador_nome: row.colaborador_nome,
            cargo: row.cargo,
            setor: row.setor,
            numero_registro: row.numero_registro,
            holerites: []
          };
        }
        
        holeritesPorColaborador[row.colaborador_id].holerites.push({
          id: row.id,
          mes_referencia: row.mes_referencia,
          salario: row.salario,
          arquivo_pdf: row.arquivo_pdf,
          criado_em: row.criado_em
        });
      });

      return res.json({
        success: true,
        data: Object.values(holeritesPorColaborador)
      });
    } catch (err) {
      console.error(' Erro em listarPorEmpresa:', err);
      return res.status(500).json({ 
        success: false, 
        message: 'Erro ao buscar holerites da empresa' 
      });
    }
  },

  async criar(req, res) {
    try {
      const { colaborador_id, mes_referencia, salario, arquivo_pdf } = req.body;

      if (!colaborador_id || !mes_referencia || !salario || !arquivo_pdf) {
        return res.status(400).json({ 
          success: false, 
          message: 'Campos obrigatórios ausentes: colaborador_id, mes_referencia, salario, arquivo_pdf' 
        });
      }

      const [colaborador] = await db.query(
        'SELECT id FROM usuario WHERE id = ? AND empresa_id = ?',
        [colaborador_id, req.usuario.empresa_id]
      );

      if (!colaborador || colaborador.length === 0) {
        return res.status(404).json({ 
          success: false, 
          message: 'Colaborador não encontrado ou não pertence à sua empresa' 
        });
      }

      const result = await HoleriteModel.criarHolerite({
        colaborador_id,
        mes_referencia,
        salario,
        arquivo_pdf
      });

      return res.status(201).json({
        success: true,
        message: 'Holerite criado com sucesso',
        holerite_id: result.insertId
      });
    } catch (err) {
      console.error(' Erro em criar:', err);
      return res.status(500).json({ 
        success: false, 
        message: 'Erro ao criar holerite: ' + err.message 
      });
    }
  },

  async atualizar(req, res) {
    try {
      const holeriteId = parseInt(req.params.id, 10);
      const { salario, arquivo_pdf } = req.body;

      if (!holeriteId) {
        return res.status(400).json({ 
          success: false, 
          message: 'ID do holerite é obrigatório' 
        });
      }

      const [holerite] = await db.query(`
        SELECT h.id 
        FROM visualizarholerites h
        INNER JOIN usuario u ON h.colaborador_id = u.id
        WHERE h.id = ? AND u.empresa_id = ?
      `, [holeriteId, req.usuario.empresa_id]);

      if (!holerite || holerite.length === 0) {
        return res.status(404).json({ 
          success: false, 
          message: 'Holerite não encontrado ou não pertence à sua empresa' 
        });
      }

      await HoleriteModel.atualizarHolerite({
        id: holeriteId,
        salario,
        arquivo_pdf
      });

      return res.json({
        success: true,
        message: 'Holerite atualizado com sucesso'
      });
    } catch (err) {
      console.error(' Erro em atualizar:', err);
      return res.status(500).json({ 
        success: false, 
        message: 'Erro ao atualizar holerite: ' + err.message 
      });
    }
  },

  async excluir(req, res) {
    try {
      const holeriteId = parseInt(req.params.id, 10);

      if (!holeriteId) {
        return res.status(400).json({ 
          success: false, 
          message: 'ID do holerite é obrigatório' 
        });
      }

      const [holerite] = await db.query(`
        SELECT h.id, h.arquivo_pdf
        FROM visualizarholerites h
        INNER JOIN usuario u ON h.colaborador_id = u.id
        WHERE h.id = ? AND u.empresa_id = ?
      `, [holeriteId, req.usuario.empresa_id]);

      if (!holerite || holerite.length === 0) {
        return res.status(404).json({ 
          success: false, 
          message: 'Holerite não encontrado ou não pertence à sua empresa' 
        });
      }

      if (holerite[0].arquivo_pdf) {
        const filePath = path.join(__dirname, '..', holerite[0].arquivo_pdf);
        if (fs.existsSync(filePath)) {
          try {
            fs.unlinkSync(filePath);
            console.log(' Arquivo PDF removido:', filePath);
          } catch (err) {
            console.warn('⚠️ Não foi possível remover o arquivo PDF:', err.message);
          }
        }
      }

      await HoleriteModel.excluirHolerite(holeriteId);

      return res.json({
        success: true,
        message: 'Holerite excluído com sucesso'
      });
    } catch (err) {
      console.error(' Erro em excluir:', err);
      return res.status(500).json({ 
        success: false, 
        message: 'Erro ao excluir holerite: ' + err.message 
      });
    }
  },

  async download(req, res) {
  try {
    const holeriteId = parseInt(req.params.id, 10);
    const usuarioId = req.usuario.id;
    const tipoUsuario = req.usuario.tipo_usuario;

    if (!holeriteId) {
      return res.status(400).json({
        success: false,
        message: 'ID do holerite é obrigatório'
      });
    }

    const [holeriteRows] = await db.query(`
      SELECT h.*, h.usuario_id as colaborador_id, u.empresa_id, u.id as usuario_id, u.nome as colaborador_nome
      FROM visualizarholerites h
      INNER JOIN usuario u ON h.usuario_id = u.id
      WHERE h.id = ?
    `, [holeriteId]);

    if (!holeriteRows || holeriteRows.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Holerite não encontrado'
      });
    }

    const holeriteData = holeriteRows[0];

    if (tipoUsuario === 'colaborador' && holeriteData.colaborador_id !== usuarioId) {
      return res.status(403).json({
        success: false,
        message: 'Você não tem permissão para acessar este holerite'
      });
    }

    if (tipoUsuario === 'gestor' && holeriteData.empresa_id !== req.usuario.empresa_id) {
      return res.status(403).json({
        success: false,
        message: 'Você não tem permissão para acessar este holerite'
      });
    }

    // usa o campo do dump: arquivo_pdf_caminho
    if (!holeriteData.arquivo_pdf_caminho) {
      return res.status(404).json({
        success: false,
        message: 'PDF não disponível para este holerite'
      });
    }

    const filePath = path.join(__dirname, '..', holeriteData.arquivo_pdf_caminho);

    if (!fs.existsSync(filePath)) {
      return res.status(404).json({
        success: false,
        message: 'Arquivo PDF não encontrado no servidor'
      });
    }

    const mesRef = holeriteData.mes_referencia
      ? new Date(holeriteData.mes_referencia).toISOString().slice(0, 7).replace('-', '_')
      : 'sem_data';
    const nomeColaborador = holeriteData.colaborador_nome
      ? holeriteData.colaborador_nome.replace(/\s+/g, '_').toLowerCase()
      : 'colaborador';
    const fileName = `holerite_${nomeColaborador}_${mesRef}.pdf`;

    res.download(filePath, fileName, (err) => {
      if (err) {
        console.error(' Erro ao fazer download:', err);
        if (!res.headersSent) {
          return res.status(500).json({
            success: false,
            message: 'Erro ao fazer download do arquivo'
          });
        }
      }
    });
  } catch (err) {
    console.error(' Erro em download:', err);
    if (!res.headersSent) {
      return res.status(500).json({
        success: false,
        message: 'Erro ao processar download: ' + err.message
      });
    }
  }
},

  // ============================
  // Função atualizada e mais tolerante: buscarPorId
  // ============================
  async buscarPorId(req, res) {
  try {
    const getIntParam = () => {
      const candidates = [
        req.params && (req.params.id || req.params.holerite_id || req.params.holeriteId),
        req.query && (req.query.id || req.query.holeriteId),
        req.body && (req.body.id || req.body.holeriteId)
      ];
      for (const c of candidates) {
        if (c === undefined || c === null) continue;
        const parsed = parseInt(String(c), 10);
        if (!Number.isNaN(parsed)) return parsed;
      }
      return null;
    };

    const holeriteId = getIntParam();

    console.debug('buscarPorId -> params:', req.params, 'query:', req.query, 'bodyKeys:', typeof req.body === 'object' ? Object.keys(req.body) : req.body, '-> holeriteId:', holeriteId);

    if (!holeriteId) {
      return res.status(400).json({
        success: false,
        message: 'ID do holerite é obrigatório (verifique param /query /body)'
      });
    }

    const [holeriteRows] = await db.query(`
      SELECT 
        h.*,
        h.usuario_id as colaborador_id,           -- aqui mapeio pra manter compatibilidade
        u.nome as colaborador_nome,
        u.cargo,
        u.setor,
        u.numero_registro,
        u.empresa_id
      FROM visualizarholerites h
      INNER JOIN usuario u ON h.usuario_id = u.id   -- uso usuario_id (não colaborador_id)
      WHERE h.id = ?
    `, [holeriteId]);

    if (!holeriteRows || holeriteRows.length === 0) {
      console.debug('buscarPorId: holerite não encontrado por id, tentando tratar como usuario_id (colaborador)...');
      const maybeColabId = holeriteId;
      const [rowsByColab] = await db.query(`
        SELECT h.*, h.usuario_id as colaborador_id, u.nome as colaborador_nome, u.empresa_id
        FROM visualizarholerites h
        INNER JOIN usuario u ON h.usuario_id = u.id
        WHERE h.usuario_id = ?
        ORDER BY h.mes_referencia DESC
        LIMIT 1
      `, [maybeColabId]);

      if (rowsByColab && rowsByColab.length > 0) {
        const first = rowsByColab[0];
        return res.json({
          success: true,
          holerite: first,
          note: 'Retornado o último holerite do colaborador (o id enviado parecia ser um usuario_id). Verifique a rota/front-end.'
        });
      }

      return res.status(404).json({
        success: false,
        message: 'Holerite não encontrado'
      });
    }

    const holeriteData = holeriteRows[0];
// dentro de buscarPorId, quando não achar por id e fizer a busca por usuario_id:
if (rowsByColab && rowsByColab.length > 0) {
  // Retorna TODOS os holerites do colaborador (ou pode limitar se quiser)
  return res.json({
    success: true,
    holerites: rowsByColab, // <--- agora é array compatível com o front
    note: 'Retornado holerites do colaborador (o id enviado parecia ser um usuario_id). Verifique a rota/front-end.'
  });
}
    // permissões: uso colaborador_id mapeado a partir de usuario_id
    if (req.usuario && req.usuario.tipo_usuario === 'colaborador' &&
        holeriteData.colaborador_id !== req.usuario.id) {
      return res.status(403).json({
        success: false,
        message: 'Você não tem permissão para acessar este holerite'
      });
    }

    if (req.usuario && req.usuario.tipo_usuario === 'gestor' &&
        holeriteData.empresa_id !== req.usuario.empresa_id) {
      return res.status(403).json({
        success: false,
        message: 'Este holerite não pertence à sua empresa'
      });
    }

    return res.json({
      success: true,
      holerite: holeriteData
    });
  } catch (err) {
    console.error(' Erro em buscarPorId:', err);
    return res.status(500).json({
      success: false,
      message: 'Erro ao buscar holerite: ' + err.message
    });
  }
}
};
// controllers/holeriteController.js
exports.listarPorUsuario = async (req, res) => {
  const usuarioId = req.params.usuario_id;
  const [rows] = await db.query(
    'SELECT * FROM holerites WHERE usuario_id = ? ORDER BY data_criacao DESC',
    [usuarioId]
  );
  return res.json({ success: true, holerites: rows });
};

module.exports = holeriteController;
