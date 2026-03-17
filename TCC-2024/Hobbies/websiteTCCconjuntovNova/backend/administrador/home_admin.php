<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../frontend/css/home.css">
    <script src="../../backend/scripts/pesquisa.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poetsen+One&display=swap" rel="stylesheet">
    <title>Mercury</title>
</head>
<body>
<!-- Sidebar começa aqui!-->
  <nav id="sidebar" role="navigation">
    <div id="sidebar_content">
        <div id="user">
            <img src="../../frontend/img/perfil.png" id="user_avatar" alt="Avatar">

            <p id="user_infos">
            <span class="item-description perfil">
                <?php
                    echo ($_SESSION['nome']);
                ?>
                </span>
                <span class="item-description">
                <?php
                    echo ($_SESSION['email']);
                ?>
                </span>
            </p>
        </div>

        <ul id="side_items">
            <li class="side-item active">
                <a href="../../backend/administrador/home_admin.php">
                  <i class="fa-solid fa-house"></i>
                    <span class="item-description">
                        Home
                    </span>
                </a>
            </li>

            <li class="side-item">
                <a href="../../backend/administrador/tblusuarios.php">
                    <i class="fa-solid fa-user"></i>
                    <span class="item-description">
                        Usuários
                    </span>
                </a>
            </li>

            <li class="side-item">
                <a href="../../frontend/html/home.php">
                  <i class="fa-solid fa-paintbrush"></i>
                    <span class="item-description">
                        Ir ao site
                    </span>
                </a>
            </li>
        </ul>

        <button aria-label="Abrir menu" id="open_btn">
            <i id="open_btn_icon" class="fa-solid fa-chevron-right"></i>
        </button>
    </div>

    <div id="logout">
        <button aria-label="Cadastrar" id="logout_btn">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span class="item-description">
            <a href="../../backend/login/logout.php">Logout </a>
            </span>
        </button>
    </div>
</nav>
<!-- Sidebar termina aqui! -->

<!-- Começa o conteúdo -->
    <div class="container

        <div role="main" class="section">
            <div class="borda">
                <div class="header">
                    <div class="row">
                        <div class="col-5">
                        </div>
                        <div class="col-4">
                            <img src="../../frontend/img/logo.png" alt="" class="logo">
                        </div>
                        <div class="col-3">
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="titulo" style="margin-left: 110px; font-size: 64px">
                        Bem-vindo.
                        </div>
                    </div>
                    <div class="col">
                        <img src="../../frontend/img/download-removebg-preview.png" alt= " class="inicio-foto" style="height: 300px; margin-top: -70px;">
                    </div>
                    <div class="col">
                        <div class="titulo" style="margin-right: 110px; font-size: 64px">
                            <p>Vamos lá! </p>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="sobre">
                <p>Vamos administrar!</p>
            </div> 

<!-- Footer div section -->
        <div class="footer">
            <!-- <p> 2024 Meu Site. Todos os direitos reservados.</p> -->
        </div>
    </div>
<script src="../../backend/scripts/script.js"></script>
</body>
</html>