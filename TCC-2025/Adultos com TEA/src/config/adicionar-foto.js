const sqlite3 = require('sqlite3').verbose();
const path = require('path');

const dbPath = path.join(__dirname, 'bdtcc.db');
const db = new sqlite3.Database(dbPath);

db.run('ALTER TABLE usuarios ADD COLUMN foto TEXT', (err) => {
  if (err) {
    if (err.message.includes('duplicate column')) {
      console.log('A coluna foto já existe!');
    } else {
      console.error('Erro:', err.message);
    }
  } else {
    console.log('Coluna foto adicionada com sucesso!');
  }
  db.close();
});