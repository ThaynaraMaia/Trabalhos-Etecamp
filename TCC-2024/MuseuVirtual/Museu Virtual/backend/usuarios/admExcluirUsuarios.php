<?php
    include_once '../classes/class_repositorioUsuarios.php';

    $id = $_GET['id'];

    //Exclui a conta do usuário.
    $excluir = $repositorioUsuario->excluirUsuario($id);

    header('Location:../../frontend/paginas/paginasAdm/gerenciarUsuarios.php');
?>