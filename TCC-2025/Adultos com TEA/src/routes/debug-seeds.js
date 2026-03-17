// debug-seed.js - Execute este arquivo para verificar e popular o banco
const sqlite3 = require('sqlite3').verbose();
const path = require('path');
const bcrypt = require('bcryptjs');

const dbPath = path.join(__dirname, 'config', 'bdtcc.db');
const db = new sqlite3.Database(dbPath);

console.log('\n========================================');
console.log('🔍 DIAGNÓSTICO DO BANCO DE DADOS');
console.log('========================================\n');

db.serialize(() => {
  // 1. Verificar usuários
  console.log('1️⃣ Verificando usuários...\n');
  db.all("SELECT id_usuario, nome, email, tipo FROM usuarios", [], (err, users) => {
    if (err) {
      console.error('❌ Erro ao buscar usuários:', err);
      return;
    }

    if (users.length === 0) {
      console.log('⚠️  Nenhum usuário encontrado! Criando usuários de teste...\n');
      
      const senhaAdmin = bcrypt.hashSync('admin123', 10);
      const senhaUser = bcrypt.hashSync('teste123', 10);

      db.run(
        `INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)`,
        ['Administrador', 'admin@sistema.com', senhaAdmin, 'Administrador'],
        function(err) {
          if (err) {
            console.error('❌ Erro ao criar admin:', err);
          } else {
            console.log('✅ Admin criado! (ID:', this.lastID, ')');
            console.log('   Email: admin@sistema.com');
            console.log('   Senha: admin123\n');
          }
        }
      );

      db.run(
        `INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)`,
        ['Usuário Teste', 'usuario@teste.com', senhaUser, 'Adulto com TEA'],
        function(err) {
          if (err) {
            console.error('❌ Erro ao criar usuário:', err);
          } else {
            console.log('✅ Usuário criado! (ID:', this.lastID, ')');
            console.log('   Email: usuario@teste.com');
            console.log('   Senha: teste123\n');
            
            // Após criar usuários, criar posts de teste
            criarPostsTeste();
          }
        }
      );
    } else {
      console.log(`✅ Usuários encontrados: ${users.length}\n`);
      users.forEach(u => {
        console.log(`   - ${u.nome} (${u.tipo}) - ID: ${u.id_usuario}`);
      });
      console.log();
      
      // Verificar posts
      verificarPosts(users);
    }
  });
});

function verificarPosts(users) {
  console.log('2️⃣ Verificando estrutura da tabela posts...\n');
  
  db.all("PRAGMA table_info(posts)", [], (err, columns) => {
    if (err) {
      console.error('❌ Erro ao verificar tabela:', err);
      return;
    }

    console.log('📋 Colunas da tabela posts:');
    columns.forEach(col => {
      console.log(`   - ${col.name} (${col.type})`);
    });
    console.log();

    const hasTipoPost = columns.some(col => col.name === 'tipo_post');
    
    if (!hasTipoPost) {
      console.log('⚠️  Coluna tipo_post NÃO encontrada!');
      console.log('   Execute o arquivo migration.js primeiro!\n');
      db.close();
      return;
    }

    console.log('✅ Coluna tipo_post existe!\n');

    // Verificar posts existentes
    console.log('3️⃣ Verificando posts existentes...\n');
    db.all("SELECT * FROM posts", [], (err, posts) => {
      if (err) {
        console.error('❌ Erro ao buscar posts:', err);
        db.close();
        return;
      }

      if (posts.length === 0) {
        console.log('⚠️  Nenhum post encontrado!\n');
        console.log('Deseja criar posts de teste? (s/n)');
        console.log('Executando criação automática...\n');
        criarPostsTeste();
      } else {
        console.log(`✅ Posts encontrados: ${posts.length}\n`);
        posts.forEach(p => {
          console.log(`   Post #${p.id}:`);
          console.log(`   - Autor: ${p.autor} (ID: ${p.user_id})`);
          console.log(`   - Tipo: ${p.tipo_post}`);
          console.log(`   - Conteúdo: ${p.conteudo.substring(0, 50)}...`);
          console.log(`   - Data: ${p.data_criacao}\n`);
        });

        // Verificar respostas
        verificarRespostas();
      }
    });
  });
}

function criarPostsTeste() {
  console.log('4️⃣ Criando posts de teste...\n');

  // Buscar usuários para pegar IDs reais
  db.all("SELECT id_usuario, nome, tipo FROM usuarios LIMIT 2", [], (err, users) => {
    if (err || users.length === 0) {
      console.error('❌ Erro: Não há usuários no banco!');
      db.close();
      return;
    }

    const admin = users.find(u => u.tipo === 'Administrador') || users[0];
    const user = users.find(u => u.tipo !== 'Administrador') || users[1] || users[0];

    const postsExemplo = [
      {
        user_id: admin.id_usuario,
        autor: admin.nome,
        conteudo: 'Bem-vindos ao fórum! Este é um espaço seguro para compartilhar experiências e fazer perguntas.',
        tipo_post: 'open'
      },
      {
        user_id: user.id_usuario,
        autor: user.nome,
        conteudo: 'Olá! Tenho dúvidas sobre como lidar com situações sociais desafiadoras. Alguém pode ajudar?',
        tipo_post: 'admin-only'
      },
      {
        user_id: user.id_usuario,
        autor: user.nome,
        conteudo: 'Gostaria de compartilhar algumas estratégias que funcionaram para mim no ambiente de trabalho.',
        tipo_post: 'open'
      }
    ];

    let contador = 0;

    postsExemplo.forEach(post => {
      db.run(
        `INSERT INTO posts (user_id, autor, conteudo, tipo_post) VALUES (?, ?, ?, ?)`,
        [post.user_id, post.autor, post.conteudo, post.tipo_post],
        function(err) {
          contador++;
          
          if (err) {
            console.error(`❌ Erro ao criar post ${contador}:`, err);
          } else {
            console.log(`✅ Post ${contador} criado! (ID: ${this.lastID})`);
            console.log(`   Autor: ${post.autor}`);
            console.log(`   Tipo: ${post.tipo_post}\n`);
          }

          if (contador === postsExemplo.length) {
            console.log('========================================');
            console.log('✅ SETUP CONCLUÍDO COM SUCESSO!');
            console.log('========================================\n');
            console.log('Agora você pode:');
            console.log('1. Fazer login com:');
            console.log('   - admin@sistema.com / admin123 (Admin)');
            console.log('   - usuario@teste.com / teste123 (Usuário)\n');
            console.log('2. Acessar /forum e ver os posts!\n');
            
            db.close();
          }
        }
      );
    });
  });
}

function verificarRespostas() {
  console.log('4️⃣ Verificando respostas...\n');
  
  db.all("SELECT * FROM respostas", [], (err, respostas) => {
    if (err) {
      console.error('❌ Erro ao buscar respostas:', err);
      db.close();
      return;
    }

    console.log(`📝 Respostas encontradas: ${respostas.length}\n`);
    
    if (respostas.length > 0) {
      respostas.forEach(r => {
        console.log(`   Resposta #${r.id}:`);
        console.log(`   - Post ID: ${r.post_id}`);
        console.log(`   - Autor: ${r.autor}`);
        console.log(`   - Conteúdo: ${r.conteudo.substring(0, 50)}...\n`);
      });
    }

    console.log('========================================');
    console.log('✅ DIAGNÓSTICO COMPLETO!');
    console.log('========================================\n');
    
    db.close();
  });
}

// Tratamento de erros
db.on('error', (err) => {
  console.error('❌ Erro no banco de dados:', err);
});

process.on('SIGINT', () => {
  db.close((err) => {
    if (err) {
      console.error(err.message);
    }
    console.log('\n🔒 Conexão com banco fechada.');
    process.exit(0);
  });
});