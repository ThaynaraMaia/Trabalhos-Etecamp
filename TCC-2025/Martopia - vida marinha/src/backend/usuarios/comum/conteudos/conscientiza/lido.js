document.addEventListener("DOMContentLoaded", function () {
    const tempoMinimo = 10000; // 10 segundos para liberar o botão "Marcar Como Lido"

    // Objeto para controlar quais conteúdos já foram "lidos"
    const conteudosLiberados = new Set();

    document.querySelectorAll(".card").forEach(card => {
        const btnLer = card.querySelector(".btn-ler");
        const btnLido = card.querySelector(".btn-lido");

        // ✅ CORRIGIDO: Agora usa o atributo correto
        const idConteudo = btnLer.getAttribute("data-id-conteudo");
        const tipoConteudo = btnLer.getAttribute("data-tipo"); // pode ser "artigo" ou "conscientizacao"

        // Verifica se já foi lido no servidor
        const jaLidoNoServidor = btnLido.disabled && btnLido.innerText === "Já Lido";

        if (jaLidoNoServidor) {
            btnLer.disabled = true;
            btnLer.style.backgroundColor = "#b0c4de";
            btnLer.style.color = "#666";
            btnLer.style.cursor = "not-allowed";
            btnLer.innerHTML = "Conteúdo Já Lido";
            return;
        }

        // Inicialmente o botão de "Marcar Como Lido" está desabilitado
        if (!conteudosLiberados.has(idConteudo)) {
            btnLido.disabled = true;
            btnLido.innerText = "Leia o conteúdo primeiro";
            btnLido.style.backgroundColor = "#b0c4de";
            btnLido.style.color = "#666";
            btnLido.style.cursor = "not-allowed";
        }

        // Ao clicar em "Ler"
        btnLer.addEventListener("click", () => {
            conteudosLiberados.add(idConteudo);

            btnLido.innerText = "Aguarde para marcar como lido...";
            btnLido.disabled = true;
            btnLido.style.backgroundColor = "#ffa500";
            btnLido.style.cursor = "wait";

            setTimeout(() => {
                if (conteudosLiberados.has(idConteudo)) {
                    btnLido.disabled = false;
                    btnLido.innerText = "Marcar Como Lido";
                    btnLido.style.backgroundColor = "#81c0e9";
                    btnLido.style.color = "white";
                    btnLido.style.cursor = "pointer";
                }
            }, tempoMinimo);
        });

        // Ao clicar em "Marcar Como Lido"
        btnLido.addEventListener("click", () => {
            if (!conteudosLiberados.has(idConteudo)) {
                alert("Você precisa ler o conteúdo antes de marcá-lo como lido!");
                return;
            }

            btnLido.disabled = true;
            btnLido.innerText = "Processando...";
            btnLido.style.backgroundColor = "#ffa500";
            btnLido.style.cursor = "wait";

            // ✅ CORRIGIDO: Requisição para o PHP genérico
            fetch("marcar_lido.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "id_conteudo=" + encodeURIComponent(idConteudo) +
                      "&tipoConteudo=" + encodeURIComponent(tipoConteudo)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na resposta do servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === "sucesso") {
                        btnLido.innerText = "✓ Conteúdo Lido";
                        btnLido.disabled = true;
                        btnLido.style.backgroundColor = "#28a745";
                        btnLido.style.color = "white";
                        btnLido.style.cursor = "not-allowed";

                        btnLer.disabled = true;
                        btnLer.style.backgroundColor = "#b0c4de";
                        btnLer.style.color = "#666";
                        btnLer.style.cursor = "not-allowed";
                        btnLer.innerHTML = "Conteúdo Já Lido";

                        conteudosLiberados.delete(idConteudo);

                        if (data.pontos) {
                            showNotification(`Parabéns! Você ganhou ${data.pontos} pontos!`);
                        }

                    } else if (data.status === "info") {
                        btnLido.innerText = "✓ Já Lido";
                        btnLido.disabled = true;
                        btnLido.style.backgroundColor = "#6c757d";
                        btnLido.style.cursor = "not-allowed";

                        btnLer.disabled = true;
                        btnLer.style.backgroundColor = "#b0c4de";
                        btnLer.style.color = "#666";
                        btnLer.innerHTML = "Conteúdo Já Lido";

                        showNotification(data.mensagem, 'info');

                    } else {
                        throw new Error(data.mensagem || 'Erro desconhecido');
                    }
                })
                .catch(error => {
                    console.error("Erro na requisição:", error);

                    if (conteudosLiberados.has(idConteudo)) {
                        btnLido.disabled = false;
                        btnLido.innerText = "Marcar Como Lido";
                        btnLido.style.backgroundColor = "#81c0e9";
                        btnLido.style.color = "white";
                        btnLido.style.cursor = "pointer";
                    } else {
                        btnLido.disabled = true;
                        btnLido.innerText = "Leia o conteúdo primeiro";
                        btnLido.style.backgroundColor = "#b0c4de";
                        btnLido.style.cursor = "not-allowed";
                    }

                    showNotification("Erro ao marcar conteúdo: " + error.message, 'error');
                });
        });
    });

    // Função de notificação
    function showNotification(message, type = 'success') {
        const existing = document.querySelector('.custom-notification');
        if (existing) existing.remove();

        const n = document.createElement('div');
        n.className = `custom-notification ${type}`;
        n.textContent = message;

        Object.assign(n.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '15px 20px',
            borderRadius: '8px',
            color: 'white',
            fontWeight: 'bold',
            zIndex: '9999',
            fontSize: '16px',
            maxWidth: '350px',
            boxShadow: '0 4px 12px rgba(0,0,0,0.3)',
            transform: 'translateX(400px)',
            transition: 'transform 0.3s ease-in-out'
        });

        const colors = {
            success: '#28a745',
            error: '#dc3545',
            info: '#17a2b8'
        };
        n.style.backgroundColor = colors[type] || colors.success;

        document.body.appendChild(n);
        setTimeout(() => { n.style.transform = 'translateX(0)'; }, 100);
        setTimeout(() => {
            n.style.transform = 'translateX(400px)';
            setTimeout(() => { if (n.parentNode) n.remove(); }, 300);
        }, 4000);
    }
});