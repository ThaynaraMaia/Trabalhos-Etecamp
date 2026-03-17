const questionElement = document.getElementById("question");
const answerButtons = document.getElementById("answer-buttons");
const nextButton = document.getElementById("next-btn"); 
const timer = document.querySelector(".timer");

let currentQuestionIndex = 0;
let score = 0;
let segundos = 0;
let loop;

// Array para armazenar as 10 perguntas do quiz atual
let currentQuizQuestions = [];

// Função para embaralhar o array (Fisher-Yates shuffle)
function embaralharArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
}

function comecaTempo() {
    clearInterval(loop);
    segundos = 0;
    timer.innerHTML = "0:00";

    loop = setInterval(() => {
        segundos++;
        let min = Math.floor(segundos / 60);
        let sec = segundos % 60;
        timer.innerHTML = `${min}:${sec.toString().padStart(2,"0")}`;
    }, 1000);
}

function startQuiz() {
    currentQuestionIndex = 0;
    score = 0;
    
    // Lógica para selecionar 10 perguntas aleatórias
    embaralharArray(questions);
    currentQuizQuestions = questions.slice(0, 20);

    comecaTempo();
    nextButton.innerHTML = "Próxima";
    nextButton.style.display = "none";
    showQuestion();
}

function resetState() {
    nextButton.style.display = "none";
    while(answerButtons.firstChild) {
        answerButtons.removeChild(answerButtons.firstChild);
    }
}

function showQuestion() {
    resetState();
    const currentQuestion = currentQuizQuestions[currentQuestionIndex];
    const questionNo = currentQuestionIndex + 1;
    questionElement.innerHTML = questionNo + ". " + currentQuestion.question;

    currentQuestion.answers.forEach((answer) => {
        const button = document.createElement("button");
        button.innerHTML = answer.text;
        button.dataset.id = answer.id;
        button.classList.add("btn");
        button.addEventListener("click", selectAnswer);
        answerButtons.appendChild(button);
    });
}

function selectAnswer(e) {
    const answers = currentQuizQuestions[currentQuestionIndex].answers;
    const correctAnswer = answers.find(a => a.correct);

    const selectedBtn = e.target;
    const isCorrect = selectedBtn.dataset.id === correctAnswer.id;

    if(isCorrect) {
        selectedBtn.classList.add("correct");
        score++;
    } else {
        selectedBtn.classList.add("incorrect");
        Array.from(answerButtons.children).forEach(button => {
            if(button.dataset.id === correctAnswer.id) {
                button.classList.add("correct");
            }
        });
    }

    Array.from(answerButtons.children).forEach(button => button.disabled = true);
    nextButton.style.display = "block";
}

function showScore() {
    clearInterval(loop); 
    resetState();

    const tempoElement = document.querySelector(".timer");
    const tempoJogo = tempoElement.innerHTML;

    // OBTER DIFICULDADE E SALVAR RESULTADO - ESSENCIAL!
    const dificuldadeQuiz = document.getElementById("dificuldade-quiz").value;
    salvarResultado(score, segundos, dificuldadeQuiz);

    questionElement.innerHTML = `Parabéns ${nomeJogador}! Você acertou ${score} de ${currentQuizQuestions.length} questões.`;
    const mensagemTempo = document.createElement("p");
    mensagemTempo.innerHTML = `Tempo de jogo: ${tempoJogo}`;
    questionElement.appendChild(mensagemTempo);

    const finalButtonsContainer = document.createElement("div");
    finalButtonsContainer.classList.add("final-buttons");

    const botaoRedirecionar = document.createElement("button");
    botaoRedirecionar.innerHTML = "Voltar à Página de Jogos";
    botaoRedirecionar.classList.add("btn");
    botaoRedirecionar.addEventListener("click", () => {
        window.location.href = "../jogos.php";
    });

    finalButtonsContainer.appendChild(botaoRedirecionar);
    questionElement.appendChild(finalButtonsContainer);
    
    nextButton.innerHTML = "Reiniciar"; 
    nextButton.style.display = "block";
}

async function salvarResultado(acertos, tempo, dificuldade) {
    try {
        const response = await fetch('./salvar_ranking.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id_usuario: idUsuario,
                acertos: acertos,
                tempo_segundos: tempo,
                dificuldade: dificuldade
            }),
        });

        const result = await response.json();
        if (result.success) {
            console.log("Ranking salvo com sucesso!");
        } else {
            console.error("Falha ao salvar o ranking:", result.message);
        }
    } catch (error) {
        console.error('Erro de conexão ao tentar salvar o ranking:', error);
    }
}

function handleNextButton() {
    currentQuestionIndex++;
    if(currentQuestionIndex < currentQuizQuestions.length) {
        showQuestion();
    } else {
        showScore();
    }
}

nextButton.addEventListener("click", () => {
    if(currentQuestionIndex < currentQuizQuestions.length) {
        handleNextButton();
    } else {
        startQuiz();
    }
});

// Inicia o quiz
startQuiz();