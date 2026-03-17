<?php
session_start();
    include_once '../conn/classes/class_IRepositorioUsuario.php';


    $email = $_POST['email'];
    $senha = $_POST['senha'];

    
    // chamar um método que verifique se o email existe e a senha está associada a este email na tbl_usuarios
    
    $encontrou = $respositorioUsuario->verificaLogin($email,$senha);

    $registroUsuario = $encontrou->fetch_object();

    $linhas = $encontrou->num_rows;


    if($linhas > 0) 
    
    {
        
        $mensagem = "Logado com sucesso!!!!";
        
        $_SESSION['mensagem']=$mensagem;
        $_SESSION['id']=$registroUsuario->id;
        $_SESSION['nome']=$registroUsuario->nome;
        $_SESSION['email']=$registroUsuario->email;
        $_SESSION['senha']=$registroUsuario->senha;
        $_SESSION['tipo']=$registroUsuario->tipo;
        $_SESSION['status']=$registroUsuario->status;
        $_SESSION['logado']=true;
    
        if($_SESSION ['tipo']==0 && $linhas > 0){
            $mensagem="";
            header('location:../php/home.php');
        }else if($_SESSION ['tipo']==1 && $linhas > 0){
            header('location: ../adm/indexAdm.php');

        }
        // }else{
        //     $mensagem="acesso nao permitido";
        //     $_SESSION['mensagem']=$mensagem
        // }
        
    }else{
        
        $mensagem = "Usuário não cadastrado - Direcionando para o Index.!!!!!";

        $_SESSION['mensagem']=$mensagem;
        $_SESSION['logado']=false;

        header('Location:../php/cadastro.php');

        exit;

    }

   

?>