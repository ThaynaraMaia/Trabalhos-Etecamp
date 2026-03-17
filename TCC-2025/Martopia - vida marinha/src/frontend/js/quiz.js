const questions = [
    {
        question: "Qual é o maior animal do oceano?",
        answers: [
            { id: 1, text: "Tubarão Branco", correct:false },
            { id: 2, text: "Baleia Azul", correct:true },
            { id: 3, text: "Tartaruga", correct:false },
            { id: 4, text: "Golfinho", correct:false },
        ],
    },
    {
        question: "Qual é uma das principais ameaças à biodiversidade marinha no litoral de São Paulo?",
        answers: [
            { id: 1, text: "Poluição por esgoto doméstico e industrial", correct:true },
            { id: 2, text: "Erupções vulcânicas submarinas", correct:false },
            { id: 3, text: "Escassez natural de nutrientes na água", correct:false },
            { id: 4, text: "Presença de recifes de coral tóxicos", correct:false },
        ],
    },
    {
        question: "O que é a Baía de Santos considerada em termos ecológicos?",
        answers: [
            { id: 1, text: "Uma zona morta sem biodiversidade", correct:false },
            { id: 2, text: "Um deserto oceânico", correct:false },
            { id: 3, text: "Um arquipélago de origem vulcânica", correct:false },
            { id: 4, text: "Uma área estuarina com grande importância para a reprodução de espécies marinhas", correct:true },
        ],
    },
    {
        question: "Qual dessas espécies é uma das mais comuns nos costões rochosos do litoral paulista?",
        answers: [
            { id: 1, text: "Polvo-gigante-do-Pacífico", correct:false },
            { id: 2, text: "Marisco", correct:true },
            { id: 3, text: "Estrela-do-mar-azul", correct:false },
            { id: 4, text: "Cavalo-marinho", correct:false },
        ],
    },
];

const questionElement = document.getElementById("question");
const answerButtons = document.getElementById("answer-buttons");
const nextButton = document.getElementById("next-btn");
const spanJogador = document.querySelector(".jogador"); 
const timer = document.querySelector(".timer");

let currentQuestionIndex = 0;
let score = 0;
let segundos = 0;
let loop;

// pega o nome salvo no localStorage
const nomeJogador = localStorage.getItem("jogador") || "Jogador";
spanJogador.innerHTML = `Jogador: ${nomeJogador}`;

// função para iniciar o tempo
function comecaTempo() {
    loop = setInterval(() => {
        segundos++;

        let min = Math.floor(segundos / 60);
        let sec = segundos % 60;

        timer.innerHTML = `${min}:${sec.toString().padStart(2, "0")}`;
    }, 1000);
}

function startQuiz() {
    currentQuestionIndex = 0;
    score = 0;
    segundos = 0;
    timer.innerHTML = "0:00";
    clearInterval(loop);
    comecaTempo();
    nextButton.innerHTML = "Próxima";
    showQuestion();
}

function resetState() {
    nextButton.style.display = "none";
    while (answerButtons.firstChild) {
        answerButtons.removeChild(answerButtons.firstChild);
    }
}

function showQuestion() {
    resetState();
    let currentQuestion = questions[currentQuestionIndex];
    let questionNo = currentQuestionIndex + 1;
    questionElement.innerHTML = questionNo + ". " + currentQuestion.question;

    currentQuestion.answers.forEach((answer) => {
        const button = document.createElement("button");
        button.innerHTML = answer.text;
        button.dataset.id = answer.id;
        button.addEventListener("click", selectAnswer);
        button.classList.add("btn");
        answerButtons.appendChild(button);
    });
}

function selectAnswer(e) {
    answers = questions[currentQuestionIndex].answers;
    const correctAnswer = answers.filter((answer) => answer.correct == true)[0];

    const selectedBtn = e.target;
    const isCorrect = selectedBtn.dataset.id == correctAnswer.id;
    if (isCorrect) {
        selectedBtn.classList.add("correct");
        score++;
    } else {
        selectedBtn.classList.add("incorrect");
    }

    Array.from(answerButtons.children).forEach((button) => {
        button.disabled = true;
    });

    nextButton.style.display = "block";
}

function showScore() {
    resetState();
    clearInterval(loop); 

    const tempoFinal = timer.innerHTML;

    questionElement.innerHTML = `${nomeJogador},   você acertou ${score} de ${questions.length}! ⏱️ O seu Tempo de ogo foi: ${tempoFinal}`;
    nextButton.innerHTML = `Jogar Novamente`;
    nextButton.style.display = "block";
}

function handleNextButton() {
    currentQuestionIndex++;
    if (currentQuestionIndex < questions.length) {
        showQuestion();
    } else {
        showScore();
    }
}

nextButton.addEventListener("click", () => {
    if (currentQuestionIndex < questions.length) {
        handleNextButton();
    } else {
        startQuiz();
    }
});

startQuiz();
