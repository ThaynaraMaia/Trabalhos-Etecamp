<?php

    include_once "../classes/class_repositorioObras.php";
    
    $id = $_GET['id'];
    
    $repositorioObra->excluirObras($id);

    header('Location:../../frontend/paginas/paginasAdm/gerenciarObras.php');

    exit;

?>