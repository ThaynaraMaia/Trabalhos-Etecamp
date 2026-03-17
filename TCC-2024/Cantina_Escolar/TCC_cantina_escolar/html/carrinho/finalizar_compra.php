<?php

include_once '../../conn/classes/class_IRepositorioCarrinho.php';
include_once '../../conn/classes/class_IRepositorioProdutos.php';
include_once '../../conn/classes/class_IRepositorioUsuarios.php';
session_start();

$id_usuario = $_SESSION['id'];
$total = $_GET['total'];

$encontrou = $respositorioUsuario->buscarUsuario($id_usuario);
$usuario = $encontrou->fetch_object();

if ($usuario->saldo > $total || $usuario->saldo == $total) {
    $novo_saldo = $usuario->saldo - $total;
    $retorne = $respositorioUsuario->alteraSaldo($id_usuario, $novo_saldo);
    $retorne = $respositorioCarrinho->finalizar_compra($id_usuario);
} else {

    echo "<script>alert('Você não tem saldo suficiente para realizar a compra!'); window.history.back();</script>";
}
?>
