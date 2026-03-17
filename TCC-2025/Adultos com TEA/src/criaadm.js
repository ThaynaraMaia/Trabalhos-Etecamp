// scripts/criarAdmin.js
const sqlite3 = require("sqlite3").verbose();
const bcrypt = require("bcryptjs");

const db = new sqlite3.Database("./config/bdtcc.db");

const email = "admin@teste.com";
const senha = "senha123";
const nome = "Administrador";

bcrypt.hash(senha, 10, (err, hash) => {
  if (err) {
    console.error("Erro ao gerar hash:", err);
    return;
  }

  db.run(
    "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)",
    [nome, email, hash, "Administrador"],
    function (err) {
      if (err) {
        console.error("Erro ao criar admin:", err.message);
      } else {
        console.log("Administrador criado com sucesso!");
      }
      db.close();
    }
  );
});
