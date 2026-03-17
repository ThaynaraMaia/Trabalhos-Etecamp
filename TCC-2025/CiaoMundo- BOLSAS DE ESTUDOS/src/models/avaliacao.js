const { DataTypes } = require("sequelize");
const sequelize = require("../config/db");
const Usuario = require("./usuario");
const Bolsa = require("./bolsa");

const Avaliacao = sequelize.define("Avaliacao", {
  id_avaliacao: { type: DataTypes.INTEGER, primaryKey: true, autoIncrement: true },
  id_usuario: { type: DataTypes.INTEGER, allowNull: false },
  id_bolsa: { type: DataTypes.INTEGER, allowNull: false },
  nota: { type: DataTypes.INTEGER, allowNull: false } // de 1 a 5
}, {
  tableName: "avaliacao_bolsa",
  timestamps: false
});

Avaliacao.belongsTo(Usuario, { foreignKey: "id_usuario" });
Avaliacao.belongsTo(Bolsa, { foreignKey: "id_bolsa" });

module.exports = Avaliacao;


