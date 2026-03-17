const { Model, DataTypes } = require('sequelize');
const connection = require('../config/db'); 
const Bolsa = require('./bolsa'); 

class Favorito extends Model {}

Favorito.init({
  id_favorito: { type: DataTypes.INTEGER, autoIncrement: true, primaryKey: true },
  id_usuario: { type: DataTypes.INTEGER, allowNull: false },
  id_bolsa: { type: DataTypes.INTEGER, allowNull: false }
}, {
  sequelize: connection,
  modelName: 'Favorito',
  tableName: 'favorito',
  timestamps: false
});

// Associação
Favorito.belongsTo(Bolsa, { foreignKey: 'id_bolsa' });
Bolsa.hasMany(Favorito, { foreignKey: 'id_bolsa' });

module.exports = Favorito;
