const express = require("express");
const router = express.Router();
const db = require("../config/db");
const multer = require("multer");
const path = require("path");
const bcrypt = require("bcrypt");

// Configuração do upload
const storage = multer.diskStorage({
  destination: (req, file, cb) => cb(null, "public/uploads/"),
  filename: (req, file, cb) => {
    const ext = path.extname(file.originalname);
    cb(null, `user-${req.session.user.id_usuario}-${Date.now()}${ext}`);
  }
});

const upload = multer({ 
  storage,
  limits: { fileSize: 5 * 1024 * 1024 },
  fileFilter: (req, file, cb) => {
    const allowedTypes = /jpeg|jpg|png|webp/;
    const extname = allowedTypes.test(path.extname(file.originalname).toLowerCase());
    const mimetype = allowedTypes.test(file.mimetype);
    
    if (mimetype && extname) {
      return cb(null, true);
    }
    cb(new Error("Apenas imagens são permitidas!"));
  }
});

// Página de perfil
router.get("/perfil", (req, res) => {
  if (!req.session.user) return res.redirect("/");
  
  db.get("SELECT * FROM usuarios WHERE id_usuario = ?", [req.session.user.id_usuario], (err, user) => {
    if (err) {
      console.error("Erro ao buscar usuário:", err);
      return res.status(500).send("Erro ao buscar usuário");
    }
    res.render("perfil", { user });
  });
});

// Atualizar perfil
router.post("/perfil", upload.single("foto"), async (req, res) => {
  console.log("=== INÍCIO DA ATUALIZAÇÃO ===");
  
  if (!req.session.user) {
    console.log("Sessão não encontrada");
    return res.redirect("/");
  }
  
  console.log("Usuário da sessão:", req.session.user.id_usuario);
  
  const { nome, email, senha_atual, senha_nova, senha_confirma } = req.body;
  console.log("Dados recebidos:", { nome, email, tem_senha_atual: !!senha_atual, tem_senha_nova: !!senha_nova });
  
  if (!nome || !email) {
    console.log("Nome ou email vazio");
    return res.status(400).send("Nome e email são obrigatórios");
  }

  try {
    console.log("Buscando usuário no banco...");
    // Buscar usuário atual
    const currentUser = await new Promise((resolve, reject) => {
      db.get("SELECT * FROM usuarios WHERE id_usuario = ?", [req.session.user.id_usuario], (err, row) => {
        if (err) {
          console.log("Erro ao buscar usuário:", err);
          reject(err);
        } else {
          console.log("Usuário encontrado:", row ? "Sim" : "Não");
          resolve(row);
        }
      });
    });

    if (!currentUser) {
      console.log("Usuário não encontrado no banco");
      return res.status(404).send("Usuário não encontrado");
    }

    console.log("Processando foto...");
    // Determinar a foto (com barra no início para funcionar com express.static)
    const foto = req.file ? `/uploads/${req.file.filename}` : currentUser.foto;
    console.log("Foto definida:", foto);

    // Variável para a senha
    let senhaFinal = currentUser.senha;

    // Se está tentando alterar a senha
    if (senha_atual || senha_nova || senha_confirma) {
      console.log("Tentando alterar senha...");
      if (!senha_atual) {
        console.log("Senha atual não fornecida");
        return res.status(400).send("Digite a senha atual para alterar a senha");
      }
      
      if (!senha_nova) {
        console.log("Nova senha não fornecida");
        return res.status(400).send("Digite a nova senha");
      }
      
      if (senha_nova.length < 6) {
        console.log("Nova senha muito curta");
        return res.status(400).send("A nova senha deve ter pelo menos 6 caracteres");
      }
      
      if (senha_nova !== senha_confirma) {
        console.log("Senhas não coincidem");
        return res.status(400).send("As senhas não coincidem");
      }

      console.log("Verificando senha atual...");
      // Verificar senha atual
      const senhaCorreta = await bcrypt.compare(senha_atual, currentUser.senha);
      
      if (!senhaCorreta) {
        console.log("Senha atual incorreta");
        return res.status(400).send("Senha atual incorreta");
      }

      console.log("Criptografando nova senha...");
      // Criptografar nova senha
      senhaFinal = await bcrypt.hash(senha_nova, 10);
      console.log("Nova senha criptografada");
    }

    console.log("Atualizando no banco de dados...");
    // Atualizar no banco
    await new Promise((resolve, reject) => {
      db.run(
        "UPDATE usuarios SET nome = ?, email = ?, foto = ?, senha = ? WHERE id_usuario = ?",
        [nome, email, foto, senhaFinal, req.session.user.id_usuario],
        function(err) {
          if (err) {
            console.log("Erro no UPDATE:", err);
            reject(err);
          } else {
            console.log("UPDATE executado com sucesso. Linhas afetadas:", this.changes);
            resolve();
          }
        }
      );
    });

    console.log("Atualizando sessão...");
    // Atualizar sessão
    req.session.user.nome = nome;
    req.session.user.email = email;
    req.session.user.foto = foto;

    console.log("Salvando sessão e redirecionando...");
    // Salvar e redirecionar
    req.session.save((err) => {
      if (err) console.error("Erro ao salvar sessão:", err);
      console.log("=== ATUALIZAÇÃO CONCLUÍDA COM SUCESSO ===");
      res.redirect("/perfil");
    });

  } catch (err) {
    console.error("===== ERRO DETALHADO =====");
    console.error("Mensagem:", err.message);
    console.error("Stack:", err.stack);
    console.error("Dados recebidos:", { nome, email, tem_arquivo: !!req.file });
    console.error("==========================");
    return res.status(500).send("Erro ao atualizar perfil: " + err.message);
  }
});

module.exports = router;