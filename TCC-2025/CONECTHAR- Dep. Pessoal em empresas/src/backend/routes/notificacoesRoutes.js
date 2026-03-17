// backend/routes/notificacoesRoutes.js
const express = require('express');
const router = express.Router();
const db = require('../config/db');
const { verificarToken } = require('../middlewares/authMiddleware');

// Listar notificações do usuário
router.get('/', verificarToken, async (req, res) => {
    try {
        const usuarioId = req.usuario.id;
        const { limite = 20, apenas_nao_lidas = false } = req.query;
        
        let query = `
            SELECT 
                n.*,
                s.tipo_solicitacao,
                s.titulo as solicitacao_titulo
            FROM notificacoes n
            LEFT JOIN realizarsolicitacoes s ON n.solicitacao_id = s.id
            WHERE n.usuario_id = ?
        `;
        
        if (apenas_nao_lidas === 'true') {
            query += ' AND n.lida = 0';
        }
        
        query += ' ORDER BY n.criado_em DESC LIMIT ?';
        
        const [notificacoes] = await db.query(query, [usuarioId, parseInt(limite)]);
        
        // Contar não lidas
        const [count] = await db.query(
            'SELECT COUNT(*) as total FROM notificacoes WHERE usuario_id = ? AND lida = 0',
            [usuarioId]
        );
        
        res.json({
            success: true,
            notificacoes,
            nao_lidas: count[0].total
        });
    } catch (error) {
        console.error('Erro ao buscar notificações:', error);
        res.status(500).json({
            success: false,
            error: 'Erro ao buscar notificações'
        });
    }
});

// Marcar notificação como lida
router.put('/:id/marcar-lida', verificarToken, async (req, res) => {
    try {
        const { id } = req.params;
        const usuarioId = req.usuario.id;
        
        await db.query(
            'UPDATE notificacoes SET lida = 1 WHERE id = ? AND usuario_id = ?',
            [id, usuarioId]
        );
        
        res.json({ success: true });
    } catch (error) {
        console.error('Erro ao marcar notificação:', error);
        res.status(500).json({
            success: false,
            error: 'Erro ao marcar notificação'
        });
    }
});

// Marcar todas como lidas
router.put('/marcar-todas-lidas', verificarToken, async (req, res) => {
    try {
        const usuarioId = req.usuario.id;
        
        await db.query(
            'UPDATE notificacoes SET lida = 1 WHERE usuario_id = ? AND lida = 0',
            [usuarioId]
        );
        
        res.json({ success: true });
    } catch (error) {
        console.error('Erro ao marcar notificações:', error);
        res.status(500).json({
            success: false,
            error: 'Erro ao marcar notificações'
        });
    }
});

module.exports = router;