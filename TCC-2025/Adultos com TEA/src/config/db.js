const sqlite3 = require('sqlite3').verbose();
const path = require('path');

// Cria/abre o banco de dados
const dbPath = path.join(__dirname, 'bdtcc.db');
const db = new sqlite3.Database(dbPath, (err) => {
  if (err) {
    console.error('Erro ao conectar ao banco de dados:', err.message);
  } else {
    console.log('Conectado ao banco de dados SQLite em:', dbPath);
  }
});

// Cria as tabelas se não existirem
db.serialize(() => {
  db.run(`CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    senha TEXT NOT NULL,
    tipo TEXT NOT NULL DEFAULT 'Adulto com TEA',
    foto TEXT
  )`);

  db.run(`CREATE TABLE IF NOT EXISTS forum (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    usuario TEXT NOT NULL,
    texto TEXT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  )`);

  db.run(`CREATE TABLE IF NOT EXISTS posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    autor TEXT NOT NULL,
    conteudo TEXT NOT NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES usuarios(id_usuario)
  )`);

  db.run(`CREATE TABLE IF NOT EXISTS respostas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    post_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    autor TEXT NOT NULL,
    conteudo TEXT NOT NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES usuarios(id_usuario)
  )`);

  db.run(`CREATE TABLE IF NOT EXISTS avaliacoes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    post_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES usuarios(id_usuario),
    UNIQUE(post_id, user_id)
  )`);

  db.run(`CREATE TABLE IF NOT EXISTS conquistas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    usuario_id INTEGER NOT NULL,
    nome TEXT NOT NULL,
    descricao TEXT,
    data_desbloqueio DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    UNIQUE(usuario_id, nome)
  )`);

  db.run(`CREATE TABLE IF NOT EXISTS agenda (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    usuario_id INTEGER NOT NULL,
    data TEXT NOT NULL,
    descricao TEXT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
  )`);
  
  db.run(`CREATE TABLE IF NOT EXISTS materiais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tipo TEXT DEFAULT 'video',
    emocao TEXT NOT NULL,
    titulo TEXT NOT NULL,
    descricao TEXT,
    url TEXT,
    icone TEXT DEFAULT '🎬',
    texto TEXT,
    fontes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  )`);

  db.run(`CREATE TABLE IF NOT EXISTS desafios_completados (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    usuario_id INTEGER NOT NULL,
    desafio_id INTEGER NOT NULL,
    data_conclusao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
  )`);

  db.run(`CREATE TABLE IF NOT EXISTS dias_login (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    usuario_id INTEGER NOT NULL,
    data TEXT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    UNIQUE(usuario_id, data)
  )`);

  // Verifica e cria usuário admin se não existir
  db.get("SELECT id_usuario FROM usuarios WHERE tipo = 'Administrador' LIMIT 1", [], (err, row) => {
    if (!row) {
      console.log('\n========================================');
      console.log('Criando usuário ADMINISTRADOR padrão...');
      console.log('========================================');
      
      const bcrypt = require('bcryptjs');
      const senhaHash = bcrypt.hashSync('admin123', 10);
      
      db.run(
        `INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)`,
        ['Administrador', 'admin@sistema.com', senhaHash, 'Administrador'],
        function(err) {
          if (err) {
            console.error('Erro ao criar admin:', err);
          } else {
            console.log('✓ Admin criado com sucesso!');
            console.log('  Email: admin@sistema.com');
            console.log('  Senha: admin123');
            console.log('========================================\n');
          }
        }
      );
    }
  });

  // Verifica e cria usuário comum se não existir
  db.get("SELECT id_usuario FROM usuarios WHERE tipo = 'Adulto com TEA' LIMIT 1", [], (err, row) => {
    if (!row) {
      console.log('Criando usuário COMUM de teste...');
      
      const bcrypt = require('bcryptjs');
      const senhaHash = bcrypt.hashSync('teste123', 10);
      
      db.run(
        `INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)`,
        ['Usuário Teste', 'usuario@teste.com', senhaHash, 'Adulto com TEA'],
        function(err) {
          if (err) {
            console.error('Erro ao criar usuário teste:', err);
          } else {
            console.log('✓ Usuário comum criado!');
            console.log('  Email: usuario@teste.com');
            console.log('  Senha: teste123\n');
          }
        }
      );
    }
  });
});

module.exports = db;