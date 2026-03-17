// backend/routes/holeriteRoutes.js
const express = require('express');
const router = express.Router();
const holeriteController = require('../controllers/holeriteController');
const { verificarToken, autorizarTipoUsuario } = require('../middlewares/authMiddleware');
const db = require('../config/db'); // ADICIONAR ESTA LINHA

// Proteger todas as rotas
router.use(verificarToken);

/**
 * GET /api/holerites/colaborador/:id
 * Lista todos os holerites de um colaborador específico
 */
router.get('/colaborador/:id', 
  autorizarTipoUsuario(['gestor', 'colaborador']), 
  holeriteController.listarPorColaborador
);

/**
 * GET /api/holerites/empresa
 * Lista todos os holerites da empresa (apenas gestor)
 * Query params: ?setor=NomeSetor (opcional)
 */
router.get('/empresa', 
  autorizarTipoUsuario(['gestor']), 
  holeriteController.listarPorEmpresa
);

/**
 * POST /api/holerites/salvar
 * Salvar ou atualizar holerite (SEM AUTENTICAÇÃO PARA TESTE)
 */
router.post('/salvar', async (req, res) => {
    try {
        const {
            usuario_id,
            mes_referencia,
            salario_base,
            proventos,
            descontos,
            salario_liquido,
            arquivo_pdf_caminho
        } = req.body;

        console.log('Salvando holerite:', { usuario_id, mes_referencia });

        // Validações
        if (!usuario_id || !mes_referencia) {
            return res.status(400).json({
                success: false,
                erro: 'Usuário e mês de referência são obrigatórios'
            });
        }

        // Converter mes_referencia (YYYY-MM) para DATE (YYYY-MM-01)
        const [ano, mes] = mes_referencia.split('-');
        const mesReferenciaDate = `${ano}-${mes}-01`;

        // Converter arrays para JSON
        const proventosJSON = JSON.stringify(proventos || []);
        const descontosJSON = JSON.stringify(descontos || []);

        // Verificar se já existe holerite para este usuário e mês
        const [holeriteExistente] = await db.query(
            'SELECT id FROM visualizarholerites WHERE usuario_id = ? AND mes_referencia = ?',
            [usuario_id, mesReferenciaDate]
        );

        let result;

        if (holeriteExistente && holeriteExistente.length > 0) {
            // ATUALIZAR holerite existente
            console.log(' Atualizando holerite existente ID:', holeriteExistente[0].id);
            
            await db.query(
                `UPDATE visualizarholerites SET
                    salario_base = ?,
                    proventos_detalhe = ?,
                    descontos_detalhe = ?,
                    salario_liquido = ?,
                    arquivo_pdf_caminho = ?
                WHERE id = ?`,
                [
                    salario_base || 0,
                    proventosJSON,
                    descontosJSON,
                    salario_liquido || 0,
                    arquivo_pdf_caminho || null,
                    holeriteExistente[0].id
                ]
            );

            result = { id: holeriteExistente[0].id, acao: 'atualizado' };
        } else {
            // INSERIR novo holerite
            console.log('➕ Criando novo holerite');
            
            const [insertResult] = await db.query(
                `INSERT INTO visualizarholerites (
                    usuario_id,
                    mes_referencia,
                    salario_base,
                    proventos_detalhe,
                    descontos_detalhe,
                    salario_liquido,
                    arquivo_pdf_caminho
                ) VALUES (?, ?, ?, ?, ?, ?, ?)`,
                [
                    usuario_id,
                    mesReferenciaDate,
                    salario_base || 0,
                    proventosJSON,
                    descontosJSON,
                    salario_liquido || 0,
                    arquivo_pdf_caminho || null
                ]
            );

            result = { id: insertResult.insertId, acao: 'criado' };
        }

        console.log(' Holerite salvo com sucesso:', result);

        return res.json({
            success: true,
            mensagem: `Holerite ${result.acao} com sucesso!`,
            holerite_id: result.id
        });

    } catch (error) {
        console.error(' Erro ao salvar holerite:', error);
        return res.status(500).json({
            success: false,
            erro: 'Erro ao salvar holerite no banco de dados',
            detalhes: error.message
        });
    }
});

/**
 * GET /api/holerites/usuario/:usuario_id
 * Buscar holerites de um usuário
 */
router.get('/usuario/:usuario_id', async (req, res) => {
    try {
        const { usuario_id } = req.params;

        const [holerites] = await db.query(
            `SELECT 
                id,
                usuario_id,
                mes_referencia,
                salario_base,
                proventos_detalhe,
                descontos_detalhe,
                salario_liquido,
                arquivo_pdf_caminho,
                data_criacao,
                data_atualizacao
             FROM visualizarholerites 
             WHERE usuario_id = ? 
             ORDER BY mes_referencia DESC`,
            [usuario_id]
        );

        const holeritesParsed = holerites.map(h => ({
            id: h.id,
            usuario_id: h.usuario_id,
            mes_referencia: h.mes_referencia,
            salario_base: h.salario_base,
            proventos: JSON.parse(h.proventos_detalhe || '[]'),
            descontos: JSON.parse(h.descontos_detalhe || '[]'),
            salario_liquido: h.salario_liquido,
            arquivo_pdf_caminho: h.arquivo_pdf_caminho,
            data_criacao: h.data_criacao,
            data_atualizacao: h.data_atualizacao
        }));

        return res.json({
            success: true,
            total: holeritesParsed.length,
            holerites: holeritesParsed
        });

    } catch (error) {
        console.error(' Erro ao buscar holerites:', error);
        return res.status(500).json({
            success: false,
            erro: 'Erro ao buscar holerites',
            detalhes: error.message
        });
    }
});
// router.get('/usuario/:usuario_id', verificarToken, holeriteController.listarPorUsuario);

// na rota GET /api/colaborador/:id
router.get('/:id', verificarToken, async (req, res, next) => {
  const pedidoId = String(req.params.id);
  const userId = String(req.usuario && req.usuario.id);

  // permite se for o próprio colaborador ou se for gestor/admin
  if (userId === pedidoId || (req.usuario && req.usuario.tipo === 'gestor')) {
    return next();
  }
  return res.status(403).json({ success:false, erro: 'Acesso negado' });
}, holeriteController.buscarPorId);

/**
 * GET /api/holerites/:id
 * Busca detalhes de um holerite específico
 */
// router.get('/:id', 
//   holeriteController.buscarPorId
// );

/**
 * POST /api/holerites/criar
 * Cria um novo holerite (apenas gestor)
 */
router.post('/criar', 
  autorizarTipoUsuario(['gestor']), 
  holeriteController.criar
);

/**
 * PUT /api/holerites/:id
 * Atualiza um holerite existente (apenas gestor)
 */
router.put('/:id', 
  autorizarTipoUsuario(['gestor']), 
  holeriteController.atualizar
);

/**
 * DELETE /api/holerites/:id
 * Exclui um holerite (apenas gestor)
 */
router.delete('/:id', 
  autorizarTipoUsuario(['gestor']), 
  holeriteController.excluir
);

/**
 * GET /api/holerites/download/:id
 * Download do PDF do holerite
 */
router.get('/download/:id', 
  holeriteController.download
);
router.get('/:id', 
  verificarToken,
  async (req, res) => {
    try {
      const usuarioId = req.params.id;
      const usuarioRequisitante = req.usuario;

      console.log(`🔍 Buscando holerites do colaborador ${usuarioId}`);

      // Verificar permissão: usuário só pode ver seus próprios holerites, a menos que seja gestor
      if (usuarioRequisitante.id !== parseInt(usuarioId) && 
          usuarioRequisitante.tipo_usuario !== 'gestor') {
        return res.status(403).json({ 
          success: false, 
          erro: 'Você não tem permissão para acessar estes holerites' 
        });
      }

      // Buscar holerites do usuário
      const [holerites] = await db.query(
        `SELECT 
          id,
          usuario_id,
          mes_referencia,
          salario_base,
          proventos_detalhe,
          descontos_detalhe,
          salario_liquido,
          arquivo_pdf_caminho,
          data_criacao,
          data_atualizacao
        FROM visualizarholerites 
        WHERE usuario_id = ? 
        ORDER BY mes_referencia DESC`,
        [usuarioId]
      );

      if (!holerites || holerites.length === 0) {
        console.log(`⚠️ Nenhum holerite encontrado para usuário ${usuarioId}`);
        return res.json({
          success: true,
          total: 0,
          holerites: []
        });
      }

      // Parse dos dados JSON de proventos e descontos
      const holeritesParsed = holerites.map(h => ({
        id: h.id,
        usuario_id: h.usuario_id,
        mes_referencia: h.mes_referencia,
        salario_base: parseFloat(h.salario_base) || 0,
        proventos_detalhe: h.proventos_detalhe,
        descontos_detalhe: h.descontos_detalhe,
        salario_liquido: parseFloat(h.salario_liquido) || 0,
        arquivo_pdf_caminho: h.arquivo_pdf_caminho,
        data_criacao: h.data_criacao,
        data_atualizacao: h.data_atualizacao
      }));

      console.log(`✅ ${holeritesParsed.length} holerite(s) encontrado(s)`);

      return res.json({
        success: true,
        total: holeritesParsed.length,
        holerites: holeritesParsed
      });

    } catch (error) {
      console.error('❌ Erro ao buscar holerites:', error);
      return res.status(500).json({
        success: false,
        erro: 'Erro ao buscar holerites',
        detalhes: error.message
      });
    }
  }
);

module.exports = router;