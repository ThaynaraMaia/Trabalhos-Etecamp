<?php

include_once "../../backend/classes/class_iRepositorioUsuario.php";

$id = $_GET['id'];
$tipo = $_GET['tipo'];

$altera = $respositorioUsuario->alteraTipo($id,$tipo);

header('Location:tblusuarios.php');

?>