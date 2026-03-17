<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';
include_once '../conn/classes/class_RepositorioConteudosMYSQL.php'; 
session_start();

if (isset($_GET['id_conteudos']) && isset($_POST['bipolaridade']) && isset($_POST['nome_bipolaridade'])) {
    $id_conteudos = $_GET['id_conteudos'];
    $bipolaridade = $_POST['bipolaridade'];
    $nome_bipolaridade = $_POST['nome_bipolaridade'];

    print_r($bipolaridade);

    $respositorioConteudo = new RepositorioConteudosMYSQL(); 
    $alterar = $respositorioConteudo->atualizarBipolaridade($id_conteudos, $nome_bipolaridade, $bipolaridade);

    if ($alterar) {
        header('Location: bipolaridadeAdm.php'); 
    } else {
        echo "Erro ao atualizar.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>
