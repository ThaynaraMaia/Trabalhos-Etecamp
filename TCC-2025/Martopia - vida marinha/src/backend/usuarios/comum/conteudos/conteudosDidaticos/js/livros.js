document.addEventListener("DOMContentLoaded", function() {
    const tempoMinimo = 15000; // 15 segundos para livros (mais tempo que artigos)
    
    // Objeto para controlar quais conteúdos já foram "lidos"
    const conteudosLiberados = new Set();
    
    document.querySelectorAll(".card").forEach(card => {
        const btnLer = card.querySelector(".btn-ler");
        const btnLido = card.querySelector(".btn-lido");
        const idConteudo = btnLer.getAttribute("data-id-artigo");
        
        // Verifica se é um conteúdo estático (como o livro "Biologia Marinha")
        const isStaticContent = idConteudo && idConteudo.startsWith('static-');
        
        // Verifica se o conteúdo já foi marcado como lido no servidor
        const jaLidoNoServidor = btnLido.disabled && 
            (btnLido.innerText === "✓ Conteúdo Lido" || btnLido.innerText === "Já Lido");
        
        // Se já foi lido no servidor, mantém o botão "Ler" habilitado
        if (jaLidoNoServidor) {
            // Para conteúdos do banco de dados, permite releitura
            if (!isStaticContent) {
                btnLer.innerHTML = btnLer.innerHTML.replace("Ler Conteúdo", "Ler Conteúdo Novamente");
            }
            return; // Pula para o próximo card
        }
        
        // Inicialmente, o botão "Marcar Como Lido" deve estar desabilitado
        if (!conteudosLiberados.has(idConteudo)) {
            btnLido.disabled = true;
            
            if (isStaticContent) {
                btnLido.innerText = "Leia o livro primeiro";
            } else {
                btnLido.innerText = "Leia o conteúdo primeiro";
            }
            
            btnLido.style.backgroundColor = "#b0c4de";
            btnLido.style.color = "#666";
            btnLido.style.cursor = "not-allowed";
        }
        
        // Evento do botão "Ler/Onde Adquirir"
        btnLer.addEventListener("click", () => {
            // Marca que o usuário clicou para ler
            conteudosLiberados.add(idConteudo);
            
            // Define o tempo de espera baseado no tipo de conteúdo
            const tempoEspera = isStaticContent ? 20000 : tempoMinimo; // 20s para livros, 15s para outros
            
            // Mostra feedback imediato
            if (isStaticContent) {
                btnLido.innerText = "Aguarde para confirmar leitura...";
            } else {
                btnLido.innerText = "Aguarde para marcar como lido...";
            }
            
            btnLido.disabled = true;
            btnLido.style.backgroundColor = "#ffa500";
            btnLido.style.cursor = "wait";
            
            // Após o tempo mínimo, libera o botão
            setTimeout(() => {
                if (conteudosLiberados.has(idConteudo)) {
                    btnLido.disabled = false;
                    
                    if (isStaticContent) {
                        btnLido.innerText = "Confirmar Leitura";
                    } else {
                        btnLido.innerText = "Marcar Como Lido";
                    }
                    
                    btnLido.style.backgroundColor = "#81c0e9";
                    btnLido.style.color = "white";
                    btnLido.style.cursor = "pointer";
                }
            }, tempoEspera);
        });
        
        // Evento do botão "Marcar Como Lido"
        btnLido.addEventListener("click", () => {
            // Verifica se o conteúdo foi liberado
            if (!conteudosLiberados.has(idConteudo)) {
                if (isStaticContent) {
                    alert("Você precisa acessar o link do livro antes de confirmar a leitura!");
                } else {
                    alert("Você precisa ler o conteúdo antes de marcá-lo como lido!");
                }
                return;
            }
            
            // Para conteúdos estáticos, apenas simula a marcação
            if (isStaticContent) {
                // Simula sucesso para conteúdos estáticos
                btnLido.innerText = "✓ Leitura Confirmada";
                btnLido.disabled = true;
                btnLido.style.backgroundColor = "#28a745";
                btnLido.style.color = "white";
                btnLido.style.cursor = "not-allowed";
                
                conteudosLiberados.delete(idConteudo);
                showNotification("Leitura confirmada! Continue aprendendo!", 'success');
                return;
            }
            
            // Para conteúdos do banco de dados, faz a requisição normal
            btnLido.disabled = true;
            btnLido.innerText = "Processando...";
            btnLido.style.backgroundColor = "#ffa500";
            btnLido.style.cursor = "wait";
            
            fetch("./marcar_lido.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "id_artigo=" + encodeURIComponent(idConteudo)
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
                    
                    // Atualiza o botão "Ler" para "Ler Novamente"
                    btnLer.innerHTML = btnLer.innerHTML.replace("Ler Conteúdo", "Ler Conteúdo Novamente");
                    
                    conteudosLiberados.delete(idConteudo);
                    
                    if (data.pontos) {
                        showNotification(`Parabéns! Você ganhou ${data.pontos} pontos!`);
                    }
                    
                } else if (data.status === "info") {
                    btnLido.innerText = "✓ Já Lido";
                    btnLido.disabled = true;
                    btnLido.style.backgroundColor = "#6c757d";
                    btnLido.style.cursor = "not-allowed";
                    
                    btnLer.innerHTML = btnLer.innerHTML.replace("Ler Conteúdo", "Ler Conteúdo Novamente");
                    
                    showNotification(data.mensagem, 'info');
                    
                } else {
                    throw new Error(data.mensagem || 'Erro desconhecido');
                }
            })
            .catch(error => {
                console.error("Erro na requisição:", error);
                
                // Reverte o estado do botão em caso de erro
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
    
    // Função para mostrar notificações (igual à versão anterior)
    function showNotification(message, type = 'success') {
        const existingNotification = document.querySelector('.custom-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        const notification = document.createElement('div');
        notification.className = `custom-notification ${type}`;
        notification.textContent = message;
        
        Object.assign(notification.style, {
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
        notification.style.backgroundColor = colors[type] || colors.success;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(400px)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, 4000);
    }
});