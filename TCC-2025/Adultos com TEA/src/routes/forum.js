const express = require("express");
const router = express.Router();
const db = require("../config/db");

// Middleware para verificar login
function requireLogin(req, res, next) {
  if (!req.session.user) {
    return res.redirect("/login");
  }
  next();
}

// GET - Página do fórum (todos os posts unificados)
router.get("/forum", requireLogin, async (req, res) => {
  const isAdmin = req.session.user.tipo === "Administrador";
  console.log(`${isAdmin ? 'Admin' : 'Usuário'} acessando fórum`);
  
  try {
    // Buscar todos os posts
    const posts = await new Promise((resolve, reject) => {
      const sql = `
        SELECT p.*, u.nome as autor, u.foto as autor_foto, u.tipo as autor_tipo, u.id_usuario as user_id
        FROM posts p
        LEFT JOIN usuarios u ON p.user_id = u.id_usuario
        ORDER BY p.data_criacao DESC
      `;
      
      db.all(sql, [], (err, rows) => {
        if (err) reject(err);
        else resolve(rows || []);
      });
    });

    console.log(`Total de posts encontrados: ${posts.length}`);

    if (posts.length === 0) {
      console.log("Nenhum post encontrado");
      return res.render("forum", { posts: [], user: req.session.user });
    }

    // Buscar respostas para cada post usando Promise.all
    const postsComRespostas = await Promise.all(
      posts.map(async (post) => {
        const respostas = await new Promise((resolve, reject) => {
          const sqlRespostas = `
            SELECT r.*, u.nome as autor, u.foto as autor_foto, u.tipo as autor_tipo, u.id_usuario as autor_id
            FROM respostas r
            LEFT JOIN usuarios u ON r.user_id = u.id_usuario
            WHERE r.post_id = ?
            ORDER BY r.data_criacao ASC
          `;
          
          db.all(sqlRespostas, [post.id], (err, rows) => {
            if (err) reject(err);
            else resolve(rows || []);
          });
        });

        return {
          ...post,
          respostas: respostas
        };
      })
    );

    console.log(`Posts processados com respostas: ${postsComRespostas.length}`);
    
    res.render("forum", { 
      posts: postsComRespostas, 
      user: req.session.user 
    });

  } catch (error) {
    console.error("Erro ao buscar posts:", error);
    res.render("forum", { posts: [], user: req.session.user });
  }
});

// POST - Criar novo post
router.post("/forum/post", requireLogin, (req, res) => {
  const { conteudo, tipo_post } = req.body;
  const userId = req.session.user.id_usuario;
  const autor = req.session.user.nome;

  if (!conteudo || conteudo.trim().length === 0) {
    if (req.headers['content-type']?.includes('application/json')) {
      return res.json({ success: false, message: "Conteúdo vazio" });
    }
    return res.redirect("/forum");
  }

  // Valida o tipo do post
  const tipoPost = (tipo_post === 'open' || tipo_post === 'admin-only') ? tipo_post : 'admin-only';

  const sql = `INSERT INTO posts (user_id, autor, conteudo, tipo_post) VALUES (?, ?, ?, ?)`;

  db.run(sql, [userId, autor, conteudo, tipoPost], function(err) {
    if (err) {
      console.error("Erro ao criar post:", err);
      if (req.headers['content-type']?.includes('application/json')) {
        return res.json({ success: false, message: "Erro ao criar post" });
      }
      return res.redirect("/forum");
    }

    console.log(`Usuário ${autor} criou post ID ${this.lastID} (tipo: ${tipoPost})`);
    
    if (req.headers['content-type']?.includes('application/json')) {
      return res.json({ 
        success: true, 
        message: "Post criado com sucesso!",
        postId: this.lastID 
      });
    }
    
    res.redirect("/forum");
  });
});

// POST - Adicionar resposta
router.post("/forum/resposta", requireLogin, (req, res) => {
  const { post_id, conteudo } = req.body;
  const userId = req.session.user.id_usuario;
  const autor = req.session.user.nome;
  const isAdmin = req.session.user.tipo === "Administrador";

  if (!conteudo || conteudo.trim().length === 0) {
    return res.json({ success: false, message: "Conteúdo vazio" });
  }

  // Busca o tipo do post para verificar permissões
  db.get("SELECT tipo_post FROM posts WHERE id = ?", [post_id], (err, post) => {
    if (err) {
      console.error("Erro ao buscar post:", err);
      return res.json({ success: false, message: "Erro ao verificar post" });
    }

    if (!post) {
      return res.json({ success: false, message: "Post não encontrado" });
    }

    // Verifica permissões
    if (post.tipo_post === 'admin-only' && !isAdmin) {
      return res.json({ 
        success: false, 
        message: "Apenas especialistas podem responder este post" 
      });
    }

    const sql = `INSERT INTO respostas (post_id, user_id, autor, conteudo) VALUES (?, ?, ?, ?)`;

    db.run(sql, [post_id, userId, autor, conteudo], function(err) {
      if (err) {
        console.error("Erro ao criar resposta:", err);
        return res.json({ success: false, message: "Erro ao criar resposta" });
      }

      console.log(`Usuário ${autor} respondeu post ${post_id}`);
      res.json({ 
        success: true, 
        message: "Resposta adicionada!"
      });
    });
  });
});

// DELETE - Deletar post
router.delete("/forum/post/:id", requireLogin, (req, res) => {
  const postId = req.params.id;
  const userId = req.session.user.id_usuario;
  const isAdmin = req.session.user.tipo === "Administrador";

  // Admin pode deletar qualquer post
  if (isAdmin) {
    db.run("DELETE FROM posts WHERE id = ?", [postId], function(err) {
      if (err) {
        console.error("Erro ao deletar post:", err);
        return res.json({ success: false, message: "Erro ao deletar post" });
      }

      console.log(`Admin deletou post ${postId}`);
      res.json({ success: true, message: "Post deletado com sucesso" });
    });
    return;
  }

  // Usuário comum só pode deletar seus próprios posts
  db.get("SELECT user_id FROM posts WHERE id = ?", [postId], (err, post) => {
    if (err) {
      console.error("Erro ao buscar post:", err);
      return res.json({ success: false, message: "Erro ao buscar post" });
    }

    if (!post) {
      return res.json({ success: false, message: "Post não encontrado" });
    }

    if (post.user_id !== userId) {
      return res.json({ success: false, message: "Você não pode deletar posts de outros usuários" });
    }

    db.run("DELETE FROM posts WHERE id = ?", [postId], function(err) {
      if (err) {
        console.error("Erro ao deletar post:", err);
        return res.json({ success: false, message: "Erro ao deletar post" });
      }

      console.log(`Usuário deletou post ${postId}`);
      res.json({ success: true, message: "Post deletado com sucesso" });
    });
  });
});

// DELETE - Deletar resposta (apenas admins)
router.delete("/forum/resposta/:id", requireLogin, (req, res) => {
  const respostaId = req.params.id;
  const isAdmin = req.session.user.tipo === "Administrador";

  if (!isAdmin) {
    return res.json({ 
      success: false, 
      message: "Apenas administradores podem deletar respostas" 
    });
  }

  db.run("DELETE FROM respostas WHERE id = ?", [respostaId], function(err) {
    if (err) {
      console.error("Erro ao deletar resposta:", err);
      return res.json({ success: false, message: "Erro ao deletar resposta" });
    }

    console.log(`Admin deletou resposta ${respostaId}`);
    res.json({ success: true, message: "Resposta deletada com sucesso" });
  });
});

module.exports = router;