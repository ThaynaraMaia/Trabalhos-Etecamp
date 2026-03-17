const { DataTypes } = require('sequelize');
const connection = require('../config/db');
const Usuario = require('./usuario');

const LembretePessoal = connection.define('LembretePessoal', {
  id_lembrete_pessoal: {
    type: DataTypes.INTEGER,
    autoIncrement: true,
    primaryKey: true
  },
  id_usuario: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: Usuario,
      key: 'id_usuario'
    }
  },
  titulo: {
    type: DataTypes.STRING(255),
    allowNull: false
  },
  descricao: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  data_lembrete: {
    type: DataTypes.DATEONLY,
    allowNull: false
  }
}, {
  tableName: 'lembrete_pessoal',
  timestamps: true
});

// Associações
LembretePessoal.belongsTo(Usuario, { foreignKey: 'id_usuario' });
Usuario.hasMany(LembretePessoal, { foreignKey: 'id_usuario' });

module.exports = LembretePessoal;