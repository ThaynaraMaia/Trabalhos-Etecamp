<?php
include_once '../conn/classes/class_IRepositorioConteudo.php';

session_start();

if (isset($_GET['id_conteudos']) && isset($_GET['status'])) {
    $id_conteudos = $_GET['id_conteudos'];
    $status = $_GET['status'];

    // Chama o método para alterar o status
    $respositorioConteudos->alterarStatus($id_conteudos, $status);
    
    // Redireciona de volta para a página de listagem após a atualização
    header('Location: indexAdm.php');
}
?>