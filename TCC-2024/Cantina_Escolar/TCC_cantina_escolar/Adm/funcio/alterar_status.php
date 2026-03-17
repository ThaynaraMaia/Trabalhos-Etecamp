<?php

session_start();
include_once '../../conn/classes/class_IRepositorioUsuarios.php';
include_once '../../conn/classes/class_IRepositorioFuncionario.php';

$id = $_GET['id'];
$status = $_GET['status'];

$altera = $respositorioUsuario->alteraStatus($id,$status);
$altera = $respositorioFuncionario->alterarStatus($id,$status);
header('Location:../gerenciar_funcionario.php');
?>
