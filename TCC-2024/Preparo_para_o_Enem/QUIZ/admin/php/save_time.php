<?php
// Inclua a conexão com o banco de dados
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pegue o ID do quiz do formulário
    $quiz_id = (int)$_POST['quiz_id'];  // Certifique-se de que o ID do quiz é passado no formulário

    // Pegue os valores de horas, minutos e segundos do formulário
    $hours = (int)$_POST['quiz_hours'];
    $minutes = (int)$_POST['quiz_minutes'];
    $seconds = (int)$_POST['quiz_seconds'];

    // Converta o tempo total para segundos
    $total_time_in_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;

    // Atualize o tempo do quiz específico no banco de dados (usando o quiz_id)
    $stmt = $pdo->prepare('UPDATE quizzes SET quiz_time = :quiz_time WHERE id = :quiz_id');
    $stmt->execute([
        'quiz_time' => $total_time_in_seconds,
        'quiz_id' => $quiz_id
    ]);

    // Redireciona de volta para a página de administração
    header('Location: ../index.php');
    exit;
}
?>
