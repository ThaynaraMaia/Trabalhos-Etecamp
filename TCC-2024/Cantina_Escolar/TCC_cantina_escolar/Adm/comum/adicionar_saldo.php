<?php

session_start();
include_once '../../conn/classes/class_IRepositorioUsuarios.php';


$id_usuario= $_POST['id_usuario'];
$saldo_novo = $_POST['saldo_novo'];

$encontrou=$respositorioUsuario->buscarUsuario($id_usuario);
$usuario = $encontrou->fetch_object();

if($usuario->saldo > 0){
    $saldo_atualizado = $usuario->saldo + $saldo_novo;

    $altera = $respositorioUsuario->atualizarSaldo($id_usuario,$saldo_atualizado);
}else{

$altera = $respositorioUsuario->alterarSaldo($id_usuario,$saldo_novo);
}

header('Location:../gerenciar_cliente.php');
?>
