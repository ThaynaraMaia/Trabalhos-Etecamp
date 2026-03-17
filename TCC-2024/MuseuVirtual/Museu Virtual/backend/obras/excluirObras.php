<?php

    include_once "../classes/class_repositorioObras.php";
    
    $id = $_GET['id'];
    
    $repositorioObra->excluirObras($id);

    header('Location:../../frontend/paginas/paginasAluno/mostre_sua_arte-obras.php');

    exit;

?>