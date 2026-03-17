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

$repositorio = new RepositorioAnimalEstimacaoMYSQL();

$erro = "";
$sucesso = "";

$id_animal = $_GET['id'] ?? null;
if (!$id_animal || !is_numeric($id_animal)) {
    die("ID do animal inválido.");
}

$animal = $repositorio->buscarAnimalPorId($id_animal, $user_id);

if (!$animal) {
    die("Animal não encontrado ou não pertence ao usuário.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_animal'])) {
    $excluiu = $repositorio->removerAnimal($id_animal, $user_id);
    if ($excluiu) {
        header('Location: perfilusuario.php'); // ou onde você lista os animais
        exit;
    } else {
        $erro = "Erro ao excluir o animal.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome_animale'] ?? '');
    $genero = trim($_POST['genero_animale'] ?? '');
    $especie = trim($_POST['especie_animale'] ?? '');
    $idade = trim($_POST['idade_animale'] ?? '');
    $condicao = trim($_POST['condicao_saudee'] ?? '');
    $foto = $animal->foto_animale; // manter foto atual

    if ($nome === '' || $genero === '' || $especie === '' || !is_numeric($idade) || $idade < 0) {
        $erro = "Por favor, preencha todos os campos corretamente.";
    } else {
        // Upload da nova foto (opcional)
        if (isset($_FILES['foto_animale']) && $_FILES['foto_animale']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../../imgAnimais/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $nomeArquivo = uniqid() . '-' . preg_replace('/[^a-zA-Z0-9_.-]/', '', basename($_FILES['foto_animale']['name']));
            $caminhoCompleto = $uploadDir . $nomeArquivo;
            if (move_uploaded_file($_FILES['foto_animale']['tmp_name'], $caminhoCompleto)) {
                $foto = '../../../imgAnimais/' . $nomeArquivo;
            } else {
                $erro = "Erro ao fazer upload da foto.";
            }
        }

        if (!$erro) {
            $animalAtualizado = new AnimalE($id_animal, $nome, $genero, $especie, $idade, $condicao, $foto, $user_id);
            $atualizou = $repositorio->atualizarAnimal($animalAtualizado, $user_id);
            if ($atualizou) {
                $sucesso = "Animal atualizado com sucesso!";
                // Atualizar objeto animal para mostrar as atualizações no formulário
                $animal = $repositorio->buscarAnimalPorId($id_animal, $user_id);
            } else {
                $erro = "Erro ao atualizar o animal.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="perfilA.css">
    <link rel="stylesheet" href="../../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <title>Editar Animal de Estimação</title>
    <style>
        .perfil-container {
            background-color: #efebce;
            padding: 40px;
            width: 900px;
            margin-top: 50px;
            margin-bottom: 150px;
        }

        .tudo {
            width: 800px;
        }

        .image {
            display: flex;
            justify-items: center;
            align-items: center;
            justify-content: center;
        }

        .foto-perfil {
            width: 200px;
            height: 200px;
            margin-top: 15px;
            border: solid 1px #4E6422;
        }

        h1 {
            text-align: center;
            margin: 20px;
            padding-bottom: 20px;
            color: #4E6422;
            font-size: 55px;
        }

        .salvar-btn {
            border: 1px solid #fff;
            border-radius: 20px;
            background: linear-gradient(90deg, #b3dd5f 0%, #f1ba7b 100%);
            color: #7a4100;
            width: 350px;
        }

        .botao {
            display: flex;
            justify-items: center;
            align-items: center;
            justify-content: center;
        }

        .excluir {
            border: 1px solid #4E6422;
            border-radius: 20px;
            color: #4E6422;
            background-color: #fff;
            transition: 0.5s;
        }

        .excluir:hover {
            background-color: #4E6422;
            color: #fff;
            border: 1px solid #fff;
            transition: 0.5s;
        }

        #linksnav {
            color: #4E6422;
            margin-top: 15px;
        }

        #linksnav:hover {
            color: #A8B16B;
        }

        .texto{
            font-size: 20px;
            color: #4E6422;
        }

        .value-view{
            font-size: 20px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row" id="rownav" style="background-color: #A8B16B;">
            <nav class="navbar position-relative" style="height: 135px; position: relative;">
                <div class="container position-relative" style="position: relative;">

                    <!-- Botão à direita -->
                    <div class="ms-auto">
                        <a href="../imgUsuarios/user_padrao.png"></a>
                        <?php
                        // session_start();

                        if (isset($_SESSION['user'])) {
                            // Caminho padrão da foto do usuário
                            $foto_padrao = '/imgUsuarios/user_padrao.png'; // Usando o caminho correto para a imagem padrão

                            // Usa a foto do usuário ou a foto padrão se estiver vazia
                            $foto_user = !empty($_SESSION['user']['foto_usuario']) ? $_SESSION['user']['foto_usuario'] : $foto_padrao;

                            // Verifica se a imagem do usuário é a imagem padrão ou personalizada
                            if ($foto_user == $foto_padrao) {
                                // Caminho da imagem padrão com "../"
                                $caminho_imagem = '../../../' . $foto_user;
                            } else {
                                // Caminho da imagem personalizada (sem "../")
                                $caminho_imagem = $foto_user;
                            }

                            // Exibe o link para o perfil com a foto do usuário
                            echo '<a href="../comum/perfilusuario.php">';
                            echo '<img src="' . htmlspecialchars($caminho_imagem) . '?t=' . time() . '" alt="Perfil" style="width:50px; height:50px; border-radius:50%; margin-right:20px;">';
                            echo '</a>';

                            // Botão de logout
                            echo '<a class="btnLogout" href="../comum/logout.php" style="border: #4E6422 1px solid; background-color: #737b3f; width: 80px; height: 30px; border-radius: 50px; color: #FFF5EA; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">Sair</a>';
                        } else {
                            // Caso o usuário não esteja logado, exibe o botão de login
                            echo '<a class="btnLogin" href="../backend/login/login_form.php"><button class="btnLogin" style="border: #4E6422 1px solid; background-color: #737b3f; width: 130px; height: 30px; border-radius: 50px; color: #FFF5EA;">Login</button></a>';
                        }
                        ?>

                        <!-- Logo centralizada -->
                        <div style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
                            <img src="../../../img/logonav2.png" alt="Logo" id="imgnav"
                                style="width: 320px; max-height: 140px; object-fit: contain; display: block; margin: 0 auto;">
                        </div>
                    </div>
            </nav>
        </div>
        <div class="row">
            <div class="nav2">
                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../../../frontend/pgInicial.php" class="nav-link" id="linksnav">Pagina Inicial </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../../../frontend/parceiros/parceiros.php" class="nav-link" id="linksnav">Parceiros</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../../../frontend/deficiencias/guiasc.php" class="nav-link" id="linksnav">Guias</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../../../frontend/adocao/adocao2.php" class="nav-link" id="linksnav">Adoção</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../../../frontend/locais.php" class="nav-link" id="linksnav">Locais</a>
                        </div>
                    </li>

                    <?php
                    if (isset($_SESSION['user']) && ($_SESSION['user']['tipo_usuario'] ?? '') === 'administrador') {
                        echo '<li class="nav-item">';
                        echo '<div class="col-sm">';
                        echo '<a href="../adm/pgAdm.php" class="nav-link" id="linksnav">Administração</a>';
                        echo '</div>';
                        echo '</li>';
                    }
                    ?>

                </ul>
            </div>
        </div>


        <div class="perfil-container">
            <h1>Editar Animal de Estimação</h1>

            <!-- <?php if ($sucesso) : ?>
            <script>
                showAlert("<?= addslashes($sucesso) ?>", "success");
            </script>
        <?php endif; ?> -->
            <!-- <?php if ($erro) : ?>
            <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
        <?php elseif ($sucesso) : ?>
            <p id="msgSucesso" style="color:green;"><?= htmlspecialchars($sucesso) ?></p>
        <?php endif; ?> -->


            <form method="POST" enctype="multipart/form-data" id="perfilAnimalForm">
                <div class="form-group image">
                    <div class="foto-wrapper">
                        <img class="foto-perfil" src="<?= htmlspecialchars($animal->foto_animale) ?>" alt="Foto do animal" style="max-width:200px;">
                        <svg class="edit-photo-icon" title="Editar foto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" onclick="document.getElementById('foto_animale').click()">
                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM21.41 6.34a1.25 1.25 0 000-1.77l-2-2a1.25 1.25 0 00-1.77 0l-1.83 1.83 3.75 3.75 1.85-1.84z" />
                        </svg>
                    </div>
                    <input type="file" id="foto_animale" name="foto_animale" accept="image/*" style="display:none;">
                </div>
                <div class="form-group" data-field="nome_animale">
                    <label class="texto" for="nome_animale">Nome:</label>
                    <span class="value-view" id="nome_animaleView"><?= htmlspecialchars($animal->nome_animale) ?></span>
                    <svg class="edit-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" onclick="toggleEdit('nome_animale')" title="Editar nome">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM21.41 6.34a1.25 1.25 0 000-1.77l-2-2a1.25 1.25 0 00-1.77 0l-1.83 1.83 3.75 3.75 1.85-1.84z" />
                    </svg>
                    <input type="text" class="editar-input" id="nome_animale" name="nome_animale" value="<?= htmlspecialchars($animal->nome_animale) ?>" />
                </div>

                <div class="form-group" data-field="genero_animale">
                    <label class="texto" for="genero_animale">Gênero:</label>
                    <span class="value-view" id="genero_animaleView"><?= htmlspecialchars($animal->genero_animale) ?></span>
                    <svg class="edit-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" onclick="toggleEdit('genero_animale')" title="Editar gênero">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM21.41 6.34a1.25 1.25 0 000-1.77l-2-2a1.25 1.25 0 00-1.77 0l-1.83 1.83 3.75 3.75 1.85-1.84z" />
                    </svg>
                    <select class="editar-input" id="genero_animale" name="genero_animale">
                        <option value="Macho" <?= ($animal->genero_animale === 'Macho') ? 'selected' : '' ?>>Macho</option>
                        <option value="Fêmea" <?= $animal->genero_animale === 'Fêmea' ? 'selected' : '' ?>>Fêmea</option>
                    </select>
                </div>

                <div class="form-group" data-field="especie_animale">
                    <label class="texto" for="especie_animale">Espécie:</label>
                    <span class="value-view" id="especie_animaleView"><?= htmlspecialchars($animal->especie_animale) ?></span>
                    <svg class="edit-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" onclick="toggleEdit('especie_animale')" title="Editar espécie">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM21.41 6.34a1.25 1.25 0 000-1.77l-2-2a1.25 1.25 0 00-1.77 0l-1.83 1.83 3.75 3.75 1.85-1.84z" />
                    </svg>
                    <input type="text" class="editar-input" id="especie_animale" name="especie_animale" value="<?= htmlspecialchars($animal->especie_animale) ?>" />
                </div>

                <div class="form-group" data-field="idade_animale">
                    <label class="texto" for="idade_animale">Idade (anos):</label>
                    <span class="value-view" id="idade_animaleView"><?= htmlspecialchars($animal->idade_animale) ?></span>
                    <svg class="edit-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" onclick="toggleEdit('idade_animale')" title="Editar idade">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM21.41 6.34a1.25 1.25 0 000-1.77l-2-2a1.25 1.25 0 00-1.77 0l-1.83 1.83 3.75 3.75 1.85-1.84z" />
                    </svg>
                    <input type="number" class="editar-input" id="idade_animale" name="idade_animale" min="0" value="<?= htmlspecialchars($animal->idade_animale) ?>" />
                </div>

                <div class="form-group" data-field="condicao_saudee">
                    <label class="texto" for="condicao_saudee">Condição de Saúde:</label>
                    <span class="value-view" id="condicao_saudeeView"><?= htmlspecialchars($animal->condicao_saudee) ?></span>
                    <svg class="edit-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" onclick="toggleEdit('condicao_saudee')" title="Editar condição">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM21.41 6.34a1.25 1.25 0 000-1.77l-2-2a1.25 1.25 0 00-1.77 0l-1.83 1.83 3.75 3.75 1.85-1.84z" />
                    </svg>
                    <input type="text" class="editar-input" id="condicao_saudee" name="condicao_saudee" value="<?= htmlspecialchars($animal->condicao_saudee) ?>" />
                </div>

                <div class="botao">
                    <button class="salvar-btn" type="submit" id="salvarBtn">Salvar alterações</button>
                </div>

            </form>
            <form method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este animal? Esta ação não poderá ser desfeita.');">
                <input type="hidden" name="excluir_animal" value="1">
                <button type="submit" class="btn excluir" style="margin-top: 10px;">Excluir Animal</button>
            </form>


        </div>
    </div>

    <script>
        function toggleEdit(field) {
            const view = document.getElementById(field + "View");
            const input = document.getElementById(field);
            const btn = document.getElementById("salvarBtn");

            if (input.style.display === "none" || input.style.display === "") {
                view.style.display = "none";
                input.style.display = "inline-block";
                btn.style.display = "block";
                input.focus();
            } else {
                input.style.display = "none";
                view.style.display = "inline-block";
                if (!document.querySelector(".editar-input:not([style*='display: none'])")) {
                    btn.style.display = "none";
                }
            }
        }

        // Mostrar botão salvar ao escolher arquivo no input file
        document.getElementById('foto_animale').addEventListener('change', function() {
            const btn = document.getElementById('salvarBtn');
            btn.style.display = 'block';
        });

        // window.onload = function() {
        //     const msg = document.getElementById('msgSucesso');
        //     if (msg) {
        //         setTimeout(() => {
        //             msg.style.transition = 'opacity 1s ease';
        //             msg.style.opacity = 0;
        //             setTimeout(() => msg.remove(), 1000);
        //         }, 3000); // espera 3 segundos antes de sumir
        //     }
        // };
    </script>

    <!-- <script src="msgSucesso.js"></script> -->

</body>

</html>