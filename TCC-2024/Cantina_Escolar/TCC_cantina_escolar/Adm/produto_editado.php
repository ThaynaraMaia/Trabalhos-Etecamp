<?php

include_once '../conn/classes/class_IRepositorioProdutos.php';

session_start(); 

$id_produto = $_GET['id_produto'];

$nome_produto = $_POST['nome_produto'];
$descricao_curta = $_POST['descricao_curta'];
$descricao_produto = $_POST['descricao_produto'];
$preco = $_POST['preco'];
$quantidade_estoque = $_POST['quantidade_estoque'];
$foto = $_FILES['foto'];

if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $encontrou= $respositorioProduto->verificaFoto($foto);
    $respositorioProduto->atualizar_produto($id_produto,$nome_produto,$descricao_produto,$descricao_curta,$preco,$quantidade_estoque,$encontrou);

} else {
    $respositorioProduto->atualizar_produto_sem_foto($id_produto,$nome_produto,$descricao_produto,$descricao_curta,$preco,$quantidade_estoque);
}

header('Location: cardapio_adm.php');
?>