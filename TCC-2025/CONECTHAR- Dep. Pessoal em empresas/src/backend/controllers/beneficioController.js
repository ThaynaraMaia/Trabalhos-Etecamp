// backend/controllers/beneficioController.js
const beneficioModel = require('../models/beneficioModel');

const beneficioController = {

  // Listar por cargo
  // backend/controllers/beneficioController.js - Adicione este método
// backend/controllers/beneficioController.js - CORREÇÃO COMPLETA
// Método atualizado para considerar cargo E setor
async listarPorCargo(req, res) {
  try {
    const { cargo_id } = req.params;
    
    console.log(`📋 Buscando benefícios para cargo_id: ${cargo_id}`);
    
    if (!cargo_id) {
      return res.status(400).json({
        success: false,
        message: 'ID do cargo é obrigatório'
      });
    }

    // Primeiro, buscar informações completas do cargo (incluindo setor_id)
    const [cargos] = await require('../config/db').query(
      `SELECT 
        c.id, 
        c.nome_cargo, 
        c.setor_id,
        s.nome_setor 
      FROM cargos c 
      LEFT JOIN setores s ON c.setor_id = s.id 
      WHERE c.id = ?`,
      [cargo_id]
    );

    if (cargos.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Cargo não encontrado'
      });
    }

    const cargo = cargos[0];
    const setor_id = cargo.setor_id;

    console.log(` Cargo encontrado: ${cargo.nome_cargo}, Setor ID: ${setor_id}`);

    // Buscar benefícios que correspondam ao cargo E setor
    const sql = `
      SELECT 
        gb.id,
        gb.nome_do_beneficio,
        gb.descricao_beneficio,
        gb.valor_aplicado,
        gb.data_inicio,
        gb.data_fim,
        gb.ativo,
        gb.cargo_id,
        gb.setor_id,
        c.nome_cargo,
        s.nome_setor
      FROM gerenciarbeneficios gb
      LEFT JOIN cargos c ON gb.cargo_id = c.id
      LEFT JOIN setores s ON gb.setor_id = s.id
      WHERE (
        (gb.cargo_id = ? AND gb.setor_id = ?) OR  -- Benefícios específicos para este cargo neste setor
        (gb.cargo_id = ? AND gb.setor_id IS NULL) OR  -- Benefícios para este cargo em qualquer setor
        (gb.cargo_id IS NULL AND gb.setor_id = ?)     -- Benefícios para este setor (qualquer cargo)
      )
        AND gb.usuario_id IS NULL
        AND gb.ativo = 1
      ORDER BY 
        gb.cargo_id DESC,  -- Prioriza benefícios específicos do cargo
        gb.setor_id DESC,  -- Depois benefícios específicos do setor
        gb.nome_do_beneficio ASC
    `;
    
    const [beneficios] = await require('../config/db').query(sql, [
      cargo_id, setor_id,  // cargo específico + setor específico
      cargo_id,            // cargo específico (setor NULL)
      setor_id             // setor específico (cargo NULL)
    ]);
    
    console.log(` Encontrados ${beneficios.length} benefícios para cargo ${cargo_id} no setor ${setor_id}`);
    
    // Adicionar informações do contexto
    const beneficiosComContexto = beneficios.map(beneficio => ({
      ...beneficio,
      contexto: beneficio.cargo_id && beneficio.setor_id ? 'cargo_setor_especifico' :
                beneficio.cargo_id ? 'cargo_especifico' :
                beneficio.setor_id ? 'setor_especifico' : 'geral'
    }));

    res.json({
      success: true,
      data: beneficiosComContexto,
      contexto: {
        cargo_id: cargo.id,
        cargo_nome: cargo.nome_cargo,
        setor_id: cargo.setor_id,
        setor_nome: cargo.nome_setor
      }
    });
  } catch (err) {
    console.error(' Erro ao listar benefícios por cargo:', err);
    res.status(500).json({
      success: false,
      message: 'Erro interno ao listar benefícios'
    });
  }
},
  // Criar benefício
  async criar(req, res) {
    try {
      const { cargo_id, setor_id, nome_beneficio, descricao, valor, data_inicio, data_fim } = req.body;
      const gestor_id = req.usuario?.id;

      if (!gestor_id || !cargo_id || !nome_beneficio || !valor) {
        return res.status(400).json({
          success: false,
          message: 'Dados incompletos'
        });
      }

      const result = await beneficioModel.criar({
        gestor_id,
        cargo_id,
        setor_id,
        nome_beneficio,
        descricao,
        valor,
        data_inicio: data_inicio || new Date(),
        data_fim
      });

      res.status(201).json({
        success: true,
        message: 'Benefício criado com sucesso',
        data: result
      });
    } catch (err) {
      console.error('Erro ao criar benefício:', err);
      res.status(500).json({
        success: false,
        message: 'Erro ao criar benefício'
      });
    }
  },

  // Listar benefícios
  async listar(req, res) {
    try {
      let empresa_id = req.usuario?.empresa_id;
      if (!empresa_id && req.usuario?.id) {
        const [usuarios] = await require('../config/db').query(
          'SELECT empresa_id FROM usuario WHERE id = ?',
          [req.usuario.id]
        );
        if (usuarios.length > 0) {
          empresa_id = usuarios[0].empresa_id;
        }
      }

      if (!empresa_id) {
        return res.status(400).json({
          success: false,
          message: 'Empresa não identificada'
        });
      }

      const beneficios = await beneficioModel.listarPorEmpresa(empresa_id);
      const stats = await beneficioModel.obterEstatisticas(empresa_id);

      res.json({
        success: true,
        data: beneficios,
        stats: stats
      });
    } catch (err) {
      console.error('Erro ao listar benefícios:', err);
      res.status(500).json({
        success: false,
        message: 'Erro ao listar benefícios'
      });
    }
  },

  // Atualizar benefício
  async atualizar(req, res) {
    try {
      const { id } = req.params;
      const dados = req.body;

      const result = await beneficioModel.atualizar(id, dados);

      if (result.success) {
        res.json({
          success: true,
          message: 'Benefício atualizado com sucesso'
        });
      } else {
        res.status(404).json({
          success: false,
          message: 'Benefício não encontrado'
        });
      }
    } catch (err) {
      console.error('Erro ao atualizar benefício:', err);
      res.status(500).json({
        success: false,
        message: 'Erro ao atualizar benefício'
      });
    }
  },

  // Deletar benefício
  async deletar(req, res) {
    try {
      const { id } = req.params;
      const sucesso = await beneficioModel.deletar(id);

      if (sucesso) {
        res.json({
          success: true,
          message: 'Benefício deletado com sucesso'
        });
      } else {
        res.status(404).json({
          success: false,
          message: 'Benefício não encontrado'
        });
      }
    } catch (err) {
      console.error('Erro ao deletar benefício:', err);
      res.status(500).json({
        success: false,
        message: 'Erro ao deletar benefício'
      });
    }
  }
};

module.exports = beneficioController;