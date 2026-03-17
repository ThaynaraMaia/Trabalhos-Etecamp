<?php

include_once '../../conn/classes/class_IRepositorioCarrinho.php';

session_start();

$id_produto = $_GET['id_produto'];

$registro = $respositorioCarrinho->aumentarCarrinho($id_produto);

header("location: ../carrinho.php");
?>