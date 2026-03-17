<?php

    include_once '../conn/classes/class_IRepositorioUsuario.php';

    session_start();

    $usuarioNovo = new usuario('',$_POST['nome'],$_POST['email'],$_POST['senha'],0,1);

    // chamar um método que verifique se o email do novo usuário já existe na tbl_usuarios
    
    $encontrou = $respositorioUsuario->verificaEmail($_POST['email']);

    $linhas = $encontrou->num_rows;

    $senha = $_POST['senha'];
    $confirmeSenha = $_POST['confirmeSenha'];

    if($linhas > 0){
        if($senha !== $confirmaSenha){
            echo "<script>alert('As senhas não coincidem. Tente novamente.'); window.history.back();</script>";
        }
        $mensagem = "Email já cadastrado!!!! - tente um diferente......";

        $_SESSION['mensagem']=$mensagem;

    }else if($senha !==$confirmeSenha){
        echo "<script>alert('as senhas nao coincidem. tente novamente.'); window.history.back();</script>;";
    }

    else

    {

        $respositorioUsuario->cadastrarUsuario($usuarioNovo); // Método de Cadastrar novo usuário

        $mensagem = "Usuário cadastrado com sucesso!!!!!";

        $_SESSION['mensagem']=$mensagem;

        header('location: ../php/login.php');
    }

?>