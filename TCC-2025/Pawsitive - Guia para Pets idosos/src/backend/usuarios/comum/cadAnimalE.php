<?php
session_start();
include_once __DIR__ . '/../../classes/class_conexao.php';
include_once __DIR__ . '/../../classes/class_IRepositorioAnimaisEstimacao.php';
include_once __DIR__ . '/../../classes/class_AnimaisEstimacao.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../login/login_form.php");
    exit;
}

$user_id = $_SESSION['user']['id'] ?? null;
if (!$user_id) {
    die("Usuário não identificado.");
}

$erro = "";
$sucesso = "";

// Instanciar repositório
$repositorio = new RepositorioAnimalEstimacaoMYSQL();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar entradas e validar
    $nome = trim($_POST['nome_animale'] ?? '');
    $genero = trim($_POST['genero_animale'] ?? '');
    $especie = trim($_POST['especie_animale'] ?? '');
    $idade = trim($_POST['idade_animale'] ?? '');
    $condicao = trim($_POST['condicao_saudee'] ?? '');
    $foto = '';  // trataremos arquivo abaixo

    // Validações básicas
    if ($nome === '' || $genero === '' || $especie === '' || !is_numeric($idade) || $idade < 0) {
        $erro = "Por favor, preencha todos os campos corretamente.";
    } else {

        // Upload da foto
        $uploadDir = '../../../imgAnimais/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $nomeArquivo = uniqid() . '-' . preg_replace('/[^a-zA-Z0-9_.-]/', '', basename($_FILES['foto_animale']['name']));
        $caminhoCompleto = $uploadDir . $nomeArquivo;

        if (isset($_FILES['foto_animale']) && $_FILES['foto_animale']['error'] === UPLOAD_ERR_OK) {
            if (move_uploaded_file($_FILES['foto_animale']['tmp_name'], $caminhoCompleto)) {
                $foto = '../../../imgAnimais/' . $nomeArquivo; // Caminho relativo salvo no banco
            } else {
                die("Erro ao fazer upload da foto.");
            }
        } else {
            die("Arquivo de foto não enviado ou com erro.");
        }
    }

    if (!$erro) {
        // Cria objeto AnimalE
        $animal = new AnimalE(null, $nome, $genero, $especie, $idade, $condicao, $foto, $user_id);

        // Cadastra no banco
        $sucesso = $repositorio->cadastrarAnimal($animal, $user_id);

        if ($sucesso) {
            // $sucesso = "Animal cadastrado com sucesso!";
            header('Location: perfilusuario.php');
            exit;
        } else {
            $erro = "Erro ao cadastrar animal.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../adm/gusuarios.css">
    <title>Cadastro de Animal de Estimação</title>
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
            border-radius: 15px;
            margin-left: 13px;
            margin-top: 8px;
            margin-bottom: 8px;
            width: 700px;
            height: 170px;
            background: white;
            border: none;
            margin-left: 65px;
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


            <form method="POST" enctype="multipart/form-data" style="margin-top: 10px;">
                <div class="cadastro">
                    <h1>Cadastrar Animal de Estimação</h1>

                    <?php if ($erro): ?>
                        <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
                    <?php elseif ($sucesso): ?>
                        <p style="color:green;"><?= htmlspecialchars($sucesso) ?></p>
                    <?php endif; ?>

                    <input type="text" name="nome_animale" value="<?= htmlspecialchars($_POST['nome_animale'] ?? '') ?>" placeholder="Nome" class="input" required>

                    <div class="telefone">
                        <label for="">Gênero:</label>
                        <select name="genero_animale" required>
                            <option value="">Selecione</option>
                            <option value="Macho" <?= (($_POST['genero_animale'] ?? '') === 'Macho') ? 'selected' : '' ?>>Macho</option>
                            <option value="Fêmea" <?= (($_POST['genero_animale'] ?? '') === 'Fêmea') ? 'selected' : '' ?>>Fêmea</option>
                        </select>
                    </div>


                    <div class="telefone">
                        <label>Espécie:</label>
                        <select name="especie_animale" required>
                            <option value="">Selecione</option>
                            <option value="gato" <?= (($_POST['especie_animale'] ?? '') === 'gato') ? 'selected' : '' ?>>Gato</option>
                            <option value="cachorro" <?= (($_POST['especie_animale'] ?? '') === 'cachorro') ? 'selected' : '' ?>>Cachorro</option>
                            <option value="outro" <?= (($_POST['especie_animale'] ?? '') === 'outro') ? 'selected' : '' ?>>Outro</option>
                        </select>
                    </div>

                    <input type="number" name="idade_animale" min="0" value="<?= htmlspecialchars($_POST['idade_animale'] ?? '') ?>" placeholder="Idade" class="input" required>

                    <div class="telefone">
                        <label>Condição de Saúde:</label>
                        <select name="condicao_saudee" required>
                            <option value="">Selecione</option>
                            <option value="deficiencia motora" <?= (($_POST['condicao_saudee'] ?? '') === 'deficiencia motora') ? 'selected' : '' ?>>Deficiência Motora</option>
                            <option value="deficiencia visual" <?= (($_POST['condicao_saudee'] ?? '') === 'deficiencia visual') ? 'selected' : '' ?>>Deficiência Visual</option>
                            <option value="deficiencia auditiva" <?= (($_POST['condicao_saudee'] ?? '') === 'deficiencia auditiva') ? 'selected' : '' ?>>Deficiência Auditiva</option>
                            <option value="idoso" <?= (($_POST['condicao_saudee'] ?? '') === 'idoso') ? 'selected' : '' ?>>Idoso</option>
                        </select>
                    </div>

                        <input type="file" name="foto_animale" class="input" accept="image/*" required>

                    <div class="cadastrar">
                        <button class="botao" type="submit">Cadastrar</button>
                    </div>
                </div>

            </form>
        </div>

    </div>


</body>

</html>