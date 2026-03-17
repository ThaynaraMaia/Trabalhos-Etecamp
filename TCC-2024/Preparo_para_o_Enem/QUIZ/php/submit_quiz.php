<?php 
include '../../db/db_connection.php';

$correct_count = 0;
$answered_questions = 0; // Contador de perguntas respondidas
$unanswered_questions = []; // Array para armazenar perguntas não respondidas
$feedback = [];  // Array para armazenar feedback de cada questão
$automatic_submit = isset($_POST['automatic_submit']) ? (bool)$_POST['automatic_submit'] : false; // Verifica se o envio foi automático

// Define os rótulos das alternativas
$alternative_labels = ['A', 'B', 'C', 'D', 'E'];

// Obter o ID do quiz a partir da requisição POST
$quiz_id = isset($_POST['quiz_id']) ? (int)$_POST['quiz_id'] : 0;

// Obter o total de perguntas do banco de dados para o quiz específico
$total_questions_stmt = $pdo->prepare('SELECT COUNT(*) FROM questions WHERE quizzes_id = :quiz_id');
$total_questions_stmt->execute(['quiz_id' => $quiz_id]);
$total_questions = $total_questions_stmt->fetchColumn(); // Total de perguntas no banco de dados

// Obtenha o tempo total permitido do banco de dados para o quiz específico
$query = $pdo->prepare('SELECT quiz_time FROM quizzes WHERE id = :quiz_id');
$query->execute(['quiz_id' => $quiz_id]);
$setting = $query->fetch(PDO::FETCH_ASSOC);
$total_quiz_time = $setting['quiz_time'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $timeSpent = isset($_POST['timeSpent']) ? (int)$_POST['timeSpent'] : 0; // Tempo gasto em segundos

    foreach ($_POST as $question_id => $alternative_id) {
        if ($question_id === 'timeSpent' || $question_id === 'automatic_submit' || $question_id === 'quiz_id') continue; // Ignora os campos de tempo, envio automático e ID do quiz

        // Verifica se a questão foi respondida
        if (empty($alternative_id)) {
            $unanswered_questions[] = $question_id; // Adiciona a pergunta não respondida
            continue; // Pula para a próxima iteração
        }

        $answered_questions++; // Incrementa perguntas respondidas

        // Obter o texto da questão e a alternativa selecionada
        $stmt = $pdo->prepare('
            SELECT a.is_correct, q.question_text, a.alternative_text, a.id AS alternative_id, a.question_id
            FROM alternatives a
            JOIN questions q ON a.question_id = q.id 
            WHERE a.id = :alt_id
        ');
        $stmt->execute(['alt_id' => $alternative_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Obter todas as alternativas da questão para comparar
            $stmt_alternatives = $pdo->prepare('
                SELECT a.alternative_text, a.id, a.is_correct
                FROM alternatives a
                WHERE a.question_id = :question_id
            ');
            $stmt_alternatives->execute(['question_id' => $result['question_id']]);
            $alternatives = $stmt_alternatives->fetchAll(PDO::FETCH_ASSOC);

            // Identificar a alternativa correta e sua letra
            $correct_alternative = null;
            $correct_label = 'N/A'; // Inicializa o rótulo da alternativa correta
            $selected_label = 'N/A'; // Inicializa o rótulo da alternativa selecionada
            foreach ($alternatives as $index => $alt) {
                if ($alt['is_correct']) {
                    $correct_alternative = $alt;
                    $correct_label = $alternative_labels[$index];
                }
                // Identifica a letra da alternativa selecionada
                if ($alt['id'] == $alternative_id) {
                    $selected_label = $alternative_labels[$index];
                }
            }

            $status = $result['is_correct'] ? 'acertou' : 'errou';
            $feedback[] = [
                'question_number' => $answered_questions,
                'question' => $result['question_text'], 
                'selected_alternative' => $result['alternative_text'],
                'correct_alternative' => $correct_alternative['alternative_text'] ?? 'Não encontrada',
                'selected_alternative_label' => $selected_label,
                'correct_alternative_label' => $correct_label,
                'status' => $status
            ];

            if ($result['is_correct']) {
                $correct_count++;
            }
        } else {
            // Caso a alternativa não seja encontrada
            $feedback[] = [
                'question_number' => $answered_questions,
                'question' => 'Questão não encontrada',
                'selected_alternative' => 'N/A',
                'correct_alternative' => 'N/A',
                'selected_alternative_label' => 'N/A',
                'correct_alternative_label' => 'N/A',
                'status' => 'não encontrada'
            ];
        }
    }
} else {
    echo "Nenhuma resposta foi enviada!";
    exit;
}

// Contar questões não respondidas
$unanswered_count = $total_questions - $answered_questions;

// Considerar as questões não respondidas como erradas no cálculo da porcentagem
$percentage = number_format(($correct_count / $total_questions) * 100, 2);

// Converte o tempo total em horas, minutos e segundos
$total_hours = floor($total_quiz_time / 3600);
$total_minutes = floor(($total_quiz_time % 3600) / 60);
$total_seconds = $total_quiz_time % 60;

// Calcular o tempo utilizado
$time_used = $timeSpent > 0 ? $timeSpent : $total_quiz_time;

// Mensagens para o tempo gasto e o tempo total permitido
$time_used_message = "Você levou <strong>" . floor($time_used / 60) . "m " . $time_used % 60 . "s</strong> para responder.";
$total_time_message = "O tempo total permitido foi <strong>{$total_hours}h {$total_minutes}m {$total_seconds}s</strong>.";

// Adicionar feedback para questões não respondidas
foreach ($unanswered_questions as $question_id) {
    $stmt = $pdo->prepare('
        SELECT q.question_text 
        FROM questions q 
        WHERE q.id = :question_id
    ');
    $stmt->execute(['question_id' => $question_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $feedback[] = [
            'question_number' => count($feedback) + 1, // Adiciona a numeração das questões
            'question' => $result['question_text'],
            'selected_alternative' => 'N/A',
            'correct_alternative' => 'N/A',
            'selected_alternative_label' => 'N/A',
            'correct_alternative_label' => 'N/A',
            'status' => 'não respondida'
        ];
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../quiz/css/submit.css"> <!-- Referência ao arquivo CSS -->
    <title>Resultado do Quiz</title>
</head>
<body>
    <div class="container">
        <div class="result-container">
            <h1>Resultado do Quiz</h1>
            <div class="result-box summary-box">
                <div class="summary-item">
                    <p class="summary-label">Tempo Gasto:</p>
                    <p class="summary-value"><?php echo $time_used_message; ?></p>
                </div>
                <div class="summary-item">
                    <p class="summary-label">Tempo Total Permitido:</p>
                    <p class="summary-value"><?php echo $total_time_message; ?></p>
                </div>
                <div class="summary-item">
                    <p class="summary-label">Total de Acertos:</p>
                    <p class="summary-value"><?php echo $correct_count; ?> de <?php echo $total_questions; ?></p>
                </div>
                <div class="summary-item">
                    <p class="summary-label">Total de Perguntas no Quiz:</p>
                    <p class="summary-value"><?php echo $total_questions; ?></p>
                </div>
                <div class="summary-item">
                    <p class="summary-label">Total de Perguntas Não Respondidas:</p>
                    <p class="summary-value"><?php echo $unanswered_count; ?></p>
                </div>
                <div class="summary-item">
                    <p class="summary-label">Porcentagem de Acertos:</p>
                    <p class="summary-value"><?php echo $percentage; ?>%</p>
                </div>
            </div>

            <button class="toggle-btn" onclick="toggleFeedback()">Ver Detalhes</button>
            <div class="feedback-box" id="feedbackBox" style="display: none;">
                <ul>
                    <?php foreach ($feedback as $item): ?>
                        <li class="<?php echo $item['status']; ?>">
                            <h3>Questão <?php echo $item['question_number']; ?>: <?php echo $item['question']; ?></h3>
                            <p><strong>Resposta Selecionada:</strong> <?php echo $item['selected_alternative_label']; ?> - <?php echo $item['selected_alternative']; ?></p>
                            <p><strong>Resposta Correta:</strong> <?php echo $item['correct_alternative_label']; ?> - <?php echo $item['correct_alternative']; ?></p>
                            <p class="status-message">Você <strong><?php echo $item['status'] === 'acertou' ? 'Acertou' : ($item['status'] === 'não respondida' ? 'Não Respondida' : 'Errou'); ?></strong>.</p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <a class="back-btn" href="../php/quizzes_list.php">Voltar</a>
        </div>
    </div>

    <script>
        function toggleFeedback() {
            var feedbackBox = document.getElementById('feedbackBox');
            if (feedbackBox.style.display === 'none') {
                feedbackBox.style.display = 'block';
            } else {
                feedbackBox.style.display = 'none';
            }
        }
    </script>
</body>
</html>
