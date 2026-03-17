<?php
include_once '../classes/class_repositorioObras.php';

$id = $_GET['id'];
$status = $_GET['status'];

$altera = $repositorioObra->alteraStatus($id,$status);

header('Location:../../frontend/paginas/paginasAdm/gerenciarObras.php');
?>