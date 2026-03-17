// backend/models/solicitacoesModel.js
const db = require('../config/db');
const path = require('path');

const ALLOWED_TIPOS = [
  'ferias', 'alteracao_dados', 'consulta_banco_horas', 'banco_horas',
  'desligamento', 'reembolso', 'outros', 'reajuste_salarial'
];

const SolicitacaoModel = {

  /**
   * Encontra gestor_id para um usuario_id (por empresa_id)
   */
  async _findGestorIdForUsuario(usuarioId, conn = null) {
    if (!usuarioId) return null;
    
    const poolOrConn = conn || db;
    try {
      // Buscar gestor da mesma empresa
      const sql = `
        SELECT g.id
        FROM usuario g
        INNER JOIN usuario u ON u.empresa_id = g.empresa_id
        WHERE u.id = ? AND g.tipo_usuario = 'gestor'
        ORDER BY g.id
        LIMIT 1
      `;
      
      const [rows] = await poolOrConn.query(sql, [usuarioId]);
      return rows && rows.length ? rows[0].id : null;
    } catch (err) {
      console.error('Erro em _findGestorIdForUsuario:', err);
      return null;
    }
  },

  /**
   * Criar solicitação
   */
  async criar(payload = {}) {
    const {
      usuario_id,
      tipo_solicitacao,
      titulo = null,
      descricao = null,
      data_inicio = null,
      data_fim = null,
      salario_solicitado = null,
      justificativa = null,
      campo = null,
      novo_valor = null,
      periodo_inicio = null,
      periodo_fim = null,
      valor_reembolso = null,
      categoria_reembolso = null,
      data_desligamento = null,
      motivo_desligamento = null,
      anexos = []
    } = payload;

    // Validações
    if (!usuario_id || !tipo_solicitacao) {
      throw new Error('usuario_id e tipo_solicitacao são obrigatórios');
    }
    
    if (!ALLOWED_TIPOS.includes(tipo_solicitacao)) {
      throw new Error('tipo_solicitacao inválido: ' + tipo_solicitacao);
    }

    let connection = null;
    let useConn = false;
    
    try {
      // Obter conexão para transação
      if (typeof db.getConnection === 'function') {
        connection = await db.getConnection();
        useConn = true;
        await connection.beginTransaction();
      } else {
        connection = db;
      }

      // Buscar gestor responsável
      const gestorId = await this._findGestorIdForUsuario(usuario_id, connection);

      const now = new Date();
      const insertSql = `
        INSERT INTO realizarsolicitacoes
        (usuario_id, tipo_solicitacao, titulo, descricao, data_inicio, data_fim, status, created_at, gestor_id,
         salario_solicitado, justificativa, campo, novo_valor, periodo_inicio, periodo_fim,
         valor_reembolso, categoria_reembolso, data_desligamento, motivo_desligamento)
        VALUES (?, ?, ?, ?, ?, ?, 'pendente', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
      `;

      const params = [
        usuario_id, 
        tipo_solicitacao, 
        titulo, 
        descricao, 
        data_inicio, 
        data_fim,
        now, 
        gestorId,
        salario_solicitado, 
        justificativa, 
        campo, 
        novo_valor, 
        periodo_inicio, 
        periodo_fim,
        valor_reembolso, 
        categoria_reembolso, 
        data_desligamento, 
        motivo_desligamento
      ];

      console.log(' Inserindo solicitação no banco:', { usuario_id, tipo_solicitacao, gestorId });

      const [result] = await connection.query(insertSql, params);
      const solicitacaoId = result.insertId;

      if (!solicitacaoId) {
        throw new Error('Falha ao obter ID da solicitação criada');
      }

      // Inserir anexos se houver
      if (Array.isArray(anexos) && anexos.length > 0) {
        console.log(` Inserindo ${anexos.length} anexos para solicitação ${solicitacaoId}`);
        
        const values = anexos.map(a => [
          solicitacaoId,
          a.original_name || a.nome || a.filename || 'anexo',
          a.filename || a.path || `anexo_${Date.now()}`,
          a.mime || 'application/octet-stream',
          a.size || 0
        ]);

        await connection.query(
          `INSERT INTO solicitacao_anexos (solicitacao_id, nome, path, mime_type, size) VALUES ?`,
          [values]
        );
      }

      // Commit da transação
      if (useConn) {
        await connection.commit();
        connection.release();
      }

      console.log(' Solicitação criada com sucesso:', solicitacaoId);
      return { id: solicitacaoId };

      
    } catch (err) {
      // Rollback em caso de erro
      if (useConn && connection) {
        try { 
          await connection.rollback(); 
          connection.release(); 
        } catch (e) { 
          console.error('Erro no rollback:', e);
        }
      }
      console.error(' Erro em SolicitacaoModel.criar:', err);
      throw err;
    }
    
  },

  
  async listarPorGestor(options = {}) {
    try {
      const {
        status,
        setor,
        colaborador,
        q,
        limit = 100,
        offset = 0,
        order = 'r.created_at DESC',
        gestor_id 
      } = options;

      if (!gestor_id) {
        throw new Error('gestor_id é obrigatório para listar solicitações');
      }

      const where = ['r.gestor_id = ?']; 
      const params = [gestor_id];

      if (status && status !== 'all' && status !== '') {
        where.push('r.status = ?');
        params.push(status);
      }
      
      if (setor) {
        where.push('u.setor = ?');
        params.push(setor);
      }
      
      if (colaborador) {
        where.push('(u.nome LIKE ? OR u.id = ?)');
        params.push(`%${colaborador}%`, colaborador);
      }
      
      if (q) {
        where.push('(r.titulo LIKE ? OR r.descricao LIKE ? OR u.nome LIKE ? OR r.tipo_solicitacao LIKE ?)');
        params.push(`%${q}%`, `%${q}%`, `%${q}%`, `%${q}%`);
      }

      const whereSql = where.length ? `WHERE ${where.join(' AND ')}` : '';

      // Count total
      const countSql = `
        SELECT COUNT(*) as total
        FROM realizarsolicitacoes r
        LEFT JOIN usuario u ON r.usuario_id = u.id
        ${whereSql}
      `;

      console.log(' SQL Count:', countSql);
      console.log(' Params Count:', params);

      const [countRows] = await db.query(countSql, params);
      const total = countRows && countRows[0] ? countRows[0].total : 0;

      console.log(` Total de solicitações encontradas: ${total}`);

      // Dados
      const dataSql = `
        SELECT 
          r.id, 
          r.usuario_id, 
          r.tipo_solicitacao, 
          r.titulo, 
          r.descricao, 
          r.status, 
          r.created_at, 
          r.gestor_id, 
          r.data_inicio, 
          r.data_fim,
          r.observacao_gestor,
          u.nome AS colaborador_nome, 
          u.cargo AS colaborador_cargo, 
          u.setor AS colaborador_setor, 
          u.foto AS colaborador_foto
        FROM realizarsolicitacoes r
        LEFT JOIN usuario u ON r.usuario_id = u.id
        ${whereSql}
        ORDER BY ${order}
        LIMIT ? OFFSET ?
      `;

      console.log(' SQL Data:', dataSql);
      console.log(' Params Data:', [...params, parseInt(limit), parseInt(offset)]);

      const [rows] = await db.query(dataSql, [...params, parseInt(limit), parseInt(offset)]);

      console.log(` ${rows.length} solicitações retornadas da query`);

      // Buscar anexos
      const ids = rows.map(r => r.id).filter(Boolean);
      const anexosMap = {};
      
      if (ids.length > 0) {
        const [anexRows] = await db.query(
          `SELECT solicitacao_id, id, nome, path, mime_type, size 
           FROM solicitacao_anexos 
           WHERE solicitacao_id IN (?) 
           ORDER BY id`,
          [ids]
        );
        
        anexRows.forEach(a => {
          anexosMap[a.solicitacao_id] = anexosMap[a.solicitacao_id] || [];
          anexosMap[a.solicitacao_id].push({
            id: a.id,
            nome: a.nome,
            path: a.path,
            filename: a.path ? path.basename(a.path) : null,
            url: a.path ? `/uploads/${path.basename(a.path)}` : null,
            mime: a.mime_type,
            size: a.size
          });
        });
      }

      const items = rows.map(r => ({
        id: r.id,
        tipo: r.tipo_solicitacao,
        titulo: r.titulo,
        descricao: r.descricao,
        status: r.status,
        created_at: r.created_at,
        data_inicio: r.data_inicio,
        data_fim: r.data_fim,
        gestor_id: r.gestor_id,
        observacao_gestor: r.observacao_gestor,
        colaborador: {
          id: r.usuario_id,
          nome: r.colaborador_nome,
          cargo: r.colaborador_cargo,
          setor: r.colaborador_setor,
          foto: r.colaborador_foto
        },
        anexos: anexosMap[r.id] || []
      }));

      console.log(` ${items.length} solicitações formatadas para retorno`);

      return { total, rows: items };

    } catch (err) {
      console.error(' Erro em SolicitacaoModel.listarPorGestor:', err);
      throw err;
    }
  },

  /**
   * Listar solicitações por usuário
   */
  async listarPorUsuario(usuarioId, options = {}) {
    try {
      const {
        q,
        limit = 100,
        offset = 0,
        order = 'r.created_at DESC'
      } = options;

      const where = ['r.usuario_id = ?'];
      const params = [usuarioId];

      if (q && q.trim()) {
        where.push('(r.titulo LIKE ? OR r.descricao LIKE ? OR r.tipo_solicitacao LIKE ?)');
        params.push(`%${q}%`, `%${q}%`, `%${q}%`);
      }

      const whereSql = where.length ? `WHERE ${where.join(' AND ')}` : '';

      // Count
      const [countRows] = await db.query(
        `SELECT COUNT(*) as total
         FROM realizarsolicitacoes r
         ${whereSql}`,
        params
      );
      
      const total = countRows && countRows[0] ? countRows[0].total : 0;

      // Dados
      const [rows] = await db.query(
        `SELECT 
          r.id, r.usuario_id, r.tipo_solicitacao AS tipo, r.titulo, r.descricao, 
          r.status, r.created_at, r.gestor_id, r.data_inicio, r.data_fim,
          u.nome AS colaborador_nome, u.cargo AS colaborador_cargo, 
          u.setor AS colaborador_setor, u.foto AS colaborador_foto
         FROM realizarsolicitacoes r
         LEFT JOIN usuario u ON r.usuario_id = u.id
         ${whereSql}
         ORDER BY ${order}
         LIMIT ? OFFSET ?`,
        [...params, parseInt(limit), parseInt(offset)]
      );

      // Buscar anexos
      const ids = rows.map(r => r.id).filter(Boolean);
      const anexosMap = {};
      
      if (ids.length > 0) {
        const [anexRows] = await db.query(
          `SELECT solicitacao_id, id, nome, path, mime_type, size 
           FROM solicitacao_anexos 
           WHERE solicitacao_id IN (?) 
           ORDER BY id`,
          [ids]
        );
        
        anexRows.forEach(a => {
          anexosMap[a.solicitacao_id] = anexosMap[a.solicitacao_id] || [];
          anexosMap[a.solicitacao_id].push({
            id: a.id,
            nome: a.nome,
            path: a.path,
            filename: a.path ? path.basename(a.path) : null,
            url: a.path ? `/uploads/${path.basename(a.path)}` : null,
            mime: a.mime_type,
            size: a.size
          });
        });
      }

      const items = rows.map(r => ({
        id: r.id,
        tipo: r.tipo_solicitacao,
        titulo: r.titulo,
        descricao: r.descricao,
        status: r.status,
        created_at: r.created_at,
        data_inicio: r.data_inicio,
        data_fim: r.data_fim,
        gestor_id: r.gestor_id,
        colaborador: {
          id: r.usuario_id,
          nome: r.colaborador_nome,
          cargo: r.colaborador_cargo,
          setor: r.colaborador_setor,
          foto: r.colaborador_foto
        },
        anexos: anexosMap[r.id] || []
      }));

      console.log(` ${items.length} solicitações encontradas para usuário ${usuarioId}`);
      return { total, rows: items };

    } catch (err) {
      console.error(' Erro em SolicitacaoModel.listarPorUsuario:', err);
      throw err;
    }
  },

  /**
   * Buscar solicitação por ID
   */
  async buscarPorId(id) {
    if (!id) return null;
    
    try {
      const sql = `
        SELECT 
          r.id, r.usuario_id, r.tipo_solicitacao, r.titulo, r.descricao, 
          r.status, r.created_at, r.updated_at, r.data_inicio, r.data_fim, 
          r.observacao_gestor, r.gestor_id, r.salario_solicitado, r.justificativa, 
          r.campo, r.novo_valor, r.periodo_inicio, r.periodo_fim,
          r.valor_reembolso, r.categoria_reembolso, r.data_desligamento, r.motivo_desligamento,
          u.nome AS colaborador_nome,
          u.cargo AS colaborador_cargo,
          u.setor AS colaborador_setor,
          u.foto AS colaborador_foto,
          u.salario AS colaborador_salario,
          u.email AS colaborador_email,
          u.telefone AS colaborador_telefone,
          u.data_admissao AS colaborador_data_admissao,
          u.cnpj AS colaborador_cnpj,
          u.empresa_id AS colaborador_empresa_id,
          ug.nome AS gestor_nome,
          ug.cargo AS gestor_cargo,
          ug.setor AS gestor_setor,
          ug.foto AS gestor_foto
        FROM realizarsolicitacoes r
        LEFT JOIN usuario u ON r.usuario_id = u.id
        LEFT JOIN usuario ug ON r.gestor_id = ug.id
        WHERE r.id = ? 
        LIMIT 1
      `;

      const [rows] = await db.query(sql, [id]);
      
      if (!rows || rows.length === 0) {
        console.log('ℹ Solicitação não encontrada:', id);
        return null;
      }

      const item = rows[0];

      // Buscar anexos
      const [anexos] = await db.query(
        `SELECT id, nome, path, mime_type, size 
         FROM solicitacao_anexos 
         WHERE solicitacao_id = ? 
         ORDER BY id`,
        [id]
      );

      item.anexos = (anexos || []).map(a => ({
        id: a.id,
        nome: a.nome,
        path: a.path,
        filename: a.path ? path.basename(a.path) : null,
        url: a.path ? `/uploads/${path.basename(a.path)}` : null,
        mime: a.mime_type,
        size: a.size
      }));

      // Estruturar dados do colaborador
      item.colaborador = {
        id: item.usuario_id,
        nome: item.colaborador_nome,
        cargo: item.colaborador_cargo,
        setor: item.colaborador_setor,
        foto: item.colaborador_foto,
        salario: item.colaborador_salario,
        email: item.colaborador_email,
        telefone: item.colaborador_telefone,
        data_admissao: item.colaborador_data_admissao,
        cnpj: item.colaborador_cnpj,
        empresa_id: item.colaborador_empresa_id
      };

      // Estruturar dados do gestor
      item.gestor = item.gestor_id ? {
        id: item.gestor_id,
        nome: item.gestor_nome,
        cargo: item.gestor_cargo,
        setor: item.gestor_setor,
        foto: item.gestor_foto
      } : null;

      // Remover campos duplicados
      const camposParaRemover = [
        'colaborador_nome', 'colaborador_cargo', 'colaborador_setor', 'colaborador_foto',
        'colaborador_salario', 'colaborador_email', 'colaborador_telefone', 
        'colaborador_data_admissao', 'colaborador_cnpj', 'colaborador_empresa_id',
        'gestor_nome', 'gestor_cargo', 'gestor_setor', 'gestor_foto'
      ];

      camposParaRemover.forEach(campo => {
        delete item[campo];
      });

      console.log(' Solicitação encontrada:', item.id);
      return item;

    } catch (err) {
      console.error(' Erro em SolicitacaoModel.buscarPorId:', err);
      throw err;
    }
  },

  /**
   * Atualizar status da solicitação
   */
  async atualizarStatus(id, novoStatus, observacao = null, gestor_id = null) {
    try {
      const allowed = ['aprovada', 'reprovada', 'pendente', 'em_analise'];
      if (!allowed.includes(novoStatus)) {
        throw new Error('Status inválido. Permitidos: ' + allowed.join(', '));
      }

      const now = new Date();
      const setFields = ['status = ?', 'updated_at = ?'];
      const params = [novoStatus, now];

      if (observacao !== null) {
        setFields.push('observacao_gestor = ?');
        params.push(observacao);
      }

      if (gestor_id) {
        setFields.push('gestor_id = ?');
        params.push(gestor_id);
      }

      if (novoStatus === 'aprovada' || novoStatus === 'reprovada') {
        setFields.push('data_aprovacao_rejeicao = ?');
        params.push(now);
      }

      params.push(id);

      const sql = `UPDATE realizarsolicitacoes SET ${setFields.join(', ')} WHERE id = ?`;
      const [result] = await db.query(sql, params);

      console.log(` Status da solicitação ${id} atualizado para: ${novoStatus}`);

      // Log opcional (se a tabela existir)
      try {
        if (gestor_id) {
          await db.query(
            `INSERT INTO solicitacao_log (solicitacao_id, gestor_id, acao, observacao, created_at)
             VALUES (?, ?, ?, ?, ?)`,
            [id, gestor_id, `status:${novoStatus}`, observacao, now]
          );
        }
      } catch (logErr) {
        console.warn(' Não foi possível registrar log da solicitação:', logErr.message);
      }

      return { affectedRows: result.affectedRows };

    } catch (err) {
      console.error(' Erro em SolicitacaoModel.atualizarStatus:', err);
      throw err;
    }
  },

  /**
   * Adicionar anexos à solicitação
   */
  async adicionarAnexos(solicitacaoId, anexosArray = []) {
    if (!solicitacaoId) throw new Error('solicitacaoId obrigatório');
    if (!Array.isArray(anexosArray) || anexosArray.length === 0) {
      return [];
    }

    try {
      const values = anexosArray.map(a => [
        solicitacaoId,
        a.original_name || a.nome || a.filename || 'anexo',
        a.filename || a.path || `anexo_${Date.now()}`,
        a.mime || 'application/octet-stream',
        a.size || 0
      ]);

      console.log(` Adicionando ${values.length} anexos à solicitação ${solicitacaoId}`);

      await db.query(
        `INSERT INTO solicitacao_anexos (solicitacao_id, nome, path, mime_type, size) VALUES ?`,
        [values]
      );

      // Buscar anexos inseridos
      const [rows] = await db.query(
        `SELECT id, nome, path, mime_type, size 
         FROM solicitacao_anexos 
         WHERE solicitacao_id = ? 
         ORDER BY id DESC 
         LIMIT ?`,
        [solicitacaoId, anexosArray.length]
      );

      const inserted = (rows || []).map(a => ({
        id: a.id,
        nome: a.nome,
        path: a.path,
        filename: a.path ? path.basename(a.path) : null,
        url: a.path ? `/uploads/${path.basename(a.path)}` : null,
        mime: a.mime_type,
        size: a.size
      }));

      console.log(` ${inserted.length} anexos adicionados com sucesso`);
      return inserted;

    } catch (err) {
      console.error(' Erro em SolicitacaoModel.adicionarAnexos:', err);
      throw err;
    }
  },

  /**
   * Buscar anexo por ID
   */
  async buscarAnexoPorId(anexoId) {
    if (!anexoId) return null;
    
    try {
      const [rows] = await db.query(
        `SELECT id, solicitacao_id, nome, path, mime_type, size 
         FROM solicitacao_anexos 
         WHERE id = ? 
         LIMIT 1`,
        [anexoId]
      );
      
      if (!rows || rows.length === 0) return null;
      
      const a = rows[0];
      return {
        id: a.id,
        solicitacao_id: a.solicitacao_id,
        nome: a.nome,
        path: a.path,
        filename: a.path ? path.basename(a.path) : null,
        url: a.path ? `/uploads/${path.basename(a.path)}` : null,
        mime: a.mime_type,
        size: a.size
      };
    } catch (err) {
      console.error(' Erro em SolicitacaoModel.buscarAnexoPorId:', err);
      throw err;
    }
  },

  /**
   * Remover anexo
   */
  async removerAnexo(solicitacaoId, anexoIdOrFilename) {
    if (!solicitacaoId || !anexoIdOrFilename) {
      throw new Error('solicitacaoId e anexoIdOrFilename são obrigatórios');
    }

    try {
      let query, params;
      
      if (/^\d+$/.test(String(anexoIdOrFilename))) {
        // Buscar por ID numérico
        query = `SELECT id, solicitacao_id, nome, path 
                 FROM solicitacao_anexos 
                 WHERE id = ? AND solicitacao_id = ? 
                 LIMIT 1`;
        params = [anexoIdOrFilename, solicitacaoId];
      } else {
        // Buscar por nome/filename
        query = `SELECT id, solicitacao_id, nome, path 
                 FROM solicitacao_anexos 
                 WHERE solicitacao_id = ? AND (nome = ? OR path LIKE ?) 
                 LIMIT 1`;
        params = [solicitacaoId, anexoIdOrFilename, `%${anexoIdOrFilename}%`];
      }

      const [rows] = await db.query(query, params);
      
      if (!rows || rows.length === 0) {
        return null;
      }

      const anexo = rows[0];

      // Remover do banco
      await db.query(
        `DELETE FROM solicitacao_anexos WHERE id = ? AND solicitacao_id = ?`,
        [anexo.id, solicitacaoId]
      );

      console.log(` Anexo removido: ${anexo.id} da solicitação ${solicitacaoId}`);

      return {
        id: anexo.id,
        nome: anexo.nome,
        path: anexo.path,
        filename: anexo.path ? path.basename(anexo.path) : null,
        url: anexo.path ? `/uploads/${path.basename(anexo.path)}` : null
      };

    } catch (err) {
      console.error(' Erro em SolicitacaoModel.removerAnexo:', err);
      throw err;
    }
  },

  /**
   * Atualizar solicitação
   */
  async atualizar(id, campos = {}) {
    if (!id) throw new Error('ID obrigatório');
    
    const allowed = [
      'titulo', 'descricao', 'data_inicio', 'data_fim', 
      'tipo_solicitacao', 'status', 'observacao_gestor', 'gestor_id'
    ];
    
    const updates = [];
    const params = [];

    for (const campo of allowed) {
      if (Object.prototype.hasOwnProperty.call(campos, campo)) {
        updates.push(`${campo} = ?`);
        params.push(campos[campo]);
      }
    }

    if (updates.length === 0) {
      throw new Error('Nenhum campo permitido para atualizar');
    }

    params.push(new Date()); // updated_at
    params.push(id);

    const sql = `UPDATE realizarsolicitacoes SET ${updates.join(', ')}, updated_at = ? WHERE id = ?`;
    
    try {
      const [result] = await db.query(sql, params);
      console.log(` Solicitação ${id} atualizada: ${updates.length} campos modificados`);
      return { affectedRows: result.affectedRows };
    } catch (err) {
      console.error(' Erro em SolicitacaoModel.atualizar:', err);
      throw err;
    }
  },

  /**
   * Deletar solicitação
   */
  async deletar(id) {
    if (!id) throw new Error('ID obrigatório');
    
    try {
      // Buscar anexos antes de deletar
      const [anexos] = await db.query(
        `SELECT id, nome, path 
         FROM solicitacao_anexos 
         WHERE solicitacao_id = ?`,
        [id]
      );

      // Deletar anexos
      await db.query(`DELETE FROM solicitacao_anexos WHERE solicitacao_id = ?`, [id]);

      // Deletar solicitação
      const [result] = await db.query(`DELETE FROM realizarsolicitacoes WHERE id = ?`, [id]);

      console.log(` Solicitação ${id} deletada com ${anexos.length} anexos`);

      return {
        affectedRows: result.affectedRows,
        anexos: (anexos || []).map(a => ({
          id: a.id,
          nome: a.nome,
          path: a.path,
          filename: a.path ? path.basename(a.path) : null,
          url: a.path ? `/uploads/${path.basename(a.path)}` : null
        }))
      };

    } catch (err) {
      console.error(' Erro em SolicitacaoModel.deletar:', err);
      throw err;
    }
  }

};

module.exports = SolicitacaoModel;