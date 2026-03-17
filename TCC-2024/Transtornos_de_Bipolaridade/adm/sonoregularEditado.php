<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';
include_once '../conn/classes/class_RepositorioConteudosMYSQL.php'; 
session_start();

if (isset($_GET['id_conteudos']) && isset($_POST['sonoregular']) && isset($_POST['nome_sonoregular'])) {
    $id_conteudos = $_GET['id_conteudos'];
    $sonoregular = $_POST['sonoregular'];
    $nome_sonoregular = $_POST['nome_sonoregular'];

    print_r($sonoregular);

    $respositorioConteudo = new RepositorioConteudosMYSQL(); 
    $alterar = $respositorioConteudo->atualizarSonoregular($id_conteudos, $nome_sonoregular, $sonoregular);

    if ($alterar) {
        header('Location: sonoregularAdm.php'); 
    } else {
        echo "Erro ao atualizar.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>