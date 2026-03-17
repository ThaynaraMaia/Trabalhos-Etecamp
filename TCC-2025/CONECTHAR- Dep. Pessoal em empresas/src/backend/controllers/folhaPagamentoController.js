// backend/controllers/folhaPagamentoController.js
const db = require('../config/db');
const folhaService = require('../services/folhadePagamentoService');
const UsuarioBeneficiosModel = require('../models/usuariosBeneficiosModel');

const folhaPagamentoController = {
  /**
   * Calcula a folha preliminar de um funcionário específico
   * GET /api/folha/:usuarioId?mes=YYYY-MM
   */
  async calcularFolhaUsuario(req, res) {
    try {
      const { usuarioId } = req.params;
      const { mes } = req.query; // formato: YYYY-MM
      
      if (!usuarioId || !mes) {
        return res.status(400).json({ erro: 'usuarioId e mes são obrigatórios' });
      }

      // Buscar dados do usuário
      const [usuarios] = await db.query(
        `SELECT id, nome, salario, horas_diarias, cargo, setor FROM usuario WHERE id = ?`,
        [usuarioId]
      );

      if (!usuarios || usuarios.length === 0) {
        return res.status(404).json({ erro: 'Usuário não encontrado' });
      }

      const usuario = usuarios[0];

      // Buscar registros de ponto do mês
      const [ano, mesNum] = mes.split('-');
      const primeiroDia = `${mes}-01`;
      const ultimoDia = new Date(ano, mesNum, 0).toISOString().split('T')[0];

      const [registrosPonto] = await db.query(
        `SELECT data_registro AS data_hora_registro, tipo_registro, horas 
         FROM pontos 
         WHERE usuario_id = ? AND DATE(data_registro) BETWEEN ? AND ?
         ORDER BY data_registro`,
        [usuarioId, primeiroDia, ultimoDia]
      );

      // Calcular dias úteis do mês
      const diasUteis = folhaService.calcularDiasUteis(parseInt(ano), parseInt(mesNum));
      const horasEsperadas = diasUteis * (usuario.horas_diarias || 8);

      // Calcular horas trabalhadas
      const { totalHoras, faltas, detalhesPorDia, horasFaltadas } = 
        folhaService.calcularHorasTrabalhadas(registrosPonto, usuario.horas_diarias || 8);

      // Buscar benefícios do usuário
      const beneficios = await UsuarioBeneficiosModel.findByUsuario(usuarioId);

      // Calcular folha
      const folha = folhaService.calcularFolhaFuncionario({
        salarioBruto: usuario.salario,
        dependentes: 0, // pode ser parametrizado depois
        horasFaltadas,
        horasEsperadas,
        beneficios
      });

      return res.json({
        usuario: {
          id: usuario.id,
          nome: usuario.nome,
          cargo: usuario.cargo,
          setor: usuario.setor
        },
        mes,
        diasUteis,
        horasEsperadas,
        totalHorasTrabalhadas: totalHoras,
        faltas,
        detalhesPorDia,
        folha,
        beneficios
      });
    } catch (err) {
      console.error('Erro em calcularFolhaUsuario:', err);
      return res.status(500).json({ erro: 'Erro ao calcular folha do usuário' });
    }
  },

  // Estatísticas do dashboard (colaboradores, soma e média salarial)
  // backend/controllers/folhaPagamentoController.js

async obterEstatisticasDashboard(req, res) {
  try {
    const gestorId = req.usuario?.id || null;
    let empresa_id = req.usuario?.empresa_id || null;

    // Buscar empresa_id se não veio no token
    if (!empresa_id && gestorId) {
      try {
        const [rowsUser] = await db.query(
          'SELECT empresa_id FROM usuario WHERE id = ? LIMIT 1',
          [gestorId]
        );
        if (Array.isArray(rowsUser) && rowsUser.length) {
          empresa_id = rowsUser[0].empresa_id;
        }
      } catch (e) {
        console.warn('[obterEstatisticasDashboard] erro ao buscar empresa_id do usuario:', e?.message);
      }
    }

    if (!empresa_id) {
      return res.status(400).json({
        success: false,
        message: 'Empresa não identificada.'
      });
    }

    // Query principal para estatísticas
    const statsSql = `
      SELECT
        COUNT(*) AS total,
        COALESCE(SUM(salario), 0) AS total_salarios,
        COALESCE(AVG(salario), 0) AS media_salarios
      FROM usuario
      WHERE empresa_id = ?
        AND tipo_usuario IN ('colaborador', 'funcionario', 'empregado')
        AND salario > 0
    `;

    const [statsRows] = await db.query(statsSql, [empresa_id]);
    const stats = statsRows && statsRows[0] ? statsRows[0] : { total: 0, total_salarios: 0, media_salarios: 0 };

    // Garantir tipos numéricos
    const total = Number(stats.total || 0);
    const total_salarios = parseFloat(stats.total_salarios || 0);
    const media_salarios = parseFloat(stats.media_salarios || 0);

    // Formatar valores monetários
    const formatter = new Intl.NumberFormat('pt-BR', { 
      style: 'currency', 
      currency: 'BRL' 
    });

    const total_salarios_formatted = formatter.format(total_salarios);
    const media_salarios_formatted = formatter.format(media_salarios);

    // Buscar configuração de pagamento
    let proximo_pagamento = null;
    let proximo_pagamento_label = null;

    try {
      const [configRows] = await db.query(
        'SELECT qtdPagamentos, diaPagamento1, diaPagamento2 FROM config_pagamento WHERE empresa_id = ? LIMIT 1',
        [empresa_id]
      );

      if (configRows && configRows.length) {
        const config = configRows[0];
        const hoje = new Date();
        const diaAtual = hoje.getDate();
        
        let diaPagamento = parseInt(config.diaPagamento1) || 5;
        
        // Determinar o próximo pagamento
        let mesPagamento = hoje.getMonth();
        let anoPagamento = hoje.getFullYear();
        
        if (diaAtual > diaPagamento) {
          mesPagamento += 1;
          if (mesPagamento > 11) {
            mesPagamento = 0;
            anoPagamento += 1;
          }
        }
        
        const dataPagamento = new Date(anoPagamento, mesPagamento, diaPagamento);
        const diffDias = Math.ceil((dataPagamento - hoje) / (1000 * 60 * 60 * 24));
        
        if (diffDias === 0) {
          proximo_pagamento_label = 'Hoje';
        } else if (diffDias === 1) {
          proximo_pagamento_label = '1 dia';
        } else {
          proximo_pagamento_label = `${diffDias} dias`;
        }
        
        proximo_pagamento = dataPagamento.toISOString().split('T')[0];
      }
    } catch (configErr) {
      console.warn('Erro ao buscar configuração de pagamento:', configErr?.message);
    }

    const payload = {
      total,
      total_salarios,
      media_salarios,
      total_salarios_formatted,
      media_salarios_formatted,
      proximo_pagamento,
      proximo_pagamento_label
    };

    return res.json({ success: true, data: payload });

  } catch (err) {
    console.error('[obterEstatisticasDashboard] Erro inesperado:', err);
    return res.status(500).json({
      success: false,
      message: 'Erro interno ao obter estatísticas.'
    });
  }
},

  /**
   * Processar folha de um usuário (gravar na tabela controlarfolhadepagamento)
   * POST /api/folha/:usuarioId/processar
   * Body: { mes, ajustes: [{dia, tipo, observacao}], overrides: {salario, beneficios} }
   */
  async processarFolhaUsuario(req, res) {
    try {
      const { usuarioId } = req.params;
      const { mes, ajustes = [], overrides = {} } = req.body;
      const gestorId = req.usuario?.id;

      if (!usuarioId || !mes) {
        return res.status(400).json({ erro: 'usuarioId e mes são obrigatórios' });
      }

      // Recalcular com ajustes (implementação básica - pode ser expandida)
      // Por enquanto, apenas calculamos normalmente
      const calculoResult = await this.calcularFolhaUsuario(
        { params: { usuarioId }, query: { mes } },
        { json: (data) => data } // mock response
      );

      if (calculoResult.erro) {
        return res.status(400).json(calculoResult);
      }

      // Gravar na tabela controlarfolhadepagamento
      const [ano, mesNum] = mes.split('-');
      const mesReferencia = `${ano}-${mesNum}-01`;

      await db.query(
        `INSERT INTO controlarfolhadepagamento 
         (mes_referencia, total_bruto_geral, total_liquido_geral, processado_por_gestor_id)
         VALUES (?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE 
         total_bruto_geral = VALUES(total_bruto_geral),
         total_liquido_geral = VALUES(total_liquido_geral),
         processado_por_gestor_id = VALUES(processado_por_gestor_id)`,
        [
          mesReferencia,
          calculoResult.folha.salarioBruto,
          calculoResult.folha.salarioLiquido,
          gestorId
        ]
      );

      return res.json({ 
        mensagem: 'Folha processada com sucesso',
        resultado: calculoResult
      });
    } catch (err) {
      console.error('Erro em processarFolhaUsuario:', err);
      return res.status(500).json({ erro: 'Erro ao processar folha' });
    }
  },

  /**
   * Calcular folha de toda a empresa
   * GET /api/folha/empresa?mes=YYYY-MM
   */
  async calcularFolhaEmpresa(req, res) {
    try {
      const { mes } = req.query;
      const empresaId = req.usuario?.empresa_id;

      if (!mes) {
        return res.status(400).json({ erro: 'mes é obrigatório' });
      }

      if (!empresaId) {
        return res.status(401).json({ erro: 'Empresa não identificada' });
      }

      // Buscar todos os funcionários ativos da empresa
      const [funcionarios] = await db.query(
        `SELECT id, nome, salario, horas_diarias, cargo, setor 
         FROM usuario 
         WHERE empresa_id = ? AND tipo_usuario IN ('colaborador', 'gestor')`,
        [empresaId]
      );

      // Calcular folha de cada funcionário
      const [ano, mesNum] = mes.split('-');
      const diasUteis = folhaService.calcularDiasUteis(parseInt(ano), parseInt(mesNum));

      const resultados = [];
      
      for (const func of funcionarios) {
        const horasEsperadas = diasUteis * (func.horas_diarias || 8);
        const beneficios = await UsuarioBeneficiosModel.findByUsuario(func.id);

        // Por simplicidade, assumir 0 faltas (pode ser calculado com registros reais)
        const folha = folhaService.calcularFolhaFuncionario({
          salarioBruto: func.salario,
          dependentes: 0,
          horasFaltadas: 0,
          horasEsperadas,
          beneficios
        });

        resultados.push({
          id: func.id,
          nome: func.nome,
          cargo: func.cargo,
          setor: func.setor,
          ...folha
        });
      }

      // Calcular resumo geral
      const resumo = {
        totalBruto: resultados.reduce((acc, r) => acc + r.salarioBruto, 0),
        totalINSS: resultados.reduce((acc, r) => acc + r.totalINSS, 0),
        totalIRRF: resultados.reduce((acc, r) => acc + r.totalIRRF, 0),
        totalFGTS: resultados.reduce((acc, r) => acc + r.totalFGTS, 0),
        totalLiquido: resultados.reduce((acc, r) => acc + r.salarioLiquido, 0)
      };

      return res.json({
        mes,
        diasUteis,
        totalFuncionarios: funcionarios.length,
        resumo,
        funcionarios: resultados
      });
    } catch (err) {
      console.error('Erro em calcularFolhaEmpresa:', err);
      return res.status(500).json({ erro: 'Erro ao calcular folha da empresa' });
    }
  },

  /**
   * Calcular folha com dados fornecidos pelo usuário
   * POST /api/folha/calcular
   * Body: { funcionarioId, mes, salarioBase, horasDiarias, diasTrabalhados, faltas, horasExtras, dependentes }
   */
  async calcularComDadosUsuario(req, res) {
    try {
      const {
        funcionarioId,
        mes,
        salarioBase,
        horasDiarias = 8,
        diasTrabalhados = 22,
        faltas = 0,
        horasExtras = 0,
        dependentes = 0
      } = req.body;

      if (!funcionarioId || !mes || !salarioBase) {
        return res.status(400).json({ 
          erro: 'Funcionário, mês e salário base são obrigatórios' 
        });
      }

      // Buscar nome do funcionário
      const [usuarios] = await db.query(
        `SELECT nome, cargo, setor FROM usuario WHERE id = ?`,
        [funcionarioId]
      );

      if (!usuarios || usuarios.length === 0) {
        return res.status(404).json({ erro: 'Funcionário não encontrado' });
      }

      const usuario = usuarios[0];

      // Calcular horas esperadas
      const horasEsperadas = diasTrabalhados * horasDiarias;
      
      // Calcular horas faltadas
      const horasFaltadas = faltas * horasDiarias;

      // Buscar benefícios
      const beneficios = await UsuarioBeneficiosModel.findByUsuario(funcionarioId);

      // Calcular folha
      const folha = folhaService.calcularFolhaFuncionario({
        salarioBruto: parseFloat(salarioBase),
        dependentes: parseInt(dependentes),
        horasFaltadas,
        horasEsperadas,
        beneficios
      });

      return res.json({
        usuario: {
          id: funcionarioId,
          nome: usuario.nome,
          cargo: usuario.cargo,
          setor: usuario.setor
        },
        mes,
        dadosInformados: {
          salarioBase: parseFloat(salarioBase),
          horasDiarias,
          diasTrabalhados,
          faltas,
          horasExtras,
          dependentes
        },
        horasEsperadas,
        horasFaltadas,
        ...folha
      });
    } catch (err) {
      console.error('Erro em calcularComDadosUsuario:', err);
      return res.status(500).json({ erro: 'Erro ao calcular folha' });
    }
  },

  async mostrarFolha(req, res) {
    try {
      const empresaId = req.usuario?.empresa_id || 1;
      
      // Buscar funcionários ativos
      const [funcionarios] = await db.query(
        `SELECT u.id, u.nome, u.tipo_usuario, u.setor, u.salario, u.horas_diarias
         FROM usuario u
         WHERE u.empresa_id = ? AND u.tipo_usuario IN ('gestor','colaborador')`,
        [empresaId]
      );

      const listaFuncionarios = funcionarios || [];

      // Calcular folha completa
      const { detalhesFuncionarios, resumo } = folhaService.calcularFolhaCompleta(
        listaFuncionarios.map(f => ({
          ...f,
          salarioBruto: f.salario,
          horasEsperadas: 22 * (f.horas_diarias || 8)
        }))
      );

      // Dados para o template
      const data = {
        funcionariosAtivos: listaFuncionarios.length,
        folhaAtual: new Intl.DateTimeFormat('pt-BR', { month: 'long', year: 'numeric' }).format(new Date()),
        totalFolha: `R$ ${resumo.totalLiquido.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`,
        folhasRecentes: [
          { data: '12/2024', tipo: 'Folha Normal', valor: 'R$ 150.000,00', status: 'Processada', icone: 'bi-file-earmark-text' },
          { data: '11/2024', tipo: '13º Salário', valor: 'R$ 50.000,00', status: 'Processada', icone: 'bi-cash-stack' },
          { data: '10/2024', tipo: 'Férias', valor: 'R$ 30.000,00', status: 'Pendente', icone: 'bi-calendar' }
        ],
        resumoFolha: resumo,
        funcionarios: detalhesFuncionarios
      };

      res.render('folhadepagamento', data);
    } catch (err) {
      console.error('Erro em mostrarFolha:', err);
      return res.status(500).json({ erro: 'Erro ao carregar folha de pagamento' });
    }
  }
};

module.exports = folhaPagamentoController;