<?php

session_start();
include_once '../../conn/classes/class_IRepositorioUsuarios.php';

$id_usuario = $_GET['id_usuario'];


$registro = $respositorioUsuario->excluir_usuario($id_usuario);

header('Location:../gerenciar_cliente.php');
?>
