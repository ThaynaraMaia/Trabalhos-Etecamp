<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../admin/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <title>Administração de Quiz</title>
    <style>
 
 
 .btn-container{
    display: flex;
    justify-content: flex-start;
    gap: 10px;
    margin-bottom: 10px; /*botão do editor */
}

/* Estilos gerais para uma aparência moderna */
body {
    font-family: 'Poppins', Arial, sans-serif; /* Fonte mais moderna e elegante */
    background-color: #f3f7fa; /* Fundo claro suave */
    color: #444; /* Texto com boa legibilidade */
    margin: 0;
    padding: 20px;
}

/* Estilos para os títulos */
h1, h2 {
    color: #2c3e50; /* Tom mais sóbrio e sofisticado */
    margin-bottom: 15px;
    font-weight: 600;
}

/* Container principal estilizado */
.container {
    max-width: 1000px;
    margin: 0 auto;
    background-color: #fff;
    border-radius: 16px; /* Bordas mais suaves */
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05); /* Sombra mais leve e espaçada */
    padding: 40px;
    transition: box-shadow 0.3s ease;
}

.container:hover {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); /* Sombra mais profunda ao passar o mouse */
}

/* Estilizando os formulários */
form {
    background-color: #f9fafc;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    margin-bottom: 40px;
}

/* Estilo para os labels */
label {
    font-weight: 500;
    margin-bottom: 8px;
    display: block;
    color: #2c3e50;
}

/* Campos de input e seleções */
input[type="text"],
input[type="number"],
input[type="file"],
select {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #dcdfe6;
    border-radius: 8px;
    transition: border-color 0.3s ease;
}

input[type="text"]:focus,
input[type="number"]:focus,
select:focus {
    border-color: #3498db;
    outline: none;
}

/* Botões */
button {
    background-color: #45624E;
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

button:hover {
    background-color: #273526;
    transform: translateY(-2px);
}

.btn-container {
    display: flex;
    gap: 15px;
}

/* Seção das perguntas */
.questions-section {
    margin-top: 40px;
}

/* Container das quizzes */
.quiz-container {
    padding: 20px;
    margin-bottom: 35px;
    border-radius: 12px;
    background-color: #f0f4f8;
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
}

/* Estilo para cada questão */
.question-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    border-bottom: 1px solid #e0e0e0;
    transition: background-color 0.3s ease;
}

.question-item:hover {
    background-color: #ecf0f1;
}

/* Estilo para as ações das questões */
.question-actions {
    display: flex;
    gap: 12px;
}

/* Estilo dos links de ação (editar e deletar) */
.question-actions a {
    padding: 10px 15px;
    border-radius: 8px;
    transition: background-color 0.3s ease;
    font-size: 0.95rem;
    font-weight: 500;
}

/* Estilo para editar e deletar */
.question-actions a.edit-btn {
    background-color: #2ecc71;
    color: #fff;
}

.question-actions a.delete-btn {
    background-color: #e74c3c;
    color: #fff;
}

/* Efeitos de hover nos botões de ação */
.question-actions a:hover {
    opacity: 0.85;
}

/* Estilo para alertas */
.alert {
    margin-top: 20px;
    padding: 15px;
    background-color: #f1c40f;
    color: #fff;
    border-radius: 8px;
    text-align: center;
}
.back-btn {
            background-color: #45624E;
            padding: 10px 20px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .back-btn:hover {
            background-color: #273526;
        }

    </style>
</head>
<body>

<div class="container">
    <h1>Criar Novo Simulado</h1>
    <form action="../admin/php/save_quiz.php" method="POST">
        <label for="quiz_name">Nome do Simulado:</label>
        <input type="text" id="quiz_name" name="quiz_name" required>
        <button type="submit">Criar Simulado</button>
    </form>

    <header>
        <h1>Administração de Simulado</h1>
    </header>

    <main>
        <?php
        include '../db/db_connection.php'; // Incluindo a conexão ao banco de dados

        // Consulta para buscar os quizzes
        $stmt = $pdo->query("SELECT * FROM quizzes");
        $quizzes = $stmt->fetchAll();
        ?>
    </main>

    <section class="form-section">
        <form action="php/save_question.php" method="POST" enctype="multipart/form-data" class="question-form" onsubmit="return setQuestionText();">
            <label for="existing_quiz">Selecionar Simulado Existente:</label>
            <select id="existing_quiz" name="existing_quiz">
                <option value="">Selecione um Simulado</option>
                <?php foreach ($quizzes as $quiz): ?>
                    <option value="<?= $quiz['id'] ?>"><?= htmlspecialchars($quiz['name']) ?></option>
                <?php endforeach; ?>
            </select>
            
            <label for="question_number">Número da Questão:</label>
            <input type="number" id="question_number" name="question_number" required>

            <label for="question">Questão:</label>
            <div class="btn-container">
                <button type="button" class="btn btn-secondary" onclick="executarComando('bold')"><i class="bi bi-type-bold"></i></button>
                <button type="button" class="btn btn-secondary" onclick="executarComando('italic')"><i class="bi bi-type-italic"></i></button>
                <button type="button" class="btn btn-secondary" onclick="executarComando('justifyLeft')"><i class="bi bi-text-left"></i></button>
                <button type="button" class="btn btn-secondary" onclick="executarComando('justifyCenter')"><i class="bi bi-text-center"></i></button>
                <button type="button" class="btn btn-secondary" onclick="executarComando('justifyRight')"><i class="bi bi-text-right"></i></button>
                <button type="button" class="btn btn-secondary" onclick="transformarTexto('uppercase')">A</button>
                <button type="button" class="btn btn-secondary" onclick="transformarTexto('lowercase')">a</button>
                <button type="button" class="btn btn-secondary" onclick="transformarTexto('capitalize')">Aa</button>
                <button type="button" class="btn btn-secondary" onclick="aumentarFonte()">A+</button>
                <button type="button" class="btn btn-secondary" onclick="diminuirFonte()">A-</button>
                <button type="button" class="btn btn-secondary" onclick="limparTexto()">Limpar</button>
            </div>
            <div id="editor" contenteditable="true" placeholder="Digite seu texto aqui..." required></div>
            <input type="hidden" id="question" name="question"> <!-- Campo para armazenar o texto da questão -->

            <label>Alternativas:</label>
                <div class="alternatives-container">
                    <?php 
                    $letras = ['A', 'B', 'C', 'D', 'E']; // Array com as letras
                    for ($i = 0; $i < 5; $i++): ?>
                        <div class="alternative-item">
                            <input type="text" name="alternative_<?php echo $letras[$i]; ?>" placeholder="Alternativa <?php echo $letras[$i]; ?>" required>
                            <label><input type="radio" name="correct" value="<?php echo $letras[$i]; ?>" required> Correta</label>
                        </div>
                    <?php endfor; ?>
                </div>

            <button type="submit">Adicionar Questão</button>
        </form>
    </section>

     <!-- Formulário para Configurar o Tempo do Quiz -->
     <section class="time-section">
        <h2>Configurar Tempo do Simulado</h2>
        <form action="php/save_time.php" method="POST" class="time-form">
            <label for="quiz_id">Selecionar Simulado:</label>
            <select id="quiz_id" name="quiz_id" required>
                <option value="">Selecione um Simulado</option>
                <?php foreach ($quizzes as $quiz): ?>
                    <option value="<?= $quiz['id'] ?>"><?= htmlspecialchars($quiz['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="quiz_hours">Horas:</label>
            <input type="number" name="quiz_hours" id="quiz_hours" value="0" min="0">

            <label for="quiz_minutes">Minutos:</label>
            <input type="number" name="quiz_minutes" id="quiz_minutes" value="0" min="0">

            <label for="quiz_seconds">Segundos:</label>
            <input type="number" name="quiz_seconds" id="quiz_seconds" value="0" min="0">

            <button type="submit">Salvar Tempo</button>
        </form>

        <script>
            // Validação para garantir que um quiz seja selecionado antes de salvar o tempo
            document.querySelector('.time-form').addEventListener('submit', function(event) {
                const quizSelector = document.getElementById('quiz_id');
                const selectedQuizId = quizSelector.value;

                if (!selectedQuizId) {
                    alert('Por favor, selecione um quiz.');
                    event.preventDefault(); // Impede o envio do formulário
                }
            });
        </script>
    </section>


    <section class="questions-section">
    <h2>Questões Cadastradas</h2>
    <div class="questions-list">
    <?php
    // Consulta para buscar as questões agrupadas por quiz
    foreach ($quizzes as $quiz) {
        echo '<div class="quiz-container">';
        echo '<h3>' . htmlspecialchars($quiz['name']) . '</h3>';
        
        // Formulário para excluir o quiz
        echo '<form action="php/delete_quiz.php" method="POST" onsubmit="return confirm(\'Tem certeza que deseja deletar este simulado?\');">';
        echo '<input type="hidden" name="quiz_id" value="' . $quiz['id'] . '">';
        echo '<button type="submit" class="btn delete-btn btn-danger">Deletar simulado</button>';
        echo '</form>';

        echo '<div class="questions-list">';

        // Buscando questões do quiz atual
        $query = $pdo->prepare('SELECT * FROM questions WHERE quizzes_id = :quiz_id ORDER BY question_number ASC');
        $query->execute(['quiz_id' => $quiz['id']]);
        $questions = $query->fetchAll();

        if ($questions) {
            foreach ($questions as $row) {
                echo '<div class="question-item">';
                echo '<div class="question-content">';
                echo '<p><strong>Questão ' . htmlspecialchars($row['question_number']) . ':</strong> ' . htmlspecialchars_decode($row['question_text']) . '</p>';

                if (!empty($row['photo'])) {
                    echo '<img src="/Home/img/' . htmlspecialchars($row['photo']) . '" alt="Imagem da Questão" style="max-width: 200px; height: auto;">';
                }

                echo '</div>';
                echo '<div class="question-actions">';
                echo '<a href="php/edit_question.php?id=' . $row['id'] . '" class="btn edit-btn btn-info">Editar</a>';
                echo '<a href="php/delete_question.php?id=' . $row['id'] . '" class="btn delete-btn btn-danger" onclick="return confirm(\'Tem certeza que deseja deletar esta questão?\');">Deletar</a>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>Nenhuma questão encontrada para este quiz.</p>';
        }

        echo '</div>'; // questions-list
        echo '</div>'; // quiz-container

        
    }
    ?>
    </div>
</section>

</div>
<script>
// Funções JavaScript como antes
function limparTexto() {
    const selection = window.getSelection();
    if (selection.rangeCount > 0) {
        const range = selection.getRangeAt(0);
        range.deleteContents();
    }
}

function transformarTexto(tipo) {
    const selection = window.getSelection();
    const range = selection.getRangeAt(0);
    const span = document.createElement("span");

    switch (tipo) {
        case 'uppercase':
            span.style.textTransform = 'uppercase';
            break;
        case 'lowercase':
            span.style.textTransform = 'lowercase';
            break;
        case 'capitalize':
            span.style.textTransform = 'capitalize';
            break;
    }

    range.surroundContents(span);
    selection.removeAllRanges();
    selection.addRange(range);
}

function executarComando(comando) {
    document.execCommand(comando, false, null);
}

function aumentarFonte() {
    const selection = window.getSelection();
    if (selection.rangeCount > 0) {
        const range = selection.getRangeAt(0);
        const selectedText = range.toString();
        const span = document.createElement("span");
        span.style.fontSize = "larger";

        span.appendChild(document.createTextNode(selectedText));
        range.deleteContents();
        range.insertNode(span);
    }
}

function diminuirFonte() {
    const selection = window.getSelection();
    if (selection.rangeCount > 0) {
        const range = selection.getRangeAt(0);
        const selectedText = range.toString();
        const span = document.createElement("span");
        span.style.fontSize = "smaller";

        span.appendChild(document.createTextNode(selectedText));
        range.deleteContents();
        range.insertNode(span);
    }
}
</script>
</body>
</html>












    