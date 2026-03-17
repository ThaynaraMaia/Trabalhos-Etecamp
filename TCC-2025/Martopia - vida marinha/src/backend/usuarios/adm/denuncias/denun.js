

document.addEventListener("DOMContentLoaded", () => {
    const notificarBtns = document.querySelectorAll(".btn-notificar");

    notificarBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            const postId = btn.getAttribute("data-id");
            
            // Desativa o botão de notificar para evitar cliques duplos
            btn.disabled = true;
            btn.textContent = "Enviando...";

            // O nome do arquivo PHP deve ser o correto
            fetch("notificarUsuario.php", { 
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "id_post=" + postId
            })
            .then(res => {
                // Primeiro, verificamos se a resposta da rede foi bem-sucedida
                if (!res.ok) {
                    throw new Error('Erro de rede: ' + res.statusText);
                }
                return res.json(); // Convertemos a resposta para JSON
            })
            .then(data => {
                // Agora processamos a resposta JSON do PHP
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: data.message,
                    });

                    // Habilita o botão de excluir correspondente
                    // O seletor foi ajustado para pegar o <a> diretamente
                    const excluirLink = document.querySelector(`a.btn-excluir[data-id='${postId}']`);
                    if (excluirLink) {
                        excluirLink.classList.remove("disabled");
                    }
                    // Mantém o botão de notificar desabilitado e com texto de sucesso
                    btn.textContent = "Notificado ✔️";

                } else if (data.status === 'info') {
                    // Caso a notificação já tenha sido enviada
                    Swal.fire({
                        icon: 'info',
                        title: 'Aviso',
                        text: data.message,
                    });
                     const excluirLink = document.querySelector(`a.btn-excluir[data-id='${postId}']`);
                    if (excluirLink) {
                        excluirLink.classList.remove("disabled");
                    }
                     btn.textContent = "Já Notificado";
                }
                
                else {
                    // Para qualquer outro tipo de erro retornado pelo PHP
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.message,
                    });
                     // Reabilita o botão para que o admin possa tentar novamente
                    btn.disabled = false;
                    btn.textContent = "Notificar";
                }
            })
            .catch(err => {
                console.error("Erro na requisição fetch:", err);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Conexão',
                    text: 'Não foi possível se comunicar com o servidor.',
                });
                // Reabilita o botão em caso de falha de rede
                btn.disabled = false;
                btn.textContent = "Notificar";
            });
        });
    });
});

