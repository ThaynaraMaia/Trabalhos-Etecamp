const { DataTypes } = require("sequelize");
const sequelize = require("../config/db");

const Comentario = sequelize.define("Comentario", {
  id: { type: DataTypes.INTEGER, primaryKey: true, autoIncrement: true },
  id_usuario: { type: DataTypes.INTEGER, allowNull: false },
  id_bolsa: { type: DataTypes.INTEGER, allowNull: false },
  texto: { type: DataTypes.TEXT, allowNull: false }
}, {
  tableName: "comentario",
  timestamps: true
});

module.exports = Comentario;





