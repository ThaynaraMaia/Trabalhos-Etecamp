const { DataTypes } = require("sequelize");
const sequelize = require("../config/db");
const Usuario = require("./usuario");

const Simulador = sequelize.define("Simulador", {
  id: { type: DataTypes.INTEGER, primaryKey: true, autoIncrement: true },
  id_usuario: { type: DataTypes.INTEGER, allowNull: false },
  alimentacao: { type: DataTypes.DECIMAL(10,2), defaultValue: 0 },
  moradia: { type: DataTypes.DECIMAL(10,2), defaultValue: 0 },
  transporte: { type: DataTypes.DECIMAL(10,2), defaultValue: 0 },
  lazer: { type: DataTypes.DECIMAL(10,2), defaultValue: 0 },
  outros: { type: DataTypes.DECIMAL(10,2), defaultValue: 0 },
  total_mensal: { type: DataTypes.DECIMAL(10,2), allowNull: false },
  total_semanal: { type: DataTypes.DECIMAL(10,2), allowNull: false },
  total_diario: { type: DataTypes.DECIMAL(10,2), allowNull: false }
}, {
  tableName: "simulador_custos",
  timestamps: true
});

Simulador.belongsTo(Usuario, { foreignKey: "id_usuario" });

module.exports = Simulador;