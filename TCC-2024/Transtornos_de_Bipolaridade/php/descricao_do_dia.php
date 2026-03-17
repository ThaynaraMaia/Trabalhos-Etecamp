<?php
include_once '../conn/classes/class_IRepositorioRegistros.php';

session_start();
$id_usuario=$_SESSION['id'];
$registro = $respositorioRegistros->listarTodosRegistros($id_usuario);

$tipo=$_GET['tipo'];

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
    <link rel="stylesheet" href="../css/descricaodia.css">
    <link rel="shortcut icon" href="../img/logoazul (1).png" type="image/x-icon">    
    <title>Equilibrio - Autoajuda</title>

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

    <div class="container">
        <div class="form-img-descricao">
            <img src="../img/descricaodia.png" alt="" style="width: 500px;">
        </div>

        <div class="form">
            <form action="../usuario/novoRegistro.php?humor=<?php echo $_GET['humor']?>&tipo=<?php echo $_GET['tipo'];?>" method="POST">
                <div class="form-header">
                    <div class="title-descricao">
                        <h1>Descreva um pouco do seu dia</h1>
                    </div>
                </div>

        <div class="input-group">
                <div class="input-box">
                    <label for="descricao">Descrição: </label>
                        <textarea id="descricao" type="text" name="descricao" placeholder="Deixe aqui uma breve descrição do seu dia"></textarea>
                    </div>
                </div>

                <div class="btn-salvar">
                    <input type="submit" name="Salvar" value="Salvar">
                </div>
            </form>
        </div>
    </div>


</body>

<section id="Registros">


</section>

    
  </body>

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