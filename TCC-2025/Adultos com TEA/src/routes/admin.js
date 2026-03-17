const express = require("express");
const router = express.Router();
const db = require("../config/db");

// Middleware para verificar se é administrador
function isAdmin(req, res, next) {
  if (!req.session.user || req.session.user.tipo !== "Administrador") {
    return res.status(403).send("Acesso negado");
  }
  next();
}


// Página de listagem de usuários
router.get("/admin/adm-usuarios", isAdmin, (req, res) => {
  const success = req.query.success;
  const error = req.query.error;
  
  db.all("SELECT * FROM usuarios ORDER BY id_usuario DESC", (err, usuarios) => {
    if (err) {
      console.error("Erro ao buscar usuários:", err);
      return res.status(500).send("Erro no banco de dados");
    }
    res.render("admin/adm-usuarios", { 
      usuarios: usuarios || [],
      user: req.session.user,
      title: "Gerenciar Usuários",
      layout: false,
      success: success,
      error: error
    });
  });
});

// Deletar usuário
router.delete("/admin/usuarios/:id", isAdmin, (req, res) => {
  const userId = req.params.id;

  if (parseInt(userId) === req.session.user.id_usuario) {
    return res.json({ success: false, message: "Você não pode deletar sua própria conta" });
  }

  db.run("DELETE FROM usuarios WHERE id_usuario = ?", [userId], function(err) {
    if (err) {
      console.error("Erro ao deletar:", err);
      return res.json({ success: false, message: "Erro ao deletar usuário" });
    }
    res.json({ success: true });
  });
});

// Promover usuário para Administrador
router.get("/admin/usuarios/promover/:id", isAdmin, (req, res) => {
  const userId = req.params.id;

  if (parseInt(userId) === req.session.user.id_usuario) {
    return res.redirect("/admin/adm-usuarios?error=Você não pode alterar seu próprio tipo");
  }

  db.run(
    "UPDATE usuarios SET tipo = ? WHERE id_usuario = ?",
    ["Administrador", userId],
    function(err) {
      if (err) {
        console.error("Erro ao promover:", err);
        return res.redirect("/admin/adm-usuarios?error=Erro ao promover usuário");
      }
      res.redirect("/admin/adm-usuarios?success=Usuário promovido a Administrador com sucesso");
    }
  );
});

// Rebaixar usuário para Adulto com TEA
router.get("/admin/usuarios/rebaixar/:id", isAdmin, (req, res) => {
  const userId = req.params.id;

  if (parseInt(userId) === req.session.user.id_usuario) {
    return res.redirect("/admin/adm-usuarios?error=Você não pode alterar seu próprio tipo");
  }

  db.run(
    "UPDATE usuarios SET tipo = ? WHERE id_usuario = ?",
    ["Adulto com TEA", userId],
    function(err) {
      if (err) {
        console.error("Erro ao rebaixar:", err);
        return res.redirect("/admin/adm-usuarios?error=Erro ao rebaixar usuário");
      }
      res.redirect("/admin/adm-usuarios?success=Usuário rebaixado para Adulto com TEA com sucesso");
    }
  );
});

// Promover/Rebaixar usuário (mantido para compatibilidade com chamadas PUT)
router.put("/admin/usuarios/:id/tipo", isAdmin, (req, res) => {
  const userId = req.params.id;
  const { tipo } = req.body;

  if (parseInt(userId) === req.session.user.id_usuario) {
    return res.json({ success: false, message: "Você não pode alterar seu próprio tipo" });
  }

  db.run(
    "UPDATE usuarios SET tipo = ? WHERE id_usuario = ?",
    [tipo, userId],
    function(err) {
      if (err) {
        console.error("Erro ao atualizar:", err);
        return res.json({ success: false, message: "Erro ao atualizar usuário" });
      }
      res.json({ success: true });
    }
  );
});

module.exports = router;