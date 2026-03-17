// routes/desafios.js
const express = require('express');
const router = express.Router();
const sqlite3 = require('sqlite3').verbose();
const db = new sqlite3.Database('./database.db');

// Middleware para verificar autenticação
function requireAuth(req, res, next) {
  if (!req.session || !req.session.userId) {
    return res.status(401).json({ error: 'Não autenticado' });
  }
  next();
}

// Inicializar desafios para um usuário novo
function inicializarDesafiosUsuario(userId, callback) {
  db.all('SELECT id FROM desafios_templates WHERE ativo = 1', [], (err, desafios) => {
    if (err) return callback(err);
    
    const stmt = db.prepare(`
      INSERT OR IGNORE INTO usuario_desafios (usuario_id, desafio_id, progresso)
      VALUES (?, ?, 0)
    `);
    
    desafios.forEach(desafio => {
      stmt.run(userId, desafio.id);
    });
    
    stmt.finalize(callback);
  });
}

// Inicializar stats do usuário
function inicializarStatsUsuario(userId, callback) {
  db.run(`
    INSERT OR IGNORE INTO usuario_stats (usuario_id, pontos_total, nivel)
    VALUES (?, 0, 1)
  `, [userId], callback);
}

// Registrar ação do usuário
function registrarAcao(userId, tipoAcao, referenciaId = null) {
  db.run(`
    INSERT INTO usuario_acoes (usuario_id, tipo_acao, referencia_id)
    VALUES (?, ?, ?)
  `, [userId, tipoAcao, referenciaId]);
  
  // Atualizar progresso dos desafios relacionados
  atualizarProgressoDesafios(userId, tipoAcao);
}

// Atualizar progresso dos desafios
function atualizarProgressoDesafios(userId, tipoAcao) {
  // Mapear tipo de ação para tipo de desafio
  const tipoDesafioMap = {
    'forum_post': 'forum',
    'resposta_forum': 'social',
    'agenda_create': 'agenda',
    'login': 'login'
  };
  
  const tipoDesafio = tipoDesafioMap[tipoAcao];
  if (!tipoDesafio) return;
  
  // Buscar desafios não completados do tipo
  db.all(`
    SELECT ud.id, ud.desafio_id, ud.progresso, dt.meta, dt.pontos, dt.periodicidade
    FROM usuario_desafios ud
    JOIN desafios_templates dt ON ud.desafio_id = dt.id
    WHERE ud.usuario_id = ? AND ud.completado = 0 AND dt.tipo = ?
  `, [userId, tipoDesafio], (err, desafios) => {
    if (err) return console.error(err);
    
    desafios.forEach(desafio => {
      const novoProgresso = desafio.progresso + 1;
      
      if (novoProgresso >= desafio.meta) {
        // Desafio completado!
        completarDesafio(userId, desafio.desafio_id, desafio.pontos);
      } else {
        // Atualizar progresso
        db.run(`
          UPDATE usuario_desafios
          SET progresso = ?
          WHERE id = ?
        `, [novoProgresso, desafio.id]);
      }
    });
  });
}

// Completar desafio
function completarDesafio(userId, desafioId, pontos) {
  db.run(`
    UPDATE usuario_desafios
    SET completado = 1, data_conclusao = CURRENT_TIMESTAMP, pontos_ganhos = ?
    WHERE usuario_id = ? AND desafio_id = ?
  `, [pontos, userId, desafioId], (err) => {
    if (err) return console.error(err);
    
    // Atualizar stats do usuário
    db.run(`
      UPDATE usuario_stats
      SET pontos_total = pontos_total + ?,
          desafios_completados = desafios_completados + 1
      WHERE usuario_id = ?
    `, [pontos, userId]);
    
    // Atualizar nível baseado nos pontos
    atualizarNivel(userId);
  });
}

// Atualizar nível do usuário
function atualizarNivel(userId) {
  db.get(`
    SELECT pontos_total FROM usuario_stats WHERE usuario_id = ?
  `, [userId], (err, row) => {
    if (err || !row) return;
    
    // Fórmula: nível = raiz(pontos / 100) + 1
    const novoNivel = Math.floor(Math.sqrt(row.pontos_total / 100)) + 1;
    
    db.run(`
      UPDATE usuario_stats SET nivel = ? WHERE usuario_id = ?
    `, [novoNivel, userId]);
  });
}

// GET - Buscar todos os desafios do usuário
router.get('/api/desafios', requireAuth, (req, res) => {
  const userId = req.session.userId;
  
  // Garantir que o usuário tem stats e desafios inicializados
  inicializarStatsUsuario(userId, () => {
    inicializarDesafiosUsuario(userId, () => {
      db.all(`
        SELECT 
          dt.id,
          dt.nome,
          dt.descricao,
          dt.tipo,
          dt.meta,
          dt.pontos,
          dt.icone,
          dt.periodicidade,
          COALESCE(ud.progresso, 0) as progresso,
          COALESCE(ud.completado, 0) as completado,
          ud.data_conclusao,
          ud.pontos_ganhos
        FROM desafios_templates dt
        LEFT JOIN usuario_desafios ud ON dt.id = ud.desafio_id AND ud.usuario_id = ?
        WHERE dt.ativo = 1
        ORDER BY dt.periodicidade, ud.completado, dt.pontos
      `, [userId], (err, rows) => {
        if (err) {
          return res.status(500).json({ error: err.message });
        }
        res.json(rows);
      });
    });
  });
});

// GET - Buscar estatísticas do usuário
router.get('/api/desafios/stats', requireAuth, (req, res) => {
  const userId = req.session.userId;
  
  inicializarStatsUsuario(userId, () => {
    db.get(`
      SELECT * FROM usuario_stats WHERE usuario_id = ?
    `, [userId], (err, row) => {
      if (err) {
        return res.status(500).json({ error: err.message });
      }
      res.json(row || { pontos_total: 0, nivel: 1, desafios_completados: 0 });
    });
  });
});

// GET - Ranking de usuários
router.get('/api/desafios/ranking', requireAuth, (req, res) => {
  db.all(`
    SELECT 
      u.nome,
      us.pontos_total,
      us.nivel,
      us.desafios_completados,
      RANK() OVER (ORDER BY us.pontos_total DESC) as posicao
    FROM usuario_stats us
    JOIN usuarios u ON us.usuario_id = u.id_usuario
    ORDER BY us.pontos_total DESC
    LIMIT 10
  `, [], (err, rows) => {
    if (err) {
      return res.status(500).json({ error: err.message });
    }
    res.json(rows);
  });
});

// POST - Registrar ação (chamado automaticamente por outras rotas)
router.post('/api/desafios/acao', requireAuth, (req, res) => {
  const { tipoAcao, referenciaId } = req.body;
  const userId = req.session.userId;
  
  if (!tipoAcao) {
    return res.status(400).json({ error: 'Tipo de ação é obrigatório' });
  }
  
  registrarAcao(userId, tipoAcao, referenciaId);
  res.json({ success: true });
});

// POST - Atualizar streak de login
router.post('/api/desafios/login', requireAuth, (req, res) => {
  const userId = req.session.userId;
  const hoje = new Date().toISOString().split('T')[0];
  
  db.get(`
    SELECT ultimo_login, streak_dias FROM usuario_stats WHERE usuario_id = ?
  `, [userId], (err, row) => {
    if (err) return res.status(500).json({ error: err.message });
    
    let novoStreak = 1;
    
    if (row && row.ultimo_login) {
      const ultimoLogin = new Date(row.ultimo_login);
      const hoje_date = new Date(hoje);
      const diffDias = Math.floor((hoje_date - ultimoLogin) / (1000 * 60 * 60 * 24));
      
      if (diffDias === 0) {
        // Já fez login hoje
        return res.json({ streak: row.streak_dias });
      } else if (diffDias === 1) {
        // Login consecutivo
        novoStreak = row.streak_dias + 1;
      } else {
        // Quebrou o streak
        novoStreak = 1;
      }
    }
    
    db.run(`
      UPDATE usuario_stats
      SET ultimo_login = ?, streak_dias = ?
      WHERE usuario_id = ?
    `, [hoje, novoStreak, userId], () => {
      registrarAcao(userId, 'login');
      res.json({ streak: novoStreak });
    });
  });
});

// Middleware para ser usado em outras rotas (forum, agenda)
router.trackAction = (tipoAcao) => {
  return (req, res, next) => {
    if (req.session && req.session.userId) {
      const referenciaId = res.locals.insertedId || null;
      registrarAcao(req.session.userId, tipoAcao, referenciaId);
    }
    next();
  };
};

module.exports = router;