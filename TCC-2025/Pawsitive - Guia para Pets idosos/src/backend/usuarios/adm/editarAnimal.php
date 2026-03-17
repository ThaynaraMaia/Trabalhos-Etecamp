<?php
include_once '../../classes/class_IRepositorioAnimaisAdocao.php';
include_once '../../classes/class_AnimaisAdocao.php';

session_start();

// Verificação de acesso
if (!isset($_SESSION['user']) || ($_SESSION['user']['tipo_usuario'] ?? '') !== 'administrador') {
    echo '<p style="color: #7a4100; font-weight: bold">Você não tem autorização para acessar esta página.</p>';
    exit();
}

$repositorio = new RepositorioAnimaisAdocaoMYSQL();
$repositorio = new RepositorioAnimaisAdocaoMYSQL();
$animalDB = $repositorio->buscarAnimalporId(intval($_GET['id']));

if (!$animalDB) {
    echo "Animal não encontrado.";
    exit;
}

// Criar objeto Animal real a partir do stdClass
$animal = new Animal(
    $animalDB->id_animal,
    $animalDB->nome_animal,
    $animalDB->caracteristicas_animal,
    $animalDB->cidade_animal,
    $animalDB->descricao_animal,
    $animalDB->genero_animal,
    $animalDB->especie_animal,
    $animalDB->idade_animal,
    $animalDB->condicao_saude,
    $animalDB->foto_animal,
    $animalDB->status_animal
);

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $animal->setNomeAnimal($_POST['nome'] ?? '');
    $animal->setCaracteristicasAnimal($_POST['caracteristicas'] ?? '');
    $animal->setCidadeAnimal($_POST['cidade'] ?? '');
    $animal->setDescricaoAnimal($_POST['descricao'] ?? '');
    $animal->setGeneroAnimal($_POST['genero'] ?? '');
    $animal->setEspecieAnimal($_POST['especie'] ?? '');
    $animal->setIdadeAnimal($_POST['idade'] ?? '');
    $animal->setCondicaoSaude($_POST['condicao'] ?? '');
    $animal->setStatusAnimal($_POST['status'] ?? '');


    // Upload da foto, se houver
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $pasta = "../../../img/animais/";
        if (!is_dir($pasta)) {
            mkdir($pasta, 0755, true);
        }
        $nomeArquivo = basename($_FILES['foto']['name']);
        $destino = $pasta . $nomeArquivo;
        move_uploaded_file($_FILES['foto']['tmp_name'], $destino);
        $animal->setFotoAnimal($destino);
    }

    // Atualiza no banco
    $repositorio->atualizarAnimal($animal);

    header('Location: ganimais.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Animal</title>
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

        .telefone{
            width: 1000px;
            background-color: #fff;
            padding: 15px;
            border: none;
            margin-left: 65px;
            margin-bottom: 8px;
            border-radius: 20px;
        }

        .input2{
            border: none;
            width: 850px;
            padding: 3px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="formulario" style="display: flex;align-items: center; justify-content: center;">
            <form method="post">
                <div class="cadastro">
                    <h1>Editar Animal</h1>

                    <div class="areas">
                        <div class="mb-3">
                            <label>Nome</label>
                            <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($animalDB->nome_animal); ?>">
                        </div>
                        <div class="mb-3">
                            <label>Características</label>
                            <input type="text" name="caracteristicas" class="form-control" value="<?php echo htmlspecialchars($animalDB->caracteristicas_animal); ?>">
                        </div>
                        <div class="mb-3">
                            <label>Cidade</label>
                            <input type="text" name="cidade" class="form-control" value="<?php echo htmlspecialchars($animalDB->cidade_animal); ?>">
                        </div>
                        <div class="mb-3">
                            <label>Descrição</label>
                            <textarea name="descricao" class="form-control"><?php echo htmlspecialchars($animalDB->descricao_animal); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Gênero</label>
                            <input type="text" name="genero" class="form-control" value="<?php echo htmlspecialchars($animalDB->genero_animal); ?>">
                        </div>
                        <div class="mb-3">
                            <label>Espécie</label>
                            <input type="text" name="especie" class="form-control" value="<?php echo htmlspecialchars($animalDB->especie_animal); ?>">
                        </div>
                        <div class="mb-3">
                            <label>Idade</label>
                            <input type="number" name="idade" class="form-control" value="<?php echo htmlspecialchars($animalDB->idade_animal); ?>">
                        </div>
                        <div class="mb-3">
                            <label>Condição de Saúde</label>
                            <input type="text" name="condicao" class="form-control" value="<?php echo htmlspecialchars($animalDB->condicao_saude); ?>">
                        </div>
                    </div>

                    <div class="cadastrar">
                        <button type="submit" class="btn botao">Salvar Alterações</button>
                        <button class="botao2"><a href="../adm/ganimais.php" class="linkbtn">Cancelar</a></button>
                    </div>

                </div>

            </form>
        </div>

    </div>
</body>

</html>