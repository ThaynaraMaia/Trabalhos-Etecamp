const jwt = require('jsonwebtoken');

const authMiddleware = (tipoNecessario) => {
  return (req, res, next) => {
    const authHeader = req.headers['authorization'];
    if (!authHeader) return res.status(401).json({ message: "Token não fornecido" });

    const token = authHeader.split(' ')[1]; // Bearer <token>
    if (!token) return res.status(401).json({ message: "Token inválido" });

    try {
      const decoded = jwt.verify(token, process.env.JWT_SECRET || "sua-chave-secreta");
      req.usuario = { id_usuario: decoded.id, tipo_usuario: decoded.tipo_usuario };
    // ADICIONA USUÁRIO NA REQUISIÇÃO

    // Verificação corrigida para minúsculas
      if (tipoNecessario === "administrador" && decoded.tipo_usuario.toLowerCase() !== "administrador") {
        return res.status(403).json({ message: "Acesso negado: somente administradores" });
      }

      next(); // token válido
    } catch (err) {
      console.error(err);
      return res.status(401).json({ message: "Token inválido ou expirado" });
    }
  };
};

module.exports = authMiddleware;


