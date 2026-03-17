<?php
include_once '../../backend/classes/class_iRepositorioHobby.php';

$respositorioHobby = new RepositorioHobbyMYSQL();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($respositorioHobby->removerHobby($id)) {
        // Redireciona de volta para a página anterior ou uma página específica
        header('Location: ../../frontend/html/meus_hobbies.php');
        exit; // Certifique-se de encerrar o script após o redirecionamento
    } else {
        echo 'error: falha na exclusão';
    }
}else {
    echo 'error: ID não fornecido';
}
?>


