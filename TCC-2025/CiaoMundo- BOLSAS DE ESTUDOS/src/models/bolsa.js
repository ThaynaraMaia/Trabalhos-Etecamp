const { DataTypes } = require('sequelize');
const connection = require('../config/db');

const Bolsa = connection.define('Bolsa', {
  id_bolsa: {
    type: DataTypes.INTEGER,
    autoIncrement: true,
    primaryKey: true
  },
  titulo: {
    type: DataTypes.STRING(255),
    allowNull: false
  },
  descricao: {
    type: DataTypes.TEXT,
    allowNull: false
  },
  pais: {
    type: DataTypes.STRING(100),
    allowNull: false
  },
  universidade: {
    type: DataTypes.STRING(255),
    allowNull: false
  },
  curso: {
    type: DataTypes.STRING(255),
    allowNull: false
  },
  tipo_bolsa: {
    type: DataTypes.STRING(100),
    allowNull: false
  },
  requisitos: {
    type: DataTypes.TEXT,
    allowNull: false
  },
  prazo_inscricao: {
    type: DataTypes.DATEONLY,
    allowNull: false
  },
  link_inscricao: {
    type: DataTypes.STRING(500),
    allowNull: false
  }
}, {
  tableName: 'bolsa',
  timestamps: false // se tabela não tiver createdAt/updatedAt
});

module.exports = Bolsa;

