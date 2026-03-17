// backend/controllers/gestorController.js
const jwt = require('jsonwebtoken');
const bcrypt = require('bcrypt');
const db = require('../config/db');
const Gestor = require('../models/gestorModel');
require('dotenv').config();

const JWT_SECRET = process.env.JWT_SECRET || 'changeme';

function sanitizeString(v) {
  if (v === undefined || v === null) return undefined;
  return String(v).trim();
}

function buildProfileResponse(row, empresa = null) {
  if (!row) return null;
  return {
    id: row.id,
    empresa_id: row.empresa_id,
    empresa_nome: empresa ? (empresa.nome_empresa || empresa.empresa_nome) : (row.empresa_nome || null), // CORREÇÃO: nome_empresa
    numero_registro: row.numero_registro || null,
    nome: row.nome || null,
    cnpj: row.cnpj || null,
    tipo_usuario: (row.tipo_usuario || 'gestor').toLowerCase(),
    cargo: row.cargo || null,
    setor: row.setor || null,
    tipo_jornada: row.tipo_jornada || null,
    horas_diarias: row.horas_diarias == null ? null : Number(row.horas_diarias),
    foto: row.foto || null,
    criado_em: row.criado_em || null
  };
}

// backend/controllers/gestorController.js
async function fetchEmpresaById(empresa_id) {
  if (!empresa_id) return null;
  try {
    const [rows] = await db.query('SELECT id, nome_empresa, cnpj FROM empresa WHERE id = ? LIMIT 1', [empresa_id]); // CORREÇÃO: nome_empresa
    return rows && rows[0] ? rows[0] : null;
  } catch (err) {
    console.warn('Não foi possível buscar empresa:', err?.message);
    return null;
  }
}

const gestorController = {
  // Criar novo gestor
  async register(req, res) {
    try {
      const payload = {
        empresa_id: sanitizeString(req.body.empresa_id),
        numero_registro: sanitizeString(req.body.numero_registro),
        nome: sanitizeString(req.body.nome),
        cnpj: sanitizeString(req.body.cnpj),
        senha: sanitizeString(req.body.senha),
        cargo: sanitizeString(req.body.cargo),
        setor: sanitizeString(req.body.setor),
        tipo_jornada: sanitizeString(req.body.tipo_jornada),
        horas_diarias: req.body.horas_diarias === undefined ? undefined : Number(req.body.horas_diarias)
      };

      // validação mínima
      if (!payload.empresa_id || !payload.numero_registro || !payload.nome || !payload.cnpj || !payload.senha) {
        return res.status(400).json({ success: false, message: 'Campos obrigatórios ausentes (empresa_id, numero_registro, nome, cnpj, senha).' });
      }

      // cria gestor (o model cuida de verificar duplicidade e hash da senha)
      const novoGestor = await Gestor.create(payload);

      // busca empresa para enriquecer a resposta (opcional)
      const empresa = await fetchEmpresaById(novoGestor.empresa_id);

      return res.status(201).json({
        success: true,
        message: 'Gestor registrado com sucesso',
        data: buildProfileResponse(novoGestor, empresa)
      });
    } catch (error) {
      console.error('Erro ao registrar gestor:', error);
      return res.status(500).json({ success: false, message: error.message || 'Erro interno no servidor' });
    }
  },

  // Login do gestor — retorna token (e perfil resumido opcional)
  async login(req, res) {
    try {
      const empresa_id = sanitizeString(req.body.empresa_id);
      const numero_registro = sanitizeString(req.body.numero_registro);
      const senha = sanitizeString(req.body.senha);

      if (!empresa_id || !numero_registro || !senha) {
        return res.status(400).json({ success: false, message: 'Preencha empresa_id, numero_registro e senha.' });
      }

      const gestor = await Gestor.findByRegistro(empresa_id, numero_registro);
      if (!gestor) {
        return res.status(404).json({ success: false, message: 'Gestor não encontrado' });
      }

      // comparação com hash de senha do banco
      const senhaValida = await bcrypt.compare(senha, gestor.senha_hash || gestor.senha || '');
      if (!senhaValida) {
        return res.status(401).json({ success: false, message: 'Senha incorreta' });
      }

      const token = jwt.sign({
        id: gestor.id,
        empresa_id: gestor.empresa_id,
        numero_registro: gestor.numero_registro,
        tipo_usuario: 'gestor'
      }, JWT_SECRET, { expiresIn: '8h' });

      // opcional: busca nome da empresa para retornar junto
      const empresa = await fetchEmpresaById(gestor.empresa_id);

      return res.json({
        success: true,
        message: 'Login realizado com sucesso',
        token,
        data: buildProfileResponse(gestor, empresa)
      });
    } catch (error) {
      console.error('Erro no login do gestor:', error);
      return res.status(500).json({ success: false, message: 'Erro interno no servidor' });
    }
  },

  // Retorna perfil completo do gestor (enriquecido com nome da empresa)
  async getProfile(req, res) {
    try {
      const usuarioId = req.usuario?.id;
      if (!usuarioId) {
        return res.status(401).json({ success: false, message: 'Usuário não autenticado' });
      }

      const gestor = await Gestor.findById(usuarioId);
      if (!gestor) {
        return res.status(404).json({ success: false, message: 'Gestor não encontrado' });
      }

      // filler para setor caso não definido
      if ((gestor.tipo_usuario || '').toLowerCase() === 'gestor' && !gestor.setor) {
        gestor.setor = 'Departamento Pessoal';
      }

      const empresa = await fetchEmpresaById(gestor.empresa_id);

      return res.json({
        success: true,
        data: buildProfileResponse(gestor, empresa)
      });
    } catch (error) {
      console.error('Erro ao buscar perfil do gestor:', error);
      return res.status(500).json({ success: false, message: 'Erro interno no servidor' });
    }
  },

  // Atualiza dados do gestor (aceita nome, cargo, setor, tipo_jornada, horas_diarias, foto)
  async update(req, res) {
    try {
      const usuarioId = req.usuario?.id;
      if (!usuarioId) {
        return res.status(401).json({ success: false, message: 'Usuário não autenticado' });
      }

      // Aceita os campos editáveis (empresa não pode ser alterada aqui)
      const updates = {};
      const nome = sanitizeString(req.body.nome);
      const cargo = sanitizeString(req.body.cargo);
      const setor = sanitizeString(req.body.setor);
      const tipo_jornada = sanitizeString(req.body.tipo_jornada);
      const horas_diarias_raw = req.body.horas_diarias;
      const foto = sanitizeString(req.body.foto); // se usar upload, prefira endpoint separado

      if (nome) updates.nome = nome;
      if (cargo) updates.cargo = cargo;
      if (setor) updates.setor = setor;
      if (tipo_jornada) updates.tipo_jornada = tipo_jornada;
      if (foto) updates.foto = foto;
      if (horas_diarias_raw !== undefined && horas_diarias_raw !== null && horas_diarias_raw !== '') {
        const horas = Number(horas_diarias_raw);
        if (Number.isNaN(horas) || horas < 0) {
          return res.status(400).json({ success: false, message: 'Horas diárias inválidas.' });
        }
        updates.horas_diarias = horas;
      }

      if (Object.keys(updates).length === 0) {
        return res.status(400).json({ success: false, message: 'Forneça pelo menos um campo para atualizar.' });
      }

      console.log('Atualizando gestor ID:', usuarioId, 'com:', updates);

      // Atualiza via model (model ignora campo senha / senha_hash)
      const atualizado = await Gestor.update(usuarioId, updates);

      // Recarrega para retornar dados consistentes
      const gestorAtualizado = await Gestor.findById(usuarioId);
      const empresa = await fetchEmpresaById(gestorAtualizado?.empresa_id);

      return res.json({
        success: true,
        message: 'Gestor atualizado com sucesso',
        data: buildProfileResponse(gestorAtualizado, empresa)
      });
    } catch (error) {
      console.error('Erro ao atualizar gestor:', error);
      return res.status(500).json({ success: false, message: error.message || 'Erro interno no servidor' });
    }
  },

  // Deletar gestor (hard delete). Se preferir soft-delete, altere o model.
  async delete(req, res) {
    try {
      const usuarioId = req.usuario?.id;
      if (!usuarioId) {
        return res.status(401).json({ success: false, message: 'Usuário não autenticado' });
      }

      console.log('Solicitação de remoção do gestor ID:', usuarioId);
      await Gestor.delete(usuarioId);

      return res.json({ success: true, message: 'Gestor deletado com sucesso' });
    } catch (error) {
      console.error('Erro ao deletar gestor:', error);
      return res.status(500).json({ success: false, message: error.message || 'Erro interno no servidor' });
    }
  },

  // Endpoint /me simplificado — retorna perfil enriquecido
 // backend/controllers/gestorController.js
async me(req, res) {
  try {
    const usuario = req.usuario;
    if (!usuario || !usuario.id) {
      return res.status(401).json({ 
        success: false, 
        message: 'Usuário não autenticado' 
      });
    }

    console.log(' Buscando gestor ID:', usuario.id);
    
    // Use o método findById existente em vez de findByIdSimple
    const gestor = await Gestor.findById(usuario.id);
    
    if (!gestor) {
      return res.status(404).json({ 
        success: false, 
        message: 'Gestor não encontrado' 
      });
    }

    const empresa = await fetchEmpresaById(gestor.empresa_id);
    
    console.log(' Gestor encontrado:', {
      id: gestor.id,
      nome: gestor.nome,
      empresa_id: gestor.empresa_id
    });

    return res.json({ 
      success: true, 
      usuario: buildProfileResponse(gestor, empresa) 
    });
  } catch (err) {
    console.error(' Erro em /gestor/me:', err);
    return res.status(500).json({ 
      success: false, 
      message: 'Erro ao buscar dados do gestor: ' + err.message 
    });
  }
}
};

module.exports = gestorController;
