<?php

    include_once "../classes/class_repositorioUsuarios.php";

    session_start();

    //Recebe os dados do formulário que está no arquivo "mostre_sua_arte-login.php".
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $senha_cripto = sha1("Gtha@#$%!").sha1($senha).sha1("haHa123$#@!");

    $encontrou = $repositorioUsuario->verificaLogin($email, $senha_cripto); //Chama o método que verifica se o email do novo usuário já existe na tabela "usuarios".

    $registroUsuario = $encontrou->fetch_object();

    $linhas = $encontrou->num_rows;

    if($linhas > 0)
    {
        // $mensagem = "Usuário logado com sucesso!";
        // $_SESSION['mensagem'] = $mensagem;
        $_SESSION['id'] = $registroUsuario->id;
        $_SESSION['nome'] = $registroUsuario->nome;
        $_SESSION['email'] = $registroUsuario->email;
        $_SESSION['tipo'] = $registroUsuario->tipo;
        $_SESSION['status'] = $registroUsuario->status;
        $_SESSION['foto'] = $registroUsuario->foto;
        $_SESSION['logado'] = true;

        if($_SESSION['tipo'] == 1){
            header('Location:../../frontend/paginas/paginasAdm/gerenciarUsuarios.php');
        }
        elseif($_SESSION['tipo'] == 0){
            header('Location:../../frontend/paginas/paginasAluno/mostre_sua_arte-obras.php');
        }
        else{
            $mensagem = '<script>alert("Acesso não permitido!");</script>';
            echo $mensagem;
        }
    }
    else
    {
        $mensagem = '<script>alert("Usuário não encontrado, verifique email e senha e tente novamente.");</script>';
        // $_SESSION['mensagem'] = $mensagem;
        $_SESSION['logado'] = false;
        echo $mensagem;
    }


?>