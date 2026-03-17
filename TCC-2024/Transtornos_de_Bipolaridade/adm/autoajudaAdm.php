<?php  

include_once '../conn/classes/class_IRepositorioRegistros.php';

session_start();
$id_usuario=$_SESSION['id'];
$registro = $respositorioRegistros->listarTodosRegistros($id_usuario);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.flaticon.com/br/">
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/2.4.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
        <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/headerlog.css">
        <link rel="stylesheet" href="../css/autoajudacard.css">
        <link rel="shortcut icon" href="../img/logoazul (1).png" type="image/x-icon">
        <title>Equilibrio - Autoajuda Adm</title>
</head>

<body>          
    <header>
        <nav id="navbar">
            <img src="../img/logoamarela (1).png" alt="logo" style="width: 85px"> 
            <ul id="nav-list">
                <li class="nav-item">
                    <a href="indexAdm.php">Administrador</a>
                </li>
                <li class="nav-item">
                    <a href="bipolaridadeAdm.php">Bipolaridade</a>
                </li>
                <li class="nav-item active">
                    <a href="#">Autoajuda</a>
                </li>
                <li class="nav-item">
                    <a href="../usuario/logout.php">Sair</a>
                </li>
            </ul>
        </nav>
    </header>

    
<section>
        <div class="main">
            <div class="autoajudacard card3">
                <img src="../img/meditar.png">
                <h1>Yoga</h1>
                <div class="btn-card">
                    <a href="../adm/yogaAdm.php">Ir</a>
                </div>   
            </div>

            <div class="autoajudacard card4">
                <img src="../img/sono.png">
                <h1>Sono regular</h1>
                <div class="btn-card">
                    <a href="../adm/sonoAdm.php">Ir</a>
                </div> 
            </div>
            
            <div class="autoajudacard card8">
                <img src="../img/aerobicodesenho.png">
                <h1>Exercicios</h1>
                <div class="btn-card">
                    <a href="../adm/exerciciosAdm.php">Ir</a>
                </div> 
            </div>
        </div>
    </section>

   
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

    <link rel="stylesheet" href="../bootstrap/js/bootstrap.min.js">

</body>