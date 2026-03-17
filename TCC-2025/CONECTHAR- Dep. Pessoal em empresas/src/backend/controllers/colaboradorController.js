// controllers/colaboradorController.js
const db = require("../config/db");
const jwt = require("jsonwebtoken");
const bcrypt = require("bcrypt");
const Colaborador = require("../models/colaboradorModel");
const UsuarioBeneficios = require("../models/usuariosBeneficiosModel");
require("dotenv").config();

const JWT_SECRET = process.env.JWT_SECRET || "default_secret";

// tentativa de require do model de gerenciarbeneficios (opcional)
let GerenciarBeneficios = null;
try {
  GerenciarBeneficios = require("../models/usuariosBeneficiosModel");
} catch (err) {
  console.warn("Model gerenciarBeneficiosModel não encontrado — requisições com array de IDs de benefícios irão pular resolução por ID.");
}

// Função utilitária para normalizar/parsing de benefícios
function parseBeneficios(beneficios) {
  if (!beneficios) return [];
  let arr = [];

  try {
    arr = typeof beneficios === "string" ? JSON.parse(beneficios) : beneficios;
  } catch (err) {
    console.error("Erro ao parsear benefícios:", err);
    return [];
  }

  return arr.map(item => ({
    nome_do_beneficio: item.nome_do_beneficio || item.nome || null,
    descricao_beneficio: item.descricao_beneficio || item.descricao || null,
    valor_aplicado: Number(item.valor_aplicado ?? item.valor ?? item.valor_personalizado ?? 0)
  })).filter(b => b.nome_do_beneficio);
}


const colaboradorController = {
  // ==============================
  // REGISTRO E LOGIN
  // ==============================
async register(req, res) {
  try {
    const { nome, cpf, cargo, setor, tipo_jornada, horas_diarias, senha, empresa_id, numero_registro } = req.body;

    if (!nome || !senha || !empresa_id) {
      return res.status(400).json({ success: false, message: "Campos obrigatórios não preenchidos" });
    }

    const salario = req.body.salario ? parseFloat(req.body.salario) : 0;
    
    // Parse de benefícios
    let beneficiosArray = [];
    if (req.body.beneficios) {
      try {
        const parsed = typeof req.body.beneficios === 'string' 
          ? JSON.parse(req.body.beneficios) 
          : req.body.beneficios;
        
        beneficiosArray = Array.isArray(parsed) ? parsed : [];
        console.log(` ${beneficiosArray.length} benefícios para cadastro`);
      } catch (err) {
        console.error(' Erro ao parsear benefícios:', err);
      }
    }

    const foto = req.file ? req.file.filename : null;
    const registro = numero_registro || await Colaborador.proximoRegistro(empresa_id);
    
    // CORREÇÃO: Gerar senha_hash corretamente
    const senha_hash = senha ? await bcrypt.hash(senha, 10) : null;

    //  CORREÇÃO: Usar CNPJ do gestor logado (do token JWT)
    const cnpjGestor = req.user?.cnpj;
    
    if (!cnpjGestor) {
      console.warn('⚠️ CNPJ do gestor não encontrado no token JWT');
    }

    const cnpjFinal = cnpjGestor || '00000000000000';

    console.log('Dados para criação do colaborador:', {
      empresa_id,
      numero_registro: registro,
      nome,
      cpf: cpf || null,
      cnpj: cnpjFinal,
      gestor_id: req.user?.id,
      senha_hash: !!senha_hash,
      cargo: cargo || null,
      setor: setor || null,
      tipo_jornada,
      horas_diarias,
      foto: foto || null,
      salario
    });

    const novoColab = await Colaborador.create({
      empresa_id,
      numero_registro: registro,
      nome,
      cpf: cpf || null,
      cnpj: cnpjFinal, //  Usando CNPJ do gestor
      senha_hash,
      cargo: cargo || null,
      setor: setor || null,
      tipo_jornada,
      horas_diarias,
      foto: foto || null,
      salario
    });

    // ... resto do código permanece igual
    // Adicionar benefícios se existirem
    if (beneficiosArray.length > 0) {
      console.log(`➕ Adicionando ${beneficiosArray.length} benefícios ao novo colaborador`);
      
      for (const b of beneficiosArray) {
        await UsuarioBeneficios.addBeneficio({
          gestor_id: req.user?.id || 1,
          usuario_id: novoColab.id,
          cargo: cargo || null,
          nome_do_beneficio: b.nome_do_beneficio || '',
          descricao_beneficio: b.descricao_beneficio || '',
          valor_aplicado: parseFloat(b.valor_personalizado || b.valor_aplicado || 0),
          data_inicio: new Date(),
          ativo: 1
        });
      }
    }

    const criado = await Colaborador.findById(novoColab.id);
    criado.beneficios = await UsuarioBeneficios.findByUsuario(novoColab.id);

    return res.status(201).json({
      success: true,
      message: "Colaborador registrado com sucesso",
      data: criado
    });
  } catch (error) {
    console.error("Erro no registro de colaborador:", error);
    return res.status(500).json({ 
      success: false, 
      message: "Erro interno no servidor", 
      error: error.message,
      sql: error.sql
    });
  }
},
  async login(req, res) {
    try {
      const { empresa_id, numero_registro, senha } = req.body;
      const colaborador = await Colaborador.findByRegistro(empresa_id, numero_registro);
      if (!colaborador) return res.status(404).json({ success: false, message: "Colaborador não encontrado" });

      const senhaValida = await bcrypt.compare(senha, colaborador.senha_hash);
      if (!senhaValida) return res.status(401).json({ success: false, message: "Senha incorreta" });

      const token = jwt.sign(
        { id: colaborador.id, empresa_id: colaborador.empresa_id, numero_registro: colaborador.numero_registro, tipo_usuario: "colaborador" },
        JWT_SECRET,
        { expiresIn: "8h" }
      );

      return res.json({ success: true, message: "Login realizado com sucesso", token });
    } catch (error) {
      console.error("Erro no login do colaborador:", error);
      return res.status(500).json({ success: false, message: "Erro interno no servidor" });
    }
  },

  // ==============================
  // PERFIL E LISTAGEM
  // ==============================
  async getProfile(req, res) {
    try {
      const { id } = req.user;
      const colaborador = await Colaborador.findById(id);
      if (!colaborador) return res.status(404).json({ success: false, message: "Colaborador não encontrado" });

      colaborador.beneficios = await UsuarioBeneficios.findByUsuario(id);

      return res.json({ success: true, data: colaborador });
    } catch (error) {
      console.error("Erro ao buscar perfil do colaborador:", error);
      return res.status(500).json({ success: false, message: "Erro interno no servidor" });
    }
  },

async listar(req, res) {
  try {
    const empresa_id = req.query.empresa_id || req.usuario?.empresa_id;
    const setor = req.query.setor;

    if (!empresa_id) {
      return res.status(400).json({ 
        success: false, 
        message: 'empresa_id obrigatório' 
      });
    }

    console.log(' Listando colaboradores:', { empresa_id, setor });

    let query = 'SELECT * FROM usuario WHERE empresa_id = ? AND tipo_usuario = "colaborador"';
    const params = [empresa_id];

    if (setor) {
      query += ' AND setor = ?';
      params.push(setor);
    }

    query += ' ORDER BY nome';

    const db = require('../config/db');
    const [colaboradores] = await db.query(query, params);

    console.log(` ${colaboradores.length} colaboradores encontrados`);

    // Buscar benefícios para cada colaborador
    const UsuarioBeneficios = require('../models/usuariosBeneficiosModel');
    
    for (let colab of colaboradores) {
      try {
        const beneficios = await UsuarioBeneficios.findByUsuario(colab.id);
        
        colab.beneficios = beneficios.map(b => ({
          id: b.usuario_beneficio_id || b.id,
          beneficio_id: b.beneficio_id,
          nome_do_beneficio: b.nome_do_beneficio,
          descricao_beneficio: b.descricao_beneficio || '',
          valor_personalizado: parseFloat(b.valor_personalizado) || parseFloat(b.valor_aplicado) || 0,
          valor_aplicado: parseFloat(b.valor_aplicado) || 0,
          nome_cargo: b.nome_cargo,
          nome_setor: b.nome_setor,
          ativo: b.usuario_ativo || b.ativo
        }));
        
        console.log(`   Colaborador ${colab.nome}: ${colab.beneficios.length} benefícios`);
      } catch (err) {
        console.error(` Erro ao buscar benefícios do colaborador ${colab.id}:`, err);
        colab.beneficios = [];
      }
    }

    res.json({
      success: true,
      data: colaboradores
    });
  } catch (err) {
    console.error(' Erro ao listar colaboradores:', err);
    res.status(500).json({ 
      success: false, 
      message: 'Erro ao listar colaboradores',
      error: err.message 
    });
  }
},
  async getById(req, res) {
    try {
      const { id } = req.params;
      if (!id) return res.status(400).json({ success: false, message: "ID do colaborador é obrigatório" });

      const colaborador = await Colaborador.findById(id);
      if (!colaborador) return res.status(404).json({ success: false, message: "Colaborador não encontrado" });

      colaborador.beneficios = await UsuarioBeneficios.findByUsuario(id);

      return res.json({ success: true, data: colaborador });
    } catch (error) {
      console.error("Erro ao buscar colaborador por ID:", error);
      return res.status(500).json({ success: false, message: "Erro interno no servidor" });
    }
  },

  // ==============================
  // ATUALIZAÇÕES
  // ==============================
async update(req, res) {
  try {
    const { id, nome, cpf, cargo, setor, tipo_jornada, horas_diarias, senha } = req.body;
    
    if (!id) {
      return res.status(400).json({ 
        success: false, 
        message: "ID do colaborador é obrigatório" 
      });
    }

    console.log('Atualizando colaborador:', id);
    console.log(' Dados recebidos:', {
      nome,
      cargo,
      setor,
      salario: req.body.salario,
      beneficios: req.body.beneficios
    });

    const salario = req.body.salario ? parseFloat(req.body.salario) : undefined;
    
    // ===== PARSE DE BENEFÍCIOS COM VALIDAÇÃO =====
    let beneficiosArray = [];
    if (req.body.beneficios) {
      try {
        const parsed = typeof req.body.beneficios === 'string' 
          ? JSON.parse(req.body.beneficios) 
          : req.body.beneficios;
        
        if (!Array.isArray(parsed)) {
          throw new Error('Benefícios deve ser um array');
        }

        //  VALIDAR CADA BENEFÍCIO
        beneficiosArray = parsed.map((b, index) => {
          const beneficio_id = b.beneficio_id || b.id;
          
          if (!beneficio_id) {
            console.error(` Benefício ${index} sem ID:`, b);
            throw new Error(`Benefício na posição ${index} não tem ID válido`);
          }

          return {
            beneficio_id: String(beneficio_id), // Garantir que é string
            nome_do_beneficio: b.nome_do_beneficio || b.nome || '',
            valor_personalizado: parseFloat(b.valor_personalizado || b.valor_aplicado || 0),
            data_inicio: b.data_inicio || new Date(),
            data_fim: b.data_fim || null,
            ativo: 1
          };
        });
        
        console.log(`   ${beneficiosArray.length} benefícios validados:`, beneficiosArray);
      } catch (err) {
        console.error(' Erro ao processar benefícios:', err);
        return res.status(400).json({
          success: false,
          message: 'Formato inválido para benefícios: ' + err.message
        });
      }
    }

    // ===== ATUALIZAR DADOS BÁSICOS =====
    const updateData = { nome, cpf, cargo, setor, tipo_jornada, horas_diarias };
    if (typeof salario !== "undefined") updateData.salario = salario;
    if (req.file) updateData.foto = req.file.filename;
    if (senha) {
      const bcrypt = require('bcrypt');
      updateData.senha_hash = await bcrypt.hash(senha, 10);
    }

    const Colaborador = require('../models/colaboradorModel');
    await Colaborador.update(id, updateData);
    console.log('   Dados básicos atualizados');

    // ===== SINCRONIZAR BENEFÍCIOS =====
    if (beneficiosArray.length > 0) {
      console.log(`   Sincronizando ${beneficiosArray.length} benefícios`);
      
      const UsuarioBeneficios = require('../models/usuariosBeneficiosModel');
      await UsuarioBeneficios.sincronizarBeneficios(id, beneficiosArray);
      
      console.log('   Benefícios sincronizados');
    } else {
      console.log('   Removendo todos os benefícios (nenhum selecionado)');
      // CORREÇÃO: usar db importado
      await db.query('DELETE FROM usuario_beneficios WHERE usuario_id = ?', [id]);
    }

    // ===== BUSCAR DADOS ATUALIZADOS =====
    const colaborador = await Colaborador.findById(id);
    const UsuarioBeneficios = require('../models/usuariosBeneficiosModel');
    colaborador.beneficios = await UsuarioBeneficios.findByUsuario(id);

    console.log(' Colaborador atualizado com sucesso');

    return res.json({ 
      success: true, 
      message: "Colaborador atualizado com sucesso", 
      data: colaborador 
    });

  } catch (error) {
    console.error(" Erro ao atualizar colaborador:", error);
    return res.status(500).json({ 
      success: false, 
      message: "Erro interno no servidor", 
      error: error.message 
    });
  }
},
  async updateSalario(req, res) {
    try {
      const { id } = req.params;
      const { salario } = req.body;
      if (!id || typeof salario === "undefined") return res.status(400).json({ success: false, message: "ID e salário são obrigatórios" });

      const s = parseFloat(salario);
      if (isNaN(s)) return res.status(400).json({ success: false, message: "Salário inválido" });

      await Colaborador.update(id, { salario: s });

      return res.json({ success: true, message: "Salário atualizado com sucesso" });
    } catch (error) {
      console.error("Erro ao atualizar salário:", error);
      return res.status(500).json({ success: false, message: "Erro interno no servidor" });
    }
  },

  async updateBeneficios(req, res) {
    try {
      const { id } = req.params;
      const beneficiosArray = await parseBeneficios(req.body.beneficios);
      if (!id || !beneficiosArray) return res.status(400).json({ success: false, message: "ID e benefícios são obrigatórios" });

      const atuais = await UsuarioBeneficios.findByUsuario(id);
      for (const b of atuais) await UsuarioBeneficios.removeBeneficio(b.id);

  for (const b of beneficiosArray) {
  await UsuarioBeneficios.addBeneficio({
    gestor_id: req.user?.id || 1,
    usuario_id: id,
    cargo: null,
    nome_do_beneficio: b.nome_do_beneficio,
    descricao_beneficio: b.descricao_beneficio,
    valor_aplicado: Number(
      b.valor_aplicado ??
      b.valor_personalizado ??
      b.valor ??
      0
    ),
    data_inicio: new Date(),
    ativo: 1
  });
}


      const col = await Colaborador.findById(id);
      col.beneficios = await UsuarioBeneficios.findByUsuario(id);

      return res.json({ success: true, message: "Benefícios atualizados com sucesso", data: col });
    } catch (error) {
      console.error("Erro ao atualizar benefícios:", error);
      return res.status(500).json({ success: false, message: "Erro interno no servidor" });
    }
  },

 async getBeneficios(req, res) {
  try {
    const { id } = req.params;

    console.log(`📋 Buscando benefícios do colaborador ${id}`);

    if (!id || isNaN(id)) {
      return res.status(400).json({
        success: false,
        message: 'ID do colaborador inválido'
      });
    }

    // Verificar se colaborador existe
    const [colaboradorRows] = await db.query(
      'SELECT id, nome FROM usuario WHERE id = ?',
      [id]
    );

    if (colaboradorRows.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Colaborador não encontrado'
      });
    }

    // Buscar benefícios vinculados usando query direta
    const query = `
      SELECT 
        ub.id as usuario_beneficio_id,
        ub.beneficio_id,
        ub.valor_personalizado,
        ub.data_inicio,
        ub.data_fim,
        ub.ativo,
        gb.nome_do_beneficio,
        gb.descricao_beneficio,
        gb.valor_aplicado
      FROM usuario_beneficios ub
      INNER JOIN gerenciarbeneficios gb ON ub.beneficio_id = gb.id
      WHERE ub.usuario_id = ? AND ub.ativo = 1
      ORDER BY gb.nome_do_beneficio
    `;

    const [beneficios] = await db.query(query, [id]);

    console.log(` ${beneficios.length} benefícios encontrados`);

    return res.json({
      success: true,
      data: beneficios.map(b => ({
        id: b.usuario_beneficio_id,
        beneficio_id: b.beneficio_id,
        nome_do_beneficio: b.nome_do_beneficio,
        descricao_beneficio: b.descricao_beneficio || '',
        valor_aplicado: parseFloat(b.valor_aplicado || 0),
        valor_personalizado: b.valor_personalizado ? parseFloat(b.valor_personalizado) : null,
        valor: parseFloat(b.valor_personalizado || b.valor_aplicado || 0),
        data_inicio: b.data_inicio,
        data_fim: b.data_fim,
        ativo: b.ativo
      }))
    });

  } catch (error) {
    console.error(' Erro ao buscar benefícios:', error);
    return res.status(500).json({
      success: false,
      message: 'Erro interno no servidor',
      error: process.env.NODE_ENV === 'development' ? error.message : undefined
    });
  }
},

/**
 * Atualizar benefícios vinculados a um colaborador
 * @route PUT /api/colaborador/:id/beneficios
 */
async updateBeneficios(req, res) {
  let connection;
  
  try {
    const { id } = req.params;
    let { beneficios } = req.body;

    console.log(` Atualizando benefícios do colaborador ${id}`);
    console.log(' Dados recebidos:', JSON.stringify(req.body, null, 2));

    // ===== VALIDAÇÕES =====
    if (!id || isNaN(id)) {
      return res.status(400).json({
        success: false,
        message: 'ID do colaborador inválido'
      });
    }

    // Parse de benefícios se vier como string
    if (typeof beneficios === 'string') {
      try {
        beneficios = JSON.parse(beneficios);
        console.log(' Benefícios parseados de string');
      } catch (err) {
        console.error(' Erro ao parsear benefícios:', err);
        return res.status(400).json({
          success: false,
          message: 'Formato inválido de benefícios (JSON inválido)'
        });
      }
    }

    if (!Array.isArray(beneficios)) {
      return res.status(400).json({
        success: false,
        message: 'Benefícios deve ser um array'
      });
    }

    console.log(` Total de benefícios a processar: ${beneficios.length}`);

    // Verificar se colaborador existe
    const [colaboradorRows] = await db.query(
      'SELECT id, nome FROM usuario WHERE id = ?',
      [id]
    );

    if (colaboradorRows.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Colaborador não encontrado'
      });
    }

    console.log(` Colaborador encontrado: ${colaboradorRows[0].nome}`);

    // ===== INICIAR TRANSAÇÃO =====
    connection = await db.getConnection();
    await connection.beginTransaction();
    console.log(' Transação iniciada');

    try {
      // ===== PASSO 1: Remover benefícios anteriores =====
      const [deleteResult] = await connection.query(
        'DELETE FROM usuario_beneficios WHERE usuario_id = ?',
        [id]
      );

      console.log(` ${deleteResult.affectedRows} benefícios anteriores removidos`);

      // ===== PASSO 2: Inserir novos benefícios =====
      let beneficiosInseridos = 0;

      if (beneficios.length > 0) {
        console.log(` Processando ${beneficios.length} benefícios...`);

        for (const [index, beneficio] of beneficios.entries()) {
          // Extrair beneficio_id de forma flexível
          const beneficio_id = beneficio.beneficio_id || 
                              beneficio.id || 
                              beneficio.beneficioId;

          if (!beneficio_id) {
            console.warn(` Benefício ${index} sem ID válido:`, beneficio);
            continue;
          }

          console.log(`   [${index + 1}/${beneficios.length}] Processando benefício ${beneficio_id}`);

          // Verificar se o benefício existe na tabela gerenciarbeneficios
          const [beneficioExiste] = await connection.query(
            'SELECT id, nome_do_beneficio, valor_aplicado FROM gerenciarbeneficios WHERE id = ? AND ativo = 1',
            [beneficio_id]
          );

          if (beneficioExiste.length === 0) {
            console.warn(` Benefício ${beneficio_id} não encontrado ou inativo - IGNORADO`);
            continue;
          }

          // Calcular valor final
          const valorPadrao = parseFloat(beneficioExiste[0].valor_aplicado || 0);
          const valorPersonalizado = beneficio.valor_personalizado ? 
            parseFloat(beneficio.valor_personalizado) : null;
          const valorFinal = valorPersonalizado !== null ? valorPersonalizado : valorPadrao;

          console.log(`     ${beneficioExiste[0].nome_do_beneficio}: R$ ${valorFinal.toFixed(2)}`);

          // Inserir na tabela usuario_beneficios
          await connection.query(
            `INSERT INTO usuario_beneficios 
             (usuario_id, beneficio_id, valor_personalizado, ativo, data_inicio) 
             VALUES (?, ?, ?, 1, CURDATE())`,
            [id, beneficio_id, valorFinal]
          );

          beneficiosInseridos++;
        }

        console.log(` ${beneficiosInseridos} de ${beneficios.length} benefícios inseridos com sucesso`);
      } else {
        console.log('ℹ Nenhum benefício para vincular (lista vazia)');
      }

      // ===== COMMIT DA TRANSAÇÃO =====
      await connection.commit();
      console.log(' Transação concluída com sucesso');

      return res.json({
        success: true,
        message: `Benefícios atualizados com sucesso`,
        total: beneficiosInseridos,
        processados: beneficios.length
      });

    } catch (error) {
      // ===== ROLLBACK EM CASO DE ERRO =====
      await connection.rollback();
      console.error(' Erro durante transação, rollback executado');
      throw error;
    }

  } catch (error) {
    console.error('Erro ao atualizar benefícios:', error);
    console.error('Stack trace:', error.stack);
    
    return res.status(500).json({
      success: false,
      message: 'Erro interno no servidor',
      error: process.env.NODE_ENV === 'development' ? error.message : undefined,
      details: process.env.NODE_ENV === 'development' ? {
        sql: error.sql,
        code: error.code
      } : undefined
    });
  } finally {
    // ===== LIBERAR CONEXÃO =====
    if (connection) {
      connection.release();
      console.log(' Conexão liberada');
    }
  }
},

  // ==============================
  // SETORES
  // ==============================
  async criarSetor(req, res) {
    try {
      const { nome_setor } = req.body;
      const empresa_id = req.user?.empresa_id || req.body.empresa_id;
      if (!empresa_id || !nome_setor) return res.status(400).json({ success: false, message: "empresa_id e nome_setor são obrigatórios" });

      const setor = await Colaborador.criarSetor(empresa_id, nome_setor);
      return res.status(201).json({ success: true, message: "Setor criado com sucesso", data: setor });
    } catch (error) {
      console.error("Erro ao criar setor:", error);
      return res.status(500).json({ success: false, message: "Erro interno no servidor" });
    }
  },

  async listarSetores(req, res) {
    try {
      const empresa_id = req.query.empresa_id || req.user?.empresa_id;
      if (!empresa_id) return res.status(400).json({ success: false, message: "empresa_id é obrigatório" });

      const setores = await Colaborador.listarSetores(empresa_id);
      return res.json({ success: true, data: setores });
    } catch (error) {
      console.error("Erro ao listar setores:", error);
      return res.status(500).json({ success: false, message: "Erro interno no servidor" });
    }
  },

  // ==============================
  // EXCLUSÃO
  // ==============================
  async excluir(req, res) {
    try {
      const { id } = req.params;
      if (!id) return res.status(400).json({ success: false, message: "ID do colaborador é obrigatório" });

      await Colaborador.delete(id);
      return res.json({ success: true, message: "Colaborador deletado com sucesso" });
    } catch (error) {
      console.error("Erro ao deletar colaborador:", error);
      return res.status(500).json({ success: false, message: "Erro interno no servidor" });
    }
  },

  // ==============================
  // REGISTRO AUTOMÁTICO
  // ==============================
  async proximoRegistro(req, res) {
    try {
      const empresa_id = req.query.empresa_id || req.user?.empresa_id;
      if (!empresa_id) return res.status(400).json({ success: false, message: "empresa_id é obrigatório" });

      const proximo = await Colaborador.proximoRegistro(empresa_id);
      return res.json({ success: true, proximoRegistro: proximo || "C001" });
    } catch (error) {
      console.error("Erro ao obter próximo registro:", error);
      return res.status(500).json({ success: false, message: "Erro interno no servidor" });
    }
  },

  // ==============================
  // BENEFÍCIOS POR CARGO
  // ==============================
async listarBeneficiosPorCargo(req, res) {
  try {
    const { cargo, usuario_id } = req.query;
    
    if (!cargo) {
      return res.status(400).json({ 
        success: false, 
        message: "Cargo é obrigatório" 
      });
    }

    console.log(' Buscando benefícios por cargo:', { cargo, usuario_id });

    const UsuarioBeneficios = require('../models/usuariosBeneficiosModel');
    
    // Buscar benefícios templates do cargo
    const beneficiosCargo = await UsuarioBeneficios.findTemplatesByCargo(cargo, { 
      apenasAtivos: true 
    });

    console.log(` ${beneficiosCargo.length} benefícios encontrados para o cargo "${cargo}"`);

    // Se tem usuario_id, buscar benefícios já atribuídos
    let beneficiosUsuario = [];
    if (usuario_id) {
      beneficiosUsuario = await UsuarioBeneficios.findByUsuario(usuario_id);
      console.log(`   Usuário ${usuario_id} tem ${beneficiosUsuario.length} benefícios`);
    }

    // Mapear benefícios com status de seleção
    const beneficios = beneficiosCargo.map(b => ({
      id: b.id || b.beneficio_id,
      beneficio_id: b.id || b.beneficio_id,
      nome_do_beneficio: b.nome_do_beneficio || b.nome,
      descricao_beneficio: b.descricao_beneficio || b.descricao || '',
      valor_aplicado: parseFloat(b.valor_aplicado) || 0,
      selecionado: beneficiosUsuario.some(ub => 
        String(ub.beneficio_id) === String(b.id) || 
        String(ub.id) === String(b.id)
      )
    }));

    return res.json({ 
      success: true, 
      data: beneficios 
    });
  } catch (err) {
    console.error(" Erro ao listar benefícios por cargo:", err);
    return res.status(500).json({ 
      success: false, 
      message: "Erro interno no servidor",
      error: err.message 
    });
  }
},
  
async buscarBeneficiosUsuario(req, res) {
  try {
    const { id } = req.params;
    
    if (!id) {
      return res.status(400).json({
        success: false,
        message: 'ID do usuário obrigatório'
      });
    }

    console.log(' Buscando benefícios do usuário:', id);

    const UsuarioBeneficios = require('../models/usuariosBeneficiosModel');
    const beneficios = await UsuarioBeneficios.findByUsuario(id);
    
    console.log(` ${beneficios.length} benefícios encontrados para usuário ${id}`);
    
    res.json({
      success: true,
      data: beneficios.map(b => ({
        id: b.usuario_beneficio_id || b.id,
        beneficio_id: b.beneficio_id,
        nome_do_beneficio: b.nome_do_beneficio,
        descricao: b.descricao_beneficio || '',
        valor_personalizado: parseFloat(b.valor_personalizado) || parseFloat(b.valor_aplicado) || 0,
        valor_aplicado: parseFloat(b.valor_aplicado) || 0,
        nome_cargo: b.nome_cargo,
        nome_setor: b.nome_setor,
        ativo: b.usuario_ativo || b.ativo
      }))
    });
  } catch (err) {
    console.error(' Erro ao buscar benefícios do usuário:', err);
    res.status(500).json({
      success: false,
      message: 'Erro ao buscar benefícios',
      error: err.message
    });
  }
},
};

module.exports = colaboradorController;
