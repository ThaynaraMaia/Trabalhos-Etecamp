<?php
// include_once '../../classes/class_conexao.php';
// include_once '../../classes/class_IRepositorioAnimaisAdocao.php';
// include_once '../../classes/funcoes_imagem.php';
// include_once '../../classes/class_AnimaisEstimacao.php';
// include_once '../../classes/class_IRepositorioAnimaisEstimacao.php';
session_start();
// var_dump(__DIR__);  // deve mostrar caminho completo até 'usuarios\comum'
// $path = __DIR__ . '/../../classes/class_conexao.php';
// var_dump($path);
// var_dump(realpath($path));
// var_dump(file_exists($path));

include_once __DIR__ . '/../../classes/class_conexao.php';
include_once __DIR__ . '/../../classes/class_IRepositorioAnimaisAdocao.php';
include_once __DIR__ . '/../../classes/funcoes_imagem.php';
include_once __DIR__ . '/../../classes/class_AnimaisEstimacao.php';
include_once __DIR__ . '/../../classes/class_IRepositorioAnimaisEstimacao.php';

// codigo de listagem do usuario

// Habilita erros MySQLi para debug
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['user'])) {
    header("Location: ../login/login_form.php");
    exit;
}

$user_id = $_SESSION['user']['id'] ?? null;
if (!$user_id) {
    die("Usuário não identificado.");
}

// codigo de listagem do animal

$repositorioAnimaisEstimacao = new RepositorioAnimalEstimacaoMYSQL();
$animaisEstimacao = $repositorioAnimaisEstimacao->listarAnimaisPorUsuario($user_id);

// Busca dados do usuário
$sql = "SELECT nome_usuario, email_usuario, foto_usuario FROM tblusuarios WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


// Processa atualização do usuário e upload foto
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $fotoUsuario = $user['foto_usuario']; // Mantém a foto atual, caso não seja enviado um novo arquivo
    $erro_upload = '';  // Variável para capturar erros do upload

    // Lida com o upload de imagem
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        // Chama a função salvarImagemUsuario para fazer o upload da imagem
        $fotoUsuario = salvarImagemUsuario($_FILES['foto'], $user_id, $erro_upload);

        // Se houve erro no upload, exibe a mensagem de erro
        if (!$fotoUsuario) {
            die("Erro no upload da imagem: $erro_upload");
        }
    }

    // Atualiza os dados no banco
    if (!empty($_POST['senha'])) {
        $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
        $sql_update = "UPDATE tblusuarios SET nome_usuario=?, email_usuario=?, senha_usuario=?, foto_usuario=? WHERE id=?";
        $stmt = $mysqli->prepare($sql_update);
        $stmt->bind_param("ssssi", $nome, $email, $senha, $fotoUsuario, $user_id);
    } else {
        $sql_update = "UPDATE tblusuarios SET nome_usuario=?, email_usuario=?, foto_usuario=? WHERE id=?";
        $stmt = $mysqli->prepare($sql_update);
        $stmt->bind_param("sssi", $nome, $email, $fotoUsuario, $user_id);
    }
    $stmt->execute();

    // Atualiza a sessão
    $_SESSION['user']['nome_usuario'] = $nome;
    $_SESSION['user']['email_usuario'] = $email;
    $_SESSION['user']['foto_usuario'] = $fotoUsuario;

    // Redireciona para o perfil novamente
    header("Location: perfilusuario.php");
    exit;
}
$animaisFavoritos = $respositorioAnimaisAdocao->listarAnimaisCurtidosPorUsuario($user_id);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../comum/perfil.css">
    <link rel="stylesheet" href="../../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <title>Perfil do Usuário</title>

    <style>
        .local {
            background-color: #efebce;
            width: 1200px;
        }

        .TUDO {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 40px;
            margin-bottom: 40px;
        }

        .esquerda {
            margin-top: 20px;
        }

        /* css animal */

        .estimacao-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            background-color: white;
            width: 470px;
            height: 550px;
            margin: 90px auto 0 auto;
            border-radius: 25px;
            padding: 25px;
            text-align: center;
            margin-top: 110px;
            margin-left: 90px;
        }

        .card-animal {
            width: 180px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 12px;
            background-color: #f7f7f7;
            text-align: center;
            box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.1);
            margin-left: 10px;
            margin-top: 10px;
        }

        .card-animal img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .card-animal h4 {
            margin-top: 10px;
            font-size: 16px;
            color: #333;
        }

        .favoritos-container {
            display: flex;
            text-align: center;
        }

        .direita {
            margin-top: 50px;
            background-color: white;
            border-radius: 25px;
            padding: 20px;
            margin-left: 125px;
            margin-bottom: 110px;
            width: 950px;
        }

        /* .cadAnimal{

        }

        .tetulo{
            
        } */

        .cadAnimal {
            padding: 5px;
            background-color: #A8B16B;
            color: rgb(34, 44, 15);
            text-align: center;
            width: 240px;
            height: 40px;
            text-decoration: none;
            cursor: pointer;
            border: solid 1px #4E6422;
            border-radius: 20px;
            margin-left: 85px;
        }

        .tetulo {
            text-align: center;
            color: #4E6422;
            font-size: 35px;
            margin-left: 25px;
            margin-top: 30px;
        }

        .tetulo2 {
            font-size: 35px;
            color: #4E6422;
            margin-top: 20px;
            text-align: center;
        }

        .perfil-container {
            width: 450px;
            height: 550px;
            margin-left: 120px;
        }

        .foto-perfil {
            width: 140px;
            height: 140px;
            margin-bottom: 20px;
            border: 2px solid #4E6422;
        }

        .form-group {
            font-size: 19px;
            margin: 20px;
            margin-top: 20px;
        }

        .favoritados {
            margin-left: 50px;
            margin-bottom: 110px;
        }

        .animaFAV {
            border: solid 1px #4E6422;
            padding: 12px;
            border-radius: 20px;
            margin-top: 20px;
            padding-bottom: 30px;
            margin-bottom: 60px;
            width: 100%;
            max-width: 240px;
        }


        /* .botaoLink {
            padding: 3px;
            border-radius: 15px;
            border: 2px solid #4E6422;
            background-color: #fff;
            transition: 0.5s;
        } */

        /* .botaoLink:hover {
            background-color: #4E6422;
            border: 1px solid #fff;
            transition: 0.5s;
        } */

        .linkbotao {
            padding: 8px;
            border-radius: 20px;
            border: 2px solid #4E6422;
            background-color: #fff;
            transition: 0.5s;
            text-decoration: none;
            color: #4E6422;
            font-weight: bold;
        }

        .linkbotao:hover {
            background-color: #4E6422;
            border: 1px solid #fff;
            transition: 0.5s;
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        .salvar-btn {
            cursor: pointer;
            color: #4E6422;
            background-color: #A8B16B;
            border: 1px solid #4E6422;
            border-radius: 30px;
            width: 240px;
            height: 45px;
            color: rgb(34, 44, 15);
        }
    </style>

</head>

<body>
    <div class="container-fluid">
        <!-- navbar -->
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
                        echo '<a href="../backend/usuarios/adm/pgAdm.php" class="nav-link" id="linksnav">Administração</a>';
                        echo '</div>';
                        echo '</li>';
                    }
                    ?>

                </ul>
            </div>
        </div>

        <div class="TUDO">
            <div class="local">
                <div class="row">
                    <div class="esquerda col-5">
                        <div class="perfil-container">
                            <form id="perfilForm" method="POST" enctype="multipart/form-data">
                                <div class="foto-wrapper">
                                    <?php
                                    $foto_padrao = '/imgUsuarios/user_padrao.png';
                                    $foto_usuario = !empty($user['foto_usuario']) ? $user['foto_usuario'] : $foto_padrao;

                                    // Define o caminho da imagem com ou sem "../" dependendo se é padrão ou personalizada
                                    $caminho_imagem = ($foto_usuario === $foto_padrao) ? '../../../' . $foto_usuario : $foto_usuario;
                                    ?>

                                    <img class="foto-perfil" id="fotoPerfil" src="<?= htmlspecialchars($caminho_imagem) . '?t=' . time() ?>" alt="Foto do Usuário" />

                                    <svg class="edit-photo-icon" title="Editar foto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" onclick="toggleFotoInput()">
                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM21.41 6.34a1.25 1.25 0 000-1.77l-2-2a1.25 1.25 0 00-1.77 0l-1.83 1.83 3.75 3.75 1.85-1.84z" />
                                    </svg>
                                </div>
                                <input type="file" id="fotoInput" name="foto" accept="image/*" onchange="previewImage(event)" />

                                <div class="form-group" data-field="nome">
                                    <label for="nome">Nome:</label>
                                    <span class="value-view" id="nomeView"><?= htmlspecialchars($user['nome_usuario']) ?></span>
                                    <svg class="edit-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" onclick="toggleEdit('nome')" title="Editar nome">
                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM21.41 6.34a1.25 1.25 0 000-1.77l-2-2a1.25 1.25 0 00-1.77 0l-1.83 1.83 3.75 3.75 1.85-1.84z" />
                                    </svg>
                                    <input class="editar-input" type="text" id="nome" name="nome" value="<?= htmlspecialchars($user['nome_usuario']) ?>" />
                                </div>

                                <div class="form-group" data-field="email">
                                    <label for="email">Email:</label>
                                    <span class="value-view" id="emailView"><?= htmlspecialchars($user['email_usuario']) ?></span>
                                    <svg class="edit-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" onclick="toggleEdit('email')" title="Editar email">
                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM21.41 6.34a1.25 1.25 0 000-1.77l-2-2a1.25 1.25 0 00-1.77 0l-1.83 1.83 3.75 3.75 1.85-1.84z" />
                                    </svg>
                                    <input class="editar-input" type="email" id="email" name="email" value="<?= htmlspecialchars($user['email_usuario']) ?>" />
                                </div>

                                <div class="form-group" data-field="senha">
                                    <label for="senha">Nova senha:</label>
                                    <span class="value-view" id="senhaView">*********</span>
                                    <svg class="edit-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" onclick="toggleEdit('senha')" title="Editar senha">
                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM21.41 6.34a1.25 1.25 0 000-1.77l-2-2a1.25 1.25 0 00-1.77 0l-1.83 1.83 3.75 3.75 1.85-1.84z" />
                                    </svg>
                                    <input class="editar-input" type="password" id="senha" name="senha" placeholder="Deixe vazio para manter a senha atual" />
                                </div>

                                <button class="salvar-btn" type="submit" id="salvarBtn">Salvar alterações</button>
                            </form>
                        </div>
                    </div>

                    <div class="col-5">
                        <div class="estimacao-container">
                            <h3 class="tetulo">Animais de Estimação</h3>
                            <a href="cadAnimalE.php"><button class="cadAnimal">Cadastrar Animal</button></a>
                            <div class="row">
                                <?php if ($animaisEstimacao && count($animaisEstimacao) > 0): ?>
                                    <?php foreach ($animaisEstimacao as $animal): ?>
                                        <div class="col">
                                            <div class="card-animal">
                                                <a href="perfilanimal.php?id=<?= htmlspecialchars($animal->id_animale) ?>" style="text-decoration: none; color: inherit;">
                                                    <img src="<?= htmlspecialchars($animal->foto_animale) ?>" alt="Foto do animal">
                                                    <h4><?= htmlspecialchars($animal->nome_animale) ?></h4>
                                                </a>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Você ainda não cadastrou nenhum animal de estimação!</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="direita">
                    <h3 class="tetulo2">Animais Favoritos</h3>
                    <div class="favoritos-container">

                        <!-- Mostrar animais favoritos do usuário -->

                        <div class="row">
                            <?php if ($animaisFavoritos) { ?>

                                <div class="col" style="  display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; ">

                                    <?php foreach ($animaisFavoritos as $animal): ?>
                                        <div class="animaFAV">
                                            <h3 style="color: #7a4100">Nome: <?= htmlspecialchars($animal->nome_animal) ?></h3>
                                            <p>Espécie: <?= htmlspecialchars($animal->especie_animal) ?></p>
                                            <p>Cidade: <?= htmlspecialchars($animal->cidade_animal) ?></p>

                                            <div class="botaoLink">
                                                <a class="linkbotao" href="../../../frontend/adocao/detalhesAnimal.php?id=<?= urlencode($animal->id_animal) ?>">
                                                    Saber mais
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                </div>

                            <?php
                            } else {
                                echo "<p>Nenhum animal favoritado!</p>";
                            }
                            ?>

                        </div>
                    </div>
                </div>
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

            function toggleFotoInput() {
                document.getElementById("fotoInput").click();
            }

            function previewImage(event) {
                const input = event.target;
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById("fotoPerfil").src = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                    document.getElementById("salvarBtn").style.display = "block";
                }
            }
        </script>

</body>

</html>