<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';
session_start();

$respositorioConteudo = new RepositorioConteudosMYSQL(); 
$registro = $respositorioConteudo->listarTodosBipolaridadecomum();
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
    <link rel="stylesheet" href="../css/bipolaridade.css">
    <link rel="shortcut icon" href="../img/logoazul (1).png" type="image/x-icon">
    <title>Equilibrio - Bipolaridade</title>
</head>

<body>
    <header>
        <nav id="navbar">
            <img src="../img/logoamarela (1).png" alt="logo" style="width: 85px"> 
            <ul id="nav-list">
                <li class="nav-item">
                    <a href="home.php">Home</a>
                </li>
                <li class="nav-item active">
                    <a href="#">Bipolaridade</a>
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
            <div id="ctaBipolaridade">
                <h1 class="title">
                    Vamos falar um pouco mais sobre a
                    <span>Bipolaridade!</span>
                </h1>

                <p class="description">
                Aqui no Equilibrio, nossa missão é oferecer recursos, informações e apoio para quem vive 
                com o transtorno bipolar, promovendo o bem-estar e o equilíbrio emocional por meio de 
                práticas de autocuidado. Entendemos que conviver com a bipolaridade pode trazer desafios únicos, 
                por isso criamos um espaço dedicado a ajudar você a cuidar da sua saúde mental, emocional e física. 
                Nesta pagina você pode encontrar um pouco mais sobre a bipolaridade:
                </p>
            </div>

            <div id="banner">
                <img src="../img/cuidadomental.png" alt="banner" style="width: 500px">
            </div>

            <div class="shapeBipolaridade"></div>
            
        </section>


<?php 
        while ($listagem = $registro->fetch_object()){
        ?>
            <section id="conteudoBipolaridade">
                <h1 class="section-title">
                <?php  echo $listagem->nome_bipolaridade;?>
                </h1>
                <p>
                <?php  echo $listagem->bipolaridade;?>
                </p>
                </section>
                <?php
                }
                ?>

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