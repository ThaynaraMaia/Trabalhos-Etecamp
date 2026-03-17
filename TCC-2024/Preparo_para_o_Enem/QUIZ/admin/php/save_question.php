<?php
include '../../db/db_connection.php';

// Verifica se o formulário foi enviado corretamente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário
    $quiz_id = $_POST['existing_quiz'];
    $question_number = $_POST['question_number'];
    $question_text = $_POST['question']; // O conteúdo do editor de texto
    $correct_answer = $_POST['correct']; // A alternativa correta

    // Variáveis para as alternativas
    $alternatives = [];
    $letters = ['A', 'B', 'C', 'D', 'E'];

    foreach ($letters as $letter) {
        $alternatives[$letter] = $_POST['alternative_' . $letter]; // Coleta as alternativas
    }

    // Verifica se uma imagem foi enviada
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        // Define o diretório de upload
        $upload_dir = '../../Home/img/';
        $photo = basename($_FILES['photo']['name']);
        $target_path = $upload_dir . $photo;

        // Move o arquivo para o diretório
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
            $photo = $_FILES['photo']['name'];
        } else {
            echo 'Erro ao fazer o upload da imagem.';
            exit;
        }
    }

    try {
        // Inicia a transação
        $pdo->beginTransaction();

        // Insere a questão na tabela `questions`
        $query = 'INSERT INTO questions (quizzes_id, question_number, question_text, photo) VALUES (?, ?, ?, ?)';
        $stmt = $pdo->prepare($query);
        $stmt->execute([$quiz_id, $question_number, $question_text, $photo]);
        
        // Recupera o ID da questão recém-inserida
        $question_id = $pdo->lastInsertId();

        // Insere as alternativas na tabela `alternatives`
        foreach ($alternatives as $letter => $text) {
            $is_correct = ($letter === $correct_answer) ? 1 : 0; // Define se é a resposta correta
            $alt_query = 'INSERT INTO alternatives (question_id, alternative_text, is_correct) VALUES (?, ?, ?)';
            $alt_stmt = $pdo->prepare($alt_query);
            $alt_stmt->execute([$question_id, $text, $is_correct]);
        }

        // Confirma a transação
        $pdo->commit();

    } catch (Exception $e) {
        // Caso ocorra um erro, desfaz a transação
        $pdo->rollBack();
    }
}
?>
