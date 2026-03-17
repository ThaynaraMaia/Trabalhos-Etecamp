// backend/controllers/solicitacoesController.js
const SolicitacaoModel = require('../models/solicitacoesModel');
const path = require('path');
const fs = require('fs');
const multer = require('multer');
require('dotenv').config();
const jwt = require('jsonwebtoken');
const db = require('../config/db'); 

const ALLOWED_TIPOS = [
  'ferias','alteracao_dados','consulta_banco_horas','banco_horas',
  'desligamento','reembolso','outros','reajuste_salarial'
];

const UPLOADS_DIR = process.env.UPLOADS_DIR || path.resolve(__dirname, '..', 'uploads');

// Garante pasta de uploads
try {
  if (!fs.existsSync(UPLOADS_DIR)) fs.mkdirSync(UPLOADS_DIR, { recursive: true });
} catch (e) {
  console.error('Falha criando uploads dir:', UPLOADS_DIR, e);
}

// Multer config
const storage = multer.diskStorage({
  destination: (req, file, cb) => cb(null, UPLOADS_DIR),
  filename: (req, file, cb) => {
    const timestamp = Date.now();
    const safe = (file.originalname || 'anexo')
      .replace(/\s+/g, '_')
      .replace(/[^a-zA-Z0-9_\.\-]/g, '');
    cb(null, `${timestamp}_${safe}`);
  }
});

function fileFilter(req, file, cb) {
  const allowedRegex = /\.(pdf|jpe?g|png|gif)$/i;
  const original = file.originalname || '';
  if (!allowedRegex.test(original)) {
    return cb(new multer.MulterError('LIMIT_UNEXPECTED_FILE', 'Tipo de arquivo não permitido. Permitidos: pdf, jpg, jpeg, png, gif'));
  }
  cb(null, true);
}

const upload = multer({
  storage,
  limits: { fileSize: (process.env.MAX_UPLOAD_MB ? Number(process.env.MAX_UPLOAD_MB) : 8) * 1024 * 1024 },
  fileFilter
});

const uploadMiddleware = upload.fields([{ name: 'anexo', maxCount: 1 }, { name: 'anexos', maxCount: 8 }]);

/* =========================
   Helpers
   ========================= */
function sendError(res, code = 500, message = 'Erro interno', detail = null) {
  const payload = { success: false, message };
  if (detail) payload.detail = String(detail);
  return res.status(code).json(payload);
}

function extractUserFromReq(req) {
  if (!req) return null;
  
  // Primeiro tenta req.usuario (padrão do middleware)
  if (req.usuario && req.usuario.id) return req.usuario;
  
  // Depois req.user (alternativo)
  if (req.user && req.user.id) return req.user;

  // Tenta extrair do token JWT diretamente
  try {
    const authHeader = req.headers['authorization'] || req.headers['Authorization'];
    if (authHeader && typeof authHeader === 'string') {
      const token = authHeader.startsWith('Bearer ') ? authHeader.substring(7) : authHeader;
      if (token) {
        const payload = jwt.verify(token, process.env.JWT_SECRET || 'troque_essa_chave_em_producao');
        if (payload && (payload.id || payload.sub)) {
          return { 
            id: payload.id || payload.sub, 
            tipo_usuario: payload.tipo_usuario,
            empresa_id: payload.empresa_id,
            cnpj: payload.cnpj,
            ...payload 
          };
        }
      }
    }
  } catch (err) {
    console.warn('Falha ao extrair usuário do token:', err.message);
  }
  
  return null;
}

function isGestor(user) {
  if (!user) return false;
  
  const tipo = (user.tipo_usuario || user.role || user.tipo || '').toString().toLowerCase();
  if (['gestor', 'admin', 'manager'].includes(tipo)) return true;
  
  if (Array.isArray(user.roles) && user.roles.some(r => 
    ['gestor','admin','manager'].includes(String(r).toLowerCase()))
  ) return true;
  
  if (Array.isArray(user.papeis) && user.papeis.some(r => 
    ['gestor','admin','manager'].includes(String(r).toLowerCase()))
  ) return true;
  
  return false;
}

function buildAnexosFromFiles(filesArray = [], req = null) {
  return (filesArray || []).map(f => {
    const filename = f.filename || path.basename(f.path || '');
    const relative = `/uploads/${filename}`;
    const absolute = req ? `${req.protocol}://${req.get('host')}${relative}` : relative;
    return {
      original_name: f.originalname || '',
      filename,
      mime: f.mimetype || '',
      size: f.size || 0,
      url: absolute,
      path: f.path || null
    };
  });
}


function tryUnlink(fileFullPath) {
  try {
    if (fileFullPath && fs.existsSync(fileFullPath)) {
      fs.unlinkSync(fileFullPath);
      return true;
    }
  } catch (err) {
    console.warn('Falha ao remover arquivo:', fileFullPath, err);
  }
  return false;
}

/* =========================
   Controller
   ========================= */
const controller = {
  uploadMiddleware,

  
  async criar(req, res) {
    try {
      const user = extractUserFromReq(req);
      if (!user || !user.id) {
        return sendError(res, 401, 'Usuário não autenticado');
      }

      let tipo_solicitacao = (req.body.tipo_solicitacao || req.body.tipo || '').toString().trim();
      if (tipo_solicitacao === 'banco_horas') tipo_solicitacao = 'consulta_banco_horas';
      
      if (!tipo_solicitacao || !ALLOWED_TIPOS.includes(tipo_solicitacao)) {
        return sendError(res, 400, 'Tipo de solicitação inválido');
      }

      // Coletar campos
      const titulo = (req.body.titulo || req.body.title || '').toString().trim() || null;
      const descricaoRaw = req.body.descricao || req.body.descricao_text || req.body.observacao || req.body.description || null;
      const descricao = (typeof descricaoRaw === 'string' && descricaoRaw.trim().length) ? descricaoRaw.trim() : null;

      // Coletar campos específicos por tipo de solicitação
      const data_inicio = req.body.data_inicio || req.body.periodo_inicio || null;
      const data_fim = req.body.data_fim || req.body.periodo_fim || null;
      const salario_solicitado = req.body.salario_solicitado || null;
      const justificativa = req.body.justificativa || null;
      const campo = req.body.campo || null;
      const novo_valor = req.body.novo_valor || null;
      const periodo_inicio = req.body.periodo_inicio || null;
      const periodo_fim = req.body.periodo_fim || null;
      const valor_reembolso = req.body.valor_reembolso || null;
      const categoria_reembolso = req.body.categoria_reembolso || null;
      const data_desligamento = req.body.data_desligamento || null;
      const motivo_desligamento = req.body.motivo_desligamento || null;

      // Coletar arquivos
      let uploaded = [];
      if (req.files) {
        if (typeof req.files === 'object' && !Array.isArray(req.files)) {
          Object.keys(req.files).forEach(k => {
            const entry = req.files[k];
            if (Array.isArray(entry)) {
              uploaded = uploaded.concat(entry);
            } else if (entry) {
              uploaded.push(entry);
            }
          });
        } else if (Array.isArray(req.files)) {
          uploaded = req.files;
        }
      } else if (req.file) {
        uploaded = [req.file];
      }

      const anexos = buildAnexosFromFiles(uploaded);

      const payload = {
        usuario_id: user.id,
        tipo_solicitacao,
        titulo,
        descricao,
        data_inicio,
        data_fim,
        salario_solicitado,
        justificativa,
        campo,
        novo_valor,
        periodo_inicio,
        periodo_fim,
        valor_reembolso,
        categoria_reembolso,
        data_desligamento,
        motivo_desligamento,
        anexos
      };

      console.log(' Criando solicitação:', { usuario_id: user.id, tipo_solicitacao, titulo });

      const created = await SolicitacaoModel.criar(payload);
      
      if (!created || !created.id) {
        throw new Error('Falha ao criar solicitação no banco de dados');
      }

      // Buscar solicitação completa para retornar
      const solicitacaoFinal = await SolicitacaoModel.buscarPorId(created.id);
      
      if (!solicitacaoFinal) {
        throw new Error('Solicitação criada mas não encontrada ao buscar detalhes');
      }

      return res.status(201).json({ 
        success: true, 
        message: 'Solicitação criada com sucesso',
        data: solicitacaoFinal 
      });

    } catch (err) {
      console.error(' Erro em solicitacoesController.criar:', err);
      return sendError(res, 500, 'Erro ao criar solicitação', err.message);
    }
  },

  /**
   * Listar solicitações do usuário autenticado (COLABORADOR)
   */
  async listarMe(req, res) {
    try {
      const user = extractUserFromReq(req);
      if (!user || !user.id) {
        return sendError(res, 401, 'Usuário não autenticado');
      }

      const limit = Math.max(1, Math.min(1000, parseInt(req.query.limit || '100', 10)));
      const offset = Math.max(0, parseInt(req.query.offset || '0', 10));
      const q = req.query.q || undefined;

      console.log(' Listando solicitações do usuário:', { usuario_id: user.id, limit, offset });

      const result = await SolicitacaoModel.listarPorUsuario(user.id, { q, limit, offset });

      console.log(`${result.rows.length} solicitações encontradas para o usuário`);

      return res.json({ 
        success: true, 
        total: result.total, 
        solicitacoes: result.rows 
      });

    } catch (err) {
      console.error(' Erro em solicitacoesController.listarMe:', err);
      return sendError(res, 500, 'Erro ao listar suas solicitações', err.message);
    }
  },

 
  async listarSolicitacoesGestor(req, res) {
    try {
      const user = extractUserFromReq(req);
      
      console.log(' Usuario extraído:', user);
      
      if (!user || !user.id) {
        return sendError(res, 401, 'Usuário não autenticado');
      }

      if (!isGestor(user)) {
        return sendError(res, 403, 'Acesso negado: requer papel gestor');
      }

      const opts = {
        status: req.query.status || undefined,
        setor: req.query.setor || undefined,
        colaborador: req.query.colaborador || undefined,
        q: req.query.q || undefined,
        limit: Math.max(1, Math.min(2000, parseInt(req.query.limit || '100', 10))),
        offset: Math.max(0, parseInt(req.query.offset || '0', 10)),
        order: req.query.order || 'r.created_at DESC',
        gestor_id: user.id 
      };

      console.log(' Listando solicitações do gestor:', opts);

      const result = await SolicitacaoModel.listarPorGestor(opts);
      
      console.log(` ${result.rows.length} solicitações encontradas para o gestor ${user.id}`);

      return res.json({ 
        success: true, 
        total: result.total, 
        solicitacoes: result.rows 
      });

    } catch (err) {
      console.error(' Erro em solicitacoesController.listarSolicitacoesGestor:', err);
      return sendError(res, 500, 'Erro ao listar solicitações', err.message);
    }
  },

  /**
   * Listar todas as solicitações (ADMIN/GESTOR - com filtros opcionais)
   */
  async listarTodos(req, res) {
    try {
      const user = extractUserFromReq(req);
      if (!user || !isGestor(user)) {
        return sendError(res, 403, 'Acesso negado: requer papel gestor');
      }

      const opts = {
        status: req.query.status || undefined,
        setor: req.query.setor || undefined,
        colaborador: req.query.colaborador || undefined,
        q: req.query.q || undefined,
        limit: Math.max(1, Math.min(2000, parseInt(req.query.limit || '100', 10))),
        offset: Math.max(0, parseInt(req.query.offset || '0', 10)),
        order: req.query.order || 'r.created_at DESC',
        gestor_id: user.id 
      };

      console.log(' Listando todas as solicitações (gestor):', opts);

      const result = await SolicitacaoModel.listarPorGestor(opts);
      
      console.log(` ${result.rows.length} solicitações encontradas no total`);

      return res.json({ 
        success: true, 
        total: result.total, 
        solicitacoes: result.rows 
      });

    } catch (err) {
      console.error(' Erro em solicitacoesController.listarTodos:', err);
      return sendError(res, 500, 'Erro ao listar solicitações', err.message);
    }
  },

  /**
   * Buscar detalhes por ID
   */
  async getById(req, res) {
    try {
      const { id } = req.params;
      if (!id) {
        return sendError(res, 400, 'ID obrigatório');
      }

      console.log(' Buscando solicitação por ID:', id);

      const item = await SolicitacaoModel.buscarPorId(id);
      if (!item) {
        return sendError(res, 404, 'Solicitação não encontrada');
      }

      console.log('Solicitação encontrada:', item.id);

      return res.json({ 
        success: true, 
        solicitacao: item 
      });

    } catch (err) {
      console.error('Erro em solicitacoesController.getById:', err);
      return sendError(res, 500, 'Erro ao buscar solicitação', err.message);
    }
  },

  /**
   * Atualizar status (apenas gestores)
   */
 async  atualizarStatus(req, res) {
    try {
        const { id } = req.params;
        const { status, observacao } = req.body;
        const gestorId = req.usuario.id;

        console.log(`Atualizando status da solicitação ${id} para: ${status}`);

        // Validações
        const statusPermitidos = ['pendente', 'aprovada', 'reprovada', 'em_analise'];
        if (!statusPermitidos.includes(status)) {
            return res.status(400).json({
                success: false,
                error: 'Status inválido'
            });
        }

        // Buscar solicitação
        const [solicitacoes] = await db.query(
            'SELECT * FROM realizarsolicitacoes WHERE id = ?',
            [id]
        );

        if (solicitacoes.length === 0) {
            return res.status(404).json({
                success: false,
                error: 'Solicitação não encontrada'
            });
        }

        // Atualizar status
        await db.query(`
            UPDATE realizarsolicitacoes 
            SET status = ?, 
                observacao_gestor = ?,
                data_aprovacao_rejeicao = NOW(),
                gestor_id = ?,
                updated_at = NOW()
            WHERE id = ?
        `, [status, observacao || null, gestorId, id]);

        let resultado = {
            success: true,
            message: `Status atualizado para ${status}`,
            status: status
        };

        // 🔥 NOVIDADE: Se aprovado, processar automaticamente
        if (status === 'aprovada') {
            try {
                const resultadoProcessamento = await processarAprovacaoAutomatica(id, gestorId);
                
                resultado = {
                    ...resultado,
                    processamento: resultadoProcessamento,
                    message: 'Solicitação aprovada e processada automaticamente com sucesso!'
                };
                
                console.log(' Solicitação processada automaticamente:', resultadoProcessamento);
            } catch (error) {
                console.error('⚠️ Erro no processamento automático:', error);
                // Não falhar a aprovação se o processamento der erro
                resultado.warning = 'Solicitação aprovada, mas houve erro no processamento automático: ' + error.message;
            }
        }

        res.json(resultado);

    } catch (error) {
        console.error(' Erro ao atualizar status:', error);
        res.status(500).json({
            success: false,
            error: 'Erro ao atualizar status da solicitação',
            details: error.message
        });
    }
},

  /**
   * Adicionar anexos a solicitação existente
   */
  async adicionarAnexos(req, res) {
    try {
      const user = extractUserFromReq(req);
      const { id } = req.params;
      
      if (!user || !user.id) {
        return sendError(res, 401, 'Usuário não autenticado');
      }
      if (!id) {
        return sendError(res, 400, 'ID obrigatório');
      }

      const solicitacao = await SolicitacaoModel.buscarPorId(id);
      if (!solicitacao) {
        return sendError(res, 404, 'Solicitação não encontrada');
      }

      const isOwner = String(solicitacao.usuario_id) === String(user.id);
      if (!isOwner && !isGestor(user)) {
        return sendError(res, 403, 'Permissão negada para anexar arquivos');
      }

      // Coletar arquivos
      let uploaded = [];
      if (req.files) {
        if (typeof req.files === 'object' && !Array.isArray(req.files)) {
          Object.keys(req.files).forEach(k => {
            const entry = req.files[k];
            if (Array.isArray(entry)) {
              uploaded = uploaded.concat(entry);
            } else if (entry) {
              uploaded.push(entry);
            }
          });
        } else if (Array.isArray(req.files)) {
          uploaded = req.files;
        }
      }

      if (uploaded.length === 0) {
        return sendError(res, 400, 'Nenhum arquivo enviado');
      }

      const anexos = buildAnexosFromFiles(uploaded);
      console.log(` Adicionando ${anexos.length} anexos à solicitação ${id}`);

      const inserted = await SolicitacaoModel.adicionarAnexos(id, anexos);

      return res.json({ 
        success: true, 
        message: 'Anexos adicionados com sucesso', 
        anexos: inserted 
      });

    } catch (err) {
      console.error(' Erro em solicitacoesController.adicionarAnexos:', err);
      return sendError(res, 500, 'Erro ao adicionar anexos', err.message);
    }
  },

  /**
   * Remover anexo
   */
  async removerAnexo(req, res) {
    try {
      const user = extractUserFromReq(req);
      const { id, anexoId } = req.params;
      
      if (!user || !user.id) {
        return sendError(res, 401, 'Usuário não autenticado');
      }
      if (!id || !anexoId) {
        return sendError(res, 400, 'ID da solicitação e do anexo são obrigatórios');
      }

      const solicitacao = await SolicitacaoModel.buscarPorId(id);
      if (!solicitacao) {
        return sendError(res, 404, 'Solicitação não encontrada');
      }

      const isOwner = String(solicitacao.usuario_id) === String(user.id);
      if (!isOwner && !isGestor(user)) {
        return sendError(res, 403, 'Permissão negada para remover anexo');
      }

      console.log(` Removendo anexo ${anexoId} da solicitação ${id}`);

      const anexoMeta = await SolicitacaoModel.buscarAnexoPorId(anexoId);

      if (!anexoMeta) {
        return sendError(res, 404, 'Anexo não encontrado');
      }

      const removed = await SolicitacaoModel.removerAnexo(id, anexoId);

      // Remover arquivo físico
      if (anexoMeta.filename) {
        const fullPath = path.join(UPLOADS_DIR, anexoMeta.filename);
        tryUnlink(fullPath);
      }

      return res.json({ 
        success: true, 
        message: 'Anexo removido com sucesso', 
        anexo: removed 
      });

    } catch (err) {
      console.error(' Erro em solicitacoesController.removerAnexo:', err);
      return sendError(res, 500, 'Erro ao remover anexo', err.message);
    }
  },

  /**
   * Servir arquivo anexo
   */
 async serveAnexo(req, res) {
  try {
    const safeFilename = path.basename(req.params.filename);
    const fullPath = path.join(UPLOADS_DIR, safeFilename);
    if (!fs.existsSync(fullPath)) {
      return res.status(404).send('Arquivo não encontrado');
    }

    // SETA HEADERS DE CROSS-ORIGIN NECESSÁRIOS
 // em solicitacoesController.serveAnexo (ou similar)
res.setHeader('Cross-Origin-Resource-Policy', 'cross-origin');
res.setHeader('Access-Control-Allow-Origin', process.env.FRONTEND_URL || 'http://localhost:3000');
res.setHeader('Cache-Control', 'public, max-age=86400');

    return res.sendFile(fullPath);
  } catch (err) {
    console.error(' Erro em solicitacoesController.serveAnexo:', err);
    return res.status(500).send('Erro interno do servidor');
  }
}
,

  /**
   * Atualizar solicitação
   */
  async atualizar(req, res) {
    try {
      const user = extractUserFromReq(req);
      const { id } = req.params;
      
      if (!user || !user.id) {
        return sendError(res, 401, 'Usuário não autenticado');
      }
      if (!id) {
        return sendError(res, 400, 'ID obrigatório');
      }

      const solicitacao = await SolicitacaoModel.buscarPorId(id);
      if (!solicitacao) {
        return sendError(res, 404, 'Solicitação não encontrada');
      }

      const isOwner = String(solicitacao.usuario_id) === String(user.id);
      if (!isOwner && !isGestor(user)) {
        return sendError(res, 403, 'Permissão negada para atualizar');
      }

      // Campos permitidos para atualização
      const allowedFields = ['titulo', 'descricao', 'data_inicio', 'data_fim'];
      const payload = {};
      
      for (const field of allowedFields) {
        if (req.body[field] !== undefined) {
          payload[field] = req.body[field];
        }
      }

      if (Object.keys(payload).length === 0) {
        return sendError(res, 400, 'Nenhum campo válido para atualizar');
      }

      console.log(' Atualizando solicitação:', { id, payload });

      const result = await SolicitacaoModel.atualizar(id, payload);
      
      if (result && (result.affectedRows > 0 || result.changedRows > 0)) {
        const solicitacaoAtualizada = await SolicitacaoModel.buscarPorId(id);
        return res.json({ 
          success: true, 
          message: 'Solicitação atualizada com sucesso',
          data: solicitacaoAtualizada 
        });
      } else {
        return sendError(res, 404, 'Solicitação não encontrada ou não alterada');
      }

    } catch (err) {
      console.error(' Erro em solicitacoesController.atualizar:', err);
      return sendError(res, 500, 'Erro ao atualizar solicitação', err.message);
    }
  },

  /**
   * Deletar solicitação
   */
  async deletar(req, res) {
    try {
      const user = extractUserFromReq(req);
      const { id } = req.params;
      
      if (!user || !user.id) {
        return sendError(res, 401, 'Usuário não autenticado');
      }
      if (!id) {
        return sendError(res, 400, 'ID obrigatório');
      }

      const solicitacao = await SolicitacaoModel.buscarPorId(id);
      if (!solicitacao) {
        return sendError(res, 404, 'Solicitação não encontrada');
      }

      const isOwner = String(solicitacao.usuario_id) === String(user.id);
      if (!isOwner && !isGestor(user)) {
        return sendError(res, 403, 'Permissão negada para deletar');
      }

      console.log(` Deletando solicitação: ${id}`);

      // Remover anexos físicos
      const anexos = solicitacao.anexos || [];
      for (const anexo of anexos) {
        if (anexo.filename) {
          const fullPath = path.join(UPLOADS_DIR, anexo.filename);
          tryUnlink(fullPath);
        }
      }

      await SolicitacaoModel.deletar(id);

      return res.json({ 
        success: true, 
        message: 'Solicitação deletada com sucesso' 
      });

    } catch (err) {
      console.error(' Erro em solicitacoesController.deletar:', err);
      return sendError(res, 500, 'Erro ao deletar solicitação', err.message);
    }
  }
}

async function processarAprovacaoAutomatica(solicitacaoId, gestorId) {
    const connection = await db.getConnection();
    
    try {
        await connection.beginTransaction();
        
        // Buscar detalhes completos da solicitação
        const [solicitacoes] = await connection.query(`
            SELECT 
                s.*,
                u.id as usuario_id,
                u.nome as usuario_nome,
                u.email as usuario_email,
                u.cargo as usuario_cargo,
                u.setor as usuario_setor,
                u.salario as salario_atual,
                u.telefone as usuario_telefone
            FROM realizarsolicitacoes s
            INNER JOIN usuario u ON s.usuario_id = u.id
            WHERE s.id = ?
        `, [solicitacaoId]);
        
        if (solicitacoes.length === 0) {
            throw new Error('Solicitação não encontrada');
        }
        
        const solicitacao = solicitacoes[0];
        let mensagemNotificacao = '';
        let dadosAtualizados = {};
        
        // Processar conforme tipo de solicitação
        switch (solicitacao.tipo_solicitacao.toLowerCase()) {
            case 'reajuste_salarial':
                dadosAtualizados = await processarReajusteSalarial(connection, solicitacao);
                mensagemNotificacao = gerarMensagemReajuste(solicitacao, dadosAtualizados);
                break;
                
            case 'ferias':
                dadosAtualizados = await processarFerias(connection, solicitacao);
                mensagemNotificacao = gerarMensagemFerias(solicitacao, dadosAtualizados);
                break;
                
            case 'alteracao_dados':
                dadosAtualizados = await processarAlteracaoDados(connection, solicitacao);
                mensagemNotificacao = gerarMensagemAlteracaoDados(solicitacao, dadosAtualizados);
                break;
                
            case 'consulta_banco_horas':
            case 'banco_horas':
                dadosAtualizados = await consultarBancoHoras(connection, solicitacao);
                mensagemNotificacao = gerarMensagemBancoHoras(solicitacao, dadosAtualizados);
                break;
                
            case 'reembolso':
                mensagemNotificacao = gerarMensagemReembolso(solicitacao);
                break;
                
            case 'desligamento':
                dadosAtualizados = await processarDesligamento(connection, solicitacao);
                mensagemNotificacao = gerarMensagemDesligamento(solicitacao, dadosAtualizados);
                break;
                
            default:
                mensagemNotificacao = `Sua solicitação de ${solicitacao.tipo_solicitacao} foi aprovada pelo gestor.`;
        }
        
        // Criar notificação para o usuário
        await criarNotificacao(connection, {
            usuario_id: solicitacao.usuario_id,
            tipo: 'aprovacao_solicitacao',
            titulo: `Solicitação Aprovada: ${solicitacao.tipo_solicitacao}`,
            mensagem: mensagemNotificacao,
            solicitacao_id: solicitacaoId,
            dados_adicionais: JSON.stringify(dadosAtualizados)
        });
        
        // Registrar log da ação
        await registrarLogSolicitacao(connection, {
            solicitacao_id: solicitacaoId,
            gestor_id: gestorId,
            acao: 'aprovacao_automatica',
            observacao: `Solicitação aprovada e processada automaticamente. ${Object.keys(dadosAtualizados).length > 0 ? 'Dados atualizados no sistema.' : ''}`
        });
        
        await connection.commit();
        
        return {
            success: true,
            mensagem: mensagemNotificacao,
            dadosAtualizados
        };
        
    } catch (error) {
        await connection.rollback();
        console.error(' Erro ao processar aprovação automática:', error);
        throw error;
    } finally {
        connection.release();
    }
}


async function processarReajusteSalarial(connection, solicitacao) {
    const novoSalario = parseFloat(solicitacao.salario_solicitado);
    const salarioAnterior = parseFloat(solicitacao.salario_atual);
    
    if (!novoSalario || novoSalario <= 0) {
        throw new Error('Salário solicitado inválido');
    }
    
    // Atualizar salário do usuário
    await connection.query(`
        UPDATE usuario 
        SET salario = ?
        WHERE id = ?
    `, [novoSalario, solicitacao.usuario_id]);
    
    // Registrar histórico de alteração salarial
    await connection.query(`
        INSERT INTO historico_salario (
            usuario_id, 
            salario_anterior, 
            salario_novo, 
            data_alteracao, 
            motivo, 
            aprovado_por
        ) VALUES (?, ?, ?, NOW(), ?, ?)
    `, [
        solicitacao.usuario_id,
        salarioAnterior,
        novoSalario,
        solicitacao.justificativa || 'Reajuste salarial aprovado',
        solicitacao.gestor_id
    ]);
    
    const percentualAumento = ((novoSalario - salarioAnterior) / salarioAnterior * 100).toFixed(2);
    
    return {
        salario_anterior: salarioAnterior,
        salario_novo: novoSalario,
        diferenca: novoSalario - salarioAnterior,
        percentual: percentualAumento,
        data_vigencia: new Date().toISOString().split('T')[0]
    };
}

/**
 * Processa solicitação de férias
 */
async function processarFerias(connection, solicitacao) {
    const dataInicio = new Date(solicitacao.data_inicio);
    const dataFim = new Date(solicitacao.data_fim);
    
    // Calcular dias de férias
    const diasFerias = Math.ceil((dataFim - dataInicio) / (1000 * 60 * 60 * 24)) + 1;
    
    // Registrar férias na tabela de controle
    const [resultado] = await connection.query(`
        INSERT INTO ferias (
            usuario_id,
            data_inicio,
            data_fim,
            dias_corridos,
            status,
            aprovado_por,
            data_aprovacao
        ) VALUES (?, ?, ?, ?, 'aprovado', ?, NOW())
    `, [
        solicitacao.usuario_id,
        solicitacao.data_inicio,
        solicitacao.data_fim,
        diasFerias,
        solicitacao.gestor_id
    ]);
    
    // Calcular prazo CLT (comunicação 30 dias antes)
    const diasAteInicio = Math.ceil((dataInicio - new Date()) / (1000 * 60 * 60 * 24));
    const prazoConforme = diasAteInicio >= 30;
    
    return {
        data_inicio: solicitacao.data_inicio,
        data_fim: solicitacao.data_fim,
        dias_ferias: diasFerias,
        ferias_id: resultado.insertId,
        prazo_conforme_clt: prazoConforme,
        dias_ate_inicio: diasAteInicio,
        periodo_gozo: `${formatarData(dataInicio)} a ${formatarData(dataFim)}`
    };
}


async function processarAlteracaoDados(connection, solicitacao) {
    const campo = solicitacao.campo;
    const novoValor = solicitacao.novo_valor;
    
    // Mapear campos permitidos para alteração
    const camposPermitidos = {
        'telefone': 'telefone',
        'email': 'email',
        'endereco': 'endereco',
        'cep': 'cep',
        'cidade': 'cidade',
        'estado': 'estado'
    };
    
    const campoNormalizado = campo.toLowerCase().trim();
    const campoDb = camposPermitidos[campoNormalizado];
    
    if (!campoDb) {
        throw new Error(`Campo '${campo}' não pode ser alterado automaticamente`);
    }
    
    // Buscar valor anterior
    const [usuario] = await connection.query(
        `SELECT ${campoDb} as valor_anterior FROM usuario WHERE id = ?`,
        [solicitacao.usuario_id]
    );
    
    const valorAnterior = usuario[0]?.valor_anterior || null;
    
    // Atualizar campo
    await connection.query(
        `UPDATE usuario SET ${campoDb} = ? WHERE id = ?`,
        [novoValor, solicitacao.usuario_id]
    );
    
    // Registrar histórico de alteração
    await connection.query(`
        INSERT INTO historico_alteracoes (
            usuario_id,
            campo_alterado,
            valor_anterior,
            valor_novo,
            data_alteracao,
            aprovado_por
        ) VALUES (?, ?, ?, ?, NOW(), ?)
    `, [
        solicitacao.usuario_id,
        campo,
        valorAnterior,
        novoValor,
        solicitacao.gestor_id
    ]);
    
    return {
        campo: campo,
        valor_anterior: valorAnterior,
        valor_novo: novoValor,
        data_alteracao: new Date().toISOString().split('T')[0]
    };
}

/**
 * Consulta banco de horas
 */
async function consultarBancoHoras(connection, solicitacao) {
    const dataInicio = solicitacao.periodo_inicio || new Date(new Date().getFullYear(), 0, 1);
    const dataFim = solicitacao.periodo_fim || new Date();
    
    // Buscar registros de ponto no período
    const [registros] = await connection.query(`
        SELECT 
            DATE(data_registro) as data,
            MIN(CASE WHEN tipo_registro = 'entrada' THEN data_registro END) as entrada,
            MAX(CASE WHEN tipo_registro = 'saida' THEN data_registro END) as saida,
            COUNT(*) as total_registros
        FROM pontos
        WHERE usuario_id = ?
        AND data_registro BETWEEN ? AND ?
        GROUP BY DATE(data_registro)
        ORDER BY data
    `, [solicitacao.usuario_id, dataInicio, dataFim]);
    
    // Calcular saldo de horas
    let totalHorasTrabalhadas = 0;
    let totalHorasEsperadas = 0;
    
    registros.forEach(reg => {
        if (reg.entrada && reg.saida) {
            const entrada = new Date(reg.entrada);
            const saida = new Date(reg.saida);
            const horasTrabalhadas = (saida - entrada) / (1000 * 60 * 60);
            totalHorasTrabalhadas += horasTrabalhadas;
        }
        // Assumir 8h por dia como esperado
        totalHorasEsperadas += 8;
    });
    
    const saldoHoras = totalHorasTrabalhadas - totalHorasEsperadas;
    
    return {
        periodo_inicio: formatarData(dataInicio),
        periodo_fim: formatarData(dataFim),
        dias_trabalhados: registros.length,
        horas_trabalhadas: totalHorasTrabalhadas.toFixed(2),
        horas_esperadas: totalHorasEsperadas.toFixed(2),
        saldo_horas: saldoHoras.toFixed(2),
        saldo_tipo: saldoHoras > 0 ? 'positivo' : saldoHoras < 0 ? 'negativo' : 'zerado'
    };
}

/**
 * Processa desligamento
 */
async function processarDesligamento(connection, solicitacao) {
    const dataDesligamento = solicitacao.data_desligamento || new Date();
    
    // Marcar usuário como inativo (não deletar)
    await connection.query(`
        UPDATE usuario 
        SET 
            ativo = 0,
            data_desligamento = ?,
            motivo_desligamento = ?
        WHERE id = ?
    `, [
        dataDesligamento,
        solicitacao.motivo_desligamento,
        solicitacao.usuario_id
    ]);
    
    return {
        data_desligamento: formatarData(dataDesligamento),
        motivo: solicitacao.motivo_desligamento,
        status: 'desligado'
    };
}

/**
 * Cria notificação para o usuário
 */
async function criarNotificacao(connection, dados) {
    // Verificar se tabela de notificações existe
    const [tables] = await connection.query(`
        SHOW TABLES LIKE 'notificacoes'
    `);
    
    if (tables.length === 0) {
        // Criar tabela se não existir
        await connection.query(`
            CREATE TABLE IF NOT EXISTS notificacoes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                usuario_id INT NOT NULL,
                tipo VARCHAR(50) NOT NULL,
                titulo VARCHAR(255) NOT NULL,
                mensagem TEXT NOT NULL,
                solicitacao_id INT,
                lida TINYINT(1) DEFAULT 0,
                dados_adicionais JSON,
                criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
            )
        `);
    }
    
    await connection.query(`
        INSERT INTO notificacoes (
            usuario_id,
            tipo,
            titulo,
            mensagem,
            solicitacao_id,
            dados_adicionais
        ) VALUES (?, ?, ?, ?, ?, ?)
    `, [
        dados.usuario_id,
        dados.tipo,
        dados.titulo,
        dados.mensagem,
        dados.solicitacao_id,
        dados.dados_adicionais
    ]);
}

/**
 * Registra log da ação
 */
async function registrarLogSolicitacao(connection, dados) {
    await connection.query(`
        INSERT INTO solicitacao_log (
            solicitacao_id,
            gestor_id,
            acao,
            observacao,
            created_at
        ) VALUES (?, ?, ?, ?, NOW())
    `, [
        dados.solicitacao_id,
        dados.gestor_id,
        dados.acao,
        dados.observacao
    ]);
}

// ==================== FUNÇÕES DE GERAÇÃO DE MENSAGENS ====================

function gerarMensagemReajuste(solicitacao, dados) {
    return ` Boa notícia! Seu pedido de reajuste salarial foi APROVADO!

 Detalhes do Reajuste:
• Salário Anterior: ${formatarMoeda(dados.salario_anterior)}
• Novo Salário: ${formatarMoeda(dados.salario_novo)}
• Aumento: ${formatarMoeda(dados.diferenca)} (${dados.percentual}%)
• Vigência: A partir de ${dados.data_vigencia}

O novo valor já está atualizado em seu cadastro e será aplicado na próxima folha de pagamento.

Parabéns pelo reconhecimento! `;
}

function gerarMensagemFerias(solicitacao, dados) {
    const avisoAcesso = dados.dias_ate_inicio <= 7 ? 
        '\n\n IMPORTANTE: Seu acesso ao sistema será bloqueado automaticamente durante o período de férias.' : 
        '\n\n Seu acesso ao sistema será bloqueado automaticamente quando suas férias iniciarem.';
    
    const avisoPrazoCLT = !dados.prazo_conforme_clt ? 
        '\n\n Nota: Conforme CLT, o ideal é comunicar férias com 30 dias de antecedência. Seu período foi aprovado com ' + dados.dias_ate_inicio + ' dias de antecedência.' : 
        '';
    
    return ` Suas férias foram APROVADAS!

Período de Gozo:
• Data Início: ${formatarData(solicitacao.data_inicio)}
• Data Fim: ${formatarData(solicitacao.data_fim)}
• Total: ${dados.dias_ferias} dias

 Status: Conforme CLT${avisoAcesso}${avisoPrazoCLT}

Aproveite seu descanso merecido! `;
}

function gerarMensagemAlteracaoDados(solicitacao, dados) {
    return ` Alteração de dados aprovada e processada!

 Detalhes da Alteração:
• Campo: ${dados.campo}
• Valor Anterior: ${dados.valor_anterior || 'Não informado'}
• Novo Valor: ${dados.valor_novo}
• Data da Alteração: ${dados.data_alteracao}

Seus dados foram atualizados com sucesso no sistema.`;
}

function gerarMensagemBancoHoras(solicitacao, dados) {
    const emoji = dados.saldo_tipo === 'positivo' ? '' : 
                  dados.saldo_tipo === 'negativo' ? '' : 'ℹ';
    
    const mensagemSaldo = dados.saldo_tipo === 'positivo' ? 
        'Você possui horas extras acumuladas!' :
        dados.saldo_tipo === 'negativo' ?
        'Você possui horas a compensar.' :
        'Seu saldo está zerado.';
    
    return `${emoji} Consulta de Banco de Horas

 Período Analisado: ${dados.periodo_inicio} a ${dados.periodo_fim}

 Resumo:
• Dias Trabalhados: ${dados.dias_trabalhados}
• Horas Trabalhadas: ${dados.horas_trabalhadas}h
• Horas Esperadas: ${dados.horas_esperadas}h
• Saldo: ${dados.saldo_horas}h

${mensagemSaldo}

Para mais detalhes, consulte seu gestor ou o setor de RH.`;
}

function gerarMensagemReembolso(solicitacao) {
    return ` Seu pedido de reembolso foi aprovado!

 Detalhes:
• Valor: ${formatarMoeda(solicitacao.valor_reembolso)}
• Categoria: ${solicitacao.categoria_reembolso || 'Não especificada'}

O valor será processado e creditado conforme os procedimentos internos da empresa.`;
}

function gerarMensagemDesligamento(solicitacao, dados) {
    return ` Sua solicitação de desligamento foi processada.

 Data do Desligamento: ${dados.data_desligamento}
 Status: ${dados.status}

Agradecemos sua contribuição e desejamos sucesso em seus próximos desafios!`;
}

// ==================== UTILITÁRIOS ====================

function formatarData(data) {
    if (!data) return 'N/A';
    const d = new Date(data);
    return d.toLocaleDateString('pt-BR');
}

function formatarMoeda(valor) {
    if (valor == null) return 'R$ 0,00';
    return Number(valor).toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });
}

module.exports = controller;