<?php

    include_once "../classes/class_repositorioCategorias.php";
    
    $id = $_GET['id'];
    
    $repositorioCategoria->excluirCategorias($id);

    header('Location:../../frontend/paginas/paginasAdm/gerenciarCategorias.php');

    exit;

?>