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
        <link rel="stylesheet" href="../css/acompanhamento.css">
        <link rel="shortcut icon" href="../img/logoazul (1).png" type="image/x-icon">
        <title>Equilibrio - Autoajuda</title>  
</head>
        
<body>     
    <header>
        <nav id="navbar">
            <img src="../img/logoamarela (1).png" alt="logo" style="width: 85px"> 
            <ul id="nav-list">
                <li class="nav-item">
                    <a href="../php/home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a href="bipolaridade.php">Bipolaridade</a>
                </li>
                <li class="nav-item active">
                    <a href="#">Autoajuda</a>
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

    <section id="acompanhamento">
        <h1 class="section-title">Como você se sente hoje?</h1>
        <p>Demonstre através dos emojis!</p>
            <?php 
            ?>
        <div class="humor-grid">
            <div class="humor-item">
                <div class="btn-default-humor">
                    <a href="descricao_do_dia.php?humor=Muito bem&tipo=1">
                        <img src="../img/feliz.png" style="width: 25px;"><span>Muito bem</span>
                    </a>
                </div>
            </div>

            <div class="humor-item">
                <div class="btn-default-humor">
                    <a href="descricao_do_dia.php?humor=Bem&tipo=2">
                        <img src="../img/sorriso.png" style="width: 25px;"><span>Bem</span>
                    </a>
                </div>
            </div>

            <div class="humor-item">
                <div class="btn-default-humor">
                    <a href="descricao_do_dia.php?humor=Neutro&tipo=3">
                        <img src="../img/infeliz.png" style="width: 25px;"><span>Neutro</span>                    
                    </a>
                </div>
            </div>

            <div class="humor-item">
                <div class="btn-default-humor">
                    <a href="descricao_do_dia.php?humor=Triste&tipo=4">
                        <img src="../img/chateado.png" style="width: 25px;"><span>Triste</span>                    
                    </a>
                </div>
            </div>

            <div class="humor-item">
                <div class="btn-default-humor">
                    <a href="descricao_do_dia.php?humor=Estressado(a)&tipo=5">
                    <img src="../img/nervoso.png" style="width: 25px;"><span>Estresado</span>                    
                </a>
                </div>
            </div>

            <div class="humor-item">
                <div class="btn-default-humor">
                    <a href="descricao_do_dia.php?humor=Ansioso&tipo=6">
                        <img src="../img/com-nojo.png" style="width: 25px;"><span>Ansioso</span>                   
                    </a>
                </div>
            </div>
        </div>
    
    </section>
</body>

<?php
if (isset($_GET['tipo'])) {
    $tipo = filter_var($_GET['tipo'], FILTER_SANITIZE_STRING);

    $encontrou = $respositorioRegistros->obterUmmensagemAleatorio($tipo);

    if ($encontrou >0) {
        ?>
        <h2><?php echo $encontrou['mensagens'];?></h2>
        <?php
    } else {
        echo "<p>Nenhuma mensagem encontrada para o tipo solicitado.</p>";
    }
} else {
    
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

<section id="Registros">

<table class="tabela-acompanhamento">
        <thead>
            <tr>
                <th>Data</th>
                <th>Descricao</th>
                <th>Humor</th>
            </tr>
        </thead>
        <?php 
        while ($listagem = $registro->fetch_object()){
        
        ?>
      
        <tbody>
            <tr>
                <td><?php echo $data_formatada = date('d/m/Y', strtotime($listagem->data));?></td>
                <td><?php echo $listagem->descricao;?></td>
                <td><?php echo $listagem->humor;?></td>
            </tr>
           
        </tbody>
        <?php
        }
        ?>
    </table>
   
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