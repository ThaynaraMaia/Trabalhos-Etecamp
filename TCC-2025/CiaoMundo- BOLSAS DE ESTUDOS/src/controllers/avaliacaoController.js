const { AvaliacaoBolsa } = require('../models');

module.exports = {
  listar: async (req, res) => {
    try {
      const id_bolsa = req.params.id_bolsa;
      const avaliacoes = await AvaliacaoBolsa.findAll({ where: { id_bolsa } });
      res.json(avaliacoes);
    } catch (err) {
      console.error(err);
      res.status(500).json({ message: 'Erro ao listar avaliações', error: err.message });
    }
  },

  avaliar: async (req, res) => {
    try {
      const id_usuario = req.usuario.id_usuario;
      const id_bolsa = req.params.id_bolsa;
      const { nota } = req.body;

      if (!nota || nota < 1 || nota > 5) return res.status(400).json({ message: "Nota inválida" });

      const [avaliacao, created] = await AvaliacaoBolsa.findOrCreate({
        where: { id_usuario, id_bolsa },
        defaults: { nota }
      });

      if (!created) {
        avaliacao.nota = nota;
        await avaliacao.save();
      }

      res.json({ message: "Avaliação adicionada", nota });
    } catch (err) {
      console.error(err);
      res.status(500).json({ message: "Erro ao avaliar bolsa", error: err.message });
    }
  },

  remover: async (req, res) => {
    try {
      const id_usuario = req.usuario.id_usuario;
      const id_bolsa = req.params.id_bolsa;

      const avaliacao = await AvaliacaoBolsa.findOne({ where: { id_usuario, id_bolsa } });
      if (!avaliacao) return res.status(404).json({ message: "Avaliação não encontrada" });

      await avaliacao.destroy();
      res.json({ message: "Avaliação removida" });
    } catch (err) {
      console.error(err);
      res.status(500).json({ message: "Erro ao remover avaliação", error: err.message });
    }
  }
};

