const connection = require('../config/db');

// Rota para buscar bolsas pelo país
exports.getBolsasPorPais = (req, res) => {
    const pais = req.query.pais; // pega o país enviado na URL
    if(!pais) return res.status(400).json({ error: 'Parâmetro "pais" é obrigatório' });

    const sql = "SELECT id_bolsa, titulo, link_inscricao, descricao, prazo_inscricao FROM bolsas WHERE pais = ?";
    connection.query(sql, [pais], (err, results) => {
        if(err) {
            console.error(err);
            return res.status(500).json({ error: 'Erro ao buscar bolsas' });
        }

        if(results.length === 0) {
            return res.status(404).json({ message: 'Nenhuma bolsa encontrada para este país' });
        }

        res.json(results);
    });
};
