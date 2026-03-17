<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';
include_once '../conn/classes/class_RepositorioConteudosMYSQL.php'; 
session_start();

if (isset($_GET['id_conteudos']) && isset($_POST['danca']) && isset($_POST['nome_danca'])) {
    $id_conteudos = $_GET['id_conteudos'];
    $danca = $_POST['danca'];
    $nome_danca = $_POST['nome_danca'];

    print_r($danca);

    $respositorioConteudo = new RepositorioConteudosMYSQL(); 
    $alterar = $respositorioConteudo->atualizarDanca($id_conteudos, $nome_danca, $danca);

    if ($alterar) {
        header('Location: dancaAdm.php'); 
    } else {
        echo "Erro ao atualizar.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>