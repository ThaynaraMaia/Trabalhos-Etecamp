<?php

session_start();
include_once '../../conn/classes/class_IRepositorioUsuarios.php';
include_once '../../conn/classes/class_IRepositorioFuncionario.php';

$id_usuario = $_GET['id_usuario'];

$registro = $respositorioUsuario->excluir_usuario($id_usuario);
$registro = $respositorioFuncionario->excluir_usuarios($id_usuario);
header('Location:../gerenciar_adm.php');
?>
