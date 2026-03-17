<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';
session_start();

// Instanciar o repositório antes de usá-lo
$respositorioConteudo = new RepositorioConteudosMYSQL(); // Certifique-se que o nome da classe está correto

// Agora você pode chamar o método sem erro
$registro = $respositorioConteudo->listarTodosAsanas();
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
    <link rel="stylesheet" href="../css/caminhada.css">
    <link rel="stylesheet" href="../css/autoajudacard.css">
    <link rel="shortcut icon" href="../img/logoazul (1).png" type="image/x-icon">
    <title>Equilibrio - Asanas yoga</title>

</head>

<body>
    <!-- barra de nav -->
    <header>
        <nav id="navbar">
            <img src="../img/logoamarela (1).png" alt="logo" style="width: 85px"> 
            <ul id="nav-list">
                <li class="nav-item">
                    <a href="bipolaridadeAdm.php">Bipolaridade</a>
                </li>
                <li class="nav-item">
                    <a href="autoajudaAdm.php">Autoajuda</a>
                </li>
                <li class="nav-item">
                    <a href="indexAdm.php">Administrador</a>
                </li>
            </ul>
        </nav>
    </header>

    <main id="content">
        <section id="home">
            <div id="ctaBipolaridade">
                <h1 class="title">
                    <span>Asanas</span>
                </h1>
                <?php 
while ($listagem = $registro->fetch_object()) {
?>
    <form action="asanasEditado.php?id_conteudos=<?php echo $listagem->id_conteudos; ?>" method="POST">
        <section id="conteudoCaminhada">
            <h1 class="section-title">
                <div class="input-group">
                    <div class="input-box">
                        <label for="name">Nome</label>
                        <input value="<?php echo $listagem->nome_asanas; ?>" id="name" type="text" name="nome_asanas" required>  
                    </div>
                    <div class="input-box">
                        <label for="asanas">Asanas</label>
                        <input value="<?php echo $listagem->asanas; ?>" id="asanas" type="text" name="asanas" required>  
                    </div>
                </div>
            </h1>
            <div class="btn-editar">
                <input value="salvar" name="editar" type="submit">
            </div>
        </section>

        <!-- Exibir link de ativar/desativar -->
        <section id="statusMensagem">
            <?php if ($listagem->status == 1): ?>
                <a href="alterarStatus.php?id_conteudos=<?php echo $listagem->id_conteudos; ?>&status=1">Desativar</a>
            <?php else: ?>
                <a href="alterarStatus.php?id_conteudos=<?php echo $listagem->id_conteudos; ?>&status=0">Ativar</a>
            <?php endif; ?>
        </section>
    </form>
<?php 
}
?>

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
