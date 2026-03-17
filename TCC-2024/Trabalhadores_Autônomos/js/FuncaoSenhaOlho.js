function mostrarSenha() {
    const senhaInput = document.getElementById('senha');
    const olhoIcon = document.getElementById('olho');
    if (senhaInput.type === 'password') {
        senhaInput.type = 'text';
        olhoIcon.classList.remove('bi-eye-slash');
        olhoIcon.classList.add('bi-eye');
    } else {
        senhaInput.type = 'password';
        olhoIcon.classList.remove('bi-eye');
        olhoIcon.classList.add('bi-eye-slash');
    }
}

function mostrarSenha2() {
    const confirmaSenhaInput = document.getElementById('ConfirmaSenha');
    const olhoIcon2 = document.getElementById('olho2');
    if (confirmaSenhaInput.type === 'password') {
        confirmaSenhaInput.type = 'text';
        olhoIcon2.classList.remove('bi-eye-slash');
        olhoIcon2.classList.add('bi-eye');
    } else {
        confirmaSenhaInput.type = 'password';
        olhoIcon2.classList.remove('bi-eye');
        olhoIcon2.classList.add('bi-eye-slash');
    }
}
