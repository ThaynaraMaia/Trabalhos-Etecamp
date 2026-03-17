<?php
include_once '../backend/classes/classIRepositorioLocais.php';
$repositorio = new RepositorioLocalMYSQL();
$locais = $repositorio->listarTodosLocais();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
    <title>Locais</title>
    <style>
        /* nav */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "AlteHassGrotesk";
            font-weight: 300;
        }

        body {
            background-color: #FFF;
        }

        @font-face {
            font-family: "AlteHassGrotesk";
            src: url(../assets/AlteHaasGroteskRegular.ttf);
            font-weight: 300;
            font-style: normal;
        }

        @font-face {
            font-family: "AlteHassGrotesk";
            src: url(../assets/AlteHaasGroteskBold.ttf);
            font-weight: 400;
            font-style: normal;
        }

        #rownav {
            text-align: center;
            background-color: #A8B16B;
        }

        #imgnav {
            width: 290px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .nav2 {
            margin-top: 15px;
        }

        #linksnav {
            color: #4E6422;
        }

        #linksnav:hover {
            color: #A8B16B;
        }

        /* locais */

        .local-card {
            border: 2px solid #f1ba7b;
            border-radius: 10px;
            padding: 15px;
            background-color: #fff;
            margin-bottom: 15px;
            margin-top: 30px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* .local-img {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            object-fit: cover;
        } */
        .map-container {
            width: 100%;
            height: 600px;
        }

        .rating {
            color: #f1c40f;
        }

        /* local */
        .info {
            height: 400px;
            padding: 30px;
        }

        /* .info2 {
            height: 403px;
        } */

        .tudo {
            display: flex;
            justify-items: center;
            align-items: center;
            justify-content: center;
            margin-top: -10px;
        }

        .local1 {
            width: 550px;
        }

        .local2 {
            width: 730px;
            margin-top: 15px;
        }

        .titulo {
            font-size: 25px;
            color: #7a4100;
        }

        .cor{
            color: #7a4100;
        }

        .desc {
            font-size: 18px;
        }

        .tipo {
            font-size: 20px;
        }

        .hora {
            font-size: 20px;
        }

        .baixo {
            font-size: 18px;
            padding-top: 3px;
        }

        .rodape {
            background-color: white;
            margin-top: 150px;
            margin-bottom: 35px;
        }

        .teste {
            border: 1px solid #4E6422;
        }

        .tudo2{
            margin-top: 10px;
        }

        .tituloPcp{
            font-size: 60px; 
            text-align: center; 
            color: #4E6422; 
            margin-top: 30px;
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
                        session_start();

                        if (isset($_SESSION['user'])) {
                            // Caminho padrão da foto do usuário
                            $foto_padrao = '/imgUsuarios/user_padrao.png'; // Usando o caminho correto para a imagem padrão

                            // Usa a foto do usuário ou a foto padrão se estiver vazia
                            $foto_user = !empty($_SESSION['user']['foto_usuario']) ? $_SESSION['user']['foto_usuario'] : $foto_padrao;

                            // Verifica se a imagem do usuário é a imagem padrão ou personalizada
                            if ($foto_user == $foto_padrao) {
                                // Caminho da imagem padrão com "../"
                                $caminho_imagem = '../' . $foto_user;
                            } else {
                                // Caminho da imagem personalizada (sem "../")
                                $caminho_imagem = $foto_user;
                            }

                            // Exibe o link para o perfil com a foto do usuário
                            echo '<a href="../backend/usuarios/comum/perfilusuario.php">';
                            echo '<img src="' . htmlspecialchars($caminho_imagem) . '?t=' . time() . '" alt="Perfil" style="width:50px; height:50px; border-radius:50%; margin-right:20px;">';
                            echo '</a>';

                            // Botão de logout
                            echo '<a class="btnLogout" href="../backend/usuarios/comum/logout.php" style="border: #4E6422 1px solid; background-color: #737b3f; width: 80px; height: 30px; border-radius: 50px; color: #FFF5EA; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">Sair</a>';
                        } else {
                            // Caso o usuário não esteja logado, exibe o botão de login
                            echo '<a class="btnLogin" href="../backend/login/login_form.php"><button class="btnLogin" style="border: #4E6422 1px solid; background-color: #737b3f; width: 130px; height: 30px; border-radius: 50px; color: #FFF5EA;">Login</button></a>';
                        }
                        ?>

                        <!-- Logo centralizada -->
                        <div style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
                            <img src="../img/logonav2.png" alt="Logo" id="imgnav"
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
                            <a href="../frontend/pgInicial.php" class="nav-link" id="linksnav">Pagina Inicial </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../frontend/parceiros/parceiros.php" class="nav-link" id="linksnav">Parceiros</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../frontend/deficiencias/guiasc.php" class="nav-link" id="linksnav">Guias</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../frontend/adocao/adocao2.php" class="nav-link" id="linksnav">Adoção</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../frontend/locais.php" class="nav-link" id="linksnav">Locais</a>
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

        <!-- conteudo -->
        <h1 class="mb-4 tituloPcp">Locais</h1>
        <div class="row tudo">
            <!-- Lista de locais -->
            <div class="col-md-5 local1">
                <?php foreach ($locais as $local): ?>
                    <div class="local-card d-flex info">

                        <div class="endereco">
                            <h5 class="titulo"><?= htmlspecialchars($local->getNomeLocal()) ?></h5>
                            <!-- <div class="rating">★ ★ ★ ★ ☆</div> Avaliação fixa por enquanto -->
                            <div class="tudo2">
                                <p class="mb-1 desc"><?= htmlspecialchars($local->getDescricaoLocal()) ?></p>
                                <p class="mb-1 tipo"><strong class="cor">Tipo:</strong> <?= htmlspecialchars($local->getTipo()) ?></p>
                                <p class="hora"> <strong class="cor">Horários: </strong> <?= htmlspecialchars($local->gethorarioAbertura()) ?> - <?= htmlspecialchars($local->gethorarioFechamento()) ?></p>

                                <?php
                                $end = $local->getEndereco();
                                $enderecoFormatado = $end->getRua() . ', ' . $end->getNumero() . ' - ' . $end->getBairro() . ', ' . $end->getCidade() . ' - ' . $end->getEstado();
                                ?>
                                <p class="text-muted mb-0 baixo"><?= $enderecoFormatado ?></p>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Mapa em iframe por local -->
            <div class="col-md-7 local2">
                <?php foreach ($locais as $local): ?>
                    <?php
                    $end = $local->getEndereco();
                    $enderecoCompleto = $end->getRua() . ', ' . $end->getNumero() . ' - ' . $end->getBairro() . ', ' . $end->getCidade() . ' - ' . $end->getEstado();
                    ?>
                    <div class="local-card mb-4 info2">
                        <h5><?= htmlspecialchars($local->getNomeLocal()) ?></h5>
                        <p><?= htmlspecialchars($enderecoCompleto) ?></p>
                        <iframe
                            src="https://www.google.com/maps?q=<?= urlencode($enderecoCompleto) ?>&output=embed"
                            width="100%" height="290" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <footer class="rodape">
            <div class="row ">
                <hr class="teste">
                <div class="col logo">
                    <img src="../img/logofooter2.png" alt="" style="width: 150px; height: 150px; margin: 20px;
          margin-left: 80px">
                    <!-- <p style="font-size: 15px; color: #4E6422;">Todos os direitos <br> reservados</p> -->
                </div>
                <div class="col colabore">
                    <h4 style="margin-top: 35px; color: #4E6422;">Colabore</h4>
                    <p style="font-size: 17px; color: #4E6422; margin-top: 15px;">Doe qualquer valor!</p>
                    <p style="font-size: 17px; color: #4E6422;">Cobertores, ração e itens são <br> sempre bem-vindos para as ONG's!
                    </p>
                </div>
                <div class="col redes">
                    <h4 style="margin-top: 35px; color: #4E6422;">Siga-nos</h4>
                    <a href="/"><img src="../img/instagram.png" alt="" style="width: 30px; height: 30px; margin-right: 15px;"></a>
                    <a href="/"><img src="../img/facebook.png" alt="" style="width: 30px; height: 30px; margin-right: 15px;"></a>
                    <a href="/"><img src="../img/tktk.png" alt="" style="width: 30px; height: 30px;"></a>
                </div>
                <div class="col parceiros">
                    <h4 style="margin-top: 35px; color: #4E6422;">Pawceiros</h4>
                    <p style="font-size: 17px; color: #4E6422; margin-top: 15px;">ONG's</p>
                    <p style="font-size: 17px; color: #4E6422;">Veterinários</p>
                </div>
            </div>
        </footer>
    </div>


</body>

</html>