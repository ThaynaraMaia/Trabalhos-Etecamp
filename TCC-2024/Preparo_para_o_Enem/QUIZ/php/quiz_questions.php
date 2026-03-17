



<?php
include '../db/db_connection.php';

// Obter o ID do quiz da URL
$quiz_id = $_GET['quiz_id'];

// Busca o tempo específico do quiz selecionado
$query = $pdo->prepare('SELECT quiz_time FROM quizzes WHERE id = :quiz_id');
$query->execute(['quiz_id' => $quiz_id]);
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
$query = $pdo->prepare('SELECT * FROM questions WHERE quizzes_id = :quizzes_id ORDER BY question_number ASC');
$query->execute(['quizzes_id' => $quiz_id]); // Corrigido para 'quizzes_id'
$questions = $query->fetchAll(PDO::FETCH_ASSOC);

$alternative_labels = ['A', 'B', 'C', 'D', 'E'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="#"> <!-- Altere para o caminho correto -->
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
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin: 20px 0;
        background: #C0CFB2;
        border-radius: 15px;
        padding: 15px 360px;
        box-shadow: #45624E;
        color: #45624E;
    }

    .timer-circle {
        width: 100px;
        height: 100px;
        border: 5px solid white;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.5rem;
        font-weight: bold;
        background-color: rgba(255, 255, 255, 0.1); /* Fundo semi-transparente */
    }

    .timer-text {
        margin-top: 10px;
        font-weight: bold;
    }

    h2 {
        text-align: center;
        color: #45624E;
    }

    h3{
        color: #45624E;
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
        background-color:#C0CFB2;
        color: #45624E;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1.2rem;
    }

    button:hover {
        background-color:#C0CFB2;
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

        // Função para passar o tempo gasto no quiz para o formulário
        function passTime() {
            document.getElementById('timeSpent').value = <?php echo $quiz_time; ?> - totalTime; // Calcula o tempo gasto
        }


        // Inicia o temporizador ao carregar a página
        window.onload = startTimer;
    </script>
</head>
<body>
    <div class="timer-container">
        <div class="timer-circle">
            <span id="timer"><?php echo formatTime($quiz_time); ?></span>
        </div>
        <div class="timer-text">Tempo restante</div>
    </div>

    <div class="container">
        <form action="../php/submit_quiz.php" method="POST" onsubmit="passTime(); return validateForm();">
            <h2>Simulado ENEM - 2022</h2>

            <div id="error-message" class="error-message"></div>

            <?php foreach ($questions as $question): ?>
                <div class="question-container">
                    <h3>Questão <?php echo htmlspecialchars($question['question_number']); ?>:</h3>
                    
                    <?php if (!empty($question['photo'])): ?>
                        <div class="image-center">
                            <?php $imagePath = "../admin/uploads/" . htmlspecialchars($question['photo']); ?>
                            <img src="<?php echo $imagePath; ?>" alt="Imagem da Questão">
                        </div>
                    <?php endif; ?>

                    <p><?php echo isset($question['question_text']) ? html_entity_decode(htmlspecialchars($question['question_text'])) : "Texto da questão não disponível."; ?></p>

                   
                        

                    <?php
                    // Buscando as alternativas da pergunta
                    $stmt = $pdo->prepare('SELECT * FROM alternatives WHERE question_id = :question_id');
                    $stmt->execute(['question_id' => $question['id']]);
                    $alternatives = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $index = 0;
                    ?>

                    <?php foreach ($alternatives as $alternative): ?>
                        <div class="alternative-container">
                            <input type="radio" id="alt_<?php echo $alternative['id']; ?>" name="question_<?php echo $question['id']; ?>" value="<?php echo $alternative['id']; ?>">
                            <label for="alt_<?php echo $alternative['id']; ?>">
                                <?php echo $alternative_labels[$index]; ?>) <?php echo htmlspecialchars($alternative['alternative_text']); ?>
                            </label>
                        </div>
                        <?php $index++; ?>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            
            <input type="hidden" id="timeSpent" name="timeSpent" value="0"> <!-- Campo oculto para armazenar o tempo gasto -->
            <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>"> <!-- Campo oculto com o ID do quiz -->
             <button type="submit">Enviar Respostas</button>
        </form>
    </div>
</body>
</html>



