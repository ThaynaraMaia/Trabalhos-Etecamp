// scripts/promoverAdmin.js
const sqlite3 = require("sqlite3").verbose();
const bcrypt = require("bcryptjs");

const db = new sqlite3.Database("./config/bdtcc.db");

const email = "admin@teste.com";
const novaSenha = "senha123"; // senha que você quer definir
const novoTipo = "Administrador";

bcrypt.hash(novaSenha, 10, (err, hash) => {
  if (err) return console.error("Erro ao gerar hash da senha:", err);

  db.run(
    "UPDATE usuarios SET tipo = ?, senha = ? WHERE email = ?",
    [novoTipo, hash, email],
    function (err) {
      if (err) {
        console.error("Erro ao promover admin:", err.message);
      } else if (this.changes === 0) {
        console.log("Nenhum usuário encontrado com esse email.");
      } else {
        console.log("Usuário promovido a administrador e senha redefinida com sucesso!");
      }
      db.close();
    }
  );
});
