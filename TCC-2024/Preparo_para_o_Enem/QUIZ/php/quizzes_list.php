<?php
include '../db/db_connection.php'; // Ajuste o caminho conforme necessário

if (!isset($pdo)) {
    die("A conexão com o banco de dados falhou.");
}

// Consulta para buscar os quizzes e o total de questões
$query = $pdo->query("
    SELECT q.*, COUNT(ques.id) AS total_questions 
    FROM quizzes q
    LEFT JOIN questions ques ON ques.quizzes_id = q.id
    GROUP BY q.id
"); // Exemplo de consulta
$quizzes = $query->fetchAll(PDO::FETCH_ASSOC);

// Função para formatar o tempo do quiz
function formatQuizTime($totalSeconds) {
    $hours = floor($totalSeconds / 3600);
    $minutes = floor(($totalSeconds % 3600) / 60);
    
    $formattedTime = '';

    if ($hours > 0) {
        $formattedTime .= $hours . ' hora' . ($hours > 1 ? 's' : '');
    }

    if ($minutes > 0) {
        if ($formattedTime !== '') {
            $formattedTime .= ' e ';
        }
        $formattedTime .= $minutes . ' minuto' . ($minutes > 1 ? 's' : '');
    }

    return $formattedTime ?: '0 minutos'; // Retorna '0 minutos' se não houver tempo
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../quiz/css/quizzes_list.css"> <!-- Ajuste o caminho conforme necessário -->
    <title>Lista de Quizzes</title>
    <!-- Adicionando Font Awesome para os ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <style>
        .back-btn {
            background-color: #fff;
            padding: 10px 20px;
            color: #272727;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .back-btn:hover {
            background-color: #f4f4f4;
        }
        .alert {
            background-color: #ffcc00; /* Cor de fundo amarelo */
            color: #272727; /* Cor do texto */
            padding: 10px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
        }
    </style>

<header>
    <div class="container">
        <h1>Lista de Simulados Disponíveis</h1>
        <a href="../../php/usuario_page.php" class="back-btn"><i class="fas fa-arrow-left"></i> Voltar</a>
    </div>
</header>

<main>
    <!-- Seção de Dicas para o ENEM -->
    <section class="tips-section">
        <h2>Dicas para Fazer o Simulado do ENEM</h2>
        <p>
            O ENEM é uma prova importante e, para ter um bom desempenho, é essencial que você esteja preparado e focado. 
            Trate este momento como se fosse realmente a prova do ENEM. Mantenha a calma e siga estas dicas para otimizar sua experiência:
        </p>
        <p><strong>Antes de começar, separe os seguintes itens:</strong></p>
        <ul class="tips-list">
            <li>
                <i class="fas fa-file-alt"></i> <strong>Uma folha</strong>: Para anotar seus cálculos e rascunhos. Isso ajuda a manter a organização e a clareza na hora de resolver as questões.
            </li>
            <li>
                <i class="fas fa-pencil-alt"></i> <strong>Um lápis ou caneta</strong>: Utilize um material de escrita que você se sinta confortável. Lembre-se de que a clareza da sua escrita é importante.
            </li>
            <li>
                <i class="fas fa-apple-alt"></i> <strong>Um lanchinho</strong>: Escolha um lanche saudável e energético para te manter focado. Barras de cereal ou frutas são ótimas opções!
            </li>
        </ul>
        <p>
            Lembre-se de que a concentração é a chave para o sucesso. Boa sorte e vamos lá, você consegue!
        </p>
    </section>
    <br>
    

    <section class="quizzes-section">
        <!-- Mensagem de atenção fora dos cards -->
        <div class="alert">
            Atenção: Ao clicar em "Iniciar", o tempo começará a contar e você não poderá abandonar o simulado.
        </div>

        <br>

        <div class="quizzes-container">
            <?php if (count($quizzes) > 0): ?>
                <?php foreach ($quizzes as $quiz): ?>
                    <div class="simulado-card">
                        <h3><?php echo htmlspecialchars($quiz['name']); ?></h3> <!-- Usando 'name' para o título -->
                        <p>Duração do Simulado: <?php echo formatQuizTime($quiz['quiz_time']); ?></p> <!-- Usando a função para formatar o tempo -->
                        <p>Total de questões: <?php echo $quiz['total_questions']; ?></p> <!-- Exibindo o total de questões -->
                        <a href="quiz_questions.php?quiz_id=<?php echo $quiz['id']; ?>" class="btn">Iniciar</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum quiz disponível no momento.</p>
            <?php endif; ?>
        </div>
    </section>

</main>

</body>
</html>


