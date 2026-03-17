const LembretePessoal = require('../models/lembretePessoal');
const { Op } = require('sequelize');

const getLembretesPersonalizados = async (req, res) => {
  try {
    const userId = req.usuario.id_usuario;
    
    // Calcular data limite (10 dias atrás)
    const hoje = new Date();
    const dataLimite = new Date();
    dataLimite.setDate(hoje.getDate() - 10);
    
    const lembretes = await LembretePessoal.findAll({
      where: { 
        id_usuario: userId,
        data_lembrete: {
          [Op.gte]: dataLimite // Apenas lembretes dos últimos 10 dias ou futuros
        }
      },
      order: [['data_lembrete', 'ASC']]
    });

    res.json(lembretes);
  } catch (error) {
    console.error('Erro ao buscar lembretes pessoais:', error);
    res.status(500).json({ message: 'Erro ao buscar lembretes pessoais' });
  }
};


const criarLembretePessoal = async (req, res) => {
  try {
    const userId = req.usuario.id_usuario;
    const { titulo, descricao, data_lembrete } = req.body;

    if (!titulo || !data_lembrete) {
      return res.status(400).json({ 
        message: 'Título e data são obrigatórios' 
      });
    }

    const novoLembrete = await LembretePessoal.create({
      id_usuario: userId,
      titulo,
      descricao: descricao || null,
      data_lembrete
    });

    res.status(201).json(novoLembrete);
  } catch (error) {
    console.error('Erro ao criar lembrete pessoal:', error);
    res.status(500).json({ message: 'Erro ao criar lembrete pessoal' });
  }
};

const removerLembretePessoal = async (req, res) => {
  try {
    const userId = req.usuario.id_usuario;
    const lembreteId = req.params.id;

    const lembrete = await LembretePessoal.findOne({
      where: { 
        id_lembrete_pessoal: lembreteId,
        id_usuario: userId 
      }
    });

    if (!lembrete) {
      return res.status(404).json({ 
        message: 'Lembrete não encontrado ou não pertence ao usuário' 
      });
    }

    await lembrete.destroy();
    res.json({ message: 'Lembrete removido com sucesso' });
  } catch (error) {
    console.error('Erro ao remover lembrete pessoal:', error);
    res.status(500).json({ message: 'Erro ao remover lembrete pessoal' });
  }
};

module.exports = {
  getLembretesPersonalizados,
  criarLembretePessoal,
  removerLembretePessoal
};