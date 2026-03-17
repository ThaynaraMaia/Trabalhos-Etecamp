<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';
session_start();

$respositorioConteudo = new RepositorioConteudosMYSQL(); // Certifique-se que o nome da classe está correto

$registro = $respositorioConteudo->listarTodosBipolaridade();
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
    <link rel="stylesheet" href="../css/bipolaridadeADM.css">
    <link rel="shortcut icon" href="../img/logoazul (1).png" type="image/x-icon">
    <title>Equilibrio - Bipolaridade Adm</title>
</head>

<body>
    <header>
        <nav id="navbar">
            <img src="../img/logoamarela (1).png" alt="logo" style="width: 85px"> 
            <ul id="nav-list">
                <li class="nav-item">
                    <a href="indexAdm.php">Área do Administrador</a>
                </li>
                <li class="nav-item active">
                    <a href="#">Bipolaridade ADM</a>
                </li>
                <li class="nav-item">
                    <a href="autoajudaAdm.php">Autoajuda ADM</a>
                </li>
            </ul>
        </nav>
    </header>

    <main id="content">
    </main>

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

    <?php while ($listagem = $registro->fetch_object()) { ?>

    <form action="bipolaridadeEditado.php?id_conteudos=<?php echo $listagem->id_conteudos; ?>" method="POST">  
        <section id="conteudoBipolaridade">
            <div class="input-group">
                <div class="input-box">
                    <label for="name">Nome</label>
                    <input value="<?php echo $listagem->nome_bipolaridade; ?>" id="name" type="text" name="nome_bipolaridade">
                </div>

                <div class="input-box">
                    <input value="<?php echo $listagem->bipolaridade; ?>" id="bipolaridade" type="text" name="bipolaridade">
                </div>
            </div>

            <div class="btn-salvar" style="justify-content: center;">
                <input value="Salvar" name="editar" type="submit">
            </div>
        </section>
    
        <section id="statusMensagem">
        <?php if ($listagem->status == 1): ?>
                    <a href="alterarStatus.php?id_conteudos=<?php echo $listagem->id_conteudos; ?>&status=1">Desativar</a>
                <?php else: ?>
                    <a href="alterarStatus.php?id_conteudos=<?php echo $listagem->id_conteudos; ?>&status=0">Ativar</a>
                <?php endif; ?>
        </section>
    </form>

    <?php } ?>
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