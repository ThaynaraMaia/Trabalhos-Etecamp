<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quiz_id'])) {
    $quiz_id = $_POST['quiz_id'];

    // Deletando as questões associadas ao quiz
    $stmt = $pdo->prepare('DELETE FROM questions WHERE quizzes_id = :quiz_id');
    $stmt->execute(['quiz_id' => $quiz_id]);

    // Deletando o quiz
    $stmt = $pdo->prepare('DELETE FROM quizzes WHERE id = :quiz_id');
    $stmt->execute(['quiz_id' => $quiz_id]);

    // Redirecionando após deletar
    header('Location: ../index.php');
    exit;
}
?>
