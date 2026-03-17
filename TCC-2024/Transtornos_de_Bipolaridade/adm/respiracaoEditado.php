<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';
include_once '../conn/classes/class_RepositorioConteudosMYSQL.php'; 
session_start();

if (isset($_GET['id_conteudos']) && isset($_POST['respiracao']) && isset($_POST['nome_respiracao'])) {
    $id_conteudos = $_GET['id_conteudos'];
    $respiracao = $_POST['respiracao'];
    $nome_respiracao = $_POST['nome_respiracao'];

    print_r($respiracao);

    $respositorioConteudo = new RepositorioConteudosMYSQL(); 
    $alterar = $respositorioConteudo->atualizarRespiracao($id_conteudos, $nome_respiracao, $respiracao);

    if ($alterar) {
        header('Location: respiracaoAdm.php'); 
    } else {
        echo "Erro ao atualizar.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>
