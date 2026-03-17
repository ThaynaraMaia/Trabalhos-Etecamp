<?php

    include_once '../../backend/classes/class_iRepositorioUsuario.php';
    

    session_start();

    $usuarioNovo = new usuario('',$_POST['nome'],$_POST['email'],$_POST['senha'], $_SESSION['foto_perfil'],  $_POST['tipo'], $_POST['status']);
    
    $encontrou = $respositorioUsuario->verificaEmail($_POST['email']);

    $linhas = $encontrou->num_rows;

    if($linhas > 0) 
    
    {

        $mensagem = "Email já cadastrado!";

        $_SESSION['mensagem']=$mensagem;

    }

    else

    {

        $respositorioUsuario->cadastrarUsuario($usuarioNovo);

        $mensagem = "Usuário cadastrado com sucesso. Bem vindo!";

        $_SESSION['mensagem']=$mensagem;

    }

    header('Location:../../backend/login/login.php');

    exit;

?>