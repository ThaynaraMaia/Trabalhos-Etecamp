<!DOCTYPE html>
<html lang="pt-BR">

<?php
session_start();
include_once '../../../../classes/class_IRepositorioUsuarios.php';
include_once '../../../../classes/class_IRepositorioMemoria.php';

$id = $_SESSION['id_usuario'];
// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);

// Inicializar variáveis
$melhor_tempo = null;
$melhor_tempo_formatado = '';

// Verificar se o repositório de memória está disponível e obter o melhor tempo
if (isset($respositorioMemoria)) {
  $melhor_tempo_result = $respositorioMemoria->obterMelhorTempoUsuario($id);

  // Verificar se o resultado é numérico (não array ou false)
  if (is_numeric($melhor_tempo_result)) {
    $melhor_tempo = (int)$melhor_tempo_result;

    // Formatar o melhor tempo para exibição
    $minutos = floor($melhor_tempo / 60);
    $segundos = $melhor_tempo % 60;
    $melhor_tempo_formatado = sprintf('%d:%02d', $minutos, $segundos);
  }
}

// Foto padrão se não tiver
// $foto = !empty($dados['foto']) ? $dados['foto'] : '../../../frontend/public/img/fotoperfil.png'; 
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jogo do Termo</title>

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
      background: #045A94;
      background: radial-gradient(circle, rgba(4, 90, 148, 1) 0%, rgba(56, 160, 221, 1) 50%, #81c0e9 100%);
      color: #fff;
      padding: 10px;
      box-sizing: border-box;
    }

    #game-container {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 100px;
      margin: 20px 0;
      flex-wrap: wrap;
      /* Permite quebra em telas menores */
    }

    #game-board {
      display: grid;
      grid-template-rows: repeat(6, 1fr);
      gap: 6px;
      max-height: 60vh;
      max-width: 350px;
      /* Limita largura para caber lado a lado */
    }

    .row {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 6px;
    }

    .tile {
      width: 70px;
      height: 70px;
      border-radius: 8px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
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
      /* Reduzido para caber lado a lado */
      margin-bottom: 10px;
      max-height: 60vh;
      /* Limita altura para alinhar com o board */
    }

    .key {
      /* padding: 20px 16px; */
      height: 60px;
      width: 60px;
      border-radius: 6px;
      border: none;
      background-color: rgba(255, 255, 255, 0.2);
      color: #fff;
      font-size: 14px;
      /* Reduzido para caber */
      cursor: pointer;
      transition: transform 0.1s, background 0.2s;
      text-transform: uppercase;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.4);
      min-height: 40px;
      /* Altura mínima para caber */
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
      font-size: 1.2em;
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
      font-size: 18px;
      cursor: pointer;
      background-color: #81c0e9;
      color: #045A94;
      transition: background 0.2s;
      margin-top: 2rem;
    }

    .btn:hover {
      background-color: #38a0dd;
    }

    /* Ajustes para caber na tela */
    #game-board,
    #keyboard {
      flex-shrink: 0;
    }

    /* Responsivo para telas menores */
    @media (max-width: 768px) {
      #game-container {
        flex-direction: column;
        align-items: center;
      }

      #keyboard {
        max-width: 100%;
        order: 2;
        /* Coloca teclado abaixo em mobile */
      }

      #game-board {
        order: 1;
      }
    }
  </style>
</head>

<body>

  <header class="cabecalho" style="background: #c6e1fe; border: 2px solid; border-color: #38a0dd; border-radius: 10px; margin-top: 3rem;">
    <span class="jogador" style="color: #045a94; font-family:'Texto';">
      <?php echo htmlspecialchars($_SESSION['nome']); ?>
    </span>
    <div class="timer-container" style="color: #045a94; font-family:'Texto';">
      <span style="font-family:'Texto';">Tempo: </span>
      <span class="timer" id="timer">00</span>
    </div>
  </header>

  <div id="game-container">
    <div id="game-board" style="font-family:'Texto';"></div>
    <div id="keyboard" style="font-family:'Texto';"></div>
  </div>

  <div id="message" style="font-family:'Texto';"></div>
  <div class="btn-container" id="buttons" style="display:none;">
    <button class="btn" onclick="location.reload()" style="font-family:'Texto';">Jogar novamente</button>
    <button class="btn" onclick="window.location.href='pagina_de_jogos.html'" style="font-family:'Texto';"> <a href="../jogos.php" class="btn" style="text-decoration: none;">Voltar à página de jogos</a> </button>
  </div>

  <script>
    const nomeJogador = <?= json_encode($_SESSION['nome']); ?>;

    const palavras = ["ONDAS", "PEIXE", "AREIA", "CORAL"];
    const semana = Math.floor(Date.now() / (1000 * 60 * 60 * 24 * 7));
    const palavraSecreta = palavras[semana % palavras.length];

    const board = document.getElementById("game-board");
    const keyboard = document.getElementById("keyboard");
    const message = document.getElementById("message");
    const timerDisplay = document.getElementById("timer");
    const buttons = document.getElementById("buttons");

    let currentRow = 0;
    let currentCol = 0;
    let gameOver = false;
    let startTime = Date.now();
    let timerInterval = setInterval(updateTimer, 1000);

    function formatTime(elapsed) {
      const minutes = Math.floor(elapsed / 60);
      const seconds = elapsed % 60;
      return `${minutes}:${seconds.toString().padStart(2, '0')}`;
    }

    function updateTimer() {
      if (!gameOver) {
        const elapsed = Math.floor((Date.now() - startTime) / 1000);
        timerDisplay.textContent = `${formatTime(elapsed)}`;
      }
    }

    for (let i = 0; i < 6; i++) {
      const row = document.createElement("div");
      row.className = "row";
      for (let j = 0; j < 5; j++) {
        const tile = document.createElement("div");
        tile.className = "tile";
        row.appendChild(tile);
      }
      board.appendChild(row);
    }

    const keyboardLayout = [
      "qwertyuiop".split(""),
      "asdfghjkl".split(""),
      ["enter", ..."zxcvbnm".split(""), "del"]
    ];

    keyboardLayout.forEach(row => {
      row.forEach(key => {
        const button = document.createElement("button");
        button.textContent = key.toUpperCase();
        button.className = "key";
        button.onclick = () => handleKey(key.toLowerCase());
        keyboard.appendChild(button);
      });
    });

    function handleKey(key) {
      if (gameOver) return;

      if (key === "enter") {
        submitGuess();
        return;
      }
      if (key === "del") {
        if (currentCol > 0) {
          currentCol--;
          board.children[currentRow].children[currentCol].textContent = "";
        }
        return;
      }
      if (currentCol < 5 && /^[a-z]$/.test(key)) {
        board.children[currentRow].children[currentCol].textContent = key.toUpperCase();
        currentCol++;
      }
    }

    function submitGuess() {
      if (currentCol < 5) {
        message.textContent = "Digite uma palavra completa!";
        return;
      }

      const row = board.children[currentRow];
      let guess = "";
      for (let i = 0; i < 5; i++) guess += row.children[i].textContent;
      guess = guess.toUpperCase();

      for (let i = 0; i < 5; i++) {
        const tile = row.children[i];
        const letter = guess[i];
        const keyButton = [...document.querySelectorAll(".key")].find(b => b.textContent.toLowerCase() === letter.toLowerCase());

        if (letter === palavraSecreta[i]) {
          tile.classList.add("correct");
          if (keyButton) keyButton.classList.add("correct");
        } else if (palavraSecreta.includes(letter)) {
          tile.classList.add("present");
          if (keyButton && !keyButton.classList.contains("correct")) keyButton.classList.add("present");
        } else {
          tile.classList.add("absent");
          if (keyButton && !keyButton.classList.contains("correct") && !keyButton.classList.contains("present")) keyButton.classList.add("absent");
        }
      }

      currentRow++;
      currentCol = 0;

      if (guess === palavraSecreta) {
        gameOver = true;
        clearInterval(timerInterval);
        const elapsed = Math.floor((Date.now() - startTime) / 1000);
        showEndGameMessage(`Parabéns ${nomeJogador} 🎉, você acertou a palavra da semana! O seu tempo foi de ${formatTime(elapsed)}`);
      } else if (currentRow === 6) {
        gameOver = true;
        clearInterval(timerInterval);
        showEndGameMessage(`Não foi dessa vez 🙁.`);
      } else {
        message.textContent = "Continue!";
      }
    }

    function showEndGameMessage(msg) {
    
      message.textContent = msg;
      board.style.display = 'none';
      keyboard.style.display = 'none';
      timerDisplay.style.display = 'none';
      buttons.style.display = 'flex';
      
    }

    // Captura teclado físico
    document.addEventListener('keydown', e => {
      if (gameOver) return;
      const key = e.key.toLowerCase();
      if (key === 'enter') handleKey('enter');
      else if (key === 'backspace') handleKey('del');
      else if (/^[a-z]$/.test(key)) handleKey(key);
    });
  </script>
</body>

</html>