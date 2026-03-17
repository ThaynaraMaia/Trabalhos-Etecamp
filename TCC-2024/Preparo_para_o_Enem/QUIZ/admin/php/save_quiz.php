<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quiz_name = $_POST['quiz_name'];

    // Insere o novo quiz na tabela 'quizzes'
    $stmt = $pdo->prepare("INSERT INTO quizzes (name) VALUES (:quiz_name)");
    $stmt->execute(['quiz_name' => $quiz_name]);

    // Redirecionar para a página de administração do quiz após salvar
    header('Location: ../index.php');
    exit;
}
?>
