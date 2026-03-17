const express = require("express");
const router = express.Router();
const Bolsa = require("../models/bolsa"); // Sequelize model

// Rota para buscar bolsas pelo país
router.get("/bolsas", async (req, res) => {
  try {
    const pais = req.query.pais;
    if (!pais) return res.status(400).json({ error: "Parâmetro 'pais' é obrigatório" });

    const bolsas = await Bolsa.findAll({
      where: { pais },
      attributes: ['id_bolsa', 'titulo', 'link_inscricao', 'descricao', 'prazo_inscricao'] // CORRIGIDO
    });

    if (bolsas.length === 0) {
      return res.status(404).json({ message: 'Nenhuma bolsa encontrada para este país' });
    }

    res.json(bolsas);
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Nenhuma bolsa encontrada para este país' });
  }
});

module.exports = router;




