<?php

include_once "../../backend/classes/class_iRepositorioUsuario.php";

$id = $_GET['id'];
$status = $_GET['status'];

$alterar = $respositorioUsuario->alterarStatus($id,$status);

header('Location:tblusuarios.php');

?>