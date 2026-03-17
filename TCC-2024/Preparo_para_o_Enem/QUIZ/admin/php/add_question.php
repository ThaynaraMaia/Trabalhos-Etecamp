<?php
include '../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_number = $_POST['question_number'];
    $question_text = $_POST['question_text'];

    // Inserir a nova pergunta
    $stmt = $pdo->prepare('INSERT INTO questions (question_number, question_text) VALUES (:question_number, :question_text)');
    $stmt->execute(['question_number' => $question_number, 'question_text' => $question_text]);
    $question_id = $pdo->lastInsertId();

    // Inserir alternativas
    for ($i = 1; $i <= 5; $i++) {
        $alternative_text = $_POST['alternative_' . $i];
        $is_correct = isset($_POST['correct_' . $i]) ? 1 : 0;

        if (!empty($alternative_text)) {
            $stmt = $pdo->prepare('INSERT INTO alternatives (question_id, alternative_text, is_correct) VALUES (:question_id, :alternative_text, :is_correct)');
            $stmt->execute(['question_id' => $question_id, 'alternative_text' => $alternative_text, 'is_correct' => $is_correct]);
        }
    }

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../quiz/css/style.css">
    <title>Adicionar Pergunta</title>
</head>
<body>
    <h1>Adicionar Pergunta</h1>
    <form action="add_question.php" method="POST">
        <label for="question_number">Número da Pergunta:</label>
        <input type="number" id="question_number" name="question_number" required><br><br>

        <label for="question_text">Texto da Pergunta:</label>
        <textarea id="question_text" name="question_text" required></textarea><br><br>

        <h3>Alternativas</h3>
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <label for="alternative_<?php echo $i; ?>">Alternativa <?php echo $i; ?>:</label>
            <input type="text" id="alternative_<?php echo $i; ?>" name="alternative_<?php echo $i; ?>" required>
            <input type="checkbox" id="correct_<?php echo $i; ?>" name="correct_<?php echo $i; ?>">
            <label for="correct_<?php echo $i; ?>">Correta</label><br><br>
        <?php endfor; ?>

        <button type="submit" class="btn">Adicionar Pergunta</button>
    </form>
    <a href="index.php" class="btn">Voltar</a>
</body>
</html>
