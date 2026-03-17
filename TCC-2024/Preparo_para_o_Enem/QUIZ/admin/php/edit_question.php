<?php
include '../../db/db_connection.php';

// Verificar se o ID da pergunta foi fornecido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ../index.php');
    exit;
}

$question_id = $_GET['id'];

// Buscar a pergunta
$stmt = $pdo->prepare('SELECT * FROM questions WHERE id = :id');
$stmt->execute(['id' => $question_id]);
$question = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$question) {
    header('Location: ../index.php');
    exit;
}

// Buscar alternativas para a pergunta
$stmt = $pdo->prepare('SELECT * FROM alternatives WHERE question_id = :question_id');
$stmt->execute(['question_id' => $question_id]);
$alternatives = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verificar se há mensagem de erro
$error_message = isset($_GET['error']) ? 'Por favor, preencha todos os campos.' : '';

// Preencher o texto da questão com o que foi enviado, se existir
$question_text = isset($_POST['question_text']) ? $_POST['question_text'] : $question['question_text'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <title>Editar Pergunta</title>
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
    <header>
        <h1>Editar Pergunta</h1>
    </header>
    <div class="container">
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="fixed-form-container">
            <form action="update_question.php" method="POST" enctype="multipart/form-data" onsubmit="return setQuestionText();">
                <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($question['id']); ?>">

                <label for="question_number">Número da Questão:</label>
                <input type="number" id="question_number" name="question_number" value="<?php echo htmlspecialchars($question['question_number']); ?>" required>

                <label for="question">Texto da Pergunta:</label>
                <div class="btn-container">
                    <button type="button" class="btn btn-secondary" onclick="executarComando('bold')"><i class="bi bi-type-bold"></i></button>
                    <button type="button" class="btn btn-secondary" onclick="executarComando('italic')"><i class="bi bi-type-italic"></i></button>
                    <button type="button" class="btn btn-secondary" onclick="executarComando('justifyLeft')"><i class="bi bi-text-left"></i></button>
                    <button type="button" class="btn btn-secondary" onclick="executarComando('justifyCenter')"><i class="bi bi-text-center"></i></button>
                    <button type="button" class="btn btn-secondary" onclick="executarComando('justifyRight')"><i class="bi bi-text-right"></i></button>
                    <button type="button" class="btn btn-secondary" onclick="transformarTexto('uppercase')">A</button>
                    <button type="button" class="btn btn-secondary" onclick="transformarTexto('lowercase')">a</button>
                    <button type="button" class="btn btn-secondary" onclick="transformarTexto('capitalize')">Aa</button>
                    <button type="button" class="btn btn-secondary" onclick="limparTexto()">Limpar</button>
                </div>
                <div id="editor" contenteditable="true" required><?php echo htmlspecialchars_decode($question_text); ?></div>
                <input type="hidden" id="question" name="question"> <!-- Campo oculto para a questão -->

                <div class="image-section">
                    <label>Gerenciamento de Imagem:</label>
                    
                    <!-- Exibe a imagem atual se existir -->
                    <?php if (!empty($question['photo'])): ?>
                        <div>
                            <label>Imagem Atual:</label>
                            <img src="../uploads/<?php echo htmlspecialchars($question['photo']); ?>" alt="Imagem da Questão">
                            <label><input type="checkbox" name="delete_image" value="1"> Excluir esta imagem</label>
                        </div>
                    <?php endif; ?>
                    
                    <label for="photo">Atualizar Imagem da Questão (opcional):</label>
                    <input type="file" id="photo" name="photo" accept="image/*">
                </div>

                <h3>Alternativas</h3>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="alternative-item">
                        <label for="alternative_<?php echo $i; ?>">Alternativa <?php echo $i; ?>:</label>
                        <input type="text" id="alternative_<?php echo $i; ?>" name="alternative_<?php echo $i; ?>"
                            value="<?php echo isset($alternatives[$i-1]) ? htmlspecialchars($alternatives[$i-1]['alternative_text']) : ''; ?>" required>
                        <input type="checkbox" id="correct_<?php echo $i; ?>" name="correct_<?php echo $i; ?>"
                            <?php echo isset($alternatives[$i-1]) && $alternatives[$i-1]['is_correct'] ? 'checked' : ''; ?>>
                        <label for="correct_<?php echo $i; ?>">Correta</label>
                        <input type="hidden" name="alternative_id_<?php echo $i; ?>" value="<?php echo isset($alternatives[$i-1]) ? htmlspecialchars($alternatives[$i-1]['id']) : ''; ?>">
                    </div>
                <?php endfor; ?>

                <button type="submit">Salvar Alterações</button>
            </form>
            <a href="../index.php" class="button">Voltar</a>
        </div>

    </div>

    <script>
        function limparTexto() {
            const selection = window.getSelection();
            if (selection.rangeCount > 0) {
                const range = selection.getRangeAt(0);
                range.deleteContents(); // Limpa o texto selecionado
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

            range.surroundContents(span); // Aplica a transformação ao texto selecionado
            selection.removeAllRanges(); // Remove a seleção atual
            selection.addRange(range); // Reaplica a seleção
        }

        function executarComando(comando) {
            document.execCommand(comando, false, null);
        }

        // Função para definir o texto da questão no campo oculto antes do envio
        function setQuestionText() {
            const editor = document.getElementById('editor');
            const questionInput = document.getElementById('question');
            questionInput.value = editor.innerHTML; // Copia o conteúdo do editor para o campo oculto

            // Validação: garantir que o campo não está vazio
            if (!questionInput.value.trim()) {
                alert("O texto da pergunta não pode estar vazio.");
                return false; // Cancela o envio do formulário
            }

            return true; // Permite o envio do formulário
        }
    </script>
</body>
</html>
