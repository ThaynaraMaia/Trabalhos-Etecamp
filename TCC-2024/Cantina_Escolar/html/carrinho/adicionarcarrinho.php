<?php

include_once '../../conn/classes/class_IRepositorioCarrinho.php';
include_once '../../conn/classes/class_IRepositorioProdutos.php';
session_start();

$id_produto = $_GET['id_produto'];
$id_usuario = $_SESSION['id'];

echo $id_produto;
echo $id_usuario;

$quantidade_existente = $respositorioCarrinho->verificarQuantidade($id_produto, $id_usuario);

if ($quantidade_existente->num_rows > 0) {
  $respositorioCarrinho->atualizarQuantidade($id_produto, $id_usuario);
} else {
  $qtde = 1;
  $registro = $respositorioProduto->buscarProduto($id_produto);
  $produto = $registro->fetch_object();
  $preco = $produto->preco;
  $encontrou = $respositorioCarrinho->adicionarCarrinho($id_produto, $id_usuario, $qtde, $preco);
}

header("location:../cardapio.php");
?>
