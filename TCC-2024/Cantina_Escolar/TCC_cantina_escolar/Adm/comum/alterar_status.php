<?php

session_start();
include_once '../../conn/classes/class_IRepositorioUsuarios.php';

$id = $_GET['id'];
$status = $_GET['status'];

$altera = $respositorioUsuario->alteraStatus($id,$status);

header('Location:../gerenciar_cliente.php');
?>
