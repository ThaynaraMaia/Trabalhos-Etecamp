<?php
$pdo = new PDO('mysql:host=localhost;dbname=jpii', 'root', '');

// Verificar se o usuário é administrador ou moderador
// if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
//     die("Acesso não autorizado.");
// }

// Adicionar nova categoria
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $cover_image = $_FILES['cover_image']['name'];
        $filetmp = $_FILES['cover_image']['tmp_name'];
        $upload_dir = '../galeria/uploads/';
        $upload_file = $upload_dir . basename($cover_image);

        if (move_uploaded_file($filetmp, $upload_file)) {
            $category = $_POST['category'];
            $stmt = $pdo->prepare("INSERT INTO categories (name, cover_image) VALUES (?, ?)");
            $stmt->execute([$category, $cover_image]);
        } else {
            echo "Erro ao enviar a imagem de capa.";
        }
    }
}

// Consultar categorias existentes
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
    session_start();
    // if(!isset($_SESSION['on'])) {
    //     $_SESSION['msg'] = 'Não está logado';
    //     echo $_SESSION['msg'];
    //     header('Location: main.php');
    // }
    $logged = false;
    if (isset($_SESSION['on'])) {
    $logged = true;
    }
    if($_SESSION['tipo']<2){
        header('Location: ../../frontend/principal/index.php');
    }
    ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../frontend/public/css/teste.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <style>
    * {
      text-align: center;
    }
    a{
        text-decoration: none;
    }
    .table-responsive {
    width: 100%;
    overflow-x: auto; 
    -webkit-overflow-scrolling: touch; 
}

.table {
    white-space: nowrap;
}
  </style>
  <link rel="icon" href="../../frontend/public/imagens/recursos/logo.png">
  <title>Painel de Controle</title>
</head>

<body>
  <nav class="navbar navbar-expand-lg py-3 custom-navbar">
    <div class="container">

      <a class="navbar-brand" href="../../frontend/principal/index.php">
        <img src="../../frontend/public/imagens/recursos/logo.png " height="80rem" width="80rem" alt="Logo" height="40">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <img src="../../frontend/public/imagens/recursos/menu.png" width="20px" alt="">
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" target="blank"
              href="../../frontend/public/docs/POR_2013_19_V2.pdf">Regulamento Interno</a>
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
          if ($logged) {
            if ($_SESSION['tipo'] >= 1) {
              echo '<li class="nav-item">
                <a class="nav-link" href="../../backend/loja/loja.php">Loja</a>
              </li>';
            }
            if ($_SESSION['tipo'] == 3) {
              echo '<li class="nav-item">
                <a class="nav-link" href="../../backend/adm/index_adm.php">ADM</a>
              </li>';
            }
            echo '<li class="nav-item">
            <a class="nav-link" href="../../frontend/principal/user.php?id='.$_SESSION["userid"].'">'.$_SESSION["nome"].'</a>
                <li class="nav-item">
            <a class="nav-link" href="../../backend/notificacoes/index.php"><img src="../../frontend/public/imagens/recursos/sino.png" width="20px"></a>
              </li>';
          } else {
            echo '<li class="nav-item">
                <a class="nav-link" href="../../backend/login/login.php">Faça Login</a>
              </li>';
          }
          ?>
        </ul>
      </div>
    </div>
  </nav>
    <div class="content">
    <br><h1>Gerenciar Categorias</h1><br>
    <form action="gerenciar_cat.php" method="post" enctype="multipart/form-data">
        <label for="category">Nova Categoria:</label>
        <input type="text" name="category" id="category" required><br><br>    
        <label for="cover_image">Imagem de Capa:</label>
        <input type="file" name="cover_image" id="cover_image" required><br><br>
        <button type="submit">Adicionar Categoria</button>
    </form>
    <br><h2>Categorias Existentes</h2><br>
    <ul>
        <?php foreach ($categories as $category): ?>
            <li>
                <?= htmlspecialchars($category['name']) ?>
                <img src="../galeria/uploads/<?= htmlspecialchars($category['cover_image']) ?>" alt="<?= htmlspecialchars($category['name']) ?>" style="width:100px;">
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="galeria_adm.php">Voltar</a><br>
    </div>
    <br><br>
    <div class="footer">
        <a href="https://www.instagram.com/ge_joaopaulo2?igsh=MWFiOTA2OTFxYW11"><img src="../../frontend/public/imagens/recursos/instagram.png" alt="Instagram do grupo"></a>
        <a href="https://www.facebook.com/GrupoescoteirojoaopauloII?mibextid=ZbWKwL"><img src="../../frontend/public/imagens/recursos/facebook.png" alt="Facebook do grupo"></a>
        <a href="https://www.tiktok.com/@ge._joaopauloii?_t=8nG7cVdsBGQ&_r=1"><img src="../../frontend/public/imagens/recursos/tiktok.png" alt="TikTok do grupo"></a>
        <a href="https://youtube.com/@gejoaopauloii?si=8nJBM0zWxWBofH_V"><img src="../../frontend/public/imagens/recursos/youtube.png" alt="Youtube do grupo"></a>
        <br><br>
        <p>© 2024 Grupo Escoteiro João Paulo II - Todos os direitos reservados​</p>
    </div>          
</body>
</html>
