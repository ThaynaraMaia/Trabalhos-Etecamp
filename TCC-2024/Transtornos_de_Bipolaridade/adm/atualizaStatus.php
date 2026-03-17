<?php

include_once '../conn/classes/class_IRepositorioUsuario.php';

$id = $_GET['id'];
$status = $_GET['status'];

$alterar = $respositorioUsuario->alterarStatus($id,$status);

header('Location:indexAdm.php');

?>