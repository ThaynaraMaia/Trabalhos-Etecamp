const Usuario = require('./usuario');
const Bolsa = require('./bolsa');
const Favorito = require('./favorito');
const AvaliacaoBolsa = require('./avaliacao');
const Comentario = require('./comentario');
const ComentarioLike = require('./comentarioLike');
const Lembrete = require('./lembrete')

// --------------------
// Associações
// --------------------

// Comentários
Usuario.hasMany(Comentario, { foreignKey: "id_usuario" });
Comentario.belongsTo(Usuario, { foreignKey: "id_usuario" });

Bolsa.hasMany(Comentario, { foreignKey: "id_bolsa" });
Comentario.belongsTo(Bolsa, { foreignKey: "id_bolsa" });

// Curtidas/Descurtidas
ComentarioLike.belongsTo(Usuario, { foreignKey: "id_usuario" });
Usuario.hasMany(ComentarioLike, { foreignKey: "id_usuario" });

ComentarioLike.belongsTo(Comentario, { foreignKey: "id_comentario" });
Comentario.hasMany(ComentarioLike, { foreignKey: "id_comentario", as: "likes" });

module.exports = {
  Usuario,
  Bolsa,
  Favorito,
  AvaliacaoBolsa,
  Comentario,
  ComentarioLike,
  Lembrete
};



