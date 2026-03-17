const express = require("express");
const router = express.Router();

// Página inicial
router.get("/", (req, res) => {
  if (req.session.user) {
    // ✅ Usuário logado → redireciona para /user/home (não renderiza direto)
    res.redirect("/user/home");
  } else {
    // Usuário visitante → página inicial aberta
    res.render("visitante", { 
      layout: "main",
      title: "AdapTEA" 
    });
  }
});

module.exports = router;