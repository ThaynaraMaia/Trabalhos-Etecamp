const express = require("express");
const router = express.Router();
const db = require("../config/db");

// GET: página de materiais para usuário
router.get("/materiais", (req, res) => {
  if (!req.session.user) return res.redirect("/login");

  const sql = `SELECT * FROM materiais ORDER BY emocao, id DESC`;
  
  db.all(sql, [], (err, materiais) => {
    if (err) {
      console.error("Erro ao buscar materiais:", err.message);
      return res.render("materiais", { 
        materiaisPorEmocao: {},
        materiaisJSON: '{}',
        user: req.session.user 
      });
    }

    const materiaisPorEmocao = {};
    if (materiais && materiais.length > 0) {
      materiais.forEach(material => {
        if (!materiaisPorEmocao[material.emocao]) {
          materiaisPorEmocao[material.emocao] = [];
        }
        materiaisPorEmocao[material.emocao].push(material);
      });
    }

    res.render("materiais", { 
      materiaisPorEmocao: materiaisPorEmocao,
      materiaisJSON: JSON.stringify(materiaisPorEmocao),
      user: req.session.user 
    });
  });
});

// POST: registrar acesso a material (para conquistas)
router.post("/materiais/acessar/:id", (req, res) => {
  if (!req.session.user) {
    return res.json({ success: false, message: "Não autenticado" });
  }

  const userId = req.session.user.id_usuario;
  const materialId = req.params.id;

  const sql = `INSERT INTO materiais_acessados (usuario_id, material_id) VALUES (?, ?)`;
  
  db.run(sql, [userId, materialId], function(err) {
    if (err) {
      if (err.message.includes('UNIQUE')) {
        return res.json({ success: true, message: "Acesso já registrado" });
      }
      console.error("Erro ao registrar acesso:", err.message);
      return res.json({ success: false, message: "Erro ao registrar acesso" });
    }

    console.log(`📚 Material acessado! Material: ${materialId} - Usuário: ${userId}`);
    res.json({ success: true, shouldCheckAchievements: true });
  });
});

// GET: página admin de gerenciar materiais
router.get("/admin/admmaterial", (req, res) => {
  if (!req.session.user || req.session.user.tipo !== "Administrador") {
    return res.redirect("/");
  }

  const sql = `SELECT * FROM materiais ORDER BY emocao, id DESC`;
  
  db.all(sql, [], (err, materiais) => {
    if (err) {
      console.error("Erro ao buscar materiais admin:", err);
      return res.render("admin/admmaterial", { 
        materiaisPorEmocao: {},
        user: req.session.user 
      });
    }

    const materiaisPorEmocao = {};
    if (materiais && materiais.length > 0) {
      materiais.forEach(material => {
        if (!materiaisPorEmocao[material.emocao]) {
          materiaisPorEmocao[material.emocao] = [];
        }
        materiaisPorEmocao[material.emocao].push(material);
      });
    }

    res.render("admin/admmaterial", { 
      materiaisPorEmocao: materiaisPorEmocao,
      user: req.session.user 
    });
  });
});

// POST: adicionar material (admin) - Suporta vídeos e textos
router.post("/admin/admmaterial", (req, res) => {
  console.log("=== REQUISIÇÃO RECEBIDA ===");
  console.log("Body:", req.body);
  
  if (!req.session.user || req.session.user.tipo !== "Administrador") {
    console.log("Acesso negado");
    return res.status(403).json({ success: false, error: "Acesso negado" });
  }

  const { tipo, emocao, titulo, descricao, url, icone, texto, fontes } = req.body;

  if (!emocao || !titulo) {
    return res.status(400).json({ 
      success: false, 
      message: "Preencha emoção e título" 
    });
  }

  // Validar campos específicos por tipo
  if (tipo === 'video' && !url) {
    return res.status(400).json({ 
      success: false, 
      message: "URL é obrigatória para vídeos" 
    });
  }

  if (tipo === 'texto' && !texto) {
    return res.status(400).json({ 
      success: false, 
      message: "Texto é obrigatório para materiais de texto" 
    });
  }

  const sql = `INSERT INTO materiais (tipo, emocao, titulo, descricao, url, icone, texto, fontes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)`;
  const params = [
    tipo || 'video',
    emocao, 
    titulo, 
    descricao || '', 
    url || '', 
    icone || (tipo === 'texto' ? '📖' : '🎬'),
    texto || '',
    fontes || ''
  ];
  
  db.run(sql, params, function(err) {
    if (err) {
      console.error("ERRO:", err.message);
      return res.status(500).json({ 
        success: false, 
        message: "Erro: " + err.message
      });
    }

    console.log(`✅ Material adicionado! ID: ${this.lastID}, Tipo: ${tipo}`);
    res.json({ success: true, id: this.lastID });
  });
});

// DELETE: excluir material (admin)
router.delete("/admin/admmaterial/:id", (req, res) => {
  if (!req.session.user || req.session.user.tipo !== "Administrador") {
    return res.status(403).json({ success: false, error: "Acesso negado" });
  }

  const { id } = req.params;
  const sql = `DELETE FROM materiais WHERE id = ?`;
  
  db.run(sql, [id], function(err) {
    if (err) {
      console.error("Erro ao excluir:", err.message);
      return res.status(500).json({ success: false, message: "Erro ao excluir" });
    }

    console.log(`🗑️ Deletado! ID: ${id}`);
    res.json({ success: true });
  });
});

module.exports = router;