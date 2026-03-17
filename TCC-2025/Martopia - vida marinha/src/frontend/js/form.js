function verificaSenha() {
    const senha = document.getElementById("senha");
    const confirmaSenha = document.getElementById("confirmaSenha");
    console.log(senha);
    console.log(confirmaSenha);
    if (senha.value != confirmaSenha.value) {
        alert("Atenção: Senhas são diferentes!!!");
        return false;
    } else {
        return true;
    }
}