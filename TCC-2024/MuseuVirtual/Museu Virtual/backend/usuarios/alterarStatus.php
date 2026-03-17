<?php
    include_once '../classes/class_repositorioUsuarios.php';

    $id = $_GET['id'];
    $status = $_GET['status'];

    $altera = $repositorioUsuario->alteraStatus($id,$status);

    header('Location:../../frontend/paginas/paginasAdm/gerenciarUsuarios.php');
?>