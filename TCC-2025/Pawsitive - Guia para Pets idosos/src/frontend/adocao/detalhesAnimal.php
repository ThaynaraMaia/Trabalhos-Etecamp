<?php
include_once '../../backend/classes/class_IRepositorioAnimaisAdocao.php';
session_start();


$userId = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0;

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null) {
  echo "ID inválido.";
  exit;
}

$repositorio = new RepositorioAnimaisAdocaoMYSQL();
$animal = $repositorio->buscarAnimalPorId($id);

if (!$animal) {
  echo "Animal não encontrado.";
  exit;
}

$totalFavoritos = $repositorio->contarFavoritos($animal->id_animal);
$jaFavoritou = $userId ? $repositorio->usuarioJaFavoritou($animal->id_animal, $userId) : false;

//Compartilhar via whatsapp

$nomeAnimal = $animal->nome_animal;
$descricao  = $animal->descricao_animal;

// URL atual da página (ajuste domínio/caminho se precisar)
$paginaUrl = "https://seusite.com/frontend/adocao/detalhesAnimal.php?id=" . $animal->id_animal;

// Texto que vai para o WhatsApp
$mensagemWhats = "Olha o " . $nomeAnimal . " para adoção: " . $descricao . " - " . $paginaUrl;

// URL final (codificada)
$whatsappLink = "https://api.whatsapp.com/send?text=" . urlencode($mensagemWhats);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Detalhes do Animal - <?php echo htmlspecialchars($animal->nome_animal); ?></title>
  <link rel="stylesheet" href="../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../../css/adocao.css" />
  <link rel="shortcut icon" href="../../img/favicon.ico" type="image/x-icon">
  <style>
    .favorito-btn {
      background: none;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 1.5rem;
      padding: 5px;
    }

    .favorito-btn:hover {
      transform: scale(1.1);
    }

    .favorito-btn .bi-star-fill {
      color: gold;
    }

    .favorito-btn .bi-star {
      color: #6c757d;
    }

    .favorito-btn.loading .bi {
      animation: spin 1s linear infinite;
    }

    .favorito-contador {
      font-size: 1.2rem;
      margin-left: 5px;
      vertical-align: middle;
      color: #6c757d;
    }

    @keyframes spin {
      from {
        transform: rotate(0deg);
      }

      to {
        transform: rotate(360deg);
      }
    }

    .alert-login {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1050;
      animation: fadeIn 0.5s, fadeOut 0.5s 2.5s;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeOut {
      from {
        opacity: 1;
        transform: translateY(0);
      }

      to {
        opacity: 0;
        transform: translateY(-20px);
      }
    }

    .titulo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 50px;
      color: #4e6422;
    }

    .imgPET {
      height: 500px;
      object-fit: cover;
      max-width: 500px;
      width: 100%;
      margin-left: 150px;
      margin-top: 50px;
    }

    .nosso {
      border: none;
      margin-bottom: 50px;
    }

    .conteudo {
      width: 550px;
      margin-top: 90px;
      margin-left: 195px;
    }

    .titulo02 {
      color: #7a4100;
    }

    .titulo03 {
      color: #53662d;
    }

    .titulo05 {
      color: #7a4100;
      font-size: 20px;
    }

    .meubotao {
      padding: 8px;
      width: 160px;
      margin-top: 25px;
      font-size: 20px;
      background-color: #4e6422;
      color: white;
      border-radius: 15px;
      margin-left: 10px;
      transition: 0.5s;
      display: inline-block;
      border: 2px solid #4e6422;
    }

    .meubotao:hover {
      border: 2px solid #4e6422;
      background-color: white;
      color: #4e6422;
      transition: 0.5s;
    }

    .modalbtn {
      padding: 8px;
      width: 260px;
      margin-top: 25px;
      font-size: 20px;
      background-color: #4e6422;
      color: white;
      border-radius: 15px;
      /* margin-left: 80px; */
      transition: 0.5s;
      display: inline-block;
      border: 2px solid #4e6422;
    }

    .modalbtn:hover {
      border: 2px solid #4e6422;
      background-color: white;
      color: #4e6422;
      transition: 0.5s;
    }

    .rodape {
      background-color: white;
      margin-top: 150px;
      margin-bottom: 35px;
    }

    .linha {
      border: 1px solid #4E6422;
    }

    .whatsapp-btn {
      background: none;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 1.5rem;
      padding: 5px;
      color: #25D366;
    }

    .whatsapp-btn:hover {
      transform: scale(1.1);
    }
  </style>
</head>

<body>
  <div id="alert-placeholder"></div>

  <div class="container-fluid">

    <div class="row" id="rownav" style="background-color: #A8B16B; ">
      <nav class="navbar position-relative" style="height: 135px; position: relative;">
        <div class="container position-relative" style="position: relative;">

          <!-- Botão à direita -->
          <div class="ms-auto">
            <a href="..imgUsuarios/user_padrao.png"></a>
            <?php
            // session_start();

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
              <a href="../pgInicial.php" class="nav-link" id="linksnav">Pagina Inicial </a>
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
              <a href="../adocao/adocao2.php" class="nav-link" id="linksnav">Adoção</a>
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

    <div class="card nosso">
      <div class="row g-0">
        <div class="col-4 text-center imagem">
          <img src="../..<?php echo htmlspecialchars($animal->foto_animal); ?>" alt="Foto do <?php echo htmlspecialchars($animal->nome_animal); ?>" class="imgPET img-fluid rounded">
        </div>
        <div class="col-md-8">
          <div class="card-body conteudo">
            <h1 class="card-title titulo">
              <?php echo htmlspecialchars($animal->nome_animal); ?>
              <!-- Botão de favoritos -->
              <button id="btn-favorito" class="favorito-btn" data-id="<?php echo $animal->id_animal; ?>">
                <i class="bi <?php echo $jaFavoritou ? 'bi-star-fill' : 'bi-star'; ?>"></i>
              </button>
              <span id="contador-favoritos" class="favorito-contador"><?php echo $totalFavoritos; ?></span>
              <!-- Fim do botão de favoritos -->

              <!-- Botão de compartilhar no WhatsApp -->
              <a href="<?php echo htmlspecialchars($whatsappLink); ?>"
                class="whatsapp-btn"
                target="_blank"
                rel="noopener noreferrer"
                title="Compartilhar no WhatsApp">
                <i class="bi bi-whatsapp"></i>
              </a>
            </h1>
            <p class="titulo02"><strong><?php echo htmlspecialchars($animal->caracteristicas_animal); ?></strong> | <strong><?php echo htmlspecialchars($animal->genero_animal); ?></strong> | <strong><?php echo htmlspecialchars($animal->especie_animal); ?></strong> | <strong><?php echo htmlspecialchars($animal->idade_animal); ?> anos</strong> | <strong><?php echo htmlspecialchars($animal->condicao_saude); ?></strong></p>

            <h3 class="titulo03">Descrição de <?php echo htmlspecialchars($animal->nome_animal); ?></h3>
            <p><?php echo htmlspecialchars($animal->descricao_animal); ?></p>

            <p class="titulo05"><strong><?php echo htmlspecialchars($animal->cidade_animal); ?></strong> </p>

            <div class="botoes" style="display: flex;">
              <!-- Button trigger modal -->
              <button class="modalbtn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Entre em contato
              </button>

              <!-- Modal -->
              <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="staticBackdropLabel" style="color: #4E6422;">Tem interesse?</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="margin-top: 10px; margin-bottom: 15px;">
                      Entre em contato com a ONG responsável pelo animal através do número: (11)955246736
                    </div>
                  </div>
                </div>
              </div>

              <!-- <a href="../adocao/adocao2.php" class="btn mt-3 meubotao">Voltar</a> -->
              <!-- <button class="meubotao"><a href="../adocao/adocao2.php"></a>Voltar</button> -->
              <button class="meubotao" onclick="window.location.href='../adocao/adocao2.php'">
                Voltar </button>
            </div>

          </div>
        </div>
      </div>
    </div>
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

  <script>
    // Variável global com o ID do usuário
    const userId = <?php echo isset($_SESSION['id']) ? $_SESSION['id'] : 0; ?>;
  </script>

  <script src="../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
  <script src="../adocao/curtir.js"></script>
</body>

</html>