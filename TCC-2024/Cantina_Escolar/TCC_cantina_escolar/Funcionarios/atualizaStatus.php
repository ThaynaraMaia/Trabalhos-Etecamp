<?php

include_once '../conn/classes/class_IRepositorioProdutos.php';

$id_pedido = $_GET['id_pedido'];
$status = $_GET['status'];

$altera = $respositorioProduto->alterar_status_pedido($id_pedido,$status);

header('Location:andamento_pedido.php');
?>
