require("dotenv").config();
const express = require("express");
const path = require("path");
const session = require("express-session");
const cors = require("cors");

const connection = require("./config/db");
const adminRoutes = require("./routes/adminRoutes");
const authRoutes = require("./routes/authRoutes");
const bolsasRoutes = require("./routes/bolsasRoutes");
const comentarioRoutes = require("./routes/comentarioRoutes");
const avaliacaoRoutes = require("./routes/avaliacaoRoutes");
const favoritoRoutes = require("./routes/favoritoRoutes");
const simuladorRoutes = require("./routes/simuladorRoutes");
const lembreteRoutes = require('./routes/lembreteRoutes');
const testeRoutes = require("./routes/testeRoutes")
const lembretePessoalRoutes = require('./routes/lembretePessoalRoutes');
const userRoutes = require("./routes/userRoutes");


const app = express();

// Middlewares globais
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// ==================== Rotas ====================
app.use("/admin/bolsas", bolsasRoutes);
app.use("/admin/usuarios", adminRoutes);
app.use("/auth", authRoutes);
app.use("/comentario", comentarioRoutes);
app.use("/avaliacao", avaliacaoRoutes);
app.use("/favoritos", favoritoRoutes);
app.use("/user", userRoutes);
app.use('/api/lembrete', lembreteRoutes);
app.use("/api", simuladorRoutes);
app.use('/api/teste', testeRoutes);
app.use('/api/lembrete-pessoal', lembretePessoalRoutes);


// Arquivos estáticos
app.use(express.static(path.join(__dirname, "..", "public")));

// Configuração de sessões
app.use(session({
  secret: process.env.SESSION_SECRET || "sua-chave-secreta",
  resave: false,
  saveUninitialized: false,
}));

// Página inicial teste
app.get("/", (req, res) => res.send("Servidor rodando 🚀"));

// Rota fallback 404
app.use((req, res) => {
  res.status(404).json({ erro: "Rota não encontrada" });
});

// ==================== Banco ====================
connection.authenticate()
  .then(() => console.log("✅ Conectado ao MySQL"))
  .catch(err => console.error("❌ Erro ao conectar no MySQL:", err));

connection.sync()
  .then(() => console.log("✅ Modelos sincronizados"))
  .catch(err => console.error("❌ Erro ao sincronizar modelos:", err));

module.exports = app;
