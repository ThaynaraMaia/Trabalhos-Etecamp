const { Usuario, Bolsa } = require('../models');

const principal = "Rayssa";

module.exports = {
  // ==================== USUÁRIOS ====================
  async listar(req, res) {
    try {
      const usuarios = await Usuario.findAll({
        attributes: ['id_usuario', 'nome', 'email', 'tipo_usuario']
      });
      res.json(usuarios);
    } catch (err) {
      console.error(err);
      res.status(500).json({ message: "Erro ao listar usuários" });
    }
  },

  async promover(req, res) {
    try {
      const id = req.params.id;
      const { tipo_usuario } = req.body;

      const usuario = await Usuario.findByPk(id);

      if (!usuario) return res.status(404).json({ message: "Usuário não encontrado" });

      if (usuario.nome.toLowerCase() === principal.toLowerCase()) {
        return res.status(403).json({ message: "Não é possível alterar o administrador principal" });
      }

      usuario.tipo_usuario = tipo_usuario;
      await usuario.save();

      res.json({ message: "Usuário atualizado com sucesso" });
    } catch (err) {
      console.error(err);
      res.status(500).json({ message: "Erro ao atualizar usuário" });
    }
  },

  async excluir(req, res) {
    try {
      const id = req.params.id;
      const usuario = await Usuario.findByPk(id);

      if (!usuario) return res.status(404).json({ message: "Usuário não encontrado" });

      if (usuario.nome.toLowerCase() === principal.toLowerCase()) {
        return res.status(403).json({ message: "Não é possível excluir o administrador principal" });
      }

      await usuario.destroy();
      res.json({ message: "Usuário excluído com sucesso" });
    } catch (err) {
      console.error(err);
      res.status(500).json({ message: "Erro ao excluir usuário" });
    }
  },

};
