<?php
include_once '../../classes/classIRepositorioLocais.php';

if (!isset($_GET['id'])) {
    echo "ID do local não informado.";
    exit;
}

$repositorio = new RepositorioLocalMYSQL();
$local = $repositorio->buscarPorId($_GET['id']);

if (!$local) {
    echo "Local não encontrado.";
    exit;
}

$endereco = $local->getEndereco();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Local</title>
    <link rel="stylesheet" href="../../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <style>
        input::placeholder {
            color: #7a4100;
            font-size: 18px;
            padding: 10px;
        }

        h1 {
            text-align: center;
            margin: 20px;
            padding-bottom: 20px;
            color: #4E6422;
            font-size: 55px;
        }

        .cadastro {
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 40px;
            margin-top: 40px;
            background-color: #eee9c1ff;
            padding-right: 90px;
            width: 1250px;
            margin-left: 20px;
            padding-left: 80px;
        }

        .input {
            width: 1000px;
            padding: 15px;
            margin: 10px;
            margin-left: 65px;
            border-radius: 20px;
            border: none;
            background: white;
        }

        /* .formulario {
            margin-top: 10px;
        } */

        label {
            color: #7a4100;
            font-size: 18px;
        }

        textarea {
            border-radius: 25px;
            margin-left: 13px;
            margin-top: 8px;
            margin-bottom: 8px;
            width: 700px;
            height: 170px;
            background: white;
            border: none;
            margin-left: 1px;
        }

        textarea::placeholder {
            font-size: 18px;
            padding: 10px;
            color: #7a4100;
        }

        .botao {
            border: 1px solid #fff;
            background: linear-gradient(90deg, #b3dd5f 0%, #f1ba7b 100%);
            color: #7a4100;
            padding: 15px;
            width: 800px;
            text-align: center;
            margin-left: 10px;
            margin-top: 30px;
            border-radius: 20px;
            margin-left: 65px;
            font-weight: bold;
            font-size: 23px;
            position: relative;
            overflow: hidden;
        }

        .botao::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .botao:hover::before {
            left: 100%;
        }

        .botao:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .botao2 {
            border: 1px solid #fff;
            background: #b3dd5f;
            padding: 15px;
            width: 180px;
            text-align: center;
            margin-left: 40px;
            margin-top: 30px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 23px;
            position: relative;
            overflow: hidden;
        }

        .botao2::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .botao2:hover::before {
            left: 100%;
        }

        .botao2:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .linkbtn {
            text-decoration: none;
            color: #4E6422;
            font-weight: bold;
        }

        .cadastrar {
            display: flex;
            justify-content: center;
            align-items: center;

        }

        .status {
            width: 1000px;
            padding: 15px;
            margin: 10px;
            margin-left: 65px;
            border-radius: 20px;
            border: none;
            background: white;
        }

        .telefone {
            width: 1000px;
            background-color: #fff;
            padding: 15px;
            border: none;
            margin-left: 65px;
            margin-bottom: 8px;
            border-radius: 20px;
        }

        .input2 {
            border: none;
            width: 850px;
            padding: 3px;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="formulario" style="display: flex;align-items: center; justify-content: center;">

            <form action="processa_edicao_locais.php" method="post">
                <div class="cadastro">
                    <h1>Editar Local</h1>

                    <div class="areas">
                        <input type="hidden" name="idLocal" value="<?= htmlspecialchars($local->getIdLocal()) ?>">
                        <input type="hidden" name="idEndereco" value="<?= htmlspecialchars($endereco->getId()) ?>">

                        <!-- Nome -->
                        <div class="mb-3">
                            <label class="form-label">Nome do Local</label>
                            <input type="text" name="nomeLocal" class="form-control"
                                value="<?= htmlspecialchars($local->getNomeLocal()) ?>" required>
                        </div>

                        <!-- Descrição -->
                        <div class="mb-3">
                            <label class="form-label">Descrição</label>
                            <textarea name="descricaoLocal" class="form-control" rows="4"><?= htmlspecialchars($local->getDescricaoLocal()) ?></textarea>
                        </div>

                        <!-- Tipo -->
                        <div class="mb-3">
                            <label class="form-label">Tipo</label>
                            <select class="form-select" name="tipo" required>
                                <option value="ong" <?= ($local->getTipo() == 'ong') ? 'selected' : '' ?>>ONG</option>
                                <option value="veterinario" <?= ($local->getTipo() == 'veterinario') ? 'selected' : '' ?>>Veterinário</option>
                                <option value="petshop" <?= ($local->getTipo() == 'petshop') ? 'selected' : '' ?>>Petshop</option>
                            </select>
                        </div>

                        <!-- Horários -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Horário de Abertura</label>
                                <input type="time" name="horarioAbertura" class="form-control"
                                    value="<?= htmlspecialchars($local->getHorarioAbertura()) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Horário de Fechamento</label>
                                <input type="time" name="horarioFechamento" class="form-control"
                                    value="<?= htmlspecialchars($local->getHorarioFechamento()) ?>">
                            </div>
                        </div>

                        <h4>Endereço</h4>

                        <div class="mb-3">
                            <label class="form-label">Rua</label>
                            <input type="text" name="rua" class="form-control" value="<?= htmlspecialchars($endereco->getRua()) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Número</label>
                            <input type="text" name="numero" class="form-control" value="<?= htmlspecialchars($endereco->getNumero()) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bairro</label>
                            <input type="text" name="bairro" class="form-control" value="<?= htmlspecialchars($endereco->getBairro()) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cidade</label>
                            <input type="text" name="cidade" class="form-control" value="<?= htmlspecialchars($endereco->getCidade()) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <input type="text" name="estado" class="form-control" value="<?= htmlspecialchars($endereco->getEstado()) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">CEP</label>
                            <input type="text" name="cep" class="form-control" value="<?= htmlspecialchars($endereco->getCep()) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Complemento</label>
                            <input type="text" name="complemento" class="form-control" value="<?= htmlspecialchars($endereco->getComplemento()) ?>">
                        </div>
                    </div>

                    <div class="cadastrar">
                        <button class="btn botao" type="submit" id="salvarBtn">Salvar alterações</button>
                        <button class="botao2"><a href="../adm/glocais.php" class="linkbtn">Cancelar</a></button>
                    </div>

                </div>

            </form>
        </div>

    </div>


</body>

</html>