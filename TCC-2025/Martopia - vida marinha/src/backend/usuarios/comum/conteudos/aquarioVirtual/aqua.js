// Event delegation para cliques em imagens e closes
document.addEventListener('click', (event) => {
    // Abrir modal ao clicar na imagem
    if (event.target.classList.contains('floating-image') || event.target.classList.contains('floating-imageE')) {
        const modalId = event.target.getAttribute('data-modal');
        if (modalId) {
            document.getElementById(modalId).classList.add('active');
        }
    }

    // Fechar modal ao clicar no X
    if (event.target.classList.contains('close')) {
        const modalId = event.target.getAttribute('data-modal');
        if (modalId) {
            document.getElementById(modalId).classList.remove('active');
        }
    }

    // Fechar modal ao clicar fora dele
    if (event.target.classList.contains('modal') && event.target.classList.contains('active')) {
        event.target.classList.remove('active');
    }
});

// Fechar com ESC (opcional, mas útil)
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        const activeModal = document.querySelector('.modal.active');
        if (activeModal) {
            activeModal.classList.remove('active');
        }
    }
});
