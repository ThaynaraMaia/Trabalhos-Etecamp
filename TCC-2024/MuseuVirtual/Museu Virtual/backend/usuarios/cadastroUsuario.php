<?php

    include_once "../classes/class_repositorioUsuarios.php";

    //Recebe os dados do formulário que está no arquivo "mostre_sua_arte-cadastro.php".
  
    session_start();

    $foto = $_FILES['foto'];

    $verificouFoto = $repositorioUsuario->verificaFoto($foto);

    if($verificouFoto){

        $novoUsuario = new Usuario('', $_POST['nome'], $_POST['email'], $_POST['senha'], 0, 1, $verificouFoto);


        $encontrou = $repositorioUsuario->verificaEmail($_POST['email']); //Chama o método que verifica se o email do novo usuário já existe na tabela "usuarios".

        $linhas = $encontrou->num_rows;

        if($linhas > 0)
        {
            // $mensagem = '<script>alert("Email já cadastrado, tente novamente utilizando um email diferente");</script>';
            $mensagemCadastro = "Email já cadastrado, tente novamente utilizando um email diferente.";
            $_SESSION['mensagemCadastro'] = $mensagemCadastro;
        }
        else
        {
            $repositorioUsuario->cadastrarUsuario($novoUsuario); // Método que cadastra o novo usuário.
            $mensagemCadastro = "Usuário cadastrado com sucesso!";
            $_SESSION['mensagemCadastro'] = $mensagemCadastro;
        }

        header('Location:../../frontend/paginas/mostre_sua_arte-login.php');

        exit;

    } else {

        // $mensagem = '<script>alert("Erro. Verfique formato e tamanho do arquivo. Observação: Somente arquivos do tipo jpg, jpeg, png são permitidos! Tamanho máximo permitido: 2MB.");</script>';
        // echo $mensagem;
        header('Location:../../frontend/paginas/mostre_sua_arte-cadastro.php');
    }

    exit;

   
?>