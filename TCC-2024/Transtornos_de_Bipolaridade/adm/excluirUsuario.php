<?php

include_once '../conn/classes/class_IRepositorioUsuario.php';

$id = $_GET['id'];
$registro = $respositorioUsuario->excluirUsuario($id);
header('location: indexAdm.php');