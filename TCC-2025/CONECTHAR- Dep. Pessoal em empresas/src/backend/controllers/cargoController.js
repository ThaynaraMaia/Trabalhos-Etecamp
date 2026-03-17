// backend/controllers/cargoController.js
const cargoModel = require('../models/cargoModel');

const cargoController = {
  // Criar cargo
  async criar(req, res) {
    try {
      const { setor_id, nome_cargo, descricao } = req.body;
      
      // Buscar empresa_id do usuário logado
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

      if (!empresa_id || !setor_id || !nome_cargo) {
        return res.status(400).json({
          success: false,
          message: 'Dados incompletos. Informe setor e nome do cargo.'
        });
      }

      const result = await cargoModel.criar({ empresa_id, setor_id, nome_cargo, descricao });
      
      res.status(201).json({
        success: true,
        message: 'Cargo criado com sucesso',
        data: result
      });
    } catch (err) {
      console.error('Erro ao criar cargo:', err);
      res.status(500).json({
        success: false,
        message: err.message || 'Erro ao criar cargo'
      });
    }
  },

  // Listar cargos
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

      const cargos = await cargoModel.listarPorEmpresa(empresa_id);
      
      res.json({
        success: true,
        data: cargos
      });
    } catch (err) {
      console.error('Erro ao listar cargos:', err);
      res.status(500).json({
        success: false,
        message: 'Erro ao listar cargos'
      });
    }
  },

  // Listar cargos por setor
  async listarPorSetor(req, res) {
    try {
      const { setor_id } = req.params;
      const cargos = await cargoModel.listarPorSetor(setor_id);
      
      res.json({
        success: true,
        data: cargos
      });
    } catch (err) {
      console.error('Erro ao listar cargos por setor:', err);
      res.status(500).json({
        success: false,
        message: 'Erro ao listar cargos'
      });
    }
  },

  // Deletar cargo
  async deletar(req, res) {
    try {
      const { id } = req.params;
      const sucesso = await cargoModel.deletar(id);
      
      if (sucesso) {
        res.json({
          success: true,
          message: 'Cargo deletado com sucesso'
        });
      } else {
        res.status(404).json({
          success: false,
          message: 'Cargo não encontrado'
        });
      }
    } catch (err) {
      console.error('Erro ao deletar cargo:', err);
      res.status(500).json({
        success: false,
        message: 'Erro ao deletar cargo'
      });
    }
  }
};

module.exports = cargoController;