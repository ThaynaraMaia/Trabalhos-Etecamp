<?php

include_once '../../conn/classes/class_IRepositorioCarrinho.php';
session_start();

$id_produto = $_GET['id_produto'];
$qtde = $_GET['qtde'];

$registro = $respositorioCarrinho->aumentarProduto_individual($id_produto, $qtde);

print_r($novaQuantidade);

header("location: ../produtos/produto_individual.php?id_produto=" . $id_produto . "?novaQuantidade=" . $novaQuantidade);
?>