<?php
include_once '../../backend/classes/class_iRepositorioHobby.php';

$id = $_POST['id']; 
$status = $_POST['status']; 
$sentimento = $_POST['sentimento'];

$repositorioHobby = new RepositorioHobbyMYSQL();

// Atualiza o hobby e depois cadastra o sentimento
if ($repositorioHobby->atualizarHobby($id, $status)) {
    // Agora, cadastra o sentimento
    if ($repositorioHobby->cadastrarSentimento($status, $sentimento, $id)) {
        header('Location: ../../frontend/html/meus_hobbies.php');
        exit(); // Adicione exit() após o redirecionamento
    } else {
        echo "Erro ao cadastrar sentimento.";
    }
} else {
    echo "Erro ao atualizar hobby.";
}
?>

