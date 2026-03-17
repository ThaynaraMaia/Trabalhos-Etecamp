<?php

    include_once "../classes/class_repositorioCategorias.php";

    $novaCategoria = new Categoria('', $_POST['nomeCategoria']);

    session_start();

    $encontrou = $repositorioCategoria->verificaCategoria($_POST['nomeCategoria']);

    $linhas = $encontrou->num_rows;

    if($linhas > 0)
    {
        $mensagem = "Categoria já existe.";
        $_SESSION['mensagem'] = $mensagem;
        header('Location:../../frontend/paginas/paginasAdm/inserirCategoria.php');
    }
    else
    {
        $mensagem = "Categoria cadastrada com sucesso!";
        $repositorioCategoria->incluirCategoria($novaCategoria);
        header('Location:../../frontend/paginas/paginasAdm/gerenciarCategorias.php');
    }

    exit;
    
?>