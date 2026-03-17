

// Obtendo os elementos
const modal = document.getElementById("myModal");
const openModalBtn = document.getElementById("openModalBtn");
const closeModalBtn = document.getElementById("closeModalBtn");

// Abrir o modal ao clicar no botão
openModalBtn.onclick = function() {
    modal.style.display = "block";
}

// Fechar o modal ao clicar no botão de fechar (X)
closeModalBtn.onclick = function() {
    modal.style.display = "none";
}

// Fechar o modal ao clicar fora do conteúdo do modal
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}


function openModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = "block";
}

// Função para fechar o modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = "none";
}

// Associando eventos aos botões de abertura
document.getElementById("openModalBtn1").onclick = function() {
    openModal("myModal1");
};
document.getElementById("openModalBtn2").onclick = function() {
    openModal("myModal2");
};
document.getElementById("openModalBtn3").onclick = function() {
    openModal("myModal3");
};

// Associando eventos aos botões de fechamento (X)
const closeButtons = document.querySelectorAll(".close");
closeButtons.forEach(button => {
    button.onclick = function() {
        closeModal(this.getAttribute("data-modal"));
    };
});

// Fechar o modal ao clicar fora do conteúdo do modal
window.onclick = function(event) {
    if (event.target.classList.contains("modal")) {
        closeModal(event.target.id);
    }
}

//adicionar sugestão
function changeButtonStyle(button) {
    button.style.backgroundColor = "#007bff"; // Muda a cor do botão ao clicar
    button.style.transform = "scale(1)"; // Remove o efeito de clique
    button.innerHTML = "<img src='../img/saved.png' alt='Salvo'>"; // Muda a imagem ao clicar
}

//botão
const botaoSalvar = document.getElementById('botaoSalvar');

botaoSalvar.addEventListener('click', function() {
    // Remove a classe 'clicado' de todos os botões (se houver mais de um)
    const botoes = document.querySelectorAll('.botao');
    botoes.forEach(b => b.classList.remove('clicado'));

    // Adiciona a classe 'clicado' ao botão atual
    this.classList.add('clicado');
});
