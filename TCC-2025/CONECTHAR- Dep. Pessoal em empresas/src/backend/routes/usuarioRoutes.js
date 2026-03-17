// backend/routes/usuarioRoutes.js
const express = require('express');
const router = express.Router();

// Middlewares e Controladores
const { verificarToken, autorizarTipoUsuario } = require('../middlewares/authMiddleware');
const authController = require('../controllers/authController');
const uploadController = require('../controllers/uploadController');
const upload = require('../middlewares/uploadMiddleware');

// Middleware para usuário autenticado
const usuarioAutenticado = verificarToken;

// ==============================
// ROTAS PÚBLICAS
// ==============================
router.post('/login', authController.login);
router.post('/registrar', authController.register);
router.post('/logout', authController.logout);

// ==============================
// ROTAS DO USUÁRIO AUTENTICADO
// ==============================
router.use(usuarioAutenticado);

// Perfil do usuário logado
router.get('/me', authController.me);
router.get('/perfil', authController.me);

// Upload de avatar/foto
router.post('/upload', upload.single('arquivo'), uploadController.realizarUpload);

// Listar uploads do usuário
router.get('/uploads', uploadController.listarMeusUploads);

// Download de arquivos
router.get('/download/:id', uploadController.downloadArquivo);

// ==============================
// ROTAS ESPECÍFICAS POR TIPO DE USUÁRIO
// ==============================

// --- Rotas para Gestores ---
router.get('/gestor/perfil', 
    autorizarTipoUsuario(['gestor']), 
    authController.me
);

// --- Rotas para Colaboradores ---
router.get('/colaborador/perfil', 
    autorizarTipoUsuario(['colaborador']), 
    authController.me
);

// ==============================
// ROTA DE FALLBACK - Buscar usuário por ID (apenas próprio usuário ou gestor)
// ==============================
router.get('/:id', usuarioAutenticado, async (req, res) => {
    try {
        const userId = req.params.id;
        const usuarioRequisitante = req.usuario;

        // Verificar permissão: usuário só pode buscar seus próprios dados, a menos que seja gestor
        if (usuarioRequisitante.id !== parseInt(userId) && 
            usuarioRequisitante.tipo_usuario !== 'gestor') {
            return res.status(403).json({ 
                success: false, 
                message: 'Permissão negada para acessar este usuário' 
            });
        }

        const query = `
            SELECT id, nome, email, cargo, setor, foto, tipo_usuario, 
                   cnpj, empresa_id, telefone, data_admissao, salario,
                   numero_registro, tipo_jornada, horas_diarias, criado_em
            FROM usuario 
            WHERE id = ?
        `;

        const db = require("../config/db");
        const [results] = await db.query(query, [userId]);

        if (results.length === 0) {
            return res.status(404).json({ 
                success: false, 
                message: 'Usuário não encontrado' 
            });
        }

        // Remover campos sensíveis se não for o próprio usuário ou gestor
        const usuario = results[0];
        if (usuarioRequisitante.id !== parseInt(userId)) {
            delete usuario.salario;
            delete usuario.cnpj;
            delete usuario.data_admissao;
        }

        res.json({ 
            success: true, 
            usuario 
        });

    } catch (error) {
        console.error('Erro na rota /usuario/:id:', error);
        res.status(500).json({ 
            success: false, 
            message: 'Erro interno do servidor' 
        });
    }
});

module.exports = router;