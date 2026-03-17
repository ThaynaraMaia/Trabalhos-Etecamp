document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("modal-editar");
    const form = document.getElementById("form-editar");
    const campoExtraTexto = document.getElementById("campo-extra-texto");
    const campoImagem = document.getElementById("campo-imagem");
    const inputImagem = document.getElementById("file-input");
    const campoLink = document.getElementById("link");

    // === Abrir modal com dados do conteúdo ===
    document.querySelectorAll(".btn-editar").forEach(btn => {
        btn.addEventListener("click", e => {
            e.preventDefault();
            e.stopPropagation(); // ADICIONA ISSO - previne propagação do evento

            const tabela = btn.dataset.tabela;
            const categoria = btn.dataset.categoria;
            
            console.log("Abrindo modal para:", tabela, categoria); // DEBUG
            
            // Preenche campos básicos
            document.getElementById("edit-id").value = btn.dataset.id || '';
            document.getElementById("edit-tabela").value = tabela || '';
            document.getElementById("edit-titulo").value = btn.dataset.titulo || '';
            document.getElementById("edit-link").value = btn.dataset.link || '';
            document.getElementById("edit-categoria").value = categoria || '';
            document.getElementById("edit-tipo").value = btn.dataset.tipo || '';
            document.getElementById("edit-texto").value = btn.dataset.texto || '';

            // === LÓGICA DE EXIBIÇÃO DE CAMPOS ===
            
            // Reseta visibilidade - MOSTRA TUDO primeiro
            if (campoExtraTexto) campoExtraTexto.style.display = "none";
            if (campoImagem) campoImagem.style.display = "block";
            if (campoLink) campoLink.style.display = "block";

            // TABELA: conscientizacao
            if (tabela === 'conscientizacao') {
                const categoriasComTexto = ['Texto'];
                if (categoriasComTexto.includes(categoria)) {
                    if (campoExtraTexto) campoExtraTexto.style.display = "block";
                    if (campoLink) campoLink.style.display = "none";
                }
                
                // Oculta imagem para vídeos
                if (categoria === 'Videos') {
                    if (campoImagem) campoImagem.style.display = "none";
                    if (campoExtraTexto) campoExtraTexto.style.display = "none";
                }
            }

            // TABELA: videos - ESCONDE O CAMPO DE IMAGEM
            if (tabela === 'videos') {
                if (campoImagem) campoImagem.style.display = "none";
                if (campoLink) campoLink.style.display = "block";
            }

            // TABELA: conteudos
            if (tabela === 'conteudos') {
                if (campoImagem) campoImagem.style.display = "block";
                if (campoLink) campoLink.style.display = "block";
            }

            // Mostra o modal com um pequeno delay para garantir que tudo foi processado
            setTimeout(() => {
                modal.style.display = "flex";
                console.log("Modal aberto"); // DEBUG
            }, 50);
        });
    });

    // === Fechar modal ===
    const fecharModal = document.getElementById("fechar-modal");
    if (fecharModal) {
        fecharModal.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            console.log("Fechando modal via botão"); // DEBUG
            modal.style.display = "none";
            form.reset();
            limparPreview();
        });
    }

    // Fecha modal ao clicar SOMENTE no fundo escuro (não no conteúdo)
    modal.addEventListener('click', (e) => {
        // Só fecha se clicar exatamente no modal (fundo), não no conteúdo
        if (e.target === modal) {
            console.log("Fechando modal via clique fora"); // DEBUG
            modal.style.display = "none";
            form.reset();
            limparPreview();
        }
    });

    // Previne que cliques dentro do modal-content fechem o modal
    const modalContent = modal.querySelector('.modal-content');
    if (modalContent) {
        modalContent.addEventListener('click', (e) => {
            e.stopPropagation(); // Impede que o clique chegue ao modal
        });
    }

    // === Enviar formulário ===
    form.addEventListener("submit", async e => {
        e.preventDefault();
        e.stopPropagation();

        const formData = new FormData(form);
        const tabela = formData.get('tabela');

        console.log("Enviando formulário..."); // DEBUG

        // Validações específicas por tabela
        if (tabela === 'videos' && !formData.get('link')) {
            showAlert("O link do vídeo é obrigatório!", "error");
            return;
        }

        // Mostra loading
        const submitBtn = form.querySelector('button[type="submit"]');
        const btnTextoOriginal = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = "Salvando...";

        try {
            const response = await fetch("editarCont.php", {
                method: "POST",
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showAlert(result.message, "success");
                modal.style.display = "none";
                form.reset();
                limparPreview();
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert(result.message || "Erro ao atualizar conteúdo!", "error");
                submitBtn.disabled = false;
                submitBtn.textContent = btnTextoOriginal;
            }
        } catch (error) {
            showAlert("Erro ao comunicar com o servidor!", "error");
            console.error("Erro:", error);
            submitBtn.disabled = false;
            submitBtn.textContent = btnTextoOriginal;
        }
    });

    // Função auxiliar para limpar preview
    function limparPreview() {
        const previewContainer = document.getElementById("preview-container");
        if (previewContainer) {
            previewContainer.style.display = "none";
            const imagePreviews = document.getElementById("image-previews");
            if (imagePreviews) imagePreviews.innerHTML = "";
        }
    }
});

// === Função de preview de imagem ===
function handleFileSelect(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('preview-container');
    const imagePreviews = document.getElementById('image-previews');
    
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            imagePreviews.innerHTML = `<img src="${e.target.result}" class="preview-image" alt="Preview">`;
            previewContainer.style.display = 'block';
        };
        
        reader.readAsDataURL(file);
    } else if (file) {
        showAlert("Por favor, selecione apenas arquivos de imagem (JPG, PNG)", "error");
    }
}

// === Função de alerta melhorada ===
function showAlert(msg, type = "success") {
    const div = document.createElement("div");
    div.className = `alert-custom ${type}`;
    div.textContent = msg;
    div.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        background: ${type === 'success' ? '#4CAF50' : '#f44336'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        z-index: 10000;
        font-family: 'Texto', sans-serif;
        font-size: 14px;
        opacity: 1;
        transition: opacity 0.5s ease;
    `;
    
    document.body.appendChild(div);

    setTimeout(() => {
        div.style.opacity = "0";
        setTimeout(() => div.remove(), 500);
    }, 3000);
}