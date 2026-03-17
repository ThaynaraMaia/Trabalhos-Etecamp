// Otimização para evitar fechar e abrir o menu em um único clique
function toggleMenu(button) {
    let dropdown = button.nextElementSibling;
    let allMenus = document.querySelectorAll(".menu-dropdown");

    allMenus.forEach(menu => {
        // Fecha outros menus somente se eles não forem o que está sendo clicado
        if (menu !== dropdown && menu.style.display === "block") {
            menu.style.display = "none";
        }
    });

    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
}

// Melhoria para fechar o menu ao clicar fora
document.addEventListener("click", function(event) {
    // Encontra o container do botão de menu, garantindo que o clique não está dentro dele
    const isClickInsideMenu = event.target.closest(".pontos");

    if (!isClickInsideMenu) {
        document.querySelectorAll(".menu-dropdown").forEach(menu => {
            menu.style.display = "none";
        });
    }
});

// ================================================================
// NOVA FUNÇÃO PARA DENUNCIAR - ADICIONE ISTO AO SEU ARQUIVO
// ================================================================
function denunciarPost(id_postagem) {
    console.log("Enviando id_postagem:", id_postagem);

    let formData = new FormData();
    formData.append("id_postagem", id_postagem);

    fetch("../../../Denunciar/denunciar.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        console.log("Resposta:", data);
        alert(data.message);
    })
    .catch(err => console.error(err));
}


// ================================================================
// FUNÇÃO PARA DENUNCIAR COMENTÁRIOS
// ================================================================
// Otimização para evitar fechar e abrir o menu em um único clique
function toggleMenu(button) {
    let dropdown = button.nextElementSibling;
    let allMenus = document.querySelectorAll(".menu-dropdown");

    allMenus.forEach(menu => {
        // Fecha outros menus somente se eles não forem o que está sendo clicado
        if (menu !== dropdown && menu.style.display === "block") {
            menu.style.display = "none";
        }
    });

    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
}

// Melhoria para fechar o menu ao clicar fora
document.addEventListener("click", function(event) {
    // Encontra o container do botão de menu, garantindo que o clique não está dentro dele
    const isClickInsideMenu = event.target.closest(".pontos");

    if (!isClickInsideMenu) {
        document.querySelectorAll(".menu-dropdown").forEach(menu => {
            menu.style.display = "none";
        });
    }
});

// ================================================================
// FUNÇÃO PARA DENUNCIAR POSTAGENS
// ================================================================
function denunciarPost(id_postagem) {
    console.log("Enviando id_postagem:", id_postagem);

    let formData = new FormData();
    formData.append("id_postagem", id_postagem);

    fetch("../../../Denunciar/denunciar.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        console.log("Resposta:", data);
        alert(data.message);
    })
    .catch(err => console.error(err));
}

// ================================================================
// FUNÇÃO PARA DENUNCIAR COMENTÁRIOS
// ================================================================
document.addEventListener('click', function (e) {
    // Quando clicar no botão de denunciar comentário
    const denBtn = e.target.closest('.acao-denunciar');
    if (denBtn) {
        e.preventDefault();

        // Pegamos o ID do comentário pelo data-id
        const id_comentario = denBtn.dataset.id;

        if (!id_comentario) {
            alert("ID do comentário não encontrado.");
            return;
        }

        if (!confirm("Deseja denunciar este comentário?")) {
            return;
        }

        console.log("Denunciando comentário ID:", id_comentario);

        // ✅ Enviando como FormData para o arquivo específico de denúncia de comentários
        let formData = new FormData();
        formData.append("id_comentario", id_comentario);

        fetch("../../../Denunciar/denunciarComent.php", {
            method: "POST",
            body: formData
        })
        .then(r => {
            if (!r.ok) {
                throw new Error(`HTTP error! status: ${r.status}`);
            }
            return r.json();
        })
        .then(data => {
            console.log("Resposta:", data);
            alert(data.message);

            // Desativa botão após denúncia bem-sucedida
            if (data.success) {
                denBtn.textContent = "✅ Denunciado";
                denBtn.style.pointerEvents = "none";
                denBtn.style.opacity = "0.6";
                denBtn.classList.remove('acao-denunciar');
            }
        })
        .catch(err => {
            console.error("Erro ao enviar denúncia:", err);
            alert("Ocorreu um erro ao denunciar. Tente novamente.");
        });

        return;
    }
});

// ================================================================
// FUNÇÃO AUXILIAR PARA DEBUGGING
// ================================================================
function verificarElementos() {
    const botoesDenunciar = document.querySelectorAll('.acao-denunciar');
    console.log("Botões de denunciar encontrados:", botoesDenunciar.length);
    
    botoesDenunciar.forEach((btn, index) => {
        console.log(`Botão ${index + 1}: ID = ${btn.dataset.id}`);
    });
}