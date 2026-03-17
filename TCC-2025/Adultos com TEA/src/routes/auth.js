const express = require("express");
const bcrypt = require("bcrypt");
const db = require("../config/db");
const router = express.Router();

// Middleware para verificar se está autenticado
function isAuthenticated(req, res, next) {
  if (req.session && req.session.user) return next();
  res.redirect("/login");
}

// Middleware para verificar se é administrador
function isAdmin(req, res, next) {
  if (req.session && req.session.user && req.session.user.tipo === "Administrador") {
    return next();
  }
  res.status(403).send("Acesso negado. Apenas administradores.");
}

// Registrar login do dia
async function registrarLoginDoDia(userId) {
  const hoje = new Date().toISOString().split('T')[0];
  return new Promise((resolve, reject) => {
    db.run(
      "INSERT OR IGNORE INTO dias_login (usuario_id, data) VALUES (?, ?)",
      [userId, hoje],
      function(err) {
        if (err) reject(err);
        else resolve(this.changes > 0);
      }
    );
  });
}

// Desbloquear conquista de primeiro login
async function verificarPrimeiroLogin(userId) {
  return new Promise((resolve, reject) => {
    db.get(
      "SELECT id FROM conquistas WHERE usuario_id = ? AND nome = 'first_login'",
      [userId],
      (err, row) => {
        if (err) reject(err);
        else if (!row) {
          db.run(
            "INSERT INTO conquistas (usuario_id, nome, descricao) VALUES (?, ?, ?)",
            [userId, 'first_login', 'Faça seu primeiro login na plataforma'],
            (err) => {
              if (err) reject(err);
              else resolve(true);
            }
          );
        } else resolve(false);
      }
    );
  });
}

// ==================== ROTAS DE AUTENTICAÇÃO ====================

// Cadastro
router.get("/cadastrar", (req, res) => {
  res.render("cadastrar", { title: "Cadastro" });
});

router.post("/cadastrar", async (req, res) => {
  const { nome, email, senha } = req.body;
  const hashedPassword = await bcrypt.hash(senha, 10);

  db.run(
    "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)",
    [nome, email, hashedPassword],
    (err) => {
      if (err) return res.render("cadastrar", { 
        errorMessage: "Erro ao cadastrar usuário. Email já pode estar em uso.",
        nome, email, title: "Cadastro"
      });
      res.redirect("/login");
    }
  );
});

// Login
router.get("/login", (req, res) => {
  res.render("login", { title: "Login" });
});

router.post("/login", async (req, res) => {
  const { email, senha } = req.body;

  db.get("SELECT * FROM usuarios WHERE email = ?", [email], async (err, user) => {
    if (err) return res.render("login", { error: "Erro no servidor. Tente novamente.", title: "Login" });
    if (!user) return res.render("login", { error: "Email ou senha incorretos", title: "Login" });

    bcrypt.compare(senha, user.senha, async (err, match) => {
      if (err) return res.render("login", { error: "Erro no servidor. Tente novamente.", title: "Login" });
      if (!match) return res.render("login", { error: "Email ou senha incorretos", title: "Login" });

      // ✅ Adiciona a foto do usuário na sessão
      req.session.user = {
        id_usuario: user.id_usuario,
        nome: user.nome,
        email: user.email,
        tipo: user.tipo,
        foto: user.foto || null
      };

      req.session.save(async () => {
        try {
          const novoLogin = await registrarLoginDoDia(user.id_usuario);
          if (novoLogin) console.log('Novo dia de login registrado');
          await verificarPrimeiroLogin(user.id_usuario);
        } catch (loginErr) {
          console.error('Erro ao registrar login do dia:', loginErr);
        }

        // ✅ TODOS os usuários vão para /user/home, inclusive administradores
        res.redirect("/user/home");
      });
    });
  });
});

// Logout
router.get("/logout", (req, res) => {
  req.session.destroy();
  res.redirect("/login");
});

// ==================== ROTAS ADMIN ====================
// ✅ REMOVIDA A ROTA /admin/admhome - agora não existe mais home separada

router.get("/admin/usuarios", isAuthenticated, isAdmin, (req, res) => {
  res.render("admin/adminusuarios", { title: "Gerenciar Usuários", user: req.session.user });
});

router.get("/admforum", isAuthenticated, isAdmin, (req, res) => {
  res.render("admin/admforum", { title: "Moderar Fórum", user: req.session.user });
});

router.get("/admconquistas", isAuthenticated, isAdmin, (req, res) => {
  res.render("admin/admconquistas", { title: "Gerenciar Conquistas", user: req.session.user });
});

// ✅ Rota para home do usuário (ÚNICA HOME PARA TODOS)
router.get("/user/home", isAuthenticated, (req, res) => {
  res.render("home", { user: req.session.user, title: "Home do Usuário" });
});

module.exports = router;