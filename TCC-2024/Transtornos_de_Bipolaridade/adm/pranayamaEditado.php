<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';
include_once '../conn/classes/class_RepositorioConteudosMYSQL.php'; 
session_start();

if (isset($_GET['id_conteudos']) && isset($_POST['pranayama']) && isset($_POST['nome_pranayama'])) {
    $id_conteudos = $_GET['id_conteudos'];
    $pranayama = $_POST['pranayama'];
    $nome_pranayama = $_POST['nome_pranayama'];

    print_r($pranayama);

    $respositorioConteudo = new RepositorioConteudosMYSQL(); 
    $alterar = $respositorioConteudo->atualizarPranayama($id_conteudos, $nome_pranayama, $pranayama);

    if ($alterar) {
        header('Location: pranayamaAdm.php'); 
    } else {
        echo "Erro ao atualizar.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>