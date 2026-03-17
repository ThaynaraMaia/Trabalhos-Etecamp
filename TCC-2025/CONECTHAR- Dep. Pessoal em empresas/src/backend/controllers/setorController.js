// backend/controllers/setorController.js
const db = require("../config/db");
const SetorModel = require("../models/setorModel");
const setorController = {
  // ==============================
  // CADASTRAR SETOR
  // ==============================
  async register(req, res) {
    try {
      const { nome, descricao } = req.body;
      
      // Buscar empresa_id do usuário logado (mesmo padrão dos outros controllers)
      let empresa_id = req.usuario?.empresa_id;
      if (!empresa_id && req.usuario?.id) {
        const [usuarios] = await db.query(
          'SELECT empresa_id FROM usuario WHERE id = ?',
          [req.usuario.id]
        );
        if (usuarios.length > 0) {
          empresa_id = usuarios[0].empresa_id;
        }
      }

      if (!empresa_id || !nome) {
        return res.status(400).json({
          success: false,
          message: "Dados incompletos. Informe o nome do setor."
        });
      }

      // Verificar se o setor já existe para esta empresa
      const [setorExistente] = await db.query(
        "SELECT id FROM setores WHERE empresa_id = ? AND nome_setor = ?",
        [empresa_id, nome]
      );

      if (setorExistente.length > 0) {
        return res.status(400).json({
          success: false,
          message: "Já existe um setor com este nome na empresa."
        });
      }

      const [result] = await db.query(
        "INSERT INTO setores (empresa_id, nome_setor, descricao) VALUES (?, ?, ?)",
        [empresa_id, nome, descricao || null]
      );

      return res.status(201).json({
        success: true,
        message: "Setor cadastrado com sucesso.",
        data: {
          id: result.insertId,
          nome_setor: nome,
          descricao: descricao || null
        }
      });
    } catch (err) {
      console.error("Erro ao cadastrar setor:", err);
      return res.status(500).json({
        success: false,
        message: "Erro ao cadastrar setor."
      });
    }
  },

  // ==============================
  // LISTAR SETORES
  // ==============================
  async listar(req, res) {
    try {
      // Buscar empresa_id do usuário logado (mesmo padrão dos outros controllers)
      let empresa_id = req.usuario?.empresa_id;
      if (!empresa_id && req.usuario?.id) {
        const [usuarios] = await db.query(
          'SELECT empresa_id FROM usuario WHERE id = ?',
          [req.usuario.id]
        );
        if (usuarios.length > 0) {
          empresa_id = usuarios[0].empresa_id;
        }
      }

      if (!empresa_id) {
        return res.status(400).json({
          success: false,
          message: "Empresa não identificada."
        });
      }

      const [rows] = await db.query(
        `SELECT 
          id, 
          nome_setor, 
          descricao,
          (SELECT COUNT(*) FROM cargos WHERE setor_id = setores.id) as total_cargos
        FROM setores 
        WHERE empresa_id = ? 
        ORDER BY nome_setor`,
        [empresa_id]
      );

      return res.json({
        success: true,
        data: rows
      });
    } catch (err) {
      console.error("Erro ao listar setores:", err);
      return res.status(500).json({
        success: false,
        message: "Erro ao listar setores."
      });
    }
  },
async listarPorEmpresa(req, res) {
  try {
    const usuario = req.usuario;
    console.log('[listarPorEmpresa] Usuario logado:', usuario);

    if (!usuario) return res.status(401).json({ success: false, message: 'Usuário não autenticado.' });

    const empresaId = usuario.empresa_id || usuario.empresaId || usuario.empresa;
    console.log('[listarPorEmpresa] Empresa ID:', empresaId);

    if (!empresaId) return res.status(400).json({ success: false, message: 'Empresa do usuário não encontrada.' });

    const setores = await SetorModel.listarPorEmpresa(empresaId);
    console.log('[listarPorEmpresa] Setores retornados:', setores);

    return res.json({ success: true, data: setores });
  } catch (err) {
    console.error('[listarPorEmpresa] Erro ao listar setores:', err);
    return res.status(500).json({ success: false, message: `Erro ao listar setores: ${err.message}` });
  }
}
,
  // ==============================
  // ATUALIZAR SETOR
  // ==============================
  async atualizar(req, res) {
    try {
      const { id } = req.params;
      const { nome, descricao } = req.body;
      
      let empresa_id = req.usuario?.empresa_id;
      if (!empresa_id && req.usuario?.id) {
        const [usuarios] = await db.query(
          'SELECT empresa_id FROM usuario WHERE id = ?',
          [req.usuario.id]
        );
        if (usuarios.length > 0) {
          empresa_id = usuarios[0].empresa_id;
        }
      }

      if (!empresa_id) {
        return res.status(400).json({
          success: false,
          message: "Empresa não identificada."
        });
      }

      // Verificar se o setor pertence à empresa
      const [setorExiste] = await db.query(
        "SELECT id FROM setores WHERE id = ? AND empresa_id = ?",
        [id, empresa_id]
      );

      if (setorExiste.length === 0) {
        return res.status(404).json({
          success: false,
          message: "Setor não encontrado."
        });
      }

      const [result] = await db.query(
        "UPDATE setores SET nome_setor = ?, descricao = ? WHERE id = ? AND empresa_id = ?",
        [nome, descricao || null, id, empresa_id]
      );

      if (result.affectedRows > 0) {
        return res.json({
          success: true,
          message: "Setor atualizado com sucesso."
        });
      } else {
        return res.status(404).json({
          success: false,
          message: "Setor não encontrado."
        });
      }
    } catch (err) {
      console.error("Erro ao atualizar setor:", err);
      return res.status(500).json({
        success: false,
        message: "Erro ao atualizar setor."
      });
    }
  },

  // ==============================
  // DELETAR SETOR
  // ==============================
  async deletar(req, res) {
    try {
      const { id } = req.params;
      
      let empresa_id = req.usuario?.empresa_id;
      if (!empresa_id && req.usuario?.id) {
        const [usuarios] = await db.query(
          'SELECT empresa_id FROM usuario WHERE id = ?',
          [req.usuario.id]
        );
        if (usuarios.length > 0) {
          empresa_id = usuarios[0].empresa_id;
        }
      }

      if (!empresa_id) {
        return res.status(400).json({
          success: false,
          message: "Empresa não identificada."
        });
      }

      // Verificar se existem cargos vinculados
      const [cargosVinculados] = await db.query(
        "SELECT COUNT(*) as total FROM cargos WHERE setor_id = ?",
        [id]
      );

      if (cargosVinculados[0].total > 0) {
        return res.status(400).json({
          success: false,
          message: `Não é possível deletar. Existem ${cargosVinculados[0].total} cargo(s) vinculado(s) a este setor.`
        });
      }

      const [result] = await db.query(
        "DELETE FROM setores WHERE id = ? AND empresa_id = ?",
        [id, empresa_id]
      );

      if (result.affectedRows > 0) {
        return res.json({
          success: true,
          message: "Setor deletado com sucesso."
        });
      } else {
        return res.status(404).json({
          success: false,
          message: "Setor não encontrado."
        });
      }
    } catch (err) {
      console.error("Erro ao deletar setor:", err);
      return res.status(500).json({
        success: false,
        message: "Erro ao deletar setor."
      });
    }
  }
  
};

module.exports = setorController;