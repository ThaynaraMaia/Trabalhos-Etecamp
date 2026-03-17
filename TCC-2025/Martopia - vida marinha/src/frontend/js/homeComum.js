// Aguarda o documento ser totalmente carregado
document.addEventListener('DOMContentLoaded', () => {
    // Adiciona um listener a todos os cliques dentro da div de notificações
    const containerNotificacoes = document.querySelector('.notificacoes-container');

    if (containerNotificacoes) {
        containerNotificacoes.addEventListener('click', (event) => {
            // Verifica se o clique foi em um botão com a classe 'btn-marcar-lida'
            if (event.target.classList.contains('btn-marcar-lida')) {
                const botao = event.target;
                const itemNotificacao = botao.closest('.notificacao-item');
                const idNotificacao = itemNotificacao.dataset.idNotificacao;

                // Usa a API Fetch para enviar a requisição de forma assíncrona
                fetch('../../../backend/usuarios/comum/marcar_lida.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id_notificacao=${idNotificacao}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'sucesso') {
                        // Se a operação for um sucesso, atualiza a interface
                        itemNotificacao.classList.add('lida');
                        botao.remove(); // Remove o botão
                        
                        // Adiciona o texto "Lida"
                        const statusLida = document.createElement('span');
                        statusLida.className = 'status-lida';
                        statusLida.textContent = 'Lida';
                        itemNotificacao.appendChild(statusLida);

                        console.log(data.mensagem);
                    } else {
                        // Exibe a mensagem de erro
                        alert('Erro: ' + data.mensagem);
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição:', error);
                    alert('Ocorreu um erro ao processar a requisição.');
                });
            }
        });
    }
});