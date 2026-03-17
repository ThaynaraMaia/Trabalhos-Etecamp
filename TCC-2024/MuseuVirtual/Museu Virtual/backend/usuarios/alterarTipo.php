<?php
    include_once '../classes/class_repositorioUsuarios.php';

    $id = $_GET['id'];
    $tipo = $_GET['tipo'];
    
    $altera = $repositorioUsuario->alteraTipo($id,$tipo);
    
    header('Location:../../frontend/paginas/paginasAdm/gerenciarUsuarios.php');
?>