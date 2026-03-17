<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';
include_once '../conn/classes/class_RepositorioConteudosMYSQL.php'; 
session_start();

if (isset($_GET['id_conteudos']) && isset($_POST['sonobipolaridade']) && isset($_POST['nome_sonobipolaridade'])) {
    $id_conteudos = $_GET['id_conteudos'];
    $sonobipolaridade = $_POST['sonobipolaridade'];
    $nome_sonobipolaridade = $_POST['nome_sonobipolaridade'];

    print_r($sonobipolaridade);

    $respositorioConteudo = new RepositorioConteudosMYSQL(); 
    $alterar = $respositorioConteudo->atualizarSonobipolaridade($id_conteudos, $nome_sonobipolaridade, $sonobipolaridade);

    if ($alterar) {
        header('Location: sonobipolaridadeAdm.php'); 
    } else {
        echo "Erro ao atualizar.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>