<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';
include_once '../conn/classes/class_RepositorioConteudosMYSQL.php'; 
session_start();

if (isset($_GET['id_conteudos']) && isset($_POST['alongamento']) && isset($_POST['nome_alongamento'])) {
    $id_conteudos = $_GET['id_conteudos'];
    $alongamento = $_POST['alongamento'];
    $nome_alongamento = $_POST['nome_alongamento'];

    print_r($alongamento);

    $respositorioConteudo = new RepositorioConteudosMYSQL(); 
    $alterar = $respositorioConteudo->atualizarAlongamento($id_conteudos, $nome_alongamento, $alongamento);

    if ($alterar) {
        header('Location: alongamentoAdm.php'); 
    } else {
        echo "Erro ao atualizar.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>