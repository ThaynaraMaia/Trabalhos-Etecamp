const express = require('express');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');
const db = require('../config/db');
const Punicao = require('../models/Punicao');
require('dotenv').config();

const router = express.Router();

const JWT_SECRET = process.env.JWT_SECRET || 'dev_secret';
const JWT_EXPIRES_IN = process.env.JWT_EXPIRES_IN || '2h';

router.post('/login', async (req, res) => {
  const { email, senha } = req.body;

  try {
    const [rows] = await db.query('SELECT * FROM usuario WHERE Email = ?', [email]);
    const user = rows[0];

    if (!user) {
      return res.status(401).json({ message: 'Email ou senha inválidos' });
    }

    const senhaOk = await bcrypt.compare(senha, user.Senha);
    if (!senhaOk) {
      return res.status(401).json({ message: 'Email ou senha inválidos' });
    }

    if (user.Status === 'inativo') {
      return res.status(403).json({
        message: 'Usuário inativo. Entre em contato com o suporte.'
      });
    }

    // 🔹 Atualiza punições expiradas
    await Punicao.updateExpired();

    // 🔹 Verifica punição ativa
    const punicaoAtiva = await Punicao.hasActivePunishment(user.Id_usuario);
    console.log(punicaoAtiva)
    if (punicaoAtiva) {
      const tipo = (punicaoAtiva.Tipo || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');

      // 🔸 Bloqueia login se for suspensão ou banimento
      if (tipo.includes('susp') || tipo.includes('ban')) {
        return res.status(403).json({
          message: `Acesso bloqueado: você possui uma punição ativa ${
            punicaoAtiva.Data_fim ? ` até ${new Date(punicaoAtiva.Data_fim).toLocaleString('pt-BR')}` : ''
          }.`,
          punicao: punicaoAtiva
        });
      }
    }

    // 🔹 Gera token normalmente (usuário liberado)
    const token = jwt.sign(
      {
        id: user.Id_usuario,
        email: user.Email,
        tipo: user.Tipo,
        status: user.Status
      },
      JWT_SECRET,
      { expiresIn: JWT_EXPIRES_IN }
    );

    res.json({
      token,
      user: {
        id: user.Id_usuario,
        apelido: user.Apelido,
        email: user.Email,
        status: user.Status,
        tipo: user.Tipo,
        foto: user.Foto,
        nametag: user.nametag
      }
    });

  } catch (err) {
    console.error('Erro no login:', err);
    res.status(500).json({ message: 'Erro interno no servidor.' });
  }
});

module.exports = router;
