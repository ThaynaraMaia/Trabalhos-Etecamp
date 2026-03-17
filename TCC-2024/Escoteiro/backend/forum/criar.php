    <?php
    session_start();
    if(!isset($_SESSION['on'])) {
        $_SESSION['msg'] = 'Não está logado';
        echo $_SESSION['msg'];
        header('Location: main.php');
    }
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
    <link rel="stylesheet" href="../../frontend/public/css/teste.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        *{text-align:center;}
    </style>
    <link rel="icon" href="../../frontend/public/imagens/recursos/logo.png">
    <title>Criar Tópico</title>
</head>
<body>
<nav class="navbar navbar-expand-lg py-3 custom-navbar">
    <div class="container">

      <a class="navbar-brand" href="../../frontend/principal/index.php">
        <img src="../../frontend/public/imagens/recursos/logo.png " height="80rem" width="80rem" alt="Logo" height="40">
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
            if($_SESSION['tipo']==3){
              echo '<li class="nav-item">
                <a class="nav-link" href="../../backend/adm/index_adm.php">ADM</a>
              </li>';
            }
            echo '<li class="nav-item">
            <a class="nav-link" href="../../frontend/principal/user.php?id='.$_SESSION["userid"].'">'.$_SESSION["nome"].'</a>
            <li class="nav-item">
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
    <div class="container">
        <form action="novo_topico.php" method="POST" enctype="multipart/form-data">
        <fieldset>
            <h1> Criar tópico</h1>
            <br>
            <div id="part-1">
            <label for="first-name">Insira o nome do tópico</label><br>
            <input class="input-custom" type="text" name="nome" id="nome" required>
            <br><br>
            <label for="message">Insira o conteúdo do tópico</label><br>
            <textarea class="input-custom" rows="5" name="body" cols="50" id="body" tabindex="-1"></textarea>
            <br><br>
            <?php
            if($_SESSION['tipo']>=1){

             echo '<label for="message">Restrição de Visibilidade</label><br>
              <select id="menu1" name="restrito" required="true" class="input-custom">
                      <option value="0"><strong>Não</strong></option>
                      <option value="1"><strong>Sim</strong></option>
                      </select>
              <br><br>';
            }
            else{
              echo '<input type="hidden" name="restrito" value="0">';
            }
            ?>
            <label>Insira os anexos do Tópico:</label>
            <input type="file" name="anexo">
          <br/><br/>
          <input type="submit" class="btn-custom" id="submitButton" name="Enviar" value="Criar Tópico">
        </div>


        </fieldset>
      </form></div>

        <footer class="footer text-center text-lg-start d-flex fixed-bottom w-100 justify-content-between align-items-center">
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