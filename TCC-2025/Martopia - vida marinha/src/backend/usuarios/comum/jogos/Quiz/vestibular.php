<!DOCTYPE html>
<html lang="pt-br">

<?php

session_start();

if (!$_SESSION['tipo']  && !$_SESSION['logado']) {
    header('Location:../../../../../frontend/home.php');
}

include_once '../../../../classes/class_IRepositorioUsuarios.php';
include_once '../../../../classes/class_IRepositorioQuiz.php';
$id = $_SESSION['id_usuario'];
// $nivel = isset($_GET['nivel']) ? (int)$_GET['nivel'] : 1;
$nivel = 4;
// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);
$perguntas = $respositorioQuiz->listarPerguntasPorNivel($nivel);
// Foto padrão se não tiver
// $foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png'; 

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz - Projeto Martopia</title>
    <link rel="stylesheet" href="../../../../../frontend/public/css/baseGeral.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./quiz3.css">
</head>

<body>

    <div class="app">
           <div class="titulo-quiz">
            <h1 style="color: #045a94; font-size: 2rem;">
                Quiz - Nível Enem
            </h1>
            <a href="./niveis.php" class="btn-voltar">Voltar</a>
        </div>

        <header class="cabecalho" style="background: #c6e1fe; border-color: #38a0dd">
            <span class="jogador" style="color: #045a94; font-size: 1.2rem; font-family: 'Texto';"><?= $_SESSION['nome']; ?></span>
            <span style="color: #045a94; font-size: 1.2rem; font-family: 'Texto';">Tempo Jogando: <span class="timer">00</span></span>
        </header>

        <style>
            body {
                background: #38a0dd;
                background: radial-gradient(circle, rgba(56, 160, 221, 1) 0%, #c6e1fe 100%);
            }

            .btn {
                background: #c6e1fe;
                border-color: #38a0dd;
                transition: background-color 0.3s, color 0.3s, border-color 0.3s;
                font-size: 1.3rem;
                font-family: 'Texto';
            }

            .app h1 {
                color: #0a4d68;
                border-bottom: 2px solid #38a0dd;
                font-weight: 700;
                text-align: center;
                font-family: 'Titulo';
            }

            #btn {
                background: #c6e1fe;
                border-color: #38a0dd;
                transition: background-color 0.3s, color 0.3s, border-color 0.3s;
            }

            .btn:hover:not([disabled]) {
                background-color: #38a0dd;
                color: #e6f7ff;
                border-color: #045a94;
                box-shadow: 0 6px 12px rgba(0, 122, 255, 0.5);
            }

            .btn:disabled {
                cursor: not-allowed;
                opacity: 0.7;
                box-shadow: none;
            }

            #next-btn {
                background: #81c0e9;
            }

            #next-btn:hover {
                background-color: #38a0dd;
            }

            .correct {
                background-color: #4caf50;
                color: #e6f7e6;
                border-color: #388e3c;
                box-shadow: 0 0 10px #4caf50;
            }

            .incorrect {
                background-color: #e57373;
                color: #fff0f0;
                border-color: #d32f2f;
                box-shadow: 0 0 10px #e57373;
            }

            .titulo-quiz {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 15rem;
                margin-bottom: 1rem;
            }

            .btn-voltar {
                padding: 0.8rem 1.4rem;
                background: linear-gradient(135deg, #c6e1f6, #9fcaec);
                color: #045a94;
                font-weight: bold;
                text-transform: uppercase;
                font-size: 1.1rem;
                font-family: 'Texto', serif;
                border-radius: 25px;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
                text-decoration: none;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
            }

            .btn-voltar:hover {
                background: linear-gradient(135deg, #81C0E9, #38a0dd);
                transform: translateY(-2px);
                box-shadow: 0 6px 14px rgba(0, 0, 0, 0.35);
            }

            .timer {
                font-family: 'Texto';
                font-size: 1.2rem;
            }
        </style>

        <div class="quiz">
            <h2 id="question" style="color: #045a94; font-size: 1.6rem;  font-family: 'Texto';">Questão 01</h2>
            <div id="answer-buttons"  style="font-size: 1.2rem; font-family: 'Texto';"></div>
            <button id="next-btn" style="font-size: 1.2rem; font-family: 'Texto';">Próxima</button>
        </div>
        <input type="hidden" id="dificuldade-quiz" value="1">
    </div>
        <input type="hidden" id="dificuldade-quiz" value="4">
    </div>
    <script>
        const nomeJogador = <?= json_encode($_SESSION['nome']); ?>;
        const questions = <?= json_encode($perguntas, JSON_UNESCAPED_UNICODE); ?>;
        const idUsuario = <?= $_SESSION['id_usuario'] ?>;
    </script>

    <script defer src="./jsQuiz/quizEnem.js"></script>

</body>

</html>