const { Usuario } = require('../models');
const bcrypt = require("bcryptjs");
const jwt = require("jsonwebtoken");

module.exports = {
  // Cadastro 
  async registrar(req, res) {
    try {
      const { nome, email, senha, confirmarSenha, tipo_usuario } = req.body;

      if (!nome || !email || !senha || !confirmarSenha) {
        return res.status(400).json({ message: "Preencha todos os campos" });
      }

      if (senha !== confirmarSenha) {
        return res.status(400).json({ message: "As senhas não coincidem" });
      }

      const existe = await Usuario.findOne({ where: { email } });
      if (existe) return res.status(400).json({ message: "Email já cadastrado" });

      const hashedSenha = await bcrypt.hash(senha, 10);

      const novoUsuario = await Usuario.create({
        nome,
        email,
        senha: hashedSenha,
        tipo_usuario: tipo_usuario ? tipo_usuario.toLowerCase() : "usuario"
      });

      res.status(201).json({ message: "Usuário cadastrado com sucesso", usuario: novoUsuario });

    } catch (error) {
      console.error(error);
      res.status(500).json({ message: "Erro ao registrar o usuário" });
    }
  },

  // Login 
  async login(req, res) {
    try {
      const { email, senha } = req.body;

      if (!email || !senha) {
        return res.status(400).json({ message: "Preencha todos os campos" });
      }

      const usuario = await Usuario.findOne({ where: { email } });
      if (!usuario) return res.status(404).json({ message: "Usuário não encontrado" });

      const senhasIguais = await bcrypt.compare(senha, usuario.senha);
      if (!senhasIguais) return res.status(401).json({ message: "Senha incorreta" });

      const token = jwt.sign(
        { id: usuario.id_usuario, tipo_usuario: usuario.tipo_usuario.toLowerCase() },
        process.env.JWT_SECRET || "sua-chave-secreta"
      );

      res.status(200).json({
        auth: true,
        token,
        tipo_usuario: usuario.tipo_usuario.toLowerCase(),
        id: usuario.id_usuario  // <-- Enviando o id do usuário aqui
      });

    } catch (error) {
      console.error(error);
      res.status(500).json({ message: "Erro ao fazer login" });
    }
  },

  // Logout (sem alterações)
  async logout(req, res) {
    res.status(200).json({ auth: false, token: null, message: "Logout realizado com sucesso" });
  }
};