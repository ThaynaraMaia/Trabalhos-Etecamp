const {Bolsa} = require("../models");

// ================== ADMIN ==================

// Listar bolsas (admin)
exports.listar = async (req, res) => {
  try {
    const bolsas = await Bolsa.findAll();
    res.json(bolsas);
  } catch (err) {
    console.error(err);
    res.status(500).json({ erro: "Erro ao listar bolsas" });
  }
};

// Criar bolsa (admin)
exports.criar = async (req, res) => {
  try {
    const bolsa = await Bolsa.create(req.body);
    res.json(bolsa);
  } catch (err) {
    console.error(err);
    res.status(500).json({ erro: "Erro ao criar bolsa" });
  }
};

// Atualizar bolsa (admin)
exports.atualizar = async (req, res) => {
  try {
    const { id } = req.params;
    await Bolsa.update(req.body, { where: { id_bolsa: id } });
    res.json({ mensagem: "Bolsa atualizada" });
  } catch (err) {
    console.error(err);
    res.status(500).json({ erro: "Erro ao atualizar bolsa" });
  }
};

// Excluir bolsa (admin)
exports.excluir = async (req, res) => {
  try {
    const { id } = req.params;
    await Bolsa.destroy({ where: { id_bolsa: id } });
    res.json({ mensagem: "Bolsa excluída" });
  } catch (err) {
    console.error(err);
    res.status(500).json({ erro: "Erro ao excluir bolsa" });
  }
};




