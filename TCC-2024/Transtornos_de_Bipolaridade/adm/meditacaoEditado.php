<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';
include_once '../conn/classes/class_RepositorioConteudosMYSQL.php'; 
session_start();

if (isset($_GET['id_conteudos']) && isset($_POST['meditacao']) && isset($_POST['nome_meditacao'])) {
    $id_conteudos = $_GET['id_conteudos'];
    $meditacao = $_POST['meditacao'];
    $nome_meditacao = $_POST['nome_meditacao'];

    print_r($meditacao);

    $respositorioConteudo = new RepositorioConteudosMYSQL(); 
    $alterar = $respositorioConteudo->atualizarMeditacao($id_conteudos, $nome_meditacao, $meditacao);

    if ($alterar) {
        header('Location: meditacaoAdm.php'); 
    } else {
        echo "Erro ao atualizar.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>