<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';
include_once '../conn/classes/class_RepositorioConteudosMYSQL.php'; 
session_start();

if (isset($_GET['id_conteudos']) && isset($_POST['sonodicas']) && isset($_POST['nome_sonodicas'])) {
    $id_conteudos = $_GET['id_conteudos'];
    $sonodicas = $_POST['sonodicas'];
    $nome_sonodicas = $_POST['nome_sonodicas'];

    print_r($sonodicas);

    $respositorioConteudo = new RepositorioConteudosMYSQL(); 
    $alterar = $respositorioConteudo->atualizarSonodicas($id_conteudos, $nome_sonodicas, $sonodicas);

    if ($alterar) {
        header('Location: sonodicasAdm.php'); 
    } else {
        echo "Erro ao atualizar.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>