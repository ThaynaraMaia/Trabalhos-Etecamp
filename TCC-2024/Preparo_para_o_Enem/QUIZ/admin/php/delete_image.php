<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_id = $_POST['question_id'];
    $photo = $_POST['photo'];

    // Caminho completo da imagem
    $image_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $photo;

    // Excluir a imagem do servidor
    if (file_exists($image_path)) {
        unlink($image_path);
    }

    // Remover o campo 'photo' no banco de dados
    $stmt = $pdo->prepare('UPDATE questions SET photo = NULL WHERE id = ?');
    $stmt->execute([$question_id]);

    // Redirecionar de volta à página de edição
    header('Location: ../edit_question.php?id=' . $question_id);
    exit();
}
?>
