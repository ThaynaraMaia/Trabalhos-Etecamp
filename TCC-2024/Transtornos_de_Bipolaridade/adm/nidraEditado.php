<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';
include_once '../conn/classes/class_RepositorioConteudosMYSQL.php'; 
session_start();

if (isset($_GET['id_conteudos']) && isset($_POST['nidra']) && isset($_POST['nome_nidra'])) {
    $id_conteudos = $_GET['id_conteudos'];
    $nidra = $_POST['nidra'];
    $nome_nidra = $_POST['nome_nidra'];

    print_r($nidra);

    $respositorioConteudo = new RepositorioConteudosMYSQL(); 
    $alterar = $respositorioConteudo->atualizarNidra($id_conteudos, $nome_nidra, $nidra);

    if ($alterar) {
        header('Location: nidraAdm.php'); 
    } else {
        echo "Erro ao atualizar.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>