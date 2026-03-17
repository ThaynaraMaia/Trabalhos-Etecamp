// backend/routes/gestorApiRoutes.js - VERSÃO UNIFICADA E COMPLETA
const express = require('express');
const router = express.Router();
const db = require('../config/db');
const { verificarToken, autorizarTipoUsuario } = require('../middlewares/authMiddleware');
const gestorController = require('../controllers/gestorController');
const solicitacoesController = require('../controllers/solicitacoesController');

// ==========================================
// MIDDLEWARE: Aplicar em todas as rotas
// ==========================================
router.use(verificarToken);
router.use(autorizarTipoUsuario(['gestor']));

// ==========================================
// ROTAS DO PERFIL DO GESTOR
// ==========================================

/**
 * @route   GET /api/gestor/me
 * @desc    Obter dados do gestor logado
 * @access  Privado (Gestor)
 */
router.get('/me', gestorController.me);

// ==========================================
// ROTAS DE ESTATÍSTICAS
// ==========================================

/**
 * @route   GET /api/gestor/stats
 * @desc    Estatísticas gerais do dashboard
 * @access  Privado (Gestor)
 */
router.get('/stats', async (req, res) => {
  try {
    const empresaId = req.usuario?.empresa_id || req.user?.empresa_id;
    
    if (!empresaId) {
      return res.status(400).json({
        success: false,
        message: 'Empresa não identificada'
      });
    }

    console.log(' Buscando stats para empresa:', empresaId);

    // Buscar estatísticas de colaboradores
    const [stats] = await db.query(`
      SELECT 
        COUNT(*) as total,
        COALESCE(SUM(salario), 0) as total_salarios,
        COALESCE(AVG(salario), 0) as media_salarios
      FROM usuario
      WHERE empresa_id = ? 
        AND tipo_usuario = 'colaborador'
    `, [empresaId]);

    // Buscar total de setores
    const [setoresCount] = await db.query(`
      SELECT COUNT(*) as total_setores
      FROM setores
      WHERE empresa_id = ?
    `, [empresaId]);

    const result = stats[0] || { total: 0, total_salarios: 0, media_salarios: 0 };
    const setoresResult = setoresCount[0] || { total_setores: 0 };

    console.log('Stats encontradas:', result);

    return res.json({
      success: true,
      data: {
        total_colaboradores: Number(result.total) || 0,
        total_salarios: Number(result.total_salarios) || 0,
        media_salarios: Number(result.media_salarios) || 0,
        total_setores: Number(setoresResult.total_setores) || 0,
        total_folha: Number(result.total_salarios) || 0
      }
    });
  } catch (err) {
    console.error('❌ Erro /api/gestor/stats:', err);
    return res.status(500).json({
      success: false,
      error: err.message
    });
  }
});

/**
 * @route   GET /api/gestor/analytics/stats
 * @desc    Estatísticas avançadas para análise de dados
 * @access  Privado (Gestor)
 */
router.get('/analytics/stats', async (req, res) => {
  try {
    const cnpj = req.usuario.cnpj;
    const empresaId = req.usuario.empresa_id;
    
    // Query para absenteísmo
    const [absenteismo] = await db.query(`
      SELECT 
        COUNT(DISTINCT usuario_id) as total_ausencias,
        (SELECT COUNT(*) FROM usuario WHERE empresa_id = ? AND tipo_usuario = 'colaborador') as total_funcionarios
      FROM pontos 
      WHERE cnpj = ? 
        AND tipo_registro = 'saida'
        AND MONTH(data_registro) = MONTH(CURRENT_DATE())
    `, [empresaId, cnpj]);
    
    const totalFunc = absenteismo[0]?.total_funcionarios || 0;
    const indiceAbsenteismo = totalFunc > 0 
      ? ((absenteismo[0].total_ausencias / totalFunc) * 100).toFixed(1)
      : 0;
    
    // Query para solicitações
    const [solicitacoes] = await db.query(`
      SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'aprovada' THEN 1 ELSE 0 END) as aprovadas
      FROM realizarsolicitacoes 
      WHERE gestor_id = ?
        AND MONTH(data_solicitacao) = MONTH(CURRENT_DATE())
    `, [req.usuario.id]);
    
    const totalSolicitacoes = solicitacoes[0]?.total || 0;
    const percentualAprovado = totalSolicitacoes > 0
      ? Math.round((solicitacoes[0].aprovadas / totalSolicitacoes) * 100)
      : 0;
    
    res.json({
      success: true,
      data: {
        absenteismo: indiceAbsenteismo,
        turnover: 0, // Calcular se tiver coluna data_demissao
        solicitacoes: totalSolicitacoes,
        solicitacoes_aprovadas_percentual: percentualAprovado
      }
    });
    
  } catch (error) {
    console.error('❌ Erro ao buscar estatísticas de análise:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Erro ao buscar estatísticas' 
    });
  }
});

/**
 * @route   GET /api/gestor/analytics/relatorios
 * @desc    Listar relatórios disponíveis por categoria
 * @access  Privado (Gestor)
 */
router.get('/analytics/relatorios', async (req, res) => {
  try {
    const tipo = req.query.tipo; // financeiro, jornada, gestao, documentos
    
    const relatorios = {
      financeiro: [
        { id: 'folha', nome: 'Folha de Pagamento', disponivel: true },
        { id: 'custos', nome: 'Custos com Pessoal', disponivel: true },
        { id: 'beneficios', nome: 'Benefícios', disponivel: true }
      ],
      jornada: [
        { id: 'ponto', nome: 'Controle de Ponto', disponivel: true },
        { id: 'horas-extras', nome: 'Horas Extras', disponivel: true },
        { id: 'absenteismo', nome: 'Absenteísmo', disponivel: true }
      ],
      gestao: [
        { id: 'turnover', nome: 'Turnover', disponivel: true },
        { id: 'ferias', nome: 'Férias', disponivel: true },
        { id: 'contratos', nome: 'Vencimento de Contratos', disponivel: true },
        { id: 'aniversariantes', nome: 'Aniversariantes', disponivel: true }
      ],
      documentos: [
        { id: 'pendentes', nome: 'Documentos Pendentes', disponivel: true },
        { id: 'treinamentos', nome: 'Treinamentos', disponivel: true }
      ]
    };
    
    if (tipo && relatorios[tipo]) {
      res.json({ success: true, relatorios: relatorios[tipo] });
    } else {
      res.json({ success: true, relatorios });
    }
    
  } catch (error) {
    console.error('❌ Erro ao buscar relatórios:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Erro ao buscar relatórios' 
    });
  }
});

// ==========================================
// ROTAS DE SETORES
// ==========================================

/**
 * @route   GET /api/gestor/setores
 * @desc    Listar setores da empresa do gestor
 * @access  Privado (Gestor)
 */
router.get('/setores', async (req, res) => {
  try {
    const empresaId = req.usuario?.empresa_id || req.user?.empresa_id;

    if (!empresaId) {
      console.warn('⚠️ Empresa ID não encontrado, retornando setores globais');
      const [rows] = await db.query('SELECT id, nome_setor, descricao FROM setores ORDER BY nome_setor');
      return res.json({ success: true, setores: rows });
    }

    const [rows] = await db.query(
      'SELECT id, nome_setor, descricao FROM setores WHERE empresa_id = ? ORDER BY nome_setor',
      [empresaId]
    );
    
    console.log(`${rows.length} setores encontrados para empresa ${empresaId}`);
    return res.json({ success: true, setores: rows });
    
  } catch (err) {
    console.error('❌ Erro /api/gestor/setores:', err);
    return res.status(500).json({ success: false, error: err.message });
  }
});

// ==========================================
// ROTAS DE COLABORADORES
// ==========================================

/**
 * @route   GET /api/gestor/colaboradores
 * @desc    Listar colaboradores da empresa (básico)
 * @access  Privado (Gestor)
 */
router.get('/colaboradores', async (req, res) => {
  try {
    const empresaId = req.usuario?.empresa_id || req.user?.empresa_id;
    const filtroSetor = req.query.setor;

    if (!empresaId) {
      return res.json({ success: true, colaboradores: [] });
    }

    let sql = `
      SELECT 
        id, nome, cargo, salario, setor, foto, 
        tipo_usuario, numero_registro, cpf, email,
        tipo_jornada, horas_diarias, data_admissao, telefone
      FROM usuario
      WHERE empresa_id = ? AND tipo_usuario = 'colaborador'
    `;
    const params = [empresaId];
    
    if (filtroSetor && filtroSetor !== 'Todos') { 
      sql += ' AND setor = ?'; 
      params.push(filtroSetor); 
    }
    
    sql += ' ORDER BY nome';
    
    const [rows] = await db.query(sql, params);
    console.log(`${rows.length} colaboradores encontrados`);
    
    return res.json({ 
      success: true, 
      colaboradores: rows.map(c => ({
        ...c,
        salario: parseFloat(c.salario) || 0
      }))
    });
    
  } catch (err) {
    console.error('❌ Erro /api/gestor/colaboradores:', err);
    return res.status(500).json({ success: false, error: err.message });
  }
});

/**
 * @route   GET /api/gestor/colaboradores-com-beneficios
 * @desc    🔥 NOVO: Listar colaboradores COM seus benefícios ativos
 * @access  Privado (Gestor)
 */
router.get('/colaboradores-com-beneficios', async (req, res) => {
  try {
    const empresaId = req.usuario.empresa_id;

    console.log(' Buscando colaboradores com benefícios para empresa:', empresaId);

    // Buscar colaboradores
    const queryColaboradores = `
      SELECT 
        u.id,
        u.nome,
        u.cargo,
        u.setor,
        u.salario,
        u.data_admissao,
        u.numero_registro,
        u.email,
        u.telefone,
        u.foto,
        u.cpf,
        u.tipo_jornada,
        u.horas_diarias
      FROM usuario u
      WHERE u.empresa_id = ? AND u.tipo_usuario = 'colaborador'
      ORDER BY u.nome
    `;

    const [colaboradores] = await db.query(queryColaboradores, [empresaId]);

    if (colaboradores.length === 0) {
      return res.json({ 
        success: true, 
        colaboradores: [],
        total: 0
      });
    }

    // Buscar benefícios de todos os colaboradores de uma vez
    const colaboradorIds = colaboradores.map(c => c.id);
    const placeholders = colaboradorIds.map(() => '?').join(',');
    
    const queryBeneficios = `
      SELECT 
        ub.usuario_id,
        ub.id as usuario_beneficio_id,
        ub.beneficio_id,
        ub.valor_personalizado,
        ub.data_inicio,
        ub.data_fim,
        ub.ativo,
        gb.nome_do_beneficio,
        gb.descricao_beneficio,
        gb.valor_aplicado
      FROM usuario_beneficios ub
      INNER JOIN gerenciarbeneficios gb ON ub.beneficio_id = gb.id
      WHERE ub.usuario_id IN (${placeholders}) 
        AND ub.ativo = 1 
        AND gb.ativo = 1
    `;

    const [beneficiosRows] = await db.query(queryBeneficios, colaboradorIds);

    // Agrupar benefícios por usuário
    const beneficiosPorUsuario = {};
    beneficiosRows.forEach(beneficio => {
      const usuarioId = beneficio.usuario_id;
      if (!beneficiosPorUsuario[usuarioId]) {
        beneficiosPorUsuario[usuarioId] = [];
      }
      
      const valor = parseFloat(beneficio.valor_personalizado || beneficio.valor_aplicado) || 0;
      
      beneficiosPorUsuario[usuarioId].push({
        id: beneficio.usuario_beneficio_id,
        beneficio_id: beneficio.beneficio_id,
        nome_do_beneficio: beneficio.nome_do_beneficio,
        descricao_beneficio: beneficio.descricao_beneficio,
        valor_aplicado: parseFloat(beneficio.valor_aplicado) || 0,
        valor_personalizado: beneficio.valor_personalizado ? parseFloat(beneficio.valor_personalizado) : null,
        valor: valor,
        data_inicio: beneficio.data_inicio,
        data_fim: beneficio.data_fim,
        ativo: beneficio.ativo
      });
    });

    // Combinar dados
    const colaboradoresComBeneficios = colaboradores.map(colab => {
      const beneficios = beneficiosPorUsuario[colab.id] || [];
      const totalBeneficios = beneficios.reduce((sum, b) => sum + b.valor, 0);

      return {
        ...colab,
        salario: parseFloat(colab.salario) || 0,
        beneficios: beneficios,
        total_beneficios: totalBeneficios
      };
    });

    console.log(`${colaboradoresComBeneficios.length} colaboradores carregados com benefícios`);
    
    // Log de debug dos primeiros 3 colaboradores
    colaboradoresComBeneficios.slice(0, 3).forEach(c => {
      console.log(`  - ${c.nome}: R$ ${c.salario} + R$ ${c.total_beneficios} em benefícios (${c.beneficios.length})`);
    });
    
    res.json({ 
      success: true, 
      colaboradores: colaboradoresComBeneficios,
      total: colaboradoresComBeneficios.length
    });

  } catch (error) {
    console.error('❌ Erro ao buscar colaboradores com benefícios:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Erro interno do servidor: ' + error.message 
    });
  }
});

/**
 * @route   GET /api/gestor/colaboradores-simples
 * @desc    Fallback: colaboradores básicos com benefícios (queries individuais)
 * @access  Privado (Gestor)
 */
router.get('/colaboradores-simples', async (req, res) => {
  try {
    const empresaId = req.usuario.empresa_id;

    console.log(' Buscando colaboradores simples (fallback)...');

    const query = `
      SELECT 
        u.id,
        u.nome,
        u.cargo,
        u.setor,
        u.salario,
        u.data_admissao,
        u.numero_registro,
        u.foto,
        u.email,
        u.telefone
      FROM usuario u
      WHERE u.empresa_id = ? AND u.tipo_usuario = 'colaborador'
      ORDER BY u.nome
    `;

    const [colaboradores] = await db.query(query, [empresaId]);

    // Para cada colaborador, buscar benefícios separadamente
    const colaboradoresComBeneficios = await Promise.all(
      colaboradores.map(async (colab) => {
        try {
          const queryBeneficios = `
            SELECT 
              ub.id,
              ub.beneficio_id,
              gb.nome_do_beneficio,
              COALESCE(ub.valor_personalizado, gb.valor_aplicado) as valor,
              ub.data_inicio,
              ub.data_fim
            FROM usuario_beneficios ub
            INNER JOIN gerenciarbeneficios gb ON ub.beneficio_id = gb.id
            WHERE ub.usuario_id = ? AND ub.ativo = 1 AND gb.ativo = 1
          `;
          
          const [beneficios] = await db.query(queryBeneficios, [colab.id]);
          
          return {
            ...colab,
            salario: parseFloat(colab.salario) || 0,
            beneficios: beneficios.map(b => ({
              ...b,
              valor: parseFloat(b.valor) || 0
            })),
            total_beneficios: beneficios.reduce((sum, b) => sum + (parseFloat(b.valor) || 0), 0)
          };
        } catch (err) {
          console.error(`Erro ao buscar benefícios do colaborador ${colab.id}:`, err);
          return {
            ...colab,
            salario: parseFloat(colab.salario) || 0,
            beneficios: [],
            total_beneficios: 0
          };
        }
      })
    );

    console.log(`${colaboradoresComBeneficios.length} colaboradores carregados (fallback)`);

    res.json({ 
      success: true, 
      colaboradores: colaboradoresComBeneficios,
      total: colaboradoresComBeneficios.length
    });

  } catch (error) {
    console.error('❌ Erro ao buscar colaboradores simples:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Erro interno do servidor: ' + error.message 
    });
  }
});

/**
 * @route   GET /api/gestor/colaborador/:id
 * @desc    Buscar colaborador específico por ID
 * @access  Privado (Gestor)
 */
router.get('/colaborador/:id', async (req, res) => {
  try {
    const id = parseInt(req.params.id, 10);
    const empresaId = req.usuario?.empresa_id || req.user?.empresa_id;
    
    if (!id || isNaN(id)) {
      return res.status(400).json({ 
        success: false, 
        error: 'ID inválido' 
      });
    }

    const [rows] = await db.query(
      `SELECT 
        id, nome, cargo, setor, salario, numero_registro, 
        cpf, email, foto, tipo_jornada, horas_diarias,
        data_admissao, telefone
       FROM usuario 
       WHERE id = ? AND empresa_id = ? AND tipo_usuario = 'colaborador'`,
      [id, empresaId]
    );

    if (!rows || rows.length === 0) {
      return res.status(404).json({ 
        success: false, 
        error: 'Colaborador não encontrado' 
      });
    }

    return res.json({
      success: true,
      colaborador: {
        ...rows[0],
        salario: parseFloat(rows[0].salario) || 0
      }
    });

  } catch (err) {
    console.error('❌ Erro /api/gestor/colaborador/:id:', err);
    return res.status(500).json({ 
      success: false, 
      error: 'Erro interno do servidor' 
    });
  }
});

// ==========================================
// ROTAS DE SOLICITAÇÕES
// ==========================================

/**
 * @route   GET /api/gestor/solicitacoes
 * @desc    Listar solicitações dos colaboradores do gestor
 * @access  Privado (Gestor)
 */
router.get('/solicitacoes', solicitacoesController.listarSolicitacoesGestor);

/**
 * @route   GET /api/gestor/solicitacoes/:id
 * @desc    Buscar solicitação específica por ID
 * @access  Privado (Gestor)
 */
router.get('/solicitacoes/:id', solicitacoesController.getById);

/**
 * @route   PUT /api/gestor/solicitacoes/:id/status
 * @desc    Atualizar status da solicitação
 * @access  Privado (Gestor)
 */
router.put('/solicitacoes/:id/status', solicitacoesController.atualizarStatus);

/**
 * @route   PATCH /api/gestor/solicitacoes/:id/status
 * @desc    Alias PATCH para atualizar status
 * @access  Privado (Gestor)
 */
router.patch('/solicitacoes/:id/status', solicitacoesController.atualizarStatus);

// ==========================================
// ROTAS DE CÁLCULO DE FOLHA
// ==========================================

/**
 * @route   POST /api/gestor/calcular
 * @desc    Calcular folha de pagamento simplificada
 * @access  Privado (Gestor)
 */
router.post('/calcular', async (req, res) => {
  try {
    const { usuarioId, salarioBruto } = req.body;
    const salario = Number(salarioBruto || 0);
    
    // Cálculos simplificados (ajuste conforme suas regras)
    const inss = +(salario * 0.08).toFixed(2);
    const irrf = +(salario * 0.075).toFixed(2);
    const fgts = +(salario * 0.08).toFixed(2);
    const totalDescontos = +(inss + irrf).toFixed(2);
    const liquido = +(salario - totalDescontos).toFixed(2);

    return res.json({
      success: true,
      result: {
        salarioBruto: salario,
        inss,
        irrf,
        fgts,
        totalDescontos,
        liquido
      }
    });
  } catch (err) {
    console.error('❌ Erro /api/gestor/calcular:', err);
    return res.status(500).json({ 
      success: false, 
      error: err.message 
    });
  }
});

/**
 * @route   POST /api/gestor/folhapaga/processar
 * @desc    Processar e salvar holerite no banco
 * @access  Privado (Gestor)
 */
router.post('/folhapaga/processar', async (req, res) => {
  try {
    const {
      usuarioId,
      mesReferencia,
      salarioBase,
      proventos = [],
      descontos = [],
      salarioLiquido
    } = req.body;

    if (!usuarioId || !mesReferencia) {
      return res.status(400).json({ 
        success: false, 
        message: 'Dados obrigatórios faltando' 
      });
    }

    const proventosJSON = JSON.stringify(proventos || []);
    const descontosJSON = JSON.stringify(descontos || []);

    const insertSql = `
      INSERT INTO visualizarholerites
      (usuario_id, mes_referencia, salario_base, proventos_detalhe, descontos_detalhe, salario_liquido, arquivo_pdf_caminho)
      VALUES (?, ?, ?, ?, ?, ?, NULL)
    `;
    const params = [usuarioId, mesReferencia, salarioBase || 0, proventosJSON, descontosJSON, salarioLiquido || 0];

    const [result] = await db.query(insertSql, params);
    
    console.log('Holerite processado e salvo:', result.insertId);
    
    return res.json({ 
      success: true, 
      holeriteId: result.insertId 
    });

  } catch (err) {
    console.error('❌ Erro ao processar holerite:', err);
    return res.status(500).json({ 
      success: false, 
      message: 'Erro interno', 
      error: err.message 
    });
  }
});

// ==========================================
// ROTAS DE COMPATIBILIDADE (ALIAS)
// ==========================================

/**
 * @route   GET /api/gestor/usuarios
 * @desc    Alias para /colaboradores (compatibilidade)
 * @access  Privado (Gestor)
 */
router.get('/usuarios', async (req, res) => {
  try {
    const empresaId = req.usuario?.empresa_id;
    
    if (!empresaId) {
      return res.json({ success: true, usuarios: [] });
    }

    const query = `
      SELECT 
        u.id, u.nome, u.email, u.cargo, u.setor, u.foto, 
        u.cnpj, u.empresa_id, u.telefone, u.data_admissao, u.salario,
        s.nome_setor
      FROM usuario u
      LEFT JOIN setores s ON u.setor = s.nome_setor AND s.empresa_id = u.empresa_id
      WHERE u.empresa_id = ? AND u.tipo_usuario = 'colaborador'
      ORDER BY u.nome
    `;

    const [results] = await db.query(query, [empresaId]);
    
    return res.json({
      success: true,
      usuarios: results.map(u => ({
        ...u,
        salario: parseFloat(u.salario) || 0
      }))
    });

  } catch (error) {
    console.error('❌ Erro na rota /usuarios:', error);
    return res.status(500).json({ 
      success: false,
      error: 'Erro interno do servidor' 
    });
  }
});

/**
 * @route   GET /api/gestor/usuario
 * @desc    Alias alternativo para /colaboradores
 * @access  Privado (Gestor)
 */
router.get('/usuario', async (req, res) => {
  try {
    const empresaId = req.usuario?.empresa_id;
    
    if (!empresaId) {
      return res.json({ success: true, usuarios: [] });
    }

    const query = `
      SELECT 
        id, nome, email, cargo, setor, foto, 
        cnpj, empresa_id, telefone, data_admissao, salario
      FROM usuario 
      WHERE empresa_id = ? AND tipo_usuario = 'colaborador'
      ORDER BY nome
    `;

    const [results] = await db.query(query, [empresaId]);
    
    return res.json({
      success: true,
      usuarios: results.map(u => ({
        ...u,
        salario: parseFloat(u.salario) || 0
      }))
    });

  } catch (error) {
    console.error(' Erro na rota /usuario:', error);
    return res.status(500).json({ 
      success: false,
      error: 'Erro interno do servidor' 
    });
  }
});

/**
 * @route   GET /api/gestor/usuarios/:id
 * @desc    Buscar usuário específico por ID (alias)
 * @access  Privado (Gestor)
 */
router.get('/usuarios/:id', async (req, res) => {
  try {
    const colaboradorId = req.params.id;
    const empresaId = req.usuario?.empresa_id;
    
    if (!empresaId) {
      return res.status(403).json({ 
        success: false,
        error: 'Empresa não identificada' 
      });
    }

    const query = `
      SELECT 
        u.id, u.nome, u.email, u.cargo, u.setor, u.foto, 
        u.cnpj, u.empresa_id, u.telefone, u.data_admissao, u.salario,
        s.nome_setor
      FROM usuario u
      LEFT JOIN setores s ON u.setor = s.nome_setor AND s.empresa_id = u.empresa_id
      WHERE u.id = ? AND u.empresa_id = ? AND u.tipo_usuario = 'colaborador'
    `;

    const [results] = await db.query(query, [colaboradorId, empresaId]);
    
    if (!results || results.length === 0) {
      return res.status(404).json({ 
        success: false,
        error: 'Colaborador não encontrado' 
      });
    }
    
    return res.json({
      success: true,
      usuario: {
        ...results[0],
        salario: parseFloat(results[0].salario) || 0
      }
    });

  } catch (error) {
    console.error(' Erro na rota /usuarios/:id:', error);
    return res.status(500).json({ 
      success: false,
      error: 'Erro interno do servidor' 
    });
  }
});

module.exports = router;