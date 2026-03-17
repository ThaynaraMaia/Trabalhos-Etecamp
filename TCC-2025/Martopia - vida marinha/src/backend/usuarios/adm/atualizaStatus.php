<?php

include_once '../../classes/class_IRepositorioUsuarios.php';

$id = $_GET['id'];
$status = $_GET['status'];

$altera = $respositorioUsuario->alteraStatus($id,$status);

header('Location:homeAdm.php');

?>