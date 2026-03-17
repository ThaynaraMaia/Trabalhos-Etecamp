const express = require("express");
const session = require("express-session");
const path = require("path");
const exphbs = require("express-handlebars");

const app = express();
const PORT = 3000;

// Middlewares
app.use(express.urlencoded({ extended: true }));
app.use(express.json());
app.use(
  session({
    secret: "segredo",
    resave: false,
    saveUninitialized: true
  })
);
app.use(express.static(path.join(__dirname, "public")));

// Handlebars
const hbs = exphbs.create({
  defaultLayout: "main",
  layoutsDir: path.join(__dirname, "views/layouts"),
  helpers: {
    eq: (a, b) => a === b,
    neq: (a, b) => a !== b,
    year: () => new Date().getFullYear(),
    eqi: (a, b) => String(a).toLowerCase() === String(b).toLowerCase(),
    extractYoutubeId: (url) => {
      if (!url) return "";
      const regex =
        /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/;
      const match = url.match(regex);
      return match ? match[1] : url;
    },
    json: (context) => JSON.stringify(context)
  }
});

app.engine("handlebars", hbs.engine);
app.set("view engine", "handlebars");
app.set("views", path.join(__dirname, "views"));

// Disponibilizar user e nível nas views
app.use((req, res, next) => {
  res.locals.user = req.session.user || null;
  
  if (req.session.user) {
    const db = require("./config/db");
    
    db.all(
      "SELECT nome FROM conquistas WHERE usuario_id = ?",
      [req.session.user.id_usuario],
      (err, desbloqueadas) => {
        if (!err && desbloqueadas) {
          const CONQUISTAS_XP = {
            'Primeira Voz': 10, 'Comunicador': 25, 'Veterano do Fórum': 50, 'Influenciador': 100,
            'Respondedor': 10, 'Participativo': 30, 'Mestre das Respostas': 75,
            'Organizador Iniciante': 10, 'Planejador': 25, 'Mestre do Tempo': 50, 'Super Organizador': 100,
            'Curioso': 10, 'Estudioso': 25, 'Conhecedor': 50, 'Sábio': 100,
            'Autoconhecimento': 10, 'Explorador Emocional': 30, 'Consistente': 60, 'Dedicado': 150,
            'Identificado': 15, 'Rosto Amigável': 10,
            'Bem-vindo': 5, 'Uma Semana': 40, 'Um Mês': 120
          };

          const totalXP = desbloqueadas.reduce((sum, c) => sum + (CONQUISTAS_XP[c.nome] || 0), 0);
          
          const NIVEIS = [
            { nivel: 1, nome: 'Ferro', xpMinimo: 0, icone: '🥉', cor: '#8B7355' },
            { nivel: 2, nome: 'Prata', xpMinimo: 100, icone: '🥈', cor: '#C0C0C0' },
            { nivel: 3, nome: 'Ouro', xpMinimo: 300, icone: '🥇', cor: '#FFD700' },
            { nivel: 4, nome: 'Platina', xpMinimo: 600, icone: '💎', cor: '#E5E4E2' }
          ];

          let nivelAtual = NIVEIS[0];
          for (let i = NIVEIS.length - 1; i >= 0; i--) {
            if (totalXP >= NIVEIS[i].xpMinimo) {
              nivelAtual = NIVEIS[i];
              break;
            }
          }

          res.locals.userNivel = nivelAtual;
          res.locals.userXP = totalXP;
        }
        next();
      }
    );
  } else {
    next();
  }
});

// Rotas
const authRoutes = require("./routes/auth");
const mainRoutes = require("./routes/main");
const adminRoutes = require("./routes/admin");
const extrasRoutes = require("./routes/extras");
const profileRoutes = require("./routes/perfil");
const materiaisRoutes = require("./routes/materiais");
const conquistasRouter = require('./routes/conquistas');
const forumRoutes = require('./routes/forum');
const agendaRoutes = require("./routes/agenda");

// Uso das rotas
app.use("/", materiaisRoutes);
app.use("/", profileRoutes);
app.use("/", authRoutes);
app.use("/", mainRoutes);
app.use("/", adminRoutes);
app.use("/", forumRoutes);
app.use("/", extrasRoutes);
app.use('/conquistas', conquistasRouter);
app.use("/", agendaRoutes);

// Iniciar servidor
app.listen(PORT, () => console.log(`Servidor rodando na porta ${PORT}`));