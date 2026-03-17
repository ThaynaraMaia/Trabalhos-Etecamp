// backend/routes/gestorViewsRoutes.js
const express = require('express');
const router = express.Router();
const db = require('../config/db');

// ROTA PRINCIPAL: FOLHA DE PAGAMENTO PARA COLABORADOR
router.get('/folhapaga/:id', async (req, res) => {
  const id = Number(req.params.id);
  if (!id) return res.status(400).send('ID inválido');

  try {
    console.log(' Buscando dados do colaborador ID:', id);

    // 1) Buscar usuário com dados da empresa
    const [rowsUser] = await db.query(`
      SELECT u.*, e.nome_empresa, e.cnpj as cnpj_empresa 
      FROM usuario u 
      LEFT JOIN empresa e ON u.empresa_id = e.id 
      WHERE u.id = ?
    `, [id]);

    const colaborador = rowsUser[0];
    if (!colaborador) return res.status(404).send('Colaborador não encontrado');

    console.log(' Colaborador encontrado:', colaborador.nome);

    // 2) Buscar benefícios do usuário
    let beneficios = [];
    try {
      const [benefUserRows] = await db.query(`
        SELECT ub.id, ub.beneficio_id, ub.valor_personalizado,
               gb.nome_do_beneficio, gb.descricao_beneficio, gb.valor_aplicado
        FROM usuario_beneficios ub
        LEFT JOIN gerenciarbeneficios gb ON gb.id = ub.beneficio_id
        WHERE ub.usuario_id = ? AND ub.ativo = 1
      `, [id]);

      beneficios = benefUserRows.map(b => ({
        id: b.beneficio_id,
        nome_do_beneficio: b.nome_do_beneficio,
        descricao_beneficio: b.descricao_beneficio,
        valor: Number(b.valor_personalizado || b.valor_aplicado || 0)
      }));
    } catch (err) {
      console.warn('Erro ao buscar beneficios:', err.message);
    }

    // 3) Preparar dados para template
    const dadosTemplate = {
      title: `Folha de Pagamento - ${colaborador.nome}`,
      usuario: {
        id: colaborador.id,
        nome: colaborador.nome,
        cpf: colaborador.cpf,
        email: colaborador.email,
        numero_registro: colaborador.numero_registro,
        cargo: colaborador.cargo,
        setor: colaborador.setor,
        salario: parseFloat(colaborador.salario) || 0,
        dependentes: colaborador.dependentes || 0
      },
      empresa: {
        nome_empresa: colaborador.nome_empresa || 'Empresa não informada',
        cnpj: colaborador.cnpj_empresa || 'CNPJ não informado'
      },
      beneficios: beneficios,
      beneficiosJSON: JSON.stringify(beneficios),
      usuarioJSON: JSON.stringify(colaborador),
      mesReferencia: new Date().toISOString().substring(0,7),
      user: req.usuario || {}
    };

    console.log('Dados empresa:', dadosTemplate.empresa);
    console.log(' Salário:', dadosTemplate.usuario.salario);

    res.render('folhapaga', dadosTemplate);

  } catch (err) {
    console.error(' Erro ao renderizar folhapaga:', err);
    res.status(500).send('Erro interno: ' + err.message);
  }
});

// ROTA ALTERNATIVA COM QUERY PARAM
router.get('/folhapaga', async (req, res) => {
  try {
    const usuarioId = req.query.usuarioId;
    if (!usuarioId) return res.status(400).send('usuarioId não fornecido');
    
    return res.redirect(`/gestor/folhapaga/${usuarioId}`);
  } catch (err) {
    console.error(' Erro GET /folhapaga (query):', err);
    res.status(500).send('Erro interno');
  }
});

module.exports = router;