const Bolsa = require('../models/bolsa');

const getLembretes = async (req, res) => {
  try {
    const hoje = new Date();

    // Formatando a data de hoje
    const hojeStr = hoje.toISOString().slice(0, 10);

    // Busca todas as bolsas
    const bolsas = await Bolsa.findAll();

    // Filtra as que precisam gerar lembrete
    const lembretes = bolsas
      .map(bolsa => {
        const prazo = new Date(bolsa.prazo_inscricao);

        // Data 5 dias antes
        const cincoDiasAntes = new Date(prazo);
        cincoDiasAntes.setDate(prazo.getDate() - 5);

        // Data limite (15 dias depois do prazo)
        const limite = new Date(prazo);
        limite.setDate(prazo.getDate() + 15);

        // Se hoje é 5 dias antes, no dia, ou até 15 dias depois
        if (
          hojeStr === cincoDiasAntes.toISOString().slice(0, 10) || // 5 dias antes
          hojeStr === prazo.toISOString().slice(0, 10) ||          // no dia
          (hoje >= prazo && hoje <= limite)                        // até 15 dias depois
        ) {
          return {
            id_usuario: 1, 
            bolsa,
            data_lembrete: hojeStr
          };
        }
        return null;
      })
      .filter(l => l !== null);

    res.json(lembretes);

  } catch (error) {
    console.error(error);
    res.status(500).json({ message: 'Erro ao buscar lembretes' });
  }
};


module.exports = { getLembretes };

