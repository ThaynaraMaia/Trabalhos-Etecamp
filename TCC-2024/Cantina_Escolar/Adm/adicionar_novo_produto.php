<?php

include_once '../conn/classes/class_IRepositorioProdutos.php';
session_start(); 

$nome_produto = $_POST['nome_produto'];
$descricao_curta = $_POST['descricao_curta'];
$descricao_produto = $_POST['descricao_produto'];
$quantidade_estoque = $_POST['quantidade_estoque'];
$preco = $_POST['preco'];
$foto = $_FILES['foto'];

$encontrou= $respositorioProduto->verificaFoto($foto);
$respositorioProduto->adicionar_produto($nome_produto,$descricao_produto,$descricao_curta,$preco,$quantidade_estoque,$encontrou);
header('Location: cardapio_adm.php');
?>