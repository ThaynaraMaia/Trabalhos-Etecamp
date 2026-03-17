<!DOCTYPE html>
<html lang="pt-BR">
<?php
session_start();
include_once '../../../../classes/class_IRepositorioUsuarios.php';
include_once '../../../../classes/class_IRepositorioMemoria.php';
include_once '../../../../classes/class_Conexao.php';

// Dados do usuário
$id = $_SESSION['id_usuario'];
$nomeJogador = $_SESSION['nome'];

// Conexão
$conn = new Conexao('localhost', 'root', '', 'vidamarinha');
$conn->conectar();

// Buscar todas as palavras fáceis
$sql = "SELECT id, palavra FROM termo WHERE dificuldade = 1 ORDER BY id ASC";
$resultado = $conn->executarQuery($sql);

$palavras = [];
while ($row = mysqli_fetch_assoc($resultado)) {
    $palavras[] = $row;
}

// Palavra da semana (determinística)
$semana = floor(time() / (60 * 60 * 24 * 7));
$indice = $semana % count($palavras);
$palavraBanco = $palavras[$indice];
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo do Termo - Fácil</title>

    <link rel="stylesheet" href="../Memoria/facilM.css">
    <link rel="stylesheet" href="../../../../../frontend/public/css/baseGeral.css">

    <style>
        @font-face {
            font-family: 'Texto';
            src: url('../../../../../frontend/fontes/Texto.otf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            height: auto;
            margin: 0;
            background: #38a0dd;
            background: radial-gradient(circle, rgba(56, 160, 221, 1) 0%, #c6e1fe 100%);
            color: #fff;
            padding: 10px;
            box-sizing: border-box;
        }

        #game-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        #game-board {
            display: grid;
            grid-template-rows: repeat(6, 1fr);
            gap: 6px;
            max-height: 60vh;
            max-width: 350px;
            margin-right: 10rem;
        }

        .row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 6px;
        }

        .tile {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            font-weight: 700;
            background-color: rgba(255, 255, 255, 0.05);
            text-transform: uppercase;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            transition: all 0.2s;
        }

        .tile.correct {
            background-color: #6aaa64;
            border: none;
            color: white;
        }

        .tile.present {
            background-color: #c9b458;
            border: none;
            color: white;
        }

        .tile.absent {
            background-color: #3a3a3c;
            border: none;
            color: white;
        }

        #keyboard {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            grid-template-rows: repeat(3, 1fr);
            gap: 5px;
            max-width: 400px;
            margin-right: 20rem;
            margin-bottom: 10px;
            max-height: 60vh;
        }

        .key {
            height: 80px;
            width: 80px;
            border-radius: 6px;
            border: none;
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 25px;
            cursor: pointer;
            transition: transform 0.1s, background 0.2s;
            text-transform: uppercase;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.4);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4);
            min-height: 40px;
        }

        .key:hover {
            background-color: rgba(255, 255, 255, 0.35);
            transform: scale(1.02);
        }

        .key.correct {
            background-color: #6aaa64;
        }

        .key.present {
            background-color: #c9b458;
        }

        .key.absent {
            background-color: #3a3a3c;
        }

        #message {
            margin-top: 5px;
            font-size: 1.8em;
            width: 550px;
            font-family: 'Texto';
            min-height: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        #timer {
            font-size: 1em;
            font-weight: bold;
        }

        .btn-container {
            margin-top: 10px;
            display: flex;
            gap: 10px;
            flex-shrink: 0;
        }

        .btn {
            padding: 16px 32px;
            border: none;
            border-radius: 12px;
            font-size: 1.2rem;
            font-family: 'Texto';
            font-weight: 700;
            cursor: pointer;
            background-color: #81c0e9;
            color: #045A94;
            transition: background 0.2s;
            margin-top: 2rem;
        }

        .btn:hover {
            background-color: #38a0dd;
        }

        #game-board,
        #keyboard {
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            #game-container {
                flex-direction: column;
                align-items: center;
            }

            #keyboard {
                max-width: 100%;
                order: 2;
            }

            #game-board {
                order: 1;
            }
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
    </style>
</head>

<body>

    <header class="cabecalho" style="background: #c6e1fe; border: 2px solid #38a0dd; border-radius: 10px; margin-top: 3rem; padding: 1rem; text-align: center;">
        <span class="jogador" style="color: #045a94; font-family:'Texto'; font-size:1.5rem; font-weight: 700;">
            <?php echo htmlspecialchars($nomeJogador); ?>
    </span>

        <!-- Botão Voltar -->
        <a href="./niveisT.php" class="btn-voltar" style="display: inline-block; margin-top: 1rem;">Voltar</a>
    </header>


    <div id="game-container">
        <div id="game-board" style="font-family:'Texto';"></div>
        <div id="keyboard" style="font-family:'Texto';"></div>
    </div>

    <div id="message" style="font-family:'Texto';"></div>

    <div class="btn-container" id="buttons" style="display:none;">
        <button class="btn" onclick="location.reload()" style="font-family:'Texto';">Jogar Novamente</button>
        <button class="btn" style="font-family:'Texto';" onclick="window.location.href='../jogos.php'">Voltar à página de jogos</button>
    </div>

    <script>
        const palavraId = <?= $palavraBanco['id'] ?>;
        const palavraSecreta = "<?= strtoupper($palavraBanco['palavra']) ?>";
        const nomeJogador = <?= json_encode($nomeJogador); ?>;
        const usuarioId = <?= (int)$id; ?>;
    </script>
    <script src="./termoJs/facil.js"></script>
</body>

</html>