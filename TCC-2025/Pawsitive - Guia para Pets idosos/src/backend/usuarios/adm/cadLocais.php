<?php
include_once '../../classes/classIRepositorioLocais.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $repositorio = new RepositorioLocalMYSQL();

    // Criar objeto Endereco
    $endereco = new Endereco(
        null,
        $_POST['rua'],
        $_POST['numero'],
        $_POST['bairro'],
        $_POST['cidade'],
        $_POST['cep'],
        $_POST['estado'],
        $_POST['complemento']
    );

    // Criar objeto Local
    $local = new Local(
        null,
        $_POST['nome_local'],
        $_POST['descricao_local'],
        $endereco,
        $_POST['horario_abertura'],
        $_POST['horario_fechamento'],
        $_POST['tipo']
    );

    $sucesso = $repositorio->inserirLocal($local);

    if ($sucesso) {
        header("Location: glocais.php"); // redireciona após cadastro
        exit;
    } else {
        $mensagemErro = "Erro ao cadastrar local.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Local</title>
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

        h4 {
            color: #4E6422;
            font-size: 35px;
            margin: 10px;
            margin-left: 30px;
            padding-bottom: 20px;
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
            width: 1050px;
            padding: 15px;
            margin: 10px;
            margin-left: 35px;
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
            margin-left: 50px;
        }

        textarea {
            border-radius: 15px;
            margin-top: 8px;
            margin-bottom: 8px;
            width: 700px;
            height: 170px;
            background: white;
            border: none;
            margin-left: 35px;
        }

        textarea::placeholder {
            font-size: 18px;
            padding: 5px;
            color: #7a4100;
        }

        .botao {
            border: 1px solid #fff;
            background: linear-gradient(90deg, #b3dd5f 0%, #f1ba7b 100%);
            color: #7a4100;
            padding: 15px;
            width: 800px;
            text-align: center;
            margin-top: 30px;
            border-radius: 20px;
            margin-right: 30px;
            margin-left: 40px;
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
            color: #7a4100;
        }

        .botao2 {
            border: 1px solid #fff;
            background: #b3dd5f;
            padding: 15px;
            width: 180px;
            text-align: center;
            margin-left: 10px;
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

        .linkbtn{
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
            margin-left: 35px;
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

<body class="container mt-5">
    <div class="formulario" style="display: flex;align-items: center; justify-content: center;">
        <form method="POST" style="margin-top: 10px;">
            <div class="cadastro">
                <h1 class="mb-4">Cadastrar Novo Local</h1>
                <?php if (!empty($mensagemErro)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($mensagemErro) ?></div>
                <?php endif; ?>
                <div class="ares">
                    <h4>Dados do Local</h4>
                    <div class="mb-3">
                        <!-- <label class="form-label">Nome do Local</label> -->
                        <input type="text" class="input" name="nome_local" placeholder="Nome do Local" required>
                    </div>
                    <div class="mb-3">
                        <!-- <label class="form-label">Descrição</label> -->
                        <textarea name="descricao_local" placeholder="Descrição" class="input" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Horário de Abertura</label>
                        <input type="time" class="input" name="horario_abertura" required>
                    </div>
                    <div class="mb-3">
                        <label>Horário de Fechamento</label>
                        <input type="time" class="input" name="horario_fechamento" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <div class="telefone">
                            <select class="form-select" name="tipo" required>
                                <option value="ong">ONG</option>
                                <option value="veterinario">Veterinário</option>
                                <option value="petshop">Petshop</option>
                            </select>
                        </div>

                    </div>

                    <h4 class="mt-4">Endereço</h4>
                    <div class="mb-3">
                        <!-- <label class="form-label">Rua</label> -->
                        <input type="text" name="rua" class="input" placeholder="Rua" required>
                    </div>
                    <div class="mb-3">
                        <!-- <label class="form-label">Número</label> -->
                        <input type="number" class="input" placeholder="Número" name="numero" required>
                    </div>
                    <div class="mb-3">
                        <!-- <label class="form-label">Bairro</label> -->
                        <input type="text" name="bairro" class="input" placeholder="Bairro" required>
                    </div>
                    <div class="mb-3">
                        <!-- <label class="form-label">Cidade</label> -->
                        <input type="text" class="input" placeholder="Cidade" name="cidade" required>
                    </div>
                    <div class="mb-3">
                        <!-- <label class="form-label">CEP</label> -->
                        <input type="text" class="input" placeholder="CEP" name="cep" required>
                    </div>
                    <div class="mb-3">
                        <!-- <label class="form-label">Estado</label> -->
                        <input type="text" class="input" placeholder="Estado" name="estado" required>
                    </div>
                    <div class="mb-3">
                        <!-- <label class="form-label">Complemento</label> -->
                        <input type="text" class="input" placeholder="Complemento" name="complemento">
                    </div>
                </div>


                <div class="cadastrar">
                    <button type="submit" class="btn botao">Cadastrar</button>
                    <button class="botao2"><a href="glocais.php" class="linkbtn">Voltar</a></button>
                </div>

        </form>
    </div>
</body>

</html>