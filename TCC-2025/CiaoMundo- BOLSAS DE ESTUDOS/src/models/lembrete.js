const { DataTypes } = require('sequelize');
const connection = require('../config/db');
const Bolsa = require('./bolsa');

const Lembrete = connection.define('Lembrete', {
  id_lembrete: {
    type: DataTypes.INTEGER,
    autoIncrement: true,
    primaryKey: true
  },
  id_usuario: {
    type: DataTypes.INTEGER,
    allowNull: false
  },
  id_bolsa: {
    type: DataTypes.INTEGER,
    allowNull: false
  },
  data_lembrete: {
    type: DataTypes.DATEONLY,
    allowNull: false
  },
  enviado: {
    type: DataTypes.BOOLEAN,
    defaultValue: false
  }
}, {
  tableName: 'lembrete',
  timestamps: false
});

// Relacionamento
Lembrete.belongsTo(Bolsa, { foreignKey: 'id_bolsa', as: 'bolsa' });

module.exports = Lembrete;
