function verificaSenha() {
    const senha = document.getElementById("senha");
    const ConfirmaSenha = document.getElementById("ConfirmaSenha");
    console.log(senha);
    console.log(ConfirmaSenha);
    if (senha.value != ConfirmaSenha.value) {
        alert("Atenção: Senhas são diferentes!!!");
        return false;
    } else {
        return true;
    }
}