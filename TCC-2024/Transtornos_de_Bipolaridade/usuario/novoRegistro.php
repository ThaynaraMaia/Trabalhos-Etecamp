<?php

    include_once '../conn/classes/class_IRepositorioRegistros.php';
    include_once '../conn/classes/class_IRepositorioUsuario.php';

    session_start();

    $id_usuario = $_SESSION['id'];
    $descricao=$_POST['descricao'];
    $humor=$_GET['humor'];
    $tipo=$_GET['tipo'];

 
    $respositorioRegistros->cadastrarRegistros($descricao,$id_usuario,$humor,$tipo); // Método de Cadastrar novo usuário

?>