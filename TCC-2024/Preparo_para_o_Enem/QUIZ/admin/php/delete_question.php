<?php
include '../../db/db_connection.php';

// Verificar se o ID da pergunta foi fornecido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ../index.php');
    exit;
}

$question_id = $_GET['id'];

// Excluir alternativas relacionadas
$stmt = $pdo->prepare('DELETE FROM alternatives WHERE question_id = :question_id');
$stmt->execute(['question_id' => $question_id]);

// Excluir a pergunta
$stmt = $pdo->prepare('DELETE FROM questions WHERE id = :id');
$stmt->execute(['id' => $question_id]);

header('Location: ../index.php');
exit;
