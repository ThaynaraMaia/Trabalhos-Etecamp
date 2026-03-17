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

// Definição de todas as conquistas
const CONQUISTAS_DEFINICAO = [
  // FÓRUM - POSTS
  { id: 'primeiro_post', nome: 'Posts I', descricao: 'Criou seu primeiro post no fórum', icone: '💬', xp: 10, tipo: 'posts', requisito: 1 },
  { id: 'postador_ativo', nome: 'Posts II', descricao: 'Criou 5 posts no fórum', icone: '📢', xp: 25, tipo: 'posts', requisito: 5 },
  { id: 'postador_veterano', nome: 'Posts III', descricao: 'Criou 10 posts no fórum', icone: '🎯', xp: 50, tipo: 'posts', requisito: 10 },
  { id: 'super_postador', nome: 'Posts IV', descricao: 'Criou 25 posts no fórum', icone: '⭐', xp: 100, tipo: 'posts', requisito: 25 },
  
  // FÓRUM - RESPOSTAS
  { id: 'primeira_resposta', nome: 'Respostas I', descricao: 'Respondeu seu primeiro post', icone: '💭', xp: 10, tipo: 'respostas', requisito: 1 },
  { id: 'responder_ativo', nome: 'Respostas II', descricao: 'Respondeu 10 posts', icone: '🗣️', xp: 30, tipo: 'respostas', requisito: 10 },
  { id: 'responder_mestre', nome: 'Respostas III', descricao: 'Respondeu 25 posts', icone: '👥', xp: 75, tipo: 'respostas', requisito: 25 },
  
  // AGENDA
  { id: 'primeiro_evento', nome: 'Agenda I', descricao: 'Adicionou seu primeiro evento na agenda', icone: '📅', xp: 10, tipo: 'agenda', requisito: 1 },
  { id: 'planejador', nome: 'Agenda II', descricao: 'Adicionou 5 eventos na agenda', icone: '📆', xp: 25, tipo: 'agenda', requisito: 5 },
  { id: 'mestre_tempo', nome: 'Agenda III', descricao: 'Adicionou 10 eventos na agenda', icone: '⏰', xp: 50, tipo: 'agenda', requisito: 10 },
  { id: 'super_organizador', nome: 'Agenda IV', descricao: 'Adicionou 20 eventos na agenda', icone: '🗓️', xp: 100, tipo: 'agenda', requisito: 20 },
  
  // MATERIAIS
  { id: 'primeiro_material', nome: 'Materiais I', descricao: 'Acessou seu primeiro material de apoio', icone: '📚', xp: 10, tipo: 'materiais', requisito: 1 },
  { id: 'leitor_ativo', nome: 'Materiais II', descricao: 'Acessou 5 materiais diferentes', icone: '📖', xp: 25, tipo: 'materiais', requisito: 5 },
  { id: 'conhecedor', nome: 'Materiais III', descricao: 'Acessou 10 materiais diferentes', icone: '🎓', xp: 50, tipo: 'materiais', requisito: 10 },
  { id: 'sabio', nome: 'Materiais IV', descricao: 'Acessou 20 materiais diferentes', icone: '🧠', xp: 100, tipo: 'materiais', requisito: 20 },
  
  // EMOÇÕES
  { id: 'primeiro_registro', nome: 'Emoções I', descricao: 'Registrou sua primeira emoção', icone: '😊', xp: 10, tipo: 'emocoes', requisito: 1 },
  { id: 'explorador_emocional', nome: 'Emoções II', descricao: 'Registrou 7 dias diferentes', icone: '🌈', xp: 30, tipo: 'emocoes', requisito: 7 },
  { id: 'consistente', nome: 'Emoções III', descricao: 'Registrou emoções por 15 dias', icone: '💪', xp: 60, tipo: 'emocoes', requisito: 15 },
  { id: 'dedicado', nome: 'Emoções IV', descricao: 'Registrou emoções por 30 dias', icone: '🏅', xp: 150, tipo: 'emocoes', requisito: 30 },
  
  // PERFIL
  { id: 'perfil_completo', nome: 'Perfil I', descricao: 'Completou as informações do perfil', icone: '👤', xp: 15, tipo: 'perfil', requisito: 1 },
  { id: 'foto_perfil', nome: 'Perfil II', descricao: 'Adicionou foto de perfil', icone: '📸', xp: 10, tipo: 'foto', requisito: 1 },
  
  // LOGIN
  { id: 'primeiro_dia', nome: 'Login I', descricao: 'Acessou o sistema pela primeira vez', icone: '🎉', xp: 5, tipo: 'acesso', requisito: 1 },
  { id: 'sete_dias', nome: 'Login II', descricao: 'Voltou ao sistema por 7 dias', icone: '🔥', xp: 40, tipo: 'dias_login', requisito: 7 },
  { id: 'trinta_dias', nome: 'Login III', descricao: 'Voltou ao sistema por 30 dias', icone: '💎', xp: 120, tipo: 'dias_login', requisito: 30 }
];

// Sistema de níveis baseado em XP
const NIVEIS = [
  { nivel: 1, nome: 'Ferro', xpMinimo: 0, xpMaximo: 99, icone: '🥉', cor: '#8B7355' },
  { nivel: 2, nome: 'Prata', xpMinimo: 100, xpMaximo: 299, icone: '🥈', cor: '#C0C0C0' },
  { nivel: 3, nome: 'Ouro', xpMinimo: 300, xpMaximo: 599, icone: '🥇', cor: '#FFD700' },
  { nivel: 4, nome: 'Platina', xpMinimo: 600, xpMaximo: 999999, icone: '💎', cor: '#E5E4E2' }
];

function calcularNivel(xpTotal) {
  for (let i = NIVEIS.length - 1; i >= 0; i--) {
    if (xpTotal >= NIVEIS[i].xpMinimo) {
      return NIVEIS[i];
    }
  }
  return NIVEIS[0];
}

// Função para calcular progresso do usuário
async function calcularProgresso(userId) {
  return new Promise((resolve, reject) => {
    const progresso = {};
    let queries = 0;
    let completed = 0;

    const checkComplete = () => {
      completed++;
      if (completed === queries) {
        resolve(progresso);
      }
    };

    // Posts
    queries++;
    db.get("SELECT COUNT(*) as total FROM posts WHERE user_id = ?", [userId], (err, row) => {
      progresso.posts = row ? row.total : 0;
      checkComplete();
    });

    // Respostas
    queries++;
    db.get("SELECT COUNT(*) as total FROM respostas WHERE user_id = ?", [userId], (err, row) => {
      progresso.respostas = row ? row.total : 0;
      checkComplete();
    });

    // Agenda
    queries++;
    db.get("SELECT COUNT(*) as total FROM agenda WHERE usuario_id = ?", [userId], (err, row) => {
      progresso.agenda = row ? row.total : 0;
      checkComplete();
    });

    // Materiais acessados
    queries++;
    db.get("SELECT COUNT(DISTINCT material_id) as total FROM materiais_acessados WHERE usuario_id = ?", [userId], (err, row) => {
      progresso.materiais = row ? row.total : 0;
      checkComplete();
    });

    // Dias de login
    queries++;
    db.get("SELECT COUNT(DISTINCT data) as total FROM dias_login WHERE usuario_id = ?", [userId], (err, row) => {
      progresso.dias_login = row ? row.total : 0;
      checkComplete();
    });

    // Perfil e foto
    queries++;
    db.get("SELECT foto FROM usuarios WHERE id_usuario = ?", [userId], (err, row) => {
      progresso.foto = row && row.foto ? 1 : 0;
      progresso.perfil = 1;
      progresso.acesso = 1;
      checkComplete();
    });
  });
}

// GET - Página de conquistas
router.get("/", requireLogin, async (req, res) => {
  const userId = req.session.user.id_usuario;
  const isAdmin = req.session.user.tipo === 'Administrador';

  try {
    // ✅ Se for admin, todas as conquistas estão desbloqueadas
    if (isAdmin) {
      const conquistas = CONQUISTAS_DEFINICAO.map(c => ({
        ...c,
        desbloqueada: true,
        progresso: c.requisito,
        progressoPorcentagem: 100
      }));

      // Calcula XP total de TODAS as conquistas
      const totalXP = CONQUISTAS_DEFINICAO.reduce((sum, c) => sum + c.xp, 0);

      const stats = {
        totalDesbloqueadas: CONQUISTAS_DEFINICAO.length,
        totalXP: totalXP,
        posts: 999,
        nivel: NIVEIS[3], // Sempre Platina
        proximoNivel: null,
        xpProximoNivel: 0
      };

      return res.render("conquistas", {
        conquistas,
        stats,
        user: req.session.user,
        userNivel: NIVEIS[3],
        userXP: totalXP,
        layout: "main"
      });
    }

    // ✅ Usuário normal - lógica original
    const progresso = await calcularProgresso(userId);

    db.all(
      "SELECT nome FROM conquistas WHERE usuario_id = ?",
      [userId],
      (err, desbloqueadas) => {
        if (err) {
          console.error("Erro ao buscar conquistas:", err);
          return res.status(500).send("Erro ao carregar conquistas");
        }

        const conquistasDesbloqueadas = new Set(desbloqueadas.map(c => c.nome));

        const conquistas = CONQUISTAS_DEFINICAO.map(c => {
          const atual = progresso[c.tipo] || 0;
          const desbloqueada = conquistasDesbloqueadas.has(c.nome);
          const progressoPorcentagem = Math.min(100, (atual / c.requisito) * 100);

          return {
            ...c,
            desbloqueada,
            progresso: atual,
            progressoPorcentagem: progressoPorcentagem.toFixed(0)
          };
        });

        const totalXP = conquistas.filter(c => c.desbloqueada).reduce((sum, c) => sum + c.xp, 0);
        const nivelAtual = calcularNivel(totalXP);
        const proximoNivel = NIVEIS.find(n => n.nivel === nivelAtual.nivel + 1);

        const stats = {
          totalDesbloqueadas: conquistas.filter(c => c.desbloqueada).length,
          totalXP: totalXP,
          posts: progresso.posts || 0,
          nivel: nivelAtual,
          proximoNivel: proximoNivel,
          xpProximoNivel: proximoNivel ? proximoNivel.xpMinimo - totalXP : 0
        };

        res.render("conquistas", {
          conquistas,
          stats,
          user: req.session.user,
          userNivel: nivelAtual,
          userXP: totalXP,
          layout: "main"
        });
      }
    );
  } catch (err) {
    console.error("Erro ao calcular progresso:", err);
    res.status(500).send("Erro ao carregar conquistas");
  }
});

// GET - Obter nível do usuário (para usar em outras páginas)
router.get("/nivel", requireLogin, async (req, res) => {
  const userId = req.session.user.id_usuario;
  const isAdmin = req.session.user.tipo === 'Administrador';

  // ✅ Admin sempre retorna Platina
  if (isAdmin) {
    const totalXP = CONQUISTAS_DEFINICAO.reduce((sum, c) => sum + c.xp, 0);
    return res.json({ nivel: NIVEIS[3], xp: totalXP });
  }

  try {
    db.all(
      "SELECT nome FROM conquistas WHERE usuario_id = ?",
      [userId],
      (err, desbloqueadas) => {
        if (err) {
          return res.json({ nivel: NIVEIS[0] });
        }

        const conquistasDesbloqueadas = new Set(desbloqueadas.map(c => c.nome));
        const conquistas = CONQUISTAS_DEFINICAO.filter(c => conquistasDesbloqueadas.has(c.nome));
        const totalXP = conquistas.reduce((sum, c) => sum + c.xp, 0);
        const nivelAtual = calcularNivel(totalXP);

        res.json({ nivel: nivelAtual, xp: totalXP });
      }
    );
  } catch (err) {
    res.json({ nivel: NIVEIS[0] });
  }
});

// POST - Verificar novas conquistas
router.post("/verificar", requireLogin, async (req, res) => {
  const userId = req.session.user.id_usuario;
  const isAdmin = req.session.user.tipo === 'Administrador';

  // ✅ Admin não precisa verificar conquistas (já tem todas)
  if (isAdmin) {
    return res.json({ success: true, novasConquistas: [] });
  }

  try {
    const progresso = await calcularProgresso(userId);
    const novasConquistas = [];

    for (const conquista of CONQUISTAS_DEFINICAO) {
      const atual = progresso[conquista.tipo] || 0;

      if (atual >= conquista.requisito) {
        await new Promise((resolve) => {
          db.run(
            "INSERT OR IGNORE INTO conquistas (usuario_id, nome, descricao) VALUES (?, ?, ?)",
            [userId, conquista.nome, conquista.descricao],
            function(err) {
              if (!err && this.changes > 0) {
                novasConquistas.push({
                  nome: conquista.nome,
                  descricao: conquista.descricao,
                  icone: conquista.icone,
                  xp: conquista.xp
                });
              }
              resolve();
            }
          );
        });
      }
    }

    res.json({
      success: true,
      novasConquistas
    });
  } catch (err) {
    console.error("Erro ao verificar conquistas:", err);
    res.status(500).json({ success: false, message: "Erro ao verificar conquistas" });
  }
});

module.exports = router;