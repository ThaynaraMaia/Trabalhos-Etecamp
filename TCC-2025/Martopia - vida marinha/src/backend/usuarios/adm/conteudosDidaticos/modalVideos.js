function abrirModal() {
            document.getElementById("modal-editar").style.display = "block";
        }

        function fecharModal() {
            document.getElementById("modal-editar").style.display = "none";
        }

        const closeBtn = document.getElementById('closeModalBtn');

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });