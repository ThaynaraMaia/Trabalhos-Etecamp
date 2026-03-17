document.addEventListener('DOMContentLoaded', function() {
    const interesses = document.querySelectorAll('.interesse');
    const submitBtn = document.getElementById('submit-btn');
    
    interesses.forEach(interesse => {
        interesse.addEventListener('click', () => {
            interesse.classList.toggle('interesse-selecionado');
        });
    });

    submitBtn.addEventListener('click', () => {
        const selecionados = [];
        interesses.forEach(interesse => {
            if (interesse.classList.contains('interesse-selecionado')) {
                selecionados.push(interesse.textContent);
            }
        });
        alert('Você selecionou: ' + selecionados.join(', '));
    });
});