document.addEventListener("DOMContentLoaded", () => {
  // ========== MODAL VER ==========
  const modal = document.getElementById("modalVer");
  const close = document.querySelector(".close");

  document.querySelectorAll(".btn-ver").forEach(btn => {
    btn.addEventListener("click", () => {
      // Pega os dados do botão
      document.getElementById("m-pergunta").textContent = btn.dataset.pergunta;
      document.getElementById("m-a").textContent = btn.dataset.a;
      document.getElementById("m-b").textContent = btn.dataset.b;
      document.getElementById("m-c").textContent = btn.dataset.c;
      document.getElementById("m-d").textContent = btn.dataset.d;
      document.getElementById("m-resposta").textContent = btn.dataset.resposta;

      // Converte dificuldade
      let nivel = "";
      switch (parseInt(btn.dataset.dificuldade)) {
        case 1: nivel = "Fácil"; break;
        case 2: nivel = "Médio"; break;
        case 3: nivel = "Difícil"; break;
        case 4: nivel = "Enem"; break;
        default: nivel = "Não definido";
      }
      document.getElementById("m-nivel").textContent = nivel;

      // Abre o modal
      modal.style.display = "block";
    });
  });

  // Fecha modal ao clicar no X
  close.addEventListener("click", () => {
    modal.style.display = "none";
  });

  // Fecha ao clicar fora
  window.addEventListener("click", (e) => {
    if (e.target === modal) modal.style.display = "none";
  });
});


// ========== MODAL EDITAR ==========
const modalEditar = document.getElementById('modalEditarPergunta');
const closeEditBtn = document.getElementById('closeEditModal');

// Função para abrir e popular o modal de edição
function editarPergunta(button) {
    // Pega os dados dos atributos data-* do botão clicado
    const id = button.dataset.id;
    const pergunta = button.dataset.pergunta;
    const opcaoA = button.dataset.opcaoA;
    const opcaoB = button.dataset.opcaoB;
    const opcaoC = button.dataset.opcaoC;
    const opcaoD = button.dataset.opcaoD;
    const resposta = button.dataset.resposta;
    const dificuldade = button.dataset.dificuldade;
    const idBiologo = button.dataset.idBiologo;

    // Preenche os campos do formulário no modal
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-pergunta').value = pergunta;
    document.getElementById('edit-opcao-a').value = opcaoA;
    document.getElementById('edit-opcao-b').value = opcaoB;
    document.getElementById('edit-opcao-c').value = opcaoC;
    document.getElementById('edit-opcao-d').value = opcaoD;
    document.getElementById('edit-resposta').value = resposta;
    document.getElementById('edit-dificuldade').value = dificuldade;
    document.getElementById('edit-id-biologo').value = idBiologo;

    // Exibe o modal com animação
    modalEditar.classList.add('active');
    modalEditar.style.display = 'flex';
}

// Evento para fechar o modal de edição ao clicar no 'X'
closeEditBtn.onclick = function() {
    modalEditar.classList.remove('active');
    setTimeout(() => {
        modalEditar.style.display = 'none';
    }, 300);
}

// Evento para fechar os modais se o usuário clicar fora da caixa de conteúdo
window.onclick = function(event) {
    // Modal Editar
    if (event.target == modalEditar) {
        modalEditar.classList.remove('active');
        setTimeout(() => {
            modalEditar.style.display = "none";
        }, 300);
    }
    
    // Modal Ver
    const modalVer = document.getElementById('modalVer');
    if (event.target == modalVer) {
        modalVer.style.display = "none";
    }
}