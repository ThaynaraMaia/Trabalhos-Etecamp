<?php
include_once '../classes/conn.php';
require '../classes/class_Produto.php';
require '../classes/class_Cart.php';
session_start();

$products = [];

$sql = "SELECT * FROM produtos";
$res = $conn -> query($sql);
for($i = 0; $i < $res ->num_rows; $i++){
  $linha = $res -> fetch_assoc();
  array_push($products, $linha);
}



$id = $_GET['id'];
$sql = 'SELECT * FROM produtos WHERE id LIKE '.$id.'';
$res = $conn -> query($sql);
$linha = $res -> fetch_assoc();
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <style>
    * {
      text-align: center;
    }
    .product-image {
    width: 50rem;
    height: auto;
    border-radius: 8px;
}

.product-details {
    text-align: center;
}

@media (min-width: 768px) {
    .product-details {
        text-align: left;
    }
}

.product-description {
    font-size: 16px;
    line-height: 1.6;
    color: #555;
}

.product-details ul {
    padding-left: 0;
    margin-bottom: 1rem;
}

.product-details ul li {
    font-size: 14px;
    line-height: 1.5;
    color: #333;
}

.product-price {
    font-size: 24px;
    color: #81B973;
}
.btn-custom-sm {
    width: 60%;
    padding: 10px;
    background-color: #81B973;
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 16px;
    cursor: pointer;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s, box-shadow 0.3s;
}

.btn-custom-sm:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
}

.btn-custom-sm:active {
    transform: translateY(0);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
  </style>
  <link rel="icon" href="../../frontend/public/imagens/recursos/logo.png">
  <title><?php echo $linha['nome']; ?></title>
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
            if($_SESSION['tipo']==2){
              echo '<li class="nav-item">
                <a class="nav-link" href="../../backend/adm/index_mod.php">MOD</a>
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
    <div class="container my-5">
    <a href="../carrinho/carrinho.php">Acesse o carrinho</a><br><br>
        <div class="row justify-content-center align-items-center">

            <div class="col-md-6 text-center mb-4 mb-md-0">
                <img src="../../frontend/public/imagens/produtos/<?php echo $linha['foto']; ?>" alt="Produto" class="img-fluid product-image">
            </div>

            <div class="col-md-6">
                <div class="product-details text-center text-md-left">
                    <h2><?php echo $linha['nome']; ?></h2><br>
                    <?php echo $linha['descricao']; ?>

                    <div class="product-price mb-4"><br>
                        <strong>R$: <?php echo $linha['preco']; ?></strong>
                    </div>
                    <form action="" method="post">
                        <button name="Enviar" class="btn-custom-sm">Comprar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    <footer class="footer fixed-bottom text-center text-lg-start d-flex w-100 justify-content-between align-items-center ">
    <div class="container p-3 d-flex justify-content-between w-100">
      <span class="text-muted">© 2024 Grupo Escoteiro João Paulo II. Todos os direitos reservados.</span>
      <div class="social-icons">
        <a href="https://www.facebook.com/GrupoescoteirojoaopauloII?mibextid=ZbWKwL" target="_blank"
          class="text-muted me-3">
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
</body>
</html>

<?php
if (isset($_POST['Enviar'])) {
    // $prod_id = array_search($_GET['id'], $products);
    // $productInfo = $products[$prod_id];
    $product = new Product($linha['id'],$linha['nome'], $linha['descricao'], $linha['quantidade'],  $linha['preco'], $linha['foto']);
    
    $cart = new Cart;
    $cart->add($product);
    $productsInCart = $cart->getCart();

  }