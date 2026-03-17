const express = require("express");
const router = express.Router();
const db = require("../config/db");

// Middleware para verificar login
function requireLogin(req, res, next) {
  if (!req.session.user) {
    return res.status(401).json({ message: "Não autenticado" });
  }
  next();
}

// Verificar conquistas da agenda
function verificarConquistasAgenda(userId) {
  db.get(
    "SELECT COUNT(*) as total FROM agenda WHERE usuario_id = ?",
    [userId],
    (err, row) => {
      if (err) {
        console.error("Erro ao contar eventos:", err);
        return;
      }

      const total = row.total;

      if (total >= 1) {
        db.run(
          `INSERT OR IGNORE INTO conquistas (usuario_id, nome, descricao) 
           VALUES (?, 'Organizador Iniciante', 'Adicionou seu primeiro evento na agenda')`,
          [userId]
        );
      }

      if (total >= 5) {
        db.run(
          `INSERT OR IGNORE INTO conquistas (usuario_id, nome, descricao) 
           VALUES (?, 'Planejador', 'Adicionou 5 eventos na agenda')`,
          [userId]
        );
      }

      if (total >= 10) {
        db.run(
          `INSERT OR IGNORE INTO conquistas (usuario_id, nome, descricao) 
           VALUES (?, 'Mestre do Tempo', 'Adicionou 10 eventos na agenda')`,
          [userId]
        );
      }
    }
  );
}

// Renderizar página
router.get("/agenda", requireLogin, (req, res) => {
  res.render("agenda", {
    user: req.session.user,
    layout: "main"
  });
});

// Listar eventos
router.get("/eventos", requireLogin, (req, res) => {
  const userId = req.session.user.id_usuario;

  db.all(
    "SELECT * FROM agenda WHERE usuario_id = ? ORDER BY data ASC",
    [userId],
    (err, results) => {
      if (err) {
        console.error(err);
        return res.status(500).json({ message: "Erro ao carregar eventos." });
      }
      res.json(results || []);
    }
  );
});

// Adicionar evento
router.post("/eventos", requireLogin, (req, res) => {
  const { titulo, data } = req.body;
  const userId = req.session.user.id_usuario;

  if (!titulo || !data) {
    return res.status(400).json({ message: "Título e data obrigatórios" });
  }

  db.run(
    "INSERT INTO agenda (usuario_id, data, descricao) VALUES (?, ?, ?)",
    [userId, data, titulo],
    function (err) {
      if (err) {
        console.error(err);
        return res.status(500).json({ message: "Erro ao adicionar evento." });
      }

      verificarConquistasAgenda(userId);

      res.status(201).json({
        message: "Evento adicionado com sucesso.",
        id: this.lastID
      });
    }
  );
});

// Editar evento
router.put("/eventos/:id", requireLogin, (req, res) => {
  const { titulo, data } = req.body;
  const eventId = req.params.id;
  const userId = req.session.user.id_usuario;

  db.run(
    "UPDATE agenda SET data = ?, descricao = ? WHERE id = ? AND usuario_id = ?",
    [data, titulo, eventId, userId],
    function (err) {
      if (err) {
        console.error(err);
        return res.status(500).json({ message: "Erro ao editar evento." });
      }
      res.status(200).json({ message: "Evento editado com sucesso." });
    }
  );
});

// Excluir evento
router.delete("/eventos/:id", requireLogin, (req, res) => {
  const eventId = req.params.id;
  const userId = req.session.user.id_usuario;

  db.run(
    "DELETE FROM agenda WHERE id = ? AND usuario_id = ?",
    [eventId, userId],
    function (err) {
      if (err) {
        console.error(err);
        return res.status(500).json({ message: "Erro ao excluir evento." });
      }
      res.status(200).json({ message: "Evento excluído com sucesso." });
    }
  );
});

module.exports = router;