<?php

include_once '../conn/classes/class_IRepositorioUsuarios.php';
session_start();

$usuarioNovo = new usuario('', $_POST['nome_completo'], $_POST['cpf'], $_POST['email'], $_POST['senha'], 0, 1, 'perfilimg.jpg', 0,);

$encontrou = $respositorioUsuario->verificaEmail($_POST['email']);
$linhas = $encontrou->num_rows;

$encontrou_cpf = $respositorioUsuario->verificaCpf($_POST['cpf']);
$linha_cpf = $encontrou_cpf->num_rows;

$encontrou_senha = $respositorioUsuario->verificaSenha($_POST['senha']);
$linha_senha = $encontrou_senha->num_rows;

$senha = $_POST['senha'];
$confirmaSenha = $_POST['confirmaSenha'];

if ($linha_cpf > 0) {
    echo "<script>alert('CPF já cadastrado, tente outro.'); window.history.back();</script>";
} else if ($linhas > 0) {
    echo "<script>alert('Email já cadastrado, tente outro.'); window.history.back();</script>";
} else if (strlen($senha) < 6) {
    echo "<script>alert('A senha deve ter pelo menos 6 caracteres. Tente novamente.'); window.history.back();</script>";
} else if ($senha !== $confirmaSenha) {
    echo "<script>alert('As senhas não coincidem. Tente novamente.'); window.history.back();</script>";
} else if ($linha_senha > 0) {
    echo "<script>alert('Essa senha já existe. Tente outra.'); window.history.back();</script>";
} else {
    $encontrou = $respositorioUsuario->cadastrarUsuario($usuarioNovo);
    header('Location: login.php');
}
?>
