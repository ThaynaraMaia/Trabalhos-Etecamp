<?php

include_once '../../classes/class_IRepositorioUsuarios.php';

$id = $_GET['id'];
$tipo = $_GET['tipo'];

$altera = $respositorioUsuario->alteraTipo($id,$tipo);

header('Location:homeAdm.php');

?>