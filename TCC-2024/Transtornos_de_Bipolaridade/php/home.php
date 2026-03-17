<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';

session_start();

$registro = $respositorioConteudos->listarTodosHumorcomum();

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.flaticon.com/br/">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/headerlog.css">
    <link rel="shortcut icon" href="../img/logoazul (1).png" type="image/x-icon">
    <title>Equilibrio - Home</title>
</head>

<body>
    <header>
        <nav id="navbar">
            <img src="../img/logoamarela (1).png" alt="logo" style="width: 85px"> 
            <ul id="nav-list">
                <li class="nav-item active">
                    <a href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a href="bipolaridade.php">Bipolaridade</a>
                </li>
                <li class="nav-item">
                    <a href="autoajuda.php">Autoajuda</a>
                </li>
            </ul>

            <div class="icones-usuario">
                <button class="phone-button">
                    <a href="usuario.php">
                        <img src="../img/usuario-de-perfil.png" style="width: 30px;">
                    </a>
                </button>

                <button class="phone-button">
                    <a href="../usuario/logout.php">
                        <img src="../img/sair.png" style="width: 30px;">
                    </a>
                </button>
            </div>
        </nav>
    </header>

     <main id="content">
        <section id="home">
            <div id="cta">
                <h1 class="title">
                    Seja bem-vindo(a) ao
                    <span>Equilibrio</span>
                </h1>

                <p class="description">
                    Aqui, você encontrará informações e recursos sobre saúde mental para promover melhor compreensão e apoio em relação ao trantorno de Bipolaridade.
                    Nossa missão é fornecer conteúdo confiáveis com orientações e informações veridicas.
                    Explore, e descubra como ter uma vida dedicada ao bem-estar mental. 
                    <a href="bipolaridade.php">Saiba mais...</a>
                </p>
            </div>

            <div id="banner">
                <img src="../img/homee.png" alt="bannerprincipal" style="width: 550px;">
            </div>

            <div class="shape"></div>
            
        </section>

        <section id="diario">
            <?php 
                while ($listagem = $registro->fetch_object()){
            ?>

            <section id="registrar-humor">
                <h1 class="section-title">
                    <?php  echo $listagem->nome_humor;?>
                </h1>

                <div class="icone-registrar">
                    <img src="../img/bom-humor.png" alt="" style="width: 200px;">
                </div>

                <p>
                    <?php  echo $listagem->humor;?>
                </p>

                <div class="btn-default">
                    <a href="autoajuda.php">Registre</a>
                </div>
            </section>

            <?php
                }
            ?>

        </section>

        <section id="menu">
            <div class="main">
                <div class="card card1">
                    <img src="../img/meditar.png">
                        <h1>Yoga</h1>
                        <div class="btn-card">
                            <a href="yoga.php">Ir</a>
                        </div>
                </div>

                <div class="card card2">
                    <img src="../img/sono.png">
                        <h1>Sono</h1>
                        <div class="btn-card">
                            <a href="sono.php">Ir</a>
                        </div>           
                </div>

                <div class="card card3">
                    <img src="../img/aerobicodesenho.png" alt="">
                        <h1>Exercicios</h1>
                    <div class="btn-card">
                        <a href="exercicios.php">Ir</a>
                    </div>           
                </div>
            </div>
        </section>

        <footer>
            <img src="../img/wave (1).svg" alt="">

            <div id="footer-item">
                <img src="../img/logoazul (1).png" alt="logo" style="width: 70px">
                <span id="copyright">
                    &copy 2024 Equilibrio
                </span>

                <a href="tel:+555" id="phone-button">
                    <button class="phone-button">
                        <i class="fi fi-sr-phone-call"></i>
                        (180)
                    </button>
                        Central de Atendimento a Mulher
                </a>

                <a href="tel:+555" id="phone-button">
                    <button class="phone-button">
                        <i class="fi fi-sr-phone-call"></i>
                        (192)
                    </button>
                        SAMU
                </a>

                <a href="tel:+555" id="phone-button">
                    <button class="phone-button">
                        <i class="fi fi-sr-phone-call"></i>
                        (193)
                    </button>
                        Corpo de Bombeiros
                </a>
            </div>
        </footer>

     </main>

     <link rel="stylesheet" href="../bootstrap/js/bootstrap.min.js">
    
</body>
</html>