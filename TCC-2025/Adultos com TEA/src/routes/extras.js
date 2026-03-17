const express = require("express");
const router = express.Router();
const db = require("../config/db");

// =================== Fórum ===================
let mensagens = []; // Armazena mensagens em memória (pode virar tabela futuramente)

// Página do fórum
router.get("/forum", (req, res) => {
  res.render("forum", { mensagens });
});

// Enviar mensagem
router.post("/forum", (req, res) => {
  if (!req.session.user) return res.json({ success: false });

  const { texto } = req.body;
  const nome = req.session.user.nome;

  if (!texto) return res.json({ success: false });

  const mensagem = { nome, texto };
  mensagens.push(mensagem);

  res.json({ success: true, nome, texto });
});

// =================== Perfil ===================
// REMOVIDO - já existe em perfil.js

// =================== Conquistas ===================
// REMOVIDO - já existe em conquistas.js
// A rota /conquistas agora é gerenciada por routes/conquistas.js

// =================== Agenda ===================

// Página da agenda
router.get("/agenda", (req, res) => {
  if (!req.session.user) return res.redirect("/login");

  const sql = `SELECT * FROM agenda WHERE usuario_id = ? ORDER BY data ASC`;
  db.all(sql, [req.session.user.id_usuario], (err, rows) => {
    if (err) {
      console.error("Erro ao buscar eventos:", err.message);
      return res.render("agenda", { eventos: [] });
    }
    res.render("agenda", { eventos: rows || [] });
  });
});

// Criar evento
router.post("/agenda", (req, res) => {
  if (!req.session.user) return res.redirect("/login");

  const { data, descricao } = req.body;
  if (!data || !descricao) return res.redirect("/agenda");

  const sql = `INSERT INTO agenda (usuario_id, data, descricao) VALUES (?, ?, ?)`;
  db.run(sql, [req.session.user.id_usuario, data, descricao], (err) => {
    if (err) {
      console.error("Erro ao criar evento:", err.message);
    }
    res.redirect("/agenda");
  });
});

// =================== Material de Apoio ===================
// REMOVIDO - já existe em materiais.js

module.exports = router;