<?php

session_start();
include_once '../conn/classes/class_IRepositorioProdutos.php';

$id_produto = $_GET['id_produto'];

$registro = $respositorioProduto->excluir_produto($id_produto);

header('Location: cardapio_adm.php');
?>
