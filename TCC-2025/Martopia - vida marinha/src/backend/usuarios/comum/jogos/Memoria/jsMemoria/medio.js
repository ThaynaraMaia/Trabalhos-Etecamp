const grid = document.querySelector('.grid');
const timer = document.querySelector('.timer');
const conteudo = document.querySelector('.conteudo');

const personagens = [
     'CartaMem07', 'CartaMem08', 'CartaMem09', 'CartaMem010', 'CartaMem011', 'CartaMem012',
];

let primeiroCard = null;
let segundoCard = null;
let segundos = 0;
let loop;
let bloqueiaClique = false;

// Obter dados do sessionStorage
const userId = sessionStorage.getItem('user_id');
let melhorTempo = sessionStorage.getItem('melhor_tempo');

const embaralhaArray = (array) => {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

const mostrarTelaFinal = (tempoFinal) => {
    // Esconder elementos do jogo
    if (grid) grid.style.display = "none";
    const cabecalhoEl = document.querySelector(".cabecalho");
    if (cabecalhoEl) cabecalhoEl.style.display = "none";

    // Esconder elementos opcionais (se existirem)
    const melhorTempoEl = document.querySelector(".melhor-tempo");
    if (melhorTempoEl) melhorTempoEl.style.display = "none";

    const semRecordeEl = document.querySelector(".sem-recorde");
    if (semRecordeEl) semRecordeEl.style.display = "none";

    // Pegar nome do jogador do elemento ou sessionStorage
    const jogadorEl = document.querySelector('.jogador');
    const nomeJogador = jogadorEl ? jogadorEl.textContent.trim() : 'Jogador';

    // Criar container da tela final
    const telaFinal = document.createElement("div");
    telaFinal.style.cssText = `
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 20px;
        margin: 0;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: transparent;
        z-index: 9999;
    `;

    // Card principal (estilo do print)
    const card = document.createElement("div");
    card.style.cssText = `
        background: linear-gradient(135deg, #e8f4fd 0%, #d4e9f7 100%);
        border-radius: 25px;
        padding: 40px 50px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        max-width: 600px;
        width: 90%;
        text-align: center;
        border: 3px solid #b8d9f0;
    `;

    // Título do jogo
    const tituloJogo = document.createElement("h2");
    tituloJogo.textContent = "Jogo da Memória - Nível Médio";
    tituloJogo.style.cssText = `
        color: #0b6bb5;
        font-size: 1.8em;
        margin: 0 0 30px 0;
        font-weight: bold;
        border-bottom: 3px solid #80c1ff;
        padding-bottom: 15px;
        font-family: 'Texto', sans-serif;
    `;

    // ⭐ AQUI ESTAVA FALTANDO: Container de informações (nome e tempo)
    const infoContainer = document.createElement("div");
    infoContainer.style.cssText = `
        background: white;
        border: 2px solid #80c1ff;
        border-radius: 15px;
        padding: 20px 30px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    `;

    // Nome do jogador
    const nomeContainer = document.createElement("div");
    nomeContainer.style.cssText = `
        display: flex;
        align-items: center;
        gap: 10px;
    `;
    nomeContainer.innerHTML = `
        <span style="color: #0b6bb5; font-weight: bold; font-size: 1.1em; font-family: 'Texto', sans-serif;">${nomeJogador}</span>
    `;

    // Tempo jogado
    const tempoContainer = document.createElement("div");
    tempoContainer.style.cssText = `
        display: flex;
        align-items: center;
        gap: 10px;
    `;
    tempoContainer.innerHTML = `
        <span style="color: #0b6bb5; font-weight: normal; font-family: 'Texto', sans-serif;">Tempo jogando:</span>
        <span style="color: #0b6bb5; font-weight: bold; font-size: 1.2em; font-family: 'Texto', sans-serif;">${tempoFinal}</span>
    `;

    infoContainer.appendChild(nomeContainer);
    infoContainer.appendChild(tempoContainer);

    // Mostrar tempo de jogo novamente
    const tempoJogoTexto = document.createElement("p");
    tempoJogoTexto.style.cssText = `
        color: #0b6bb5;
        font-size: 1.3em;
        margin: 15px 0 30px 0;
        font-weight: 600;
        font-family: 'Texto', sans-serif;
    `;
    tempoJogoTexto.textContent = `Tempo de jogo: ${tempoFinal}`;

    // Container de botões
    const botoesContainer = document.createElement("div");
    botoesContainer.style.cssText = `
        display: flex;
        flex-direction: column;
        gap: 15px;
        width: 100%;
        max-width: 350px;
        margin: 0 auto;
    `;

    // Função para criar botões
    const criarBotao = (texto, clickHandler, isPrimary = false) => {
        const botao = document.createElement("button");
        botao.textContent = texto;
        botao.style.cssText = `
            width: 100%;
            padding: 15px 30px;
            font-size: 1em;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            font-family: 'Texto', sans-serif;
            background: ${isPrimary ? '#4a9ee0' : '#89c4ed'};
            color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        `;

        botao.addEventListener("mouseover", () => {
            botao.style.transform = "translateY(-2px)";
            botao.style.boxShadow = "0 6px 15px rgba(0, 0, 0, 0.2)";
            botao.style.background = isPrimary ? '#3d8bc7' : '#6fb3e0';
        });
        
        botao.addEventListener("mouseout", () => {
            botao.style.transform = "translateY(0)";
            botao.style.boxShadow = "0 4px 10px rgba(0, 0, 0, 0.15)";
            botao.style.background = isPrimary ? '#4a9ee0' : '#89c4ed';
        });

        botao.addEventListener("click", clickHandler);
        return botao;
    };

    // Adicionar botões
    botoesContainer.appendChild(
        criarBotao("Voltar à Página de Jogos", () => window.location.href = "../jogos.php", true)
    );
    botoesContainer.appendChild(
        criarBotao("Jogar Novamente", () => location.reload(), false)
    );

    // Montar o card
    card.appendChild(tituloJogo);
    card.appendChild(infoContainer);  // ⭐ Agora o infoContainer existe!
    card.appendChild(tempoJogoTexto);
    card.appendChild(botoesContainer);

    telaFinal.appendChild(card);
    
    // Adicionar ao body para garantir visibilidade
    document.body.appendChild(telaFinal);
    
    // Log para debug
    console.log('✅ Tela final criada com sucesso!');
    console.log('👤 Nome do jogador:', nomeJogador);
    console.log('⏱️ Tempo final:', tempoFinal);
}

const checaFimDeJogo = () => {
    const disabledCards = document.querySelectorAll('.disabled_card');
    if (disabledCards.length === personagens.length * 2) {
        clearInterval(loop);
        const tempoFinal = timer.innerHTML;

        mostrarTelaFinal(tempoFinal);
        salvarResultado(segundos);
    }
}

const checaCards = () => {
    const primeiroPersonagem = primeiroCard.getAttribute('data-personagem');
    const segundoPersonagem = segundoCard.getAttribute('data-personagem');

    if (primeiroPersonagem === segundoPersonagem) {
        primeiroCard.firstChild.classList.add('disabled_card');
        segundoCard.firstChild.classList.add('disabled_card');
        primeiroCard = null;
        segundoCard = null;
        bloqueiaClique = false;
        checaFimDeJogo();
    } else {
        setTimeout(() => {
            primeiroCard.classList.remove('revela_card');
            segundoCard.classList.remove('revela_card');
            primeiroCard = null;
            segundoCard = null;
            bloqueiaClique = false;
        }, 700);
    }
}

const revelaCard = (event) => {
    if (bloqueiaClique) return;
    const card = event.currentTarget;

    if (card.classList.contains('revela_card')) return;

    if (!primeiroCard) {
        card.classList.add('revela_card');
        primeiroCard = card;
    } else if (!segundoCard) {
        card.classList.add('revela_card');
        segundoCard = card;
        bloqueiaClique = true;
        checaCards();
    }
}

const createCard = (personagem) => {
    const card = document.createElement('div');
    card.className = 'card';

    const frente = document.createElement('div');
    frente.className = 'face frente';
    frente.style.backgroundImage = `url('./img/${personagem}.jpg')`;

    const tras = document.createElement('div');
    tras.className = 'face tras';

    card.appendChild(frente);
    card.appendChild(tras);

    card.setAttribute('data-personagem', personagem);
    card.addEventListener('click', revelaCard);

    return card;
}

const carregaJogo = () => {
    grid.innerHTML = '';
    const duplicaCartas = [...personagens, ...personagens];
    const embaralha = embaralhaArray(duplicaCartas);

    embaralha.forEach(personagem => {
        const card = createCard(personagem);
        grid.appendChild(card);
    });
}

const comecaTempo = () => {
    segundos = 0;
    timer.innerHTML = '0:00';

    loop = setInterval(() => {
        segundos++;
        const min = Math.floor(segundos / 60);
        const sec = segundos % 60;
        timer.innerHTML = `${min}:${sec.toString().padStart(2, "0")}`;
    }, 1000);
}

function salvarResultado(tempoSegundos) {
    const dados = {
        id_usuario: parseInt(userId),
        tempo_segundos: tempoSegundos
    };
    
    fetch('./salvar_ranking.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dados)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Dados salvos com sucesso!');
            const novosMinutos = Math.floor(tempoSegundos / 60);
            const novosSegundos = tempoSegundos % 60;
            const novoTempoFormatado = `${novosMinutos}:${novosSegundos.toString().padStart(2, '0')}`;

            if (melhorTempo) {
                const [min, sec] = melhorTempo.split(':').map(Number);
                const melhorTempoSegundos = min * 60 + sec;
                if (tempoSegundos < melhorTempoSegundos) {
                    sessionStorage.setItem('melhor_tempo', novoTempoFormatado);
                    melhorTempo = novoTempoFormatado;
                }
            } else {
                sessionStorage.setItem('melhor_tempo', novoTempoFormatado);
                melhorTempo = novoTempoFormatado;
            }
        } else {
            console.error('Erro ao salvar dados:', data.message);
        }
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
    });
}

window.onload = () => {
    comecaTempo();
    carregaJogo();
}