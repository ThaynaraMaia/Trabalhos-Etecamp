<!DOCTYPE html>
<html lang="pt-br"><?php

                    session_start();
                    if (!$_SESSION['tipo']  && !$_SESSION['logado']) {
                      header('Location:../../../../../frontend/home.php');
                    }
                    include_once '../../../../classes/class_IRepositorioUsuarios.php';
                    $id = $_SESSION['id_usuario'];
                    // Busca os dados do usuário
                    $dados = $respositorioUsuario->buscarUsuario($id);
                    // Foto padrão se não tiver
                    $foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';

                    ?>



<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Níveis Memória - Projeto Martopia</title>

  <link rel="stylesheet" href="../../../../../frontend/public/css/niveisM.css">
  <link rel="stylesheet" href="../../../../../frontend/public/css/baseGeral.css">
  <link rel="stylesheet" href="../../../../../frontend/public/css/logo.css">
  <link rel="stylesheet" href="../../../../../frontend/public/css/footer.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

</head>

<body>

  <style>
    header {
      box-shadow: 0 40px 60px rgba(0, 0, 0, 0.2);
    }

    body {
      background: #045A94;
      background: radial-gradient(circle, rgba(4, 90, 148, 1) 0%, rgba(129, 192, 233, 1) 50%, #9fcaec);
    }

    .navbar a {
      font-size: 1.5rem;
    }

    .perfil {
      width: 80px;
      height: 80px;
      margin-left: -3rem;
      border: 1.5px solid #e18451;
      /* color: #81c0e9; */
    }

    .header {
      left: 0;
      width: 100%;
      padding: 1.6rem 1rem;
    }

    nav a.active {
      color: #c6e1fe;
      font-weight: bold;
      text-shadow: 0px 3px 6px #045a94;
    }

    .btn {
      font-size: 1.6rem;
    }
  </style>

  <header class="header">

    <div class="logo-marca" style="margin-left: -3rem;">
      <a href="./home.php" class="logo"><img src=".../../../../../../../frontend/public/img/Logo.png" alt="Logo-Projeto Martopia"></a>
      <p style="margin-left: -3rem;">Projeto <br> Martopia</p>
    </div>


    <input type="checkbox" id="check">
    <label for="check" class="icone">
      <i class="bi bi-list" id="menu-icone"></i>
      <i class="bi bi-x" id="sair-icone"></i>
    </label>


    <nav class="navbar">
      <a href="../../homeUsuario.php" style="--i:1;">Home</a>
      <a href="../../instamar/instamar.php" style="--i:1;">InstaMar</a>
      <a href="../jogos.php" style="--i:2;" class="active">Jogos</a>
      <a href="../../conteudos/conteudo.php" style="--i:3;">Conteúdos Educativos</a>

      <a href="../../../../trocar/trocarperfil.php"><img src="../../../../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil" class="perfil" style="--i:4;"></a>

    </nav>

  </header>



  <div class="container" style="margin-top: 5rem;">

    <h1>NÍVEIS</h1>

    <a href="./facil.php">
      <div class="btn">FÁCIL</div>
    </a>
    <a href="./medio.php">
      <div class="btn">MÉDIO</div>
    </a>
    <a href="./dificil.php">
      <div class="btn">DIFÍCIL</div>
    </a>

    <br>

    <a href="../jogos.php">
      <div class="btn" style="background: #045A94; color:#c6e1fe;">VOLTAR AOS JOGOS</div>
    </a>

  </div>


  <footer style="background: #045a94; margin-top: 10rem; text-shadow: 2px 2px 4px rgba(0, 0, 0, .3);">
    <div class="contatos">
      <h3>Contatos</h3>
      <p>Email: contato@martopia.com.br</p>
      <p>Telefone: +55 11 99999-9999</p>
      <p>Endereço: Rua do Oceano, 123, São Paulo, SP</p>
    </div>

    <div class="redes">
      <h3>Redes Sociais</h3>
      <div>
        <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
        <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
        <a href="#" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
      </div>
    </div>

    <div class="mapa">
      <h3>Localização</h3>
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3666.2168153595317!2d-46.766872!3d-23.235196!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94cede166027baab%3A0x566fc4df5821546c!2sEscola%20T%C3%A9cnica%20Estadual%20de%20Campo%20Limpo%20Paulista!5e0!3m2!1spt-BR!2sbr!4v1756695006929!5m2!1spt-BR!2sbr        allowfullscreen="" loading=" lazy"
        referrerpolicy="no-referrer-when-downgrade"
        title="Mapa do local">
      </iframe>
    </div>

    <div class="copyright">
      &copy; 2025 Projeto Martopia. Todos os direitos reservados.
    </div>
  </footer>

</body>

</html>