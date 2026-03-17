<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/explorar.css">
    <link rel="stylesheet" href="../css/home.css">
    <script src="../../backend/scripts/scriptexplorar.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poetsen+One&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="imagex/png" href="../img/mercury.simples.ico">
    <title>Mercury</title>
</head>
<body>
<!-- Sidebar começa aqui -->
  <nav id="sidebar">
    <div id="sidebar_content">
        <div id="user">
            <img src="../img/perfil.png" id="user_avatar" alt="Avatar">

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
            <li class="side-item">
                <a href="../html/home.php">
                  <i class="fa-solid fa-house"></i>
                    <span class="item-description">
                        Home
                    </span>
                </a>
            </li>

            <li class="side-item">
                <a href="../html/perfil.php">
                    <i class="fa-solid fa-user"></i>
                    <span class="item-description">
                        Perfil
                    </span>
                </a>
            </li>

            <li class="side-item">
                <a href="../../frontend/html/meus_hobbies.php">
                  <i class="fa-solid fa-paintbrush"></i>
                    <span class="item-description">
                        Meus Hobbies
                    </span>
                </a>
            </li>

            <li class="side-item active">
                <a href="../html/explorar.php">
                  <i class="fa-solid fa-magnifying-glass"></i>
                    <span class="item-description">
                        Explorar
                    </span>
                </a>
            </li>
        </ul>

        <button id="open_btn">
            <i id="open_btn_icon" class="fa-solid fa-chevron-right"></i>
        </button>
    </div>

        <div id="logout">
            <button aria-label="Cadastrar" id="logout_btn" onclick="window.location.href='../../backend/login/logout.php'">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="item-description">Logout</span>
            </button>
        </div>
    </div>
    </nav>
    <!-- Sidebar termina aqui -->

    <div class="container">
        <div class="header">

            <div class="row">
                <div class="col-5">
                    
                </div>
                <div class="col-4">
                    <img src="../img/logo.png" alt="" class="logo">
                </div>
                <div class="col-3">
                    
                </div>
            </div>
        </div>

        <div class="section">
            <div class="post-container">
                <div class="post-header">
                    <h2 class="post-title">Título do Hobby</h2>
                </div>
                <div class="post-content">
                    <p>Descrição do hobby e por que você gosta dele. Pode incluir detalhes sobre como você começou, o que você faz, e qualquer outra informação interessante.</p>
                </div>
                <div class="post-footer">
                    <div>
                        <button class="like-btn">Curtir</button><span class="like-count">0 curtidas</span>
                    </div>
                </div>
                <div class="comments-section" style="display:none;">
                    <div class="comments-list">
                        <!-- Comentários serão adicionados aqui -->
                    </div>
                    <input type="text" class="comment-input" placeholder="Escreva um comentário...">
                    <button class="submit-comment">Enviar</button>
                </div>
            </div>
        </div>
        <div class="adicionar-post">
            <button class="btn-adicionar">+</button>
        </div>
    </div>
<script src="../../backend/scripts/script.js"></script>
</body>
</html>