<?php
    include_once '../../backend/classes/class_iRepositorioHobby.php';
    
    

    session_start();
    $id_usuario= $_SESSION['id_usuario'];
   
    $hobbieNovo = new Hobbie('', $id_usuario, $_POST['nome'], $_POST['status'], $_POST['descricao'],'');

    
    $repositorioHobby = new RepositorioHobbyMYSQL();

    
    $repositorioHobby->cadastrarHobby($hobbieNovo);

    $mensagem = "Hobby cadastrado com sucesso!";
    $_SESSION['mensagem'] = $mensagem;

    
    header('Location: ../../frontend/html/home.php');
    exit;

   
    
?>