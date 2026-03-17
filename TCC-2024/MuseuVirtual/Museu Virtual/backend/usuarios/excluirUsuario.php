<?php
    include_once '../classes/class_repositorioUsuarios.php';

    $id = $_GET['id'];

    //Exclui a conta do usuário.
    $excluir = $repositorioUsuario->excluirUsuario($id);

    session_start();

    //Destrói a sessão do usuário.
    if($_SESSION['nome'] && $_SESSION['email']){
        session_destroy();
    }
    
    header('Location:../../frontend/paginas/mostre_sua_arte-login.php');
?>