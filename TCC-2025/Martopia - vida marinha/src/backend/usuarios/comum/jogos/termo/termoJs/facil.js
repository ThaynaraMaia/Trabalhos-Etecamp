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

// Cria board
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

// Cria teclado
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

// Timer
function formatTime(elapsed) {
    const minutes = Math.floor(elapsed/60);
    const seconds = elapsed % 60;
    return `${minutes}:${seconds.toString().padStart(2,'0')}`;
}
function updateTimer() {
    if(!gameOver) {
        const elapsed = Math.floor((Date.now() - startTime)/1000);
        timerDisplay.textContent = formatTime(elapsed);
    }
}

// Teclas
function handleKey(key) {
    if(gameOver) return;

    if(key === "enter") { submitGuess(); return; }
    if(key === "del") {
        if(currentCol > 0) {
            currentCol--;
            board.children[currentRow].children[currentCol].textContent = "";
        }
        return;
    }
    if(currentCol < 5 && /^[a-z]$/.test(key)) {
        board.children[currentRow].children[currentCol].textContent = key.toUpperCase();
        currentCol++;
    }
}

// Envio da tentativa
function submitGuess() {
    if(currentCol < 5) { message.textContent = "Digite uma palavra completa!"; return; }

    const row = board.children[currentRow];
    let guess = "";
    for(let i=0;i<5;i++) guess += row.children[i].textContent;
    guess = guess.toUpperCase();

    for(let i=0;i<5;i++) {
        const tile = row.children[i];
        const letter = guess[i];
        const keyButton = [...document.querySelectorAll(".key")].find(b => b.textContent.toUpperCase()===letter);

        if(letter === palavraSecreta[i]) {
            tile.classList.add("correct");
            if(keyButton) keyButton.classList.add("correct");
        } else if(palavraSecreta.includes(letter)) {
            tile.classList.add("present");
            if(keyButton && !keyButton.classList.contains("correct")) keyButton.classList.add("present");
        } else {
            tile.classList.add("absent");
            if(keyButton && !keyButton.classList.contains("correct") && !keyButton.classList.contains("present")) keyButton.classList.add("absent");
        }
    }

    currentRow++;
    currentCol=0;

    if(guess === palavraSecreta) {
        gameOver = true;
        clearInterval(timerInterval);
        showEndGameMessage(`Parabéns ${nomeJogador}!`, true);
    } else if(currentRow === 6) {
        gameOver = true;
        clearInterval(timerInterval);
        showEndGameMessage(`Não foi dessa vez.`, false);
    } else {
        message.textContent = "Continue!";
    }
}

// Salvar no banco
function salvarPartida(vitoria, tentativas, tempo) {
    fetch("salvar_partida.php", {
        method:"POST",
        headers: { "Content-Type":"application/json" },
        body: JSON.stringify({ usuario_id:usuarioId, palavra_id:palavraId, tentativas:tentativas, tempo:tempo, vitoria:vitoria })
    })
    .then(res=>res.text())
    .then(console.log)
    .catch(err=>console.error("Erro ao salvar partida:", err));
}

// Mensagem final
function showEndGameMessage(msg, vitoria) {
    salvarPartida(vitoria, currentRow);

    message.textContent = msg;
    board.style.display='none';
    keyboard.style.display='none';
    buttons.style.display='flex';
}

// Teclado físico
document.addEventListener('keydown', e => {
    if(gameOver) return;
    const key = e.key.toLowerCase();
    if(key==='enter') submitGuess();
    else if(key==='backspace') handleKey('del');
    else if(/^[a-z]$/.test(key)) handleKey(key);
});
