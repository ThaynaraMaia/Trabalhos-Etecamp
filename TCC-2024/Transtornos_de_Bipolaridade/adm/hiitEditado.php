<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';
include_once '../conn/classes/class_RepositorioConteudosMYSQL.php'; 
session_start();

if (isset($_GET['id_conteudos']) && isset($_POST['hiit']) && isset($_POST['nome_hiit'])) {
    $id_conteudos = $_GET['id_conteudos'];
    $hiit = $_POST['hiit'];
    $nome_hiit = $_POST['nome_hiit'];

    print_r($hiit);

    $respositorioConteudo = new RepositorioConteudosMYSQL(); 
    $alterar = $respositorioConteudo->atualizarHiit($id_conteudos, $nome_hiit, $hiit);

    if ($alterar) {
        header('Location: hiitAdm.php'); 
    } else {
        echo "Erro ao atualizar.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>