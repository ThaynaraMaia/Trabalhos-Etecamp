require('dotenv').config();
const { Sequelize } = require('sequelize');

const sequelize = new Sequelize(
  process.env.DB_NAME || 'sistema_bolsas',
  process.env.DB_USER || 'Rayssa',
  process.env.DB_PASSWORD || '123',
  {
    host: process.env.DB_HOST || 'localhost', 
    port: process.env.DB_PORT || 3306,
    dialect: 'mysql',
    logging: false
  }
);


module.exports = sequelize;
