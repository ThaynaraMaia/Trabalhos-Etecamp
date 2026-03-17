const usuarioModel = require('../models/usuarioModel');
const bcrypt = require('bcrypt');
const db = require('../config/db');

const SALT_ROUNDS = Number(process.env.SALT_ROUNDS) || 10;

const usuarioController = {
  async registerColaborador(req, res) {
    try {
      const { numero_registro, nome, cnpj, senha, cargo, setor, salario } = req.body;
      const empresaId = req.usuario?.empresa_id || null;

      if (!numero_registro || !nome || !senha) {
        return res.status(400).json({ erro: 'Campos obrigatórios ausentes (numero_registro, nome, senha).' });
      }

      const existente = await usuarioModel.buscarPorRegistro(numero_registro);
      if (existente) return res.status(409).json({ erro: 'Número de registro já cadastrado.' });

      const senha_hash = await bcrypt.hash(senha, SALT_ROUNDS);
      const result = await usuarioModel.criar({
        empresa_id: empresaId,
        numero_registro,
        nome,
        cnpj: cnpj || null,
        senha_hash,
        tipo_usuario: 'colaborador',
        cargo: cargo || null,
        setor: setor || null,
        salario: typeof salario !== 'undefined' && salario !== '' ? Number(salario) : 0.00
      });

      return res.status(201).json({
        id: result.insertId,
        numero_registro,
        salario: typeof salario !== 'undefined' ? Number(salario) : 0.00,
        mensagem: 'Colaborador registrado com sucesso.'
      });
    } catch (err) {
      console.error('Erro em registerColaborador:', err);
      return res.status(500).json({ erro: 'Erro interno ao registrar colaborador.' });
    }
  },

async atualizarColaborador(req, res) {
    try {
      const { id } = req.params;
      const { nome, cnpj, cargo, senha, numero_registro, setor, salario } = req.body;
      const empresaId = req.usuario?.empresa_id;

      const usuarioExistente = await usuarioModel.buscarPorId(id);
      if (!usuarioExistente || usuarioExistente.empresa_id !== empresaId) {
        return res.status(404).json({ erro: 'Colaborador não encontrado ou não pertence à sua empresa.' });
      }

      // montar objeto de updates para usar usuarioModel.atualizar
      const updatesObj = {};
      if (nome) updatesObj.nome = nome;
      if (cnpj) updatesObj.cnpj = cnpj;
      if (cargo) updatesObj.cargo = cargo;
      if (setor) updatesObj.setor = setor;
      if (numero_registro) updatesObj.numero_registro = numero_registro;
      if (typeof salario !== 'undefined') {
        // aceitar 0 também; validar número
        const parsed = Number(salario);
        if (Number.isNaN(parsed)) {
          return res.status(400).json({ erro: 'salario inválido.' });
        }
        updatesObj.salario = parsed;
      }
      if (senha) {
        const senha_hash = await bcrypt.hash(senha, SALT_ROUNDS);
        updatesObj.senha_hash = senha_hash;
      }

      if (Object.keys(updatesObj).length === 0) {
        return res.status(400).json({ erro: 'Nenhum dado fornecido para alteração.' });
      }

      const result = await usuarioModel.atualizar(id, updatesObj);
      return res.json({ mensagem: 'Colaborador atualizado', affectedRows: result.affectedRows });
    } catch (err) {
      console.error('Erro em atualizarColaborador:', err);
      return res.status(500).json({ erro: 'Erro interno ao atualizar colaborador.' });
    }
  },


 async excluirColaborador(req, res) {
    try {
      const { id } = req.params;
      const empresaId = req.usuario?.empresa_id;

      const usuarioExistente = await usuarioModel.buscarPorId(id);
      if (!usuarioExistente || usuarioExistente.empresa_id !== empresaId) {
        return res.status(404).json({ erro: 'Colaborador não encontrado.' });
      }

      const result = await usuarioModel.excluir(id, empresaId);
      return res.json({ mensagem: 'Colaborador excluído', affectedRows: result.affectedRows });
    } catch (err) {
      console.error('Erro em excluirColaborador:', err);
      return res.status(500).json({ erro: 'Erro interno ao excluir colaborador.' });
    }
  },


    async listarColaboradores(req, res) {
    try {
      const empresaId = req.usuario?.empresa_id;
      const colaboradores = await usuarioModel.listarPorEmpresa(empresaId);
      return res.json({ colaboradores: colaboradores || [] });
    } catch (err) {
      console.error('Erro em listarColaboradores:', err);
      return res.status(500).json({ erro: 'Erro interno ao listar colaboradores.' });
    }
  }
};


module.exports = usuarioController;
