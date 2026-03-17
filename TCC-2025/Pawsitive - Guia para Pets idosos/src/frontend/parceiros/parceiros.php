<?php
session_start();

// Conectar ao banco de dados
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "pawsitive";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Erro na conexão: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// Buscar ONGs
$sql = "SELECT * FROM tblong";
$result = $conn->query($sql);

$ongs = [];
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $id = $row['id_ong'];

    // buscar telefones
    $telefones = [];
    $sqlTel = "SELECT telefone, tipo_telefone FROM tblong_telefones WHERE id_ong = ?";
    $stmtTel = $conn->prepare($sqlTel);
    $stmtTel->bind_param("i", $id);
    $stmtTel->execute();
    $resTel = $stmtTel->get_result();
    while ($r = $resTel->fetch_assoc()) {
      $telefones[] = $r;
    }
    $stmtTel->close();

    // buscar endereços
    $enderecos = [];
    $sqlEnd = "SELECT rua, numero, complemento, cidade, estado, cep FROM tblong_enderecos WHERE id_ong = ?";
    $stmtEnd = $conn->prepare($sqlEnd);
    $stmtEnd->bind_param("i", $id);
    $stmtEnd->execute();
    $resEnd = $stmtEnd->get_result();
    while ($r = $resEnd->fetch_assoc()) {
      $enderecos[] = $r;
    }
    $stmtEnd->close();

    $row['telefones'] = $telefones;
    $row['enderecos'] = $enderecos;

    $ongs[] = $row;
  }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="../../css/ongs.css" />
  <link rel="shortcut icon" href="../../img/favicon.ico" type="image/x-icon">
  <title>Parceiros</title>
  <style>
    #Cntudo {
      margin-top: 12px;
      margin-left: 20px;
    }

    .rodape {
      background-color: white;
      margin-top: 150px;
      margin-bottom: 35px;
    }

    .linha {
      border: 1px solid #4E6422;
    }

    .ONG {
      margin-bottom: 90px;
      display: flex;
      justify-items: center;
      align-items: center;
      justify-content: center;
    }

    .imagem {
      margin-right: 45px;
    }

    .imgONG {
      width: 400px;
      height: 400px;
      margin-left: 190px;
      border-radius: 20px;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="row" id="rownav" style="background-color: #A8B16B;">
      <nav class="navbar position-relative" style="height: 135px; position: relative;">
        <div class="container position-relative" style="position: relative;">

          <!-- Botão à direita -->
          <div class="ms-auto">
            <a href="/imgUsuarios/user_padrao.png"></a>
            <?php

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

    <main>
      <div class="conteudo" style="margin-top: 30px; margin-bottom: 40px;">
        <h1 class="Paw" style="font-size: 60px; text-align: center; color: #4E6422; margin-bottom: 60px;">
          Pawceiros
        </h1>

        <?php if (!empty($ongs)): ?>
          <?php foreach ($ongs as $ong): ?>
            <div class="ONG">
              <div class="col">
                <a href="detalhe_parceiros.php?id=<?= $ong['id_ong'] ?>">
                  <img src="../../<?= htmlspecialchars($ong['foto_ong'] ?: 'img/placeholder.png') ?>" alt="Foto ONG" class="imgONG" />
                </a>
              </div>
              <div class="col" id="Cntudo" style="margin-right: 140px;">
                <h2>
                  <a href="detalhe_parceiros.php?id=<?= $ong['id_ong'] ?>" style="text-decoration: none; color: #4E6422; font-weight: bold;">
                    <?= htmlspecialchars($ong['nome_ong']) ?>
                  </a>
                </h2>
                <?php if (!empty($ong['enderecos'])): ?>
                  <div class="locais">
                    <p class="local">
                      <strong>
                        <?= htmlspecialchars($ong['enderecos'][0]['cidade']) ?> |
                        <?= htmlspecialchars($ong['enderecos'][0]['estado']) ?>
                      </strong>
                    </p>
                  </div>
                <?php endif; ?>
                <p class="paragrafo"><?= nl2br(htmlspecialchars($ong['historia_ong'])) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="text-align: center; color: #4E6422;">Nenhuma ONG cadastrada até o momento.</p>
        <?php endif; ?>
      </div>
    </main>

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
  </div>

  <script src="../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/d486d2cd81.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>

</html>