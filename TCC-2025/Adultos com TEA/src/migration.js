// migration.js - Execute este arquivo UMA VEZ para atualizar o banco
const sqlite3 = require('sqlite3').verbose();
const path = require('path');

const dbPath = path.join(__dirname, 'config', 'bdtcc.db');
const db = new sqlite3.Database(dbPath);

console.log('Iniciando migração do banco de dados...\n');

db.serialize(() => {
  // Verifica se a coluna tipo_post já existe
  db.all("PRAGMA table_info(posts)", [], (err, columns) => {
    if (err) {
      console.error('Erro ao verificar estrutura da tabela:', err);
      db.close();
      return;
    }

    const hasColumn = columns.some(col => col.name === 'tipo_post');

    if (hasColumn) {
      console.log('✓ Coluna tipo_post já existe! Nenhuma migração necessária.');
      db.close();
      return;
    }

    console.log('Adicionando coluna tipo_post na tabela posts...');

    // Adiciona a coluna tipo_post
    db.run(`ALTER TABLE posts ADD COLUMN tipo_post TEXT DEFAULT 'admin-only'`, (err) => {
      if (err) {
        console.error('Erro ao adicionar coluna:', err);
        db.close();
        return;
      }

      console.log('✓ Coluna tipo_post adicionada com sucesso!');

      // Atualiza posts existentes para o valor padrão
      db.run(`UPDATE posts SET tipo_post = 'admin-only' WHERE tipo_post IS NULL`, (err) => {
        if (err) {
          console.error('Erro ao atualizar posts existentes:', err);
        } else {
          console.log('✓ Posts existentes atualizados!');
        }

        // Cria índice para melhor performance
        db.run(`CREATE INDEX IF NOT EXISTS idx_posts_tipo ON posts(tipo_post)`, (err) => {
          if (err) {
            console.error('Erro ao criar índice:', err);
          } else {
            console.log('✓ Índice criado com sucesso!');
          }

          console.log('\n========================================');
          console.log('Migração concluída com sucesso! ✓');
          console.log('Você pode iniciar o servidor agora.');
          console.log('========================================\n');

          db.close();
        });
      });
    });
  });
});