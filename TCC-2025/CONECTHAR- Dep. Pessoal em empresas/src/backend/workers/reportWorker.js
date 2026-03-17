// workers/reportWorker.js
const { AnalyticsReportRun, AnalyticsReportDefinition, AnalyticsExport } = require('../models');
const fs = require('fs');
const path = require('path');

async function processRun(runId) {
  const run = await AnalyticsReportRun.findByPk(runId);
  if (!run) throw new Error('Run not found');

  await run.update({ status: 'running', started_at: new Date() });

  try {
    // 1) carregar definição
    const def = await AnalyticsReportDefinition.findByPk(run.report_definition_id);

    // 2) placeholder: criar um arquivo JSON com "resultado"
    const result = {
      reportId: def.id,
      nome: def.nome,
      parametros: run.parametros_used || {},
      gerado_em: new Date().toISOString(),
      rows: [
        // exemplo - na prática: consultar DB, agregar, etc.
        { nome: 'Exemplo 1', valor: 123 },
        { nome: 'Exemplo 2', valor: 456 }
      ]
    };

    const outDir = path.resolve(__dirname, '../storage/exports');
    if (!fs.existsSync(outDir)) fs.mkdirSync(outDir, { recursive: true });

    const filename = `report_${def.slug || def.id}_${run.id}_${Date.now()}.json`;
    const filePath = path.join(outDir, filename);
    fs.writeFileSync(filePath, JSON.stringify(result, null, 2), 'utf-8');

    // 3) persistir resultado_path e criar analytics_exports
    await run.update({ status: 'success', finished_at: new Date(), duration_ms: 1, rows_count: result.rows.length, resultado_path: filePath, resultado_data: JSON.stringify(result) });

    await AnalyticsExport.create({
      run_id: run.id,
      empresa_id: def.empresa_id,
      usuario_id: run.usuario_id || null,
      tipo_export: 'other',
      caminho_arquivo: filePath,
      tamanho_bytes: fs.statSync(filePath).size
    });

    console.log(`Run ${run.id} processed, file=${filePath}`);
  } catch (err) {
    console.error('Error processing run', runId, err);
    await run.update({ status: 'failed', finished_at: new Date(), error_text: String(err) });
  }
}

module.exports = { processRun };
