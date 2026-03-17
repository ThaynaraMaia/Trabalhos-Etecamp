const { Comentario, Usuario } = require('../models');
const ComentarioLike = require('../models/comentarioLike');

module.exports = {
  listar: async (req, res) => {
    try {
      const comentarios = await Comentario.findAll({
        where: { id_bolsa: req.params.id_bolsa },
        include: [
          { model: Usuario, attributes: ['nome'] },
          { model: ComentarioLike, as: 'likes', attributes: ['id_usuario', 'curtido', 'descurtido'] }
        ],
        order: [['createdAt', 'ASC']]
      });
      res.json(comentarios);
    } catch (err) {
      console.error(err);
      res.status(500).json({ message: "Erro ao listar comentários", error: err.message });
    }
  },

  adicionar: async (req, res) => {
    try {
      const id_usuario = req.usuario.id_usuario;
      const id_bolsa = req.params.id_bolsa;
      const texto = req.body.comentario;

      if (!texto || texto.trim() === "") {
        return res.status(400).json({ message: "Comentário vazio" });
      }

      const novoComentario = await Comentario.create({ id_usuario, id_bolsa, texto });
      res.status(201).json(novoComentario);
    } catch (err) {
      console.error(err);
      res.status(500).json({ message: "Erro ao adicionar comentário", error: err.message });
    }
  },

  apagar: async (req, res) => {
    try {
      const id_usuario = req.usuario.id_usuario;
      const tipo_usuario = req.usuario.tipo_usuario.toLowerCase();
      const id_comentario = req.params.id_comentario;

      const comentario = await Comentario.findByPk(id_comentario);
      if (!comentario) return res.status(404).json({ message: "Comentário não encontrado" });

      if (tipo_usuario !== "administrador" && comentario.id_usuario !== id_usuario) {
        return res.status(403).json({ message: "Acesso negado para apagar este comentário" });
      }

      await ComentarioLike.destroy({ where: { id_comentario } });
      await comentario.destroy();
      res.json({ message: "Comentário apagado com sucesso" });
    } catch (err) {
      console.error(err);
      res.status(500).json({ message: "Erro ao apagar comentário", error: err.message });
    }
  },

  curtir: async (req, res) => {
    try {
      const { id_usuario } = req.usuario;
      const { id_comentario } = req.params;

      let registro = await ComentarioLike.findOne({ where: { id_usuario, id_comentario } });
      if (!registro) {
        registro = await ComentarioLike.create({ id_usuario, id_comentario, curtido: true });
        return res.status(201).json({ curtido: true });
      }
      registro.curtido = !registro.curtido;
      await registro.save();
      res.json({ curtido: registro.curtido });
    } catch (err) {
      console.error(err);
      res.status(500).json({ message: "Erro ao curtir comentário", error: err.message });
    }
  },

  descurtir: async (req, res) => {
    try {
      const { id_usuario } = req.usuario;
      const { id_comentario } = req.params;

      let registro = await ComentarioLike.findOne({ where: { id_usuario, id_comentario } });
      if (!registro) {
        registro = await ComentarioLike.create({ id_usuario, id_comentario, descurtido: true });
        return res.status(201).json({ descurtido: true });
      }
      registro.descurtido = !registro.descurtido;
      await registro.save();
      res.json({ descurtido: registro.descurtido });
    } catch (err) {
      console.error(err);
      res.status(500).json({ message: "Erro ao descurtir comentário", error: err.message });
    }
  }
};
