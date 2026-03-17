<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';
include_once '../conn/classes/class_RepositorioConteudosMYSQL.php'; 
session_start();

if (isset($_GET['id_conteudos']) && isset($_POST['restaurativas']) && isset($_POST['nome_restaurativas'])) {
    $id_conteudos = $_GET['id_conteudos'];
    $restaurativas = $_POST['restaurativas'];
    $nome_restaurativas = $_POST['nome_restaurativas'];

    print_r($restaurativas);

    $respositorioConteudo = new RepositorioConteudosMYSQL(); 
    $alterar = $respositorioConteudo->atualizarRestaurativas($id_conteudos, $nome_restaurativas, $restaurativas);

    if ($alterar) {
        header('Location: restaurativasAdm.php'); 
    } else {
        echo "Erro ao atualizar.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>