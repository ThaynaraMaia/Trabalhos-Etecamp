<?php
include '../db/db_connection.php';

// Busca o tempo do quiz configurado no banco de dados
$query = $pdo->query('SELECT quiz_time FROM settings WHERE id = 1');
$row = $query->fetch(PDO::FETCH_ASSOC);
$quiz_time = isset($row['quiz_time']) ? (int)$row['quiz_time'] : 3600; // 1 hora como padrão se não houver configuração

// Função para formatar o tempo
function formatTime($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $remainingSeconds = $seconds % 60;
    return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
}

// Buscando as perguntas e alternativas do banco de dados
$query = $pdo->query('SELECT * FROM questions ORDER BY question_number ASC');
$questions = $query->fetchAll(PDO::FETCH_ASSOC);
$alternative_labels = ['A', 'B', 'C', 'D', 'E'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../quiz/css/index_quiz.css">
    <title>Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            width: 80%;
            max-width: 800px;
            margin: 20px 0;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .timer-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
            background-color: #3498db;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #3498db;
        }

        .question-container {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #fafafa;
        }

        .alternative-container {
            margin: 10px 0;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2rem;
        }

        button:hover {
            background-color: #2980b9;
        }

        .error-message {
            color: #e74c3c;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .image-center {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 10px 0;
        }

        .image-center img {
            max-width: 500px;
            max-height: 400px;
            width: auto;
            height: auto;
            border-radius: 5px;
        }

        .question-container.error {
            border-color: #ff6f6f;
            background-color: #ffe6e6;
            box-shadow: 0 4px 8px rgba(255, 0, 0, 0.2);
        }
    </style>
    <script>
        let totalTime = <?php echo $quiz_time; ?>; // Tempo total em segundos do banco de dados
        let interval;

        // Função para formatar o tempo no formato hh:mm:ss
        function formatTime(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const remainingSeconds = seconds % 60;
            return `${hours}:${minutes < 10 ? '0' : ''}${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
        }

        // Função para iniciar o temporizador
        function startTimer() {
            const timerElement = document.getElementById('timer');
            interval = setInterval(() => {
                if (totalTime <= 0) {
                    clearInterval(interval);
                    alert("O tempo acabou! O formulário será enviado.");
                    document.querySelector('form').submit();  // Envia automaticamente o formulário ao fim do tempo
                } else {
                    totalTime--;
                    timerElement.textContent = formatTime(totalTime);
                }
            }, 1000);
        }

        // Função para validar se todas as questões foram respondidas
        function validateForm() {
            const questions = document.querySelectorAll('.question-container');
            let allAnswered = true;

            questions.forEach((question) => {
                const selected = question.querySelector('input[type="radio"]:checked');
                if (!selected) {
                    allAnswered = false;
                    question.classList.add('error');
                } else {
                    question.classList.remove('error');
                }
            });

            if (!allAnswered) {
                document.getElementById('error-message').innerText = 'Por favor, responda todas as questões.';
                return false;
            }

            return true;
        }

        // Inicia o temporizador ao carregar a página
        window.onload = startTimer;
    </script>
</head>
<body>
    <div class="timer-container">
        <div id="timer"><?php echo formatTime($quiz_time); ?></div>
        <div>Tempo restante</div>
    </div>

    <div class="container">
        <form action="php/submit_quiz.php" method="POST" onsubmit="return validateForm();">
            <h2>Simulado ENEM - 2022</h2>

            <div id="error-message" class="error-message"></div>

            <?php foreach ($questions as $question): ?>
            <div class="question-container">
                <h3>Questão <?php echo htmlspecialchars($question['question_number']); ?>:</h3>
                
                <?php if (isset($question['questions_text'])): ?>
                    <p><?php echo html_entity_decode(htmlspecialchars($question['questions_text'])); ?></p>
                <?php else: ?>
                    <p>Texto da questão não disponível.</p> <!-- Mensagem alternativa -->
                <?php endif; ?>

                <?php if (!empty($question['photo'])): ?>
                    <div class="image-center">
                        <?php $imagePath = "../admin/uploads/" . htmlspecialchars($question['photo']); ?>
                        <img src="<?php echo $imagePath; ?>" alt="Imagem da Questão">
                    </div>
                <?php endif; ?>

                <!-- Código para as alternativas aqui -->
            </div>
        <?php endforeach; ?>

            <button type="submit">Enviar Respostas</button>
        </form>
    </div>
</body>
</html>
