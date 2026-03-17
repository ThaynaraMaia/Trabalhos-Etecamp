<?php
session_start();
$logged = false;
if (isset($_SESSION['on'])) {
    $logged = true;
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/teste.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="icon" href="../public/imagens/recursos/logo.png">
    <title>Home</title>
</head>
<body>
<nav class="navbar navbar-expand-lg py-3 fixed-top custom-navbar">
    <div class="container">

      <a class="navbar-brand" href="../../frontend/principal/index.php">
        <img src="../public/imagens/recursos/logo.png " height="80rem" width="80rem" alt="Logo" height="40">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <img src="../../frontend/public/imagens/recursos/menu.png" width="20px" alt="">
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
          <a class="nav-link active" aria-current="page" target="blank" href="../../frontend/public/docs/POR_2013_19_V2.pdf">Regulamento Interno</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../backend/galeria/index.php">Galeria</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../backend/Evento/index.php">Programação Semestral</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../backend/mailer/fale.php">Fale Conosco</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../backend/forum/main.php">Fórum</a>
          </li>
          <?php 
          if($logged){
            if($_SESSION['tipo']>=1){
              echo '<li class="nav-item">
                <a class="nav-link" href="../../backend/loja/loja.php">Loja</a>
              </li>';
            }
            if($_SESSION['tipo']==2){
              echo '<li class="nav-item">
                <a class="nav-link" href="../../backend/adm/index_mod.php">MOD</a>
              </li>';
            
            }
            if($_SESSION['tipo']==3){
              echo '<li class="nav-item">
                <a class="nav-link" href="../../backend/adm/index_adm.php">ADM</a>
              </li>';
            
            }
            echo '<li class="nav-item">
            <a class="nav-link" href="../../frontend/principal/user.php?id='.$_SESSION["userid"].'">'.$_SESSION["nome"].'</a>
            </li>';
            echo '<li class="nav-item">
            <a class="nav-link" href="../../backend/notificacoes/index.php"><img src="../../frontend/public/imagens/recursos/sino.png" width="20px"></a>
            </li>';
            }
          else{
            echo '<li class="nav-item">
                <a class="nav-link" href="../../backend/login/login.php"><strong>Faça Login</strong></a>
              </li>';
          }
            ?>
        </ul>
      </div>
    </div>
  </nav>

<!-- carrossel -->
<div class="content">
    
<section class="carousel-section">
    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
    
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="../public/imagens/carrossel/slide1.png" class="d-block w-100" alt="Slide 1">
          </div>
          <div class="carousel-item">
            <img src="../public/imagens/carrossel/slide2.png" class="d-block w-100" alt="Slide 2">
          </div>
          <div class="carousel-item">
            <img src="../public/imagens/carrossel/slide3.png" class="d-block w-100" alt="Slide 3">
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Próximo</span>
        </button>
      </div>

  </section>

<!-- section1 -->
<br>
<section class="jumbotron d-flex justify-content-center align-items-center text-center text-white">
    <div class="container">
      <h1 class="display-4">O que é o movimento escoteiro?</h1>
      <p class="lead">O Movimento Escoteiro foi criado, por essência, para ser um movimento voltado para o jovem, e também feito por eles, com o auxílio de adultos voluntários. E se chama movimento por estar sempre em constante transformação, acompanhando as mudanças da geração, mas sem perder seu propósito educacional</p>
      <a class="btn btn-primary btn-lg" href="https://www.escoteiros.org.br/o-movimento-escoteiro/" target="blank" role="button">Leia mais</a>
    </div>
  </section>
<br>

<br>


<!-- ramos -->

<div class="card-group">
  <div class="card">
    <img src="../public/imagens/recursos/lobinho.png" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title">Ramo Lobinho</h5>
      <p class="card-text">Entre os 6,5 e os 10 anos, somos lobinhos. Aprendemos muito sobre a vida em meio à natureza, a viver em grupo e desenvolvemos nossa socialização.
      “O Livro da Jângal”, que retrata as aventuras de Mowgli, o menino lobo, é o marco simbólico que inspira a organização do Ramo Lobinho.</p>
    </div>
  </div>
  <div class="card">
    <img src="../public/imagens/recursos/escoteiro.png" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title">Ramo Escoteiro</h5>
      <p class="card-text">Entre os 11 e 14 anos fazemos parte do Ramo Escoteiro – somos patrulhas de 5 a 8 jovens, de meninos e 
                meninas, que juntas formam uma tropa. Aqui, além de trabalhar em equipe e entender a importância de 
                respeitar a natureza, aprendemos diversas coisas que nos deixam mais confiantes e decididos.</p>
    </div>
  </div>
  <div class="card">
    <img src="../public/imagens/recursos/senior.png" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title">Ramo Sênior</h5>
      <p class="card-text">O Ramo Sênior é formado por jovens com idade entre 15 e 17 anos. Nós já nos conhecemos melhor, 
                aceitamos nossas características e as diferenças de um jeito mais simples, e estamos entendendo 
                melhor nossa própria personalidade. Aqui a exploração se converte em desafios pessoais e somos 
                estimulados a superar estes desafios.</p>
    </div>
  </div>
  <div class="card">
    <img src="../public/imagens/recursos/pioneiro.png" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title">Ramo Pioneiro</h5>
      <p class="card-text">A partir dos 18 anos, e até os 21 incompletos, integramos o Ramo Pioneiro. Nossa equipe forma o 
            clã, e é onde nos apoiamos e descobrimos interesses em comum. Levamos a sério nosso lema “Servir”, 
            já que vivemos uma aventura que não é mais simbólica ou imaginária, pois experimentamos o papel 
            real do adulto por meio do serviço e das atividades de desenvolvimento comunitário.</p><br>
    </div>
  </div>
</div><br>

<!-- mapa -->
<div class="mapBox">
       <h1>Onde nos Encontrar</h1><br>
    <iframe title="Localização" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14670.92310102258!2d-46.7553948!3d-23.1800242!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94cedf370c8c30e5%3A0xfacaf4f49cedae90!2sGrupo%20Escoteiro%20Jo%C3%A3o%20Paulo%20II!5e0!3m2!1spt-BR!2sbr!4v1718627802292!5m2!1spt-BR!2sbr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    <br></div>
    </div>
    </div>

    <footer class="footer text-center text-lg-start d-flex justify-content-between align-items-center">
    <div class="container p-3 d-flex justify-content-between w-100">
      <span class="text-muted">© 2024 Grupo Escoteiro João Paulo II. Todos os direitos reservados.</span>
      <div class="social-icons">
        <a href="https://www.facebook.com/GrupoescoteirojoaopauloII?mibextid=ZbWKwL" target="_blank" class="text-muted me-3">
          <i class="bi bi-facebook"></i>
        </a>
        <a href="https://youtube.com/@gejoaopauloii?si=8nJBM0zWxWBofH_V" target="_blank" class="text-muted me-3">
          <i class="bi bi-youtube"></i>
        </a>
        <a href="https://www.instagram.com/ge_joaopaulo2?igsh=MWFiOTA2OTFxYW11" target="_blank" class="text-muted me-3">
          <i class="bi bi-instagram"></i>
        </a>
        <a href="https://www.tiktok.com/@ge._joaopauloii?_t=8nG7cVdsBGQ&_r=1" target="_blank" class="text-muted">
          <i class="bi bi-tiktok"></i>
        </a>
      </div>
    </div>
  </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>