// backend/models/usuariosBeneficiosModel.js
const db = require('../config/db');

const UsuarioBeneficios = {
  // ===== MÉTODO CORRIGIDO - SINCRONIZAR BENEFÍCIOS =====
  async sincronizarBeneficios(usuario_id, beneficios) {
    try {
      console.log(` Sincronizando ${beneficios.length} benefícios para usuário ${usuario_id}`);
      
      // 1. Remover todos os benefícios atuais do usuário
      await db.query('DELETE FROM usuario_beneficios WHERE usuario_id = ?', [usuario_id]);
      console.log('   Benefícios anteriores removidos');

      // 2. Adicionar os novos benefícios
      for (const beneficio of beneficios) {
        // ⚠️ CORREÇÃO CRÍTICA: Validar beneficio_id ANTES de inserir
        const beneficio_id = beneficio.beneficio_id || beneficio.id;
        
        if (!beneficio_id) {
          console.error(' Benefício sem ID:', beneficio);
          throw new Error(`Benefício sem ID válido: ${JSON.stringify(beneficio)}`);
        }

        console.log('➕ Adicionando benefício para usuário:', {
          usuario_id,
          beneficio_id,  //  AGORA COM ID
          nome_do_beneficio: beneficio.nome_do_beneficio,
          valor_aplicado: beneficio.valor_personalizado || beneficio.valor_aplicado
        });

        await this.addBeneficio({
          usuario_id,
          beneficio_id: beneficio_id,  //  PASSANDO O ID CORRETAMENTE
          nome_do_beneficio: beneficio.nome_do_beneficio,
          valor_personalizado: parseFloat(beneficio.valor_personalizado || beneficio.valor_aplicado || 0),
          data_inicio: beneficio.data_inicio || new Date(),
          data_fim: beneficio.data_fim || null,
          ativo: 1
        });
      }

      console.log(' Benefícios sincronizados com sucesso');
      return true;
    } catch (error) {
      console.error(' Erro ao sincronizar benefícios:', error);
      throw error;
    }
  },

  // ===== MÉTODO CORRIGIDO - ADICIONAR BENEFÍCIO =====
  async addBeneficio({ usuario_id, beneficio_id, nome_do_beneficio, valor_personalizado, data_inicio, data_fim, ativo }) {
    try {
      // ⚠️ VALIDAÇÃO CRÍTICA
      if (!usuario_id) {
        throw new Error('usuario_id é obrigatório');
      }
      
      if (!beneficio_id) {
        throw new Error('beneficio_id é obrigatório');
      }

      const sql = `
        INSERT INTO usuario_beneficios 
          (usuario_id, beneficio_id, valor_personalizado, data_inicio, data_fim, ativo) 
        VALUES (?, ?, ?, ?, ?, ?)
      `;
      
      const valores = [
        usuario_id,
        beneficio_id,  //  AGORA SEMPRE PRESENTE
        valor_personalizado || 0,
        data_inicio || new Date(),
        data_fim || null,
        ativo !== undefined ? ativo : 1
      ];

      const [result] = await db.query(sql, valores);
      
      console.log(` Benefício adicionado: ID ${result.insertId}`);
      
      return {
        id: result.insertId,
        usuario_id,
        beneficio_id,
        valor_personalizado,
        data_inicio,
        data_fim,
        ativo
      };
    } catch (error) {
      console.error(' Erro ao adicionar benefício:', error);
      throw error;
    }
  },

  // ===== BUSCAR BENEFÍCIOS DO USUÁRIO =====
  async findByUsuario(usuario_id) {
    try {
      const sql = `
        SELECT 
          ub.id as usuario_beneficio_id,
          ub.usuario_id,
          ub.beneficio_id,
          ub.valor_personalizado,
          ub.data_inicio,
          ub.data_fim,
          ub.ativo as usuario_ativo,
          gb.nome_do_beneficio,
          gb.descricao_beneficio,
          gb.valor_aplicado,
          gb.ativo as beneficio_ativo,
          c.nome_cargo,
          s.nome_setor
        FROM usuario_beneficios ub
        INNER JOIN gerenciarbeneficios gb ON ub.beneficio_id = gb.id
        LEFT JOIN cargos c ON gb.cargo_id = c.id
        LEFT JOIN setores s ON gb.setor_id = s.id
        WHERE ub.usuario_id = ?
        ORDER BY gb.nome_do_beneficio
      `;
      
      const [rows] = await db.query(sql, [usuario_id]);
      return rows || [];
    } catch (error) {
      console.error(' Erro ao buscar benefícios do usuário:', error);
      return [];
    }
  },

  // ===== REMOVER BENEFÍCIO =====
  async removeBeneficio(id) {
    try {
      await db.query('DELETE FROM usuario_beneficios WHERE id = ?', [id]);
      console.log(` Benefício ${id} removido`);
      return true;
    } catch (error) {
      console.error(' Erro ao remover benefício:', error);
      throw error;
    }
  },

  // ===== BUSCAR TEMPLATES DE BENEFÍCIOS POR CARGO =====
  async findTemplatesByCargo(cargo, options = {}) {
    try {
      const sql = `
        SELECT 
          gb.id,
          gb.nome_do_beneficio,
          gb.descricao_beneficio,
          gb.valor_aplicado,
          gb.data_inicio,
          gb.data_fim,
          gb.ativo,
          c.nome_cargo,
          s.nome_setor
        FROM gerenciarbeneficios gb
        LEFT JOIN cargos c ON gb.cargo_id = c.id
        LEFT JOIN setores s ON gb.setor_id = s.id
        WHERE gb.cargo_id = ?
          AND gb.usuario_id IS NULL
          ${options.apenasAtivos ? 'AND gb.ativo = 1' : ''}
        ORDER BY gb.nome_do_beneficio
      `;
      
      const [rows] = await db.query(sql, [cargo]);
      return rows || [];
    } catch (error) {
      console.error(' Erro ao buscar templates de benefícios:', error);
      return [];
    }
  },
  // Adicione este método ao usuariosBeneficiosModel.js
async sincronizarBeneficios(usuario_id, beneficiosArray) {
  try {
    // Remover benefícios existentes
    await db.query('DELETE FROM usuario_beneficios WHERE usuario_id = ?', [usuario_id]);
    
    // Adicionar novos benefícios
    for (const beneficio of beneficiosArray) {
      await db.query(
        `INSERT INTO usuario_beneficios 
         (usuario_id, beneficio_id, valor_personalizado, data_inicio, ativo) 
         VALUES (?, ?, ?, ?, ?)`,
        [
          usuario_id,
          beneficio.beneficio_id,
          beneficio.valor_personalizado,
          beneficio.data_inicio,
          beneficio.ativo
        ]
      );
    }
    
    return true;
  } catch (error) {
    console.error('Erro ao sincronizar benefícios:', error);
    throw error;
  }
}
};

module.exports = UsuarioBeneficios;