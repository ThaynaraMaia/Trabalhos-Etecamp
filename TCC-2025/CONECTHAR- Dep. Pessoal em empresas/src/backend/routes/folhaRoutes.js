// backend/routes/folhaRoutes.js
const express = require('express');
const router = express.Router();
const db = require('../config/db');
const { verificarToken } = require('../middlewares/authMiddleware');

// ===== ROTAS DE CONFIGURAÇÃO DE PAGAMENTO =====

// GET - Buscar configurações de pagamento
router.get('/configuracoes/pagamento', async (req, res) => {
    try {
        console.log(' GET /configuracoes/pagamento recebido');
        console.log('Query params:', req.query);
        console.log('Usuario:', req.usuario);
        
        const empresa_id = req.usuario?.empresa_id || req.query.empresa_id;
        
        if (!empresa_id) {
            console.log('Empresa ID não fornecido, retornando padrão');
            return res.json({
                qtdPagamentos: '1',
                diaPagamento1: '5',
                diaPagamento2: ''
            });
        }
        
        const [rows] = await db.query(
            'SELECT * FROM config_pagamento WHERE empresa_id = ? LIMIT 1',
            [empresa_id]
        );
        
        if (rows && rows.length > 0) {
            console.log('Configurações encontradas:', rows[0]);
            res.json(rows[0]);
        } else {
            console.log('Nenhuma configuração encontrada, retornando padrão');
            res.json({
                qtdPagamentos: '1',
                diaPagamento1: '5',
                diaPagamento2: ''
            });
        }
    } catch (error) {
        console.error('Erro ao buscar configurações de pagamento:', error);
        res.status(500).json({ 
            error: 'Erro ao buscar configurações',
            qtdPagamentos: '1',
            diaPagamento1: '5',
            diaPagamento2: ''
        });
    }
});

// POST - Salvar/atualizar configurações de pagamento
router.post('/configuracoes/pagamento', async (req, res) => {
    try {
        const { qtdPagamentos, diaPagamento1, diaPagamento2 } = req.body;
        
        // Tenta pegar empresa_id de várias fontes
        let empresa_id = req.usuario?.empresa_id || req.body.empresa_id || req.query.empresa_id;
        
        // Se não tem empresa_id, tenta extrair do token manualmente
        if (!empresa_id) {
            const authHeader = req.headers.authorization;
            if (authHeader) {
                try {
                    const jwt = require('jsonwebtoken');
                    const token = authHeader.replace('Bearer ', '');
                    const decoded = jwt.verify(token, process.env.JWT_SECRET || 'seu_secret_aqui');
                    empresa_id = decoded.empresa_id;
                } catch (err) {
                    console.error('Erro ao decodificar token:', err);
                }
            }
        }
        
        console.log('Salvando configurações:', { qtdPagamentos, diaPagamento1, diaPagamento2, empresa_id });
        
        if (!empresa_id) {
            return res.status(400).json({ error: 'empresa_id não fornecido' });
        }
        
        // Verifica se já existe configuração
        const [existing] = await db.query(
            'SELECT id FROM config_pagamento WHERE empresa_id = ?',
            [empresa_id]
        );
        
        if (existing && existing.length > 0) {
            // Atualiza
            await db.query(
                `UPDATE config_pagamento 
                SET qtdPagamentos = ?, diaPagamento1 = ?, diaPagamento2 = ?
                WHERE empresa_id = ?`,
                [qtdPagamentos || '1', diaPagamento1 || '5', diaPagamento2 || '', empresa_id]
            );
            console.log('Configurações atualizadas');
        } else {
            // Insere
            await db.query(
                `INSERT INTO config_pagamento (empresa_id, qtdPagamentos, diaPagamento1, diaPagamento2)
                VALUES (?, ?, ?, ?)`,
                [empresa_id, qtdPagamentos || '1', diaPagamento1 || '5', diaPagamento2 || '']
            );
            console.log('Configurações inseridas');
        }
        
        res.json({ 
            success: true,
            message: 'Configurações salvas com sucesso',
            data: { qtdPagamentos, diaPagamento1, diaPagamento2 }
        });
        
    } catch (error) {
        console.error('Erro ao salvar configurações de pagamento:', error);
        res.status(500).json({ error: 'Erro ao salvar configurações' });
    }
});

// rota para fazer a exportação da folha de pagamento geral, a que faz pdf
router.get('/exportar', verificarToken, async (req, res) => {
    try {
        const { tipo } = req.query;
        const empresaId = req.usuario?.empresa_id;
        
        console.log(' Exportação solicitada - Tipo:', tipo, 'Empresa:', empresaId);
        
        if (!empresaId) {
            return res.status(401).json({ error: 'Empresa não identificada' });
        }
        
        // Buscar dados da empresa
        const [empresaRows] = await db.query(
            'SELECT nome_empresa, cnpj FROM empresa WHERE id = ?',
            [empresaId]
        );
        
        console.log(' Dados da empresa encontrados:', empresaRows);
        
        if (!empresaRows || empresaRows.length === 0) {
            return res.status(404).json({ error: 'Empresa não encontrada' });
        }
        
        // Ajuste aqui: usar os nomes corretos dos campos
        const empresa = {
            nome: empresaRows[0].nome_empresa || 'Empresa não informada',
            cnpj: empresaRows[0].cnpj || 'CNPJ não informado'
        };
        
        console.log(' Objeto empresa montado:', empresa);
        
        // Buscar funcionários com cálculos
        const [funcionarios] = await db.query(`
            SELECT u.id, u.nome, u.numero_registro, u.cargo, u.setor, u.salario,
                   u.dependentes, u.horas_diarias
            FROM usuario u
            WHERE u.empresa_id = ? 
            AND u.tipo_usuario IN ('colaborador', 'funcionario')
            AND u.salario > 0
            ORDER BY u.setor, u.nome
        `, [empresaId]);
        
        console.log(' Funcionários encontrados:', funcionarios.length);
        
        // Processar dados com cálculos da folha
        const folhaService = require('../services/folhadePagamentoService');
        const processados = funcionarios.map(func => {
            const folha = folhaService.calcularFolhaFuncionario({
                salarioBruto: func.salario,
                dependentes: func.dependentes || 0,
                horasFaltadas: 0,
                horasEsperadas: 22 * (func.horas_diarias || 8),
                beneficios: []
            });
            
            return {
                ...func,
                salarioBase: func.salario,
                totalProventos: folha.salarioBruto || func.salario,
                totalDescontos: folha.totalDescontos || 0,
                salarioLiquido: folha.salarioLiquido || func.salario
            };
        });
        
        if (tipo === 'setor') {
            // Agrupar por setor
            const setores = {};
            processados.forEach(func => {
                const setor = func.setor || 'Sem Setor';
                if (!setores[setor]) {
                    setores[setor] = {
                        nome: setor,
                        funcionarios: [],
                        totalProventos: 0,
                        totalDescontos: 0,
                        totalLiquido: 0
                    };
                }
                setores[setor].funcionarios.push(func);
                setores[setor].totalProventos += func.totalProventos;
                setores[setor].totalDescontos += func.totalDescontos;
                setores[setor].totalLiquido += func.salarioLiquido;
            });
            
            const resumo = {
                totalBruto: processados.reduce((sum, f) => sum + f.totalProventos, 0),
                totalINSS: processados.reduce((sum, f) => {
                    const inss = folhaService.calcularINSS ? folhaService.calcularINSS(f.salarioBase) : 0;
                    return sum + (inss || 0);
                }, 0),
                totalIRRF: 0,
                totalFGTS: processados.reduce((sum, f) => sum + (f.salarioBase * 0.08), 0),
                totalDescontos: processados.reduce((sum, f) => sum + f.totalDescontos, 0),
                totalLiquido: processados.reduce((sum, f) => sum + f.salarioLiquido, 0)
            };
        
            console.log(' Exportação por setor preparada');
            return res.json({
                empresa,
                setores: Object.values(setores),
                totalFuncionarios: processados.length,
                resumo
            });
        } else {
            // Total da empresa
            const resumo = {
                totalBruto: processados.reduce((sum, f) => sum + f.totalProventos, 0),
                totalINSS: processados.reduce((sum, f) => {
                    const inss = folhaService.calcularINSS ? folhaService.calcularINSS(f.salarioBase) : 0;
                    return sum + (inss || 0);
                }, 0),
                totalIRRF: 0,
                totalFGTS: processados.reduce((sum, f) => sum + (f.salarioBase * 0.08), 0),
                totalDescontos: processados.reduce((sum, f) => sum + f.totalDescontos, 0),
                totalLiquido: processados.reduce((sum, f) => sum + f.salarioLiquido, 0)
            };
            
            console.log(' Exportação total preparada');
            return res.json({
                empresa,
                funcionarios: processados,
                totalFuncionarios: processados.length,
                resumo
            });
        }
        
    } catch (error) {
        console.error(' Erro ao exportar folha:', error);
        res.status(500).json({ 
            error: 'Erro ao gerar exportação',
            message: error.message 
        });
    }
});

// POST /api/folha/exportar/pdf
router.post('/exportar/pdf', verificarToken, async (req, res) => {
    try {
        const puppeteer = require('puppeteer');
        const { dados, tipo } = req.body;
        
        console.log(' Gerando PDF - Tipo:', tipo);
        
        // Gerar HTML completo
        const html = gerarHTMLCompleto(dados, tipo);
        
        const browser = await puppeteer.launch({
            headless: true,
            args: ['--no-sandbox', '--disable-setuid-sandbox']
        });
        
        const page = await browser.newPage();
        await page.setContent(html, { waitUntil: 'networkidle0' });
        
        const pdf = await page.pdf({
            format: 'A4',
            printBackground: true,
            margin: { top: '20mm', bottom: '20mm', left: '15mm', right: '15mm' }
        });
        
        await browser.close();
        
        console.log(' PDF gerado com sucesso');
        
        res.contentType('application/pdf');
        res.send(pdf);
        
    } catch (error) {
        console.error(' Erro ao gerar PDF:', error);
        res.status(500).json({ 
            error: 'Erro ao gerar PDF',
            message: error.message 
        });
    }
});

// Função auxiliar para gerar HTML completo do PDF
function gerarHTMLCompleto(dados, tipo) {
    const hoje = new Date().toLocaleDateString('pt-BR');
    const mesAno = new Date().toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' });
    
    const formatMoney = (val) => {
        return new Intl.NumberFormat('pt-BR', { 
            style: 'currency', 
            currency: 'BRL' 
        }).format(val || 0);
    };
    
    let corpoHTML = '';
    
    if (tipo === 'setor') {
        dados.setores.forEach(setor => {
            corpoHTML += `
                <div class="setor-section">
                    <h3 class="setor-header">${setor.nome} - ${setor.funcionarios.length} Funcionário(s)</h3>
                    ${gerarTabelaHTML(setor.funcionarios, formatMoney)}
                    ${gerarResumoSetorHTML(setor, formatMoney)}
                </div>
            `;
        });
    } else {
        corpoHTML += `
            <h3 class="setor-header">Folha Consolidada - ${dados.totalFuncionarios} Funcionário(s)</h3>
            ${gerarTabelaHTML(dados.funcionarios, formatMoney)}
        `;
    }
    
    corpoHTML += gerarResumoGeralHTML(dados.resumo, formatMoney);
    
    return `
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Folha de Pagamento - ${dados.empresa.nome}</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .folha-header { text-align: center; border-bottom: 3px solid #000; padding-bottom: 20px; margin-bottom: 30px; }
        .folha-header h1 { margin: 0; font-size: 18pt; }
        .folha-header p { margin: 5px 0; font-size: 10pt; }
        .setor-section { margin-bottom: 40px; page-break-after: always; }
        .setor-header { background: #667eea; color: white; padding: 12px; margin: 0 0 20px 0; font-size: 14pt; }
        .folha-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .folha-table th { background: #333; color: white; padding: 12px; text-align: left; font-size: 11pt; }
        .folha-table td { padding: 10px; border-bottom: 1px solid #ddd; font-size: 10pt; }
        .folha-total { background: #f0f0f0; font-weight: bold; font-size: 12pt; }
        .text-right { text-align: right; }
        .resumo-geral { margin-top: 40px; border-top: 3px solid #000; padding-top: 20px; }
        .resumo-final { background: #000; color: white; font-size: 14pt; }
    </style>
</head>
<body>
    <div class="folha-header">
        <h1>${dados.empresa.nome}</h1>
        <p>CNPJ: ${dados.empresa.cnpj}</p>
        <h2 style="margin: 15px 0 5px 0; font-size: 16pt;">FOLHA DE PAGAMENTO</h2>
        <p>Referência: ${mesAno}</p>
        <p>Gerado em: ${hoje}</p>
    </div>
    ${corpoHTML}
    <p style="margin-top: 30px; text-align: center; color: #666; font-size: 9pt;">
        Documento gerado eletronicamente - ${new Date().toLocaleString('pt-BR')}
    </p>
</body>
</html>
    `;
}

function gerarTabelaHTML(funcionarios, formatMoney) {
    let rows = '';
    funcionarios.forEach(func => {
        rows += `
            <tr>
                <td>${func.numero_registro || '-'}</td>
                <td>${func.nome}</td>
                <td>${func.cargo}</td>
                <td class="text-right">${formatMoney(func.salarioBase)}</td>
                <td class="text-right">${formatMoney(func.totalProventos)}</td>
                <td class="text-right">${formatMoney(func.totalDescontos)}</td>
                <td class="text-right"><strong>${formatMoney(func.salarioLiquido)}</strong></td>
            </tr>
        `;
    });
    
    return `
        <table class="folha-table">
            <thead>
                <tr>
                    <th>Matrícula</th>
                    <th>Nome</th>
                    <th>Cargo</th>
                    <th class="text-right">Salário Base</th>
                    <th class="text-right">Proventos</th>
                    <th class="text-right">Descontos</th>
                    <th class="text-right">Líquido</th>
                </tr>
            </thead>
            <tbody>${rows}</tbody>
        </table>
    `;
}

function gerarResumoSetorHTML(setor, formatMoney) {
    return `
        <table class="folha-table" style="margin-top: 20px; width: 50%; margin-left: auto;">
            <tr class="folha-total">
                <td>Total de Proventos:</td>
                <td class="text-right">${formatMoney(setor.totalProventos)}</td>
            </tr>
            <tr class="folha-total">
                <td>Total de Descontos:</td>
                <td class="text-right">${formatMoney(setor.totalDescontos)}</td>
            </tr>
            <tr class="folha-total" style="background: #667eea; color: white;">
                <td>Total Líquido do Setor:</td>
                <td class="text-right">${formatMoney(setor.totalLiquido)}</td>
            </tr>
        </table>
    `;
}

function gerarResumoGeralHTML(resumo, formatMoney) {
    return `
        <div class="resumo-geral">
            <h3 style="margin-bottom: 20px; font-size: 14pt;">RESUMO GERAL</h3>
            <table class="folha-table" style="width: 60%; margin-left: auto;">
                <tr><td><strong>Total Bruto Geral:</strong></td><td class="text-right">${formatMoney(resumo.totalBruto)}</td></tr>
                <tr><td><strong>Total INSS:</strong></td><td class="text-right">${formatMoney(resumo.totalINSS)}</td></tr>
                <tr><td><strong>Total IRRF:</strong></td><td class="text-right">${formatMoney(resumo.totalIRRF)}</td></tr>
                <tr><td><strong>Total FGTS:</strong></td><td class="text-right">${formatMoney(resumo.totalFGTS)}</td></tr>
                <tr class="folha-total resumo-final">
                    <td><strong>TOTAL LÍQUIDO GERAL:</strong></td>
                    <td class="text-right"><strong>${formatMoney(resumo.totalLiquido)}</strong></td>
                </tr>
            </table>
        </div>
    `;
}
module.exports = router;