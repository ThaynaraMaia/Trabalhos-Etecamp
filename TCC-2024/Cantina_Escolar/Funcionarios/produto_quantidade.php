<?php

include_once '../conn/classes/class_IRepositorioProdutos.php';

session_start(); 

$id_produto = $_GET['id_produto'];
$quantidade_estoque = $_POST['quantidade_estoque'];

$respositorioProduto->atualizar_quantidade_estoque($id_produto,$quantidade_estoque);

header('Location: cardapio.funcio.php');
?>