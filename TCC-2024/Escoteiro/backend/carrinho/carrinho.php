<?php
ob_start();
require '../classes/class_Produto.php';
require '../classes/class_Cart.php';
require '../classes/conn.php';

session_start();
$logged = false;
if (isset($_SESSION['on'])) {
    $logged = true;
}

$cart = new Cart;
$productsInCart = $cart->getCart();


if (isset($_GET['remove'])) {
  $id = strip_tags($_GET['remove']);
  $cart->remove($id);
  header('Location: carrinho.php');
}
if (isset($_GET['adicionar'])) {
  $id = strip_tags($_GET['adicionar']);
  $cart->adicionar($id);
  header('Location: carrinho.php');
}


if (isset($_GET['update'])) {
  $id = strip_tags($_GET['update']);
  $qty = strip_tags($_GET['qty']);
  $cart->updateQty($id, $qty);
  header('Location: carrinho.php');
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
  </style>
  <link rel="icon" href="../../frontend/public/imagens/recursos/logo.png">
  <title>Carrinho</title>
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
  <br><a href="../loja/loja.php">Voltar para a loja</a><br>
  <?php echo("Saldo: R$: ".$_SESSION['saldo']);  ?>
    <?php if (count($productsInCart) <= 0) : ?>
      Nenhum produto no carrinho
    <?php endif; ?>
     <form action="" method="post">
   <?php foreach ($productsInCart as $product) : ?>
      <div class="prod">
        <img src="../../frontend/public/imagens/produtos/<?php echo($product->getFoto()); ?>" width="350px" alt="">
        <p class="text-ellipsis"><?php 
        echo $product->getNome(); ?>
        </p>
        <div class="intens">
          <input type="hidden" name="update" value="<?php echo $product->getId(); ?>">
        </div>
        <div class="intens">
        Preço: R$ <?php echo number_format($product->getPreco(), 2, ',', '.') ?> 
        </div>
        <div class="counter d-flex flex-column flex-md-row align-items-center">
          
          <a type="button" href="?remove=<?php echo $product->getId(); ?>" value="-" class="btn btn-danger btn-counter">-</a>
          <p class=" form-control text-center mx-2 mb-2 mb-md-0" readonly><?php echo $product->getQuantidade(); ?></p>
          <a href="?adicionar=<?php echo $product->getId(); ?>" type="button" value="-" class="btn btn-success btn-counter mb-2 mb-md-0">+</a>

        </div>

          Subtotal: R$ <?php echo number_format($product->getPreco() * $product->getQuantidade(), 2, ',', '.') ?>
      </div>
    <?php endforeach; ?>
    <br><h3>Total: R$ <?php echo number_format($cart->getTotal(), 2, ',', '.'); ?> </h1><br>
    <input type="submit" name="Enviar" id="Enviar" value="Comprar" class="btn-custom"></form><br><br>
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
      $compra=[];
        // $_SESSION['saldo']=$_SESSION['saldo']-$cart->getTotal();
        foreach ($productsInCart as $product) {
          $nome=$product->getNome();
          $quantidade=$product->getQuantidade();
          $preco=$product->getPreco();
          $subtotal= number_format($product->getPreco() * $product->getQuantidade(), 2, ',', '.');
          $texto = "|| Nome: ".$nome.", Quantidade comprada: ".$quantidade.", Preço unitário: ".$preco.", Subtotal: ".$subtotal." || ";
          array_push($compra, $texto);
        }
        $compra=implode("",$compra);
        if(intval($_SESSION['saldo'])>=$cart->getTotal()){
          $_SESSION['saldo']=intval($_SESSION['saldo'])-($cart->getTotal());
         $sql_add = "INSERT INTO compras (userid, compra, valor, data, estado) VALUES
         ('".$_SESSION['userid']."',
         '".$compra."', 
         '".$cart->getTotal()."',
         '".date('Y/m/d')."',
         0);";
         $sql_deb = "UPDATE `tbl_usuarios` SET `saldo` = '".$_SESSION['saldo']."' WHERE `id` = ".$_SESSION['userid'].";";
         $res = $conn -> query($sql_add);
         $userres = $conn -> query($sql_deb);
         $cart='';
         header('Location: ../recibos/index.php');
         exit;
         }
         else{
         echo  "<script>alert('Saldo insuficiente!');</script>";
         }
    }
?>
