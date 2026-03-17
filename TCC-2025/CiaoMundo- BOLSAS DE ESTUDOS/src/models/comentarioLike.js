const { DataTypes } = require("sequelize");
const sequelize = require("../config/db");

const ComentarioLike = sequelize.define("ComentarioLike", {
  id: { type: DataTypes.INTEGER, primaryKey: true, autoIncrement: true },
  id_usuario: { type: DataTypes.INTEGER, allowNull: false },
  id_comentario: { type: DataTypes.INTEGER, allowNull: false },
  curtido: { type: DataTypes.BOOLEAN, defaultValue: false },
  descurtido: { type: DataTypes.BOOLEAN, defaultValue: false },
}, {
  tableName: "comentario_like",
  timestamps: true
});

module.exports = ComentarioLike;

