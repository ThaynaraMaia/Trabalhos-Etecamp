const bcrypt = require("bcryptjs");
const { Usuario, Bolsa, Favorito } = require("../models");
const path = require("path");

// ------------------------------
// PERFIL
// ------------------------------

// Buscar perfil
exports.getPerfil = async (req, res) => {
  try {
    const usuario = await Usuario.findByPk(req.usuario.id_usuario, {
      attributes: ["id_usuario", "nome", "email", "foto", "tipo_usuario"] // incluir foto
    });

    if (!usuario) return res.status(404).json({ message: "Usuário não encontrado" });

    res.json(usuario);
  } catch (err) {
    console.error(err);
    res.status(500).json({ message: "Erro ao carregar perfil", error: err.message });
  }
};

// Atualizar perfil
exports.atualizarPerfil = async (req, res) => {
  try {
    const { nome, email, senha } = req.body;
    const usuario = await Usuario.findByPk(req.usuario.id_usuario);

    if (!usuario) return res.status(404).json({ message: "Usuário não encontrado" });

    usuario.nome = nome || usuario.nome;
    usuario.email = email || usuario.email;

    if (senha) {
      const hashed = await bcrypt.hash(senha, 10);
      usuario.senha = hashed;
    }

    await usuario.save();
    res.json({ success: true, message: "Perfil atualizado com sucesso!" });
  } catch (err) {
    res.status(500).json({ success: false, message: "Erro ao atualizar perfil", error: err.message });
  }
};

// Alterar foto do usuário
exports.alterarFotoUsuario = async (req, res) => {
  try {
    if (!req.file) return res.status(400).json({ success: false, message: 'Nenhuma foto enviada' });

    const caminhoFoto = '/uploads/' + req.file.filename;

    await Usuario.update(
      { foto: caminhoFoto },
      { where: { id_usuario: req.usuario.id_usuario } }
    );

    res.json({ success: true, foto: caminhoFoto });
  } catch (err) {
    console.error(err);
    res.status(500).json({ success: false, message: 'Erro ao atualizar foto', error: err.message });
  }
};

// Listar bolsas públicas para usuários
exports.listarBolsasPublicas = async (req, res) => {
  try {
    const bolsas = await Bolsa.findAll();
    res.json(bolsas);
  } catch (err) {
    console.error(err);
    res.status(500).json({ message: "Erro ao listar bolsas" });
  }
};






