<?php

include_once '../conn/classes/class_IRepositorioUsuario.php';

$id = $_GET['id'];
$tipo = $_GET['tipo'];

$alterar = $respositorioUsuario->alterarTipo($id,$tipo);

header('Location:indexAdm.php');

?>