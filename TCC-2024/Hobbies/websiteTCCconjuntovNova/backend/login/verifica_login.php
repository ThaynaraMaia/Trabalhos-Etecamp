<?php

include_once '../../backend/classes/class_IRepositorioUsuario.php';

    session_start();

    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $id = $_GET['id'];
    // $tipo = $_POST['tipo'];

    $senha_description = sha1("Gtha@#$%!").sha1($senha).sha1("haHa123$#@!");

    $encontrou = $respositorioUsuario->verificaLogin($email,$senha_description);

    $registroUsuario = $encontrou->fetch_object();
    
    $linhas = $encontrou->num_rows;

    if($linhas>0){
        $mensagem = "Usuário Logado com sucesso!!!!";
        $_SESSION['id_usuario']=$registroUsuario->id;
        $_SESSION['usuario_id']=$registroUsuario->id;
        $_SESSION['mensagem'] = $mensagem;
        $_SESSION['nome']=$registroUsuario->nome;
        $_SESSION['email']=$registroUsuario->email;
        $_SESSION['senha']=$registroUsuario->senha;
        $_SESSION['foto_perfil']=$registroUsuario->foto_perfil;
        $_SESSION['tipo']=$registroUsuario->tipo;
        $_SESSION['status']=$registroUsuario->status;
        $_SESSION['logado']=true;
    } 
    
    else
    
    {
        $mensagem="Usuário não econtrado!!!!!";
        $_SESSION['mensagem']=$mensagem;
        $_SESSION['logado']=false;
        header('Location:../../backend/login/login.php');
    }
    
    if($_SESSION['tipo']==1 && $linhas > 0){
        $mensagem="";
        header('Location:../../backend/administrador/home_admin.php');
    } elseif($_SESSION['tipo']==0 && $linhas > 0){
        header('Location:../../frontend/html/home.php');
    } else {
        $mensagem="Acesso não permitido!!!!!";
        $_SESSION['mensagem']=$mensagem;
    }

?>