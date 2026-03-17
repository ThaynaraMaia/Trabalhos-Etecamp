const Simulador = require("../models/simulador");

exports.calcular = async (req, res) => {
  try {
    const { alimentacao = 0, moradia = 0, transporte = 0, lazer = 0, outros = 0 } = req.body;
    const id_usuario = req.usuario.id_usuario || req.usuario.id;

    if (!id_usuario) return res.status(400).json({ message: "Usuário não identificado no token." });

    const total_mensal = Number(alimentacao) + Number(moradia) + Number(transporte) + Number(lazer) + Number(outros);
    const total_semanal = total_mensal / 4;
    const total_diario = total_mensal / 30;

    const registro = await Simulador.create({
      id_usuario,
      alimentacao,
      moradia,
      transporte,
      lazer,
      outros,
      total_mensal,
      total_semanal,
      total_diario
    });

    res.json({ message: "Cálculo realizado e salvo!", resultado: registro });
  } catch (error) {
    console.error(error);
    res.status(500).json({ message: "Erro ao calcular custos." });
  }
};

exports.historico = async (req, res) => {
  try {
    const id_usuario = req.usuario.id_usuario || req.usuario.id;

    if (!id_usuario) return res.status(400).json({ message: "Usuário não identificado no token." });

    const registros = await Simulador.findAll({
      where: { id_usuario },
      order: [["createdAt", "DESC"]]
    });

    res.json(registros);
  } catch (error) {
    console.error(error);
    res.status(500).json({ message: "Erro ao buscar histórico." });
  }
};

exports.excluir = async (req, res) => {
  try {
    const { id } = req.params;
    const id_usuario = req.usuario.id_usuario || req.usuario.id;

    const registro = await Simulador.findOne({ where: { id, id_usuario } });
    if (!registro) return res.status(404).json({ message: "Cálculo não encontrado." });

    await registro.destroy();
    res.json({ message: "Cálculo excluído com sucesso." });
  } catch (error) {
    console.error(error);
    res.status(500).json({ message: "Erro ao excluir cálculo." });
  }
};

exports.excluirTodos = async (req, res) => {
  try {
    const id_usuario = req.usuario.id_usuario || req.usuario.id;
    await Simulador.destroy({ where: { id_usuario } });
    res.json({ message: "Todo o histórico foi excluído." });
  } catch (error) {
    console.error(error);
    res.status(500).json({ message: "Erro ao excluir histórico." });
  }
};
