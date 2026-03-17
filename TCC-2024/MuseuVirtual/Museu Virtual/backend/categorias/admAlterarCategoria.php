<?php
     include_once "../classes/class_repositorioCategorias.php";
    
     $id = $_POST['id'];
     $nome = $_POST['nome'];
     
     $repositorioCategoria->editarCategorias($id, $nome);
 
     header('Location:../../frontend/paginas/paginasAdm/gerenciarCategorias.php');
     exit;
 
?>