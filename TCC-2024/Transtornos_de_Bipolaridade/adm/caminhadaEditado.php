<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';
include_once '../conn/classes/class_RepositorioConteudosMYSQL.php'; 
session_start();

if (isset($_GET['id_conteudos']) && isset($_POST['caminhada']) && isset($_POST['nome_caminhada'])) {
    $id_conteudos = $_GET['id_conteudos'];
    $caminhada = $_POST['caminhada'];
    $nome_caminhada = $_POST['nome_caminhada'];

    print_r($caminhada);

    $respositorioConteudo = new RepositorioConteudosMYSQL(); 
    $alterar = $respositorioConteudo->atualizarCaminhada($id_conteudos, $nome_caminhada, $caminhada);

    if ($alterar) {
        header('Location: caminhadaAdm.php'); 
    } else {
        echo "Erro ao atualizar.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>
