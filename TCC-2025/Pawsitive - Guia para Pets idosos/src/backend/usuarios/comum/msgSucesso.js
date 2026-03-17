function showAlert(message, type = 'success') {
    // Cria container de alertas se não existir
    let container = document.getElementById('alert-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'alert-container';
        container.style.position = 'fixed';
        container.style.top = '20px';
        container.style.right = '20px';
        container.style.width = '300px';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
    }

    // Cria o alerta
    const alertBox = document.createElement('div');
    alertBox.textContent = message;
    alertBox.className = `alert alert-${type}`;
    alertBox.style.marginBottom = '10px';
    alertBox.style.padding = '10px 20px';
    alertBox.style.borderRadius = '5px';
    alertBox.style.color = '#fff';
    alertBox.style.opacity = '1';
    alertBox.style.transition = 'opacity 0.5s ease';

    // Define cores básicas
    switch(type) {
        case 'success':
            alertBox.style.backgroundColor = '#4CAF50';
            break;
        case 'info':
            alertBox.style.backgroundColor = '#2196F3';
            break;
        case 'danger':
            alertBox.style.backgroundColor = '#f44336';
            break;
        default:
            alertBox.style.backgroundColor = '#333';
    }

    container.appendChild(alertBox);

    // Faz desaparecer após 4 segundos
    setTimeout(() => {
        alertBox.style.opacity = '0';
        setTimeout(() => {
            alertBox.remove();
        }, 500);
    }, 4000);
}