<?php
include_once '../../backend/classes/class_IRepositorioAnimaisAdocao.php';

// Criar função para exibir os animais em cards (igual ao que definimos)
function exibirAnimaisEmCards($registros)
{
  echo '<div class="container-fluid">';
  echo '<div class="tudo">';
  echo '<div class="row justify-content-center">';

  while ($animal = $registros->fetch_object()) {
    $caracteristicas = explode(',', $animal->caracteristicas_animal);
    echo '<div class="col-md-4 pet">';
    echo '  <div class="card h-100 nosso">';
    echo '    <div class="imgpts text-center">';
    echo '      <a href="detalhesAnimal.php?id=' . urlencode($animal->id_animal) . '">';
    echo '        <img src="../..' . htmlspecialchars($animal->foto_animal) . '" alt="Foto do ' . htmlspecialchars($animal->nome_animal) . '" class="imgPET">';
    echo '      </a>';
    echo '    </div>';
    echo '    <div class="card-body">';
    echo '      <div class="nomePets">';
    echo '        <h5><a href="#" class="nomePet"><strong>' . htmlspecialchars($animal->nome_animal) . '</strong></a></h5>';
    echo '      </div>';
    echo '      <div class="caracter d-flex flex-wrap gap-1">';
    foreach ($caracteristicas as $carac) {
      echo '<div class="carac" style="background-color: #9b5300; color: #ffffff;">' . htmlspecialchars(trim($carac)) . '</div>';
    }
    echo '        <div class="carac" style="background-color: #9b5300; color: #ffffff;">' . htmlspecialchars($animal->genero_animal) . '</div>';
    echo '        <div class="carac" style="background-color: #9b5300; color: #ffffff;">' . htmlspecialchars($animal->especie_animal) . '</div>';
    echo '        <div class="carac" style="background-color: #9b5300; color: #ffffff;">' . (int)$animal->idade_animal . ' anos</div>';
    echo '      </div>';
    echo '      <p class="local"><strong>' . htmlspecialchars($animal->cidade_animal) . '</strong></p>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
  }
  echo '</div>';
  echo '</div>';
  echo '</div>';
}

// Instanciar repositório e buscar animais
$repositorio = new RepositorioAnimaisAdocaoMYSQL();
$registros = $repositorio->listarTodosAnimaisAd();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Animais para Adoção</title>
  <!-- <link rel="stylesheet" href="../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../../css/adocao.css" /> -->
  <link rel="stylesheet" href="../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="../../css/adocao.css">
  <link rel="shortcut icon" href="../../img/favicon.ico" type="image/x-icon">

  <style>
    .imgPET {
      width: 100%;
      max-width: 340px;
      height: 260px;
      border-radius: 10px;
      display: block;
    }

    .nomePet {
      font-size: 45px;
      margin-bottom: 3px;
      margin-top: -9px;
    }

    .tudo {
      padding: 30px 15px;
      margin-bottom: 10px;
    }

    .nosso {
      border-radius: 10px;
      border: none;
      margin-top: 10px;
      display: flex;
      justify-content: center;
      align-items: center;
      /* margin-left: -15px; */
    }

    .carac {
      font-size: 0.75rem;
      padding: 5px 10px;
      border-radius: 5px;
      text-align: center;
      white-space: nowrap;
      cursor: default;
      margin-bottom: 5px;
    }

    .carac:hover {
      background-color: #C06500;
      color: #F1DEC3;
      transition: 0.5s;
    }

    .pet {
      text-align: center;
      margin-bottom: 30px;

    }

    .tituloAdc {
      margin-top: 30px;
      font-size: 45px;
    }

    .local {
      color: #C06500;
      margin-top: 10px;
      font-size: 17px;
    }

    .rodape {
      background-color: white;
      margin-top: 150px;
      margin-bottom: 35px;
    }

    .linha {
      border: 1px solid #4E6422;
    }
  </style>
</head>


<body>
  <div class="container-fluid">

    <!-- nav -->

    <div class="row" id="rownav" style="background-color: #A8B16B;">
      <nav class="navbar position-relative" style="height: 135px; position: relative;">
        <div class="container position-relative" style="position: relative;">

          <!-- Botão à direita -->
          <div class="ms-auto">
            <a href="/imgUsuarios/user_padrao.png"></a>
            <?php
            session_start();

            if (isset($_SESSION['user'])) {
              // Caminho padrão da foto do usuário
              $foto_padrao = '/imgUsuarios/user_padrao.png'; // Usando o caminho correto para a imagem padrão

              // Usa a foto do usuário ou a foto padrão se estiver vazia
              $foto_user = !empty($_SESSION['user']['foto_usuario']) ? $_SESSION['user']['foto_usuario'] : $foto_padrao;

              // Verifica se a imagem do usuário é a imagem padrão ou personalizada
              if ($foto_user == $foto_padrao) {
                // Caminho da imagem padrão com "../"
                $caminho_imagem = '../../' . $foto_user;
              } else {
                // Caminho da imagem personalizada (sem "../")
                $caminho_imagem = $foto_user;
              }

              // Exibe o link para o perfil com a foto do usuário
              echo '<a href="../../backend/usuarios/comum/perfilusuario.php">';
              echo '<img src="' . htmlspecialchars($caminho_imagem) . '?t=' . time() . '" alt="Perfil" style="width:50px; height:50px; border-radius:50%; margin-right:20px;">';
              echo '</a>';

              // Botão de logout
              echo '<a class="btnLogout" href="../../backend/usuarios/comum/logout.php" style="border: #4E6422 1px solid; background-color: #737b3f; width: 80px; height: 30px; border-radius: 50px; color: #FFF5EA; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">Sair</a>';
            } else {
              // Caso o usuário não esteja logado, exibe o botão de login
              echo '<a class="btnLogin" href="../../backend/login/login_form.php"><button class="btnLogin" style="border: #4E6422 1px solid; background-color: #737b3f; width: 130px; height: 30px; border-radius: 50px; color: #FFF5EA;">Login</button></a>';
            }
            ?>

            <!-- Logo centralizada -->
            <div style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
              <img src="../../img/logonav2.png" alt="Logo" id="imgnav"
                style="width: 320px; max-height: 140px; object-fit: contain; display: block; margin: 0 auto;">
            </div>
          </div>
      </nav>
    </div>

    <div class="row">
      <div class="nav2">
        <ul class="nav justify-content-center">
          <li class="nav-item">
            <div class="col-sm">
              <a href="../../frontend/pgInicial.php" class="nav-link" id="linksnav">Pagina Inicial </a>
            </div>
          </li>
          <li class="nav-item">
            <div class="col-sm">
              <a href="../parceiros/parceiros.php" class="nav-link" id="linksnav">Parceiros</a>
            </div>
          </li>
          <li class="nav-item">
            <div class="col-sm">
              <a href="../deficiencias/guiasc.php" class="nav-link" id="linksnav">Guias</a>
            </div>
          </li>
          <li class="nav-item">
            <div class="col-sm">
              <a href="#" class="nav-link" id="linksnav">Adoção</a>
            </div>
          </li>
          <li class="nav-item">
            <div class="col-sm">
              <a href="../locais.php" class="nav-link" id="linksnav">Locais</a>
            </div>
          </li>

          <?php
          if (isset($_SESSION['user']) && ($_SESSION['user']['tipo_usuario'] ?? '') === 'administrador') {
            echo '<li class="nav-item">';
            echo '<div class="col-sm">';
            echo '<a href="../../backend/usuarios/adm/pgAdm.php" class="nav-link" id="linksnav">Administração</a>';
            echo '</div>';
            echo '</li>';
          }
          ?>

        </ul>
      </div>
    </div>

    <h1 class="mb-4 text-center tituloAdc" style="color: #4E6422;">Animais para Adoção</h1>
    <?php
    if ($registros && $registros->num_rows > 0) {
      exibirAnimaisEmCards($registros);
    } else {
      echo '<p class="text-center">Nenhum animal cadastrado no momento.</p>';
    }
    ?>
  </div>

  <footer class="rodape">
    <div class="row ">
      <hr class="linha">
      <div class="col logo">
        <img src="../../img/logofooter2.png" alt="" style="width: 150px; height: 150px; margin: 20px;
          margin-left: 80px">
        <!-- <p style="font-size: 15px; color: #4E6422;">Todos os direitos <br> reservados</p> -->
      </div>
      <div class="col colabore">
        <h4 style="margin-top: 35px; color: #4E6422;">Colabore</h4>
        <p style="font-size: 17px; color: #4E6422; margin-top: 15px;">Doe qualquer valor!</p>
        <p style="font-size: 17px; color: #4E6422;">Cobertores, ração e itens são <br> sempre bem-vindos para as ONG's!
        </p>
      </div>
      <div class="col redes">
        <h4 style="margin-top: 35px; color: #4E6422;">Siga-nos</h4>
        <a href="/"><img src="../../img/instagram.png" alt="" style="width: 30px; height: 30px; margin-right: 15px;"></a>
        <a href="/"><img src="../../img/facebook.png" alt="" style="width: 30px; height: 30px; margin-right: 15px;"></a>
        <a href="/"><img src="../../img/tktk.png" alt="" style="width: 30px; height: 30px;"></a>
      </div>
      <div class="col parceiros">
        <h4 style="margin-top: 35px; color: #4E6422;">Pawceiros</h4>
        <p style="font-size: 17px; color: #4E6422; margin-top: 15px;">ONG's</p>
        <p style="font-size: 17px; color: #4E6422;">Veterinários</p>
      </div>
    </div>
  </footer>


  <script src="../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>