const Favorito = require('../models/favorito'); // Modelo ajustado
const Bolsa = require('../models/bolsa'); // Bolsa também necessário

// Alterna entre favoritar e desfavoritar
exports.toggleFavorito = async (req, res) => {
  try {
    const id_usuario = req.usuario.id_usuario; // do middleware
    const { id_bolsa } = req.params;

    if (!id_usuario) return res.status(401).json({ message: "Usuário não autenticado" });

    const favorito = await Favorito.findOne({ where: { id_usuario, id_bolsa } });

    if (favorito) {
      await favorito.destroy();
      return res.json({ favoritado: false });
    } else {
      await Favorito.create({ id_usuario, id_bolsa });
      return res.json({ favoritado: true });
    }
  } catch (err) {
    console.error("Erro toggleFavorito:", err);
    res.status(500).json({ message: "Erro ao favoritar bolsa", error: err.message });
  }
};

// Lista apenas as bolsas que o usuário favoritou
exports.listarFavoritos = async (req, res) => {
  try {
    const id_usuario = req.usuario.id_usuario;

    // Buscar favoritos do usuário incluindo dados da Bolsa
    const favoritos = await Favorito.findAll({
      where: { id_usuario },
      include: [{ model: Bolsa }]
    });

    // Retornar apenas os dados das bolsas
    res.json(favoritos.map(f => f.Bolsa));
  } catch (err) {
    console.error("Erro listarFavoritos:", err);
    res.status(500).json({ message: "Erro ao listar favoritas", error: err.message });
  }
};







