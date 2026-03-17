// src/models/usuario.js
const { Model, DataTypes } = require('sequelize');
const connection = require('../config/db');

class Usuario extends Model {}

Usuario.init({
  id_usuario: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  nome: {
    type: DataTypes.STRING,
    allowNull: false
  },
  email: {
    type: DataTypes.STRING,
    allowNull: false
  },
  tipo_usuario: {
    type: DataTypes.STRING,
    allowNull: false
  },
  senha: {
    type: DataTypes.STRING,
    allowNull: false
  },

  foto: { 
    type: DataTypes.STRING,
    allowNull: true
  }
}, {
  sequelize: connection,
  modelName: 'Usuario',
  tableName: 'usuario',
  timestamps: false
});


module.exports = Usuario;



