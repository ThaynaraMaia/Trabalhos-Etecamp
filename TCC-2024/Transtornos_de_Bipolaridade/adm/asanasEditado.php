<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';
include_once '../conn/classes/class_RepositorioConteudosMYSQL.php'; 
session_start();

if (isset($_GET['id_conteudos']) && isset($_POST['asanas']) && isset($_POST['nome_asanas'])) {
    $id_conteudos = $_GET['id_conteudos'];
    $asanas = $_POST['asanas'];
    $nome_asanas = $_POST['nome_asanas'];

    print_r($asanas);

    $respositorioConteudo = new RepositorioConteudosMYSQL(); 
    $alterar = $respositorioConteudo->atualizarAsanas($id_conteudos, $nome_asanas, $asanas);

    if ($alterar) {
        header('Location: asanasAdm.php'); 
    } else {
        echo "Erro ao atualizar.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>