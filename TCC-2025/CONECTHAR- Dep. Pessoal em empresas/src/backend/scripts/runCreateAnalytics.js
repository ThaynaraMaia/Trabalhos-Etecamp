// scripts/runCreateAnalytics.js
const path = require('path');
const fs = require('fs');
const db = require('../backend/config/db'); // ajusta o caminho se necessário

async function run() {
  try {
    const sqlPath = path.resolve(__dirname, 'create_analytics_tables.sql');
    if (!fs.existsSync(sqlPath)) {
      console.error('Arquivo SQL não encontrado em', sqlPath);
      process.exit(1);
    }
    const sql = fs.readFileSync(sqlPath, 'utf8');

    console.log('Executando SQL de criação de tabelas (pode demorar)...');
    // como o arquivo contém várias statements, usamos query com multipleStatements true no pool
    await db.query(sql);
    console.log('Tabelas analíticas criadas/confirmadas com sucesso.');
    process.exit(0);
  } catch (err) {
    console.error('Erro ao criar tabelas analíticas:', err);
    process.exit(2);
  }
}

run();
