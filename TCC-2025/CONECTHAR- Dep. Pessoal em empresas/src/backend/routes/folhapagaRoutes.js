// Rota para a página individual de folha de pagamento
router.get('/folhapaga/:id', async (req, res) => {
  try {
    const colaboradorId = req.params.id;
    console.log('ID do colaborador recebido:', colaboradorId);
    
    // Aqui você precisa buscar os dados reais do colaborador no banco
    // Por enquanto, vamos usar dados mock
    const colaborador = {
      id: colaboradorId,
      nome: `Colaborador ${colaboradorId}`,
      salario: 2500.00,
      horas_diarias: 8,
      dependentes: 2
    };
    
    console.log('Renderizando folhapaga para:', colaborador);
    res.render('gestor/folhapaga', {
      colaborador: colaborador,
      usuario: req.usuario // Mantém os dados do usuário logado
    });
    
  } catch (error) {
    console.error('Erro ao carregar folhapaga:', error);
    res.status(500).render('error', { 
      message: 'Erro ao carregar página de folha de pagamento',
      error: error.message
    });
  }
});