// Função para exibir o formulário ao clicar no botão
document.getElementById("adicionarHobby").onclick = function() {
    console.log("Botão clicado"); // Log de depuração para verificar se o evento de clique está funcionando
    var formContainer = document.getElementById("formularioContainer");
    console.log("formContainer encontrado:", formContainer); // Log de depuração para verificar se o formulário é encontrado
    formContainer.style.display = "flex"; // Usa flexbox para centralizar o conteúdo
};

// Fechar o modal ao clicar fora do conteúdo do formulário
document.getElementById("formularioContainer").onclick = function(event) {
    if (event.target === document.getElementById("formularioContainer")) {
        document.getElementById("formularioContainer").style.display = "none";
    }
};

//abrir e fechar as tesks

document.querySelectorAll('.plus-button').forEach(button => {
    button.addEventListener('click', () => {
        const task = button.parentElement.parentElement;
        const details = task.querySelector('.task-details');
        const isVisible = details.style.display === 'block';

        // Alternar visibilidade
        details.style.display = isVisible ? 'none' : 'block';

        // Alternar o sinal de + para - quando aberto
        button.textContent = isVisible ? '+' : '-';
    });
});

//modal editar

function openEditModal(hobbyId) {
    // Abrir o modal
    document.getElementById('editModal').style.display = 'block';

    // Preencher o campo hidden com o ID do hobby
    document.getElementById('id').value = hobbyId;
}

function closeEditModal() {
    // Fechar o modal
    document.getElementById('editModal').style.display = 'none';
}


// Função para fechar o modal clicando fora do conteúdo
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}

//moof track
function toggleMoodTrack() {
    var status = document.getElementById('status').value;
    var moodTrackContainer = document.getElementById('moodTrackContainer');
    
    // Mostrar o campo de feedback emocional apenas se o status for 'executados'
    if (status === 'executados') {
        moodTrackContainer.style.display = 'block';
    } else {
        moodTrackContainer.style.display = 'none';
    }
}

function setMood(mood) {
    document.getElementById('sentimento').value = mood;
    console.log("Sentimento escolhido:", mood);
    
    var moods = document.querySelectorAll('.mood');
    moods.forEach(function(el) {
        el.classList.remove('selected');
    });

    var selectedMood = document.querySelector('.mood.' + mood);
    selectedMood.classList.add('selected');

}

// mapear sentimentos
function atualizarMensagemMotivacional() {
    // Fazer uma chamada AJAX para pegar a nova mensagem do servidor
    fetch('caminho-para-seu-endpoint')
        .then(response => response.json())
        .then(data => {
            document.getElementById('mensagem-motivacional').innerText = data.mensagem;
        })
        .catch(error => console.error('Erro ao atualizar a mensagem:', error));
}
atualizarMensagemMotivacional();
    
// Função para calcular as porcentagens e atualizar na página
function atualizarPorcentagens(feliz, meh, triste) {
    const total = feliz + meh + triste;
    
    if (total > 0) {
        const porcentFeliz = ((feliz / total) * 100).toFixed(1);
        const porcentMeh = ((meh / total) * 100).toFixed(1);
        const porcentTriste = ((triste / total) * 100).toFixed(1);

        document.getElementById('percent-feliz').innerText = `${porcentFeliz}%`;
        document.getElementById('percent-meh').innerText = `${porcentMeh}%`;
        document.getElementById('percent-triste').innerText = `${porcentTriste}%`;
    }
}

// Fazer requisição AJAX para obter os dados
fetch('../../frontend/html/meus_hobbies.php')
    .then(response => response.json())
    .then(data => {
        // Chama a função com os dados retornados
        atualizarPorcentagens(data.feliz, data.meh, data.triste);
    })
    .catch(error => console.error('Erro ao buscar os sentimentos:', error));



    



