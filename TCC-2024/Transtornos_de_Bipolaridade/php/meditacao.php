<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';
session_start();

$respositorioConteudo = new RepositorioConteudosMYSQL(); 
$registro = $respositorioConteudo->listarTodosMeditacaocomum();
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
    <link rel="stylesheet" href="../css/meditacao.css">
    <link rel="stylesheet" href="../css/autoajudacard.css">
    <link rel="shortcut icon" href="../img/logoazul (1).png" type="image/x-icon">
    <title>Equilibrio - Yoga Meditação</title>

</head>

<body>
    <!-- barra de nav -->
    <header>
        <nav id="navbar">
            <img src="../img/logoamarela (1).png" alt="logo" style="width: 85px"> 
            <ul id="nav-list">
                <li class="nav-item">
                    <a href="home.php">Home</a>
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

    <section id="conteudoMeditacaoVideo">
        <div class="videoMeditacao">
            <video width="700" height="500" controls>
                <source src="../img/Meditação para Ansiedade e Angústia (ACALMAR a MENTE e o CORPO).mp4" type="video/mp4">
            <source src="movie.ogg" type="video/ogg">
            </video>
        </div>

        <div class="textoMeditacao">
            <h1>Meditação para Ansiedade e Angústia (ACALMAR a MENTE e o CORPO)</h1>
            <p>Nessa meditação guiada rápida, em poucos minutos, através de técnicas de respiração,
             visualização e relaxamento vamos trabalhar a redução da ansiedade, stress(estresse), turbilhão 
             de pensamentos e emoções, tensões e dores no corpo causadas por questões mentais e emocionais. 
            Para melhores resultados você pode e deve repetir essa meditação mais vezes na sua semana. 
            Ideal que seja entre 3 a 7 vezes na semana. Podendo intercalar com outras meditações do canal ou da
             plataforma. Conheça minha plataforma de cursos! Lá você encontrar um curso super completo de meditação além de muitas aulas e cursos de práticas de yoga.</p>
            
            <div class="icones-video">
                <div class="btn-youtube">
                    <a href="https://youtu.be/r592a5rZb_Y?si=MDFzUK8CMKukua_a" target="_blank">Assista no youtube</a>
                </div>
    
                <div class="btn-youtube">
                    <a href="../php/yoga.php">Voltar</a>
                </div>
            </div>
        </div>
    </section>

</body>

        <?php 
        while ($listagem = $registro->fetch_object()){
            ?>
                <section id="conteudoMeditacao">
                <h1 class="title-Meditacao">
                <?php  echo $listagem->nome_meditacao;?>
                </h1>
                <p>
                <?php  echo $listagem->meditacao;?>
                </p>
                </section>
                <?php
                }
                ?>

    <section>
        <div class="main">
            <div class="autoajudacard card8">
                <img src="../img/meditar.png">
                <h1>Yoga</h1>
                <div class="btn-card">
                    <a href="../php/yoga.php">Ir</a>
                </div> 
            </div>

            <div class="autoajudacard card4">
                <img src="../img/sono.png">
                <h1>Sono regular</h1>
                <div class="btn-card">
                    <a href="../php/sono.php">Ir</a>
                </div> 
            </div>
            
            <div class="autoajudacard card3">
                <img src="../img/aerobicodesenho.png" alt="">
                <h1>Exercicios</h1>
                <div class="btn-card">
                    <a href="../php/exercicios.php">Ir</a>
                </div>
            </div>  
        </div>
    </section>

<!-- rodapé -->
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