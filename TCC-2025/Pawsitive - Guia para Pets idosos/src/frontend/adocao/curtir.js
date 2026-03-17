// Sistema de favoritos para animais de adoção

document.addEventListener('DOMContentLoaded', function() {
    inicializarSistemaFavoritos();
});

function inicializarSistemaFavoritos() {
    const btnFavorito = document.getElementById('btn-favorito');
    const contadorFavoritos = document.getElementById('contador-favoritos');
    
    if (btnFavorito) {
        btnFavorito.addEventListener('click', function() {
            const idAnimal = this.getAttribute('data-id');
            
            if (userId === 0) {
                showAlert('Você precisa estar logado para favoritar animais!', 'warning');
                // Redirecionar para a página de login após 2 segundos
                setTimeout(() => {
                    window.location.href = '../../backend/login/login_form.php';
                }, 2000);
                return;
            }
            
            toggleFavorito(userId, idAnimal, this, contadorFavoritos);
        });
    }
}

function toggleFavorito(idUsuario, idAnimal, botao, contadorElement) {
    // Salvar estado original
    const icone = botao.querySelector('i');
    const classeOriginal = icone.className;
    
    // Mostrar estado de carregamento
    botao.classList.add('loading');
    botao.disabled = true;
    
    // Criar FormData para enviar os dados
    const formData = new FormData();
    formData.append('id', idUsuario);
    formData.append('id_animal', idAnimal);
    
    fetch('curtir.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na requisição: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Atualiza contagem de favoritos
            if (contadorElement) {
                contadorElement.textContent = data.total_favoritos;
            }
            
            // Atualiza aparência do botão
            if (data.acao === 'adicionado') {
                icone.className = 'bi bi-star-fill';
                showAlert('Animal adicionado aos favoritos!', 'success');
            } else {
                icone.className = 'bi bi-star';
                showAlert('Animal removido dos favoritos.', 'info');
            }
        } else {
            showAlert('Erro: ' + data.message, 'danger');
            // Restaurar estado original em caso de erro
            icone.className = classeOriginal;
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showAlert('Erro ao conectar com o servidor. Verifique sua conexão.', 'danger');
        // Restaurar estado original em caso de erro
        icone.className = classeOriginal;
    })
    .finally(() => {
        botao.classList.remove('loading');
        botao.disabled = false;
    });
}

function showAlert(message, type) {
    // Remover alertas anteriores
    const existingAlerts = document.querySelectorAll('.alert-login');
    existingAlerts.forEach(alert => alert.remove());
    
    const alertPlaceholder = document.getElementById('alert-placeholder');
    const alertId = 'alert-' + Date.now();
    
    const alertElement = document.createElement('div');
    alertElement.id = alertId;
    alertElement.className = `alert alert-${type} alert-dismissible fade show alert-login`;
    alertElement.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    alertPlaceholder.appendChild(alertElement);
    
    // Remove o alerta após 5 segundos
    setTimeout(() => {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.remove();
        }
    }, 5000);
}

