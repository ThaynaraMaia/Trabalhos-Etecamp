<?php
session_start();
include_once '../classes/class_IRepositorioUsuarios.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$nome = trim($_POST['nome']);
$email = trim($_POST['email']);
$senha_atual = $_POST['senha_atual'];
$nova_senha = $_POST['nova_senha'];

// Busca usuário no banco
// Nota: Aqui o nome da variável está com erro de digitação
$dados = $respositorioUsuario->buscarUsuario($id_usuario);

// Criptografa a senha atual para comparação com o hash do banco
// A senha no banco foi criptografada com sha1
$senha_atual_cripto = sha1("Gtha@#$%!") . sha1($senha_atual) . sha1("haHa123$#@!");

// Verifica se a senha atual digitada é igual à senha do banco
// Atenção: Lembre-se que o método buscarUsuario() deve estar seguro contra SQL Injection
if ($senha_atual_cripto != $dados['senha']) {
    echo "<script>alert('Senha atual incorreta!'); window.location.href='trocarperfil.php';</script>";
    exit;
}

// Criptografa a nova senha da mesma forma que o cadastro
$nova_senha_cripto = sha1("Gtha@#$%!") . sha1($nova_senha) . sha1("haHa123$#@!");

// Atualiza os dados
// Usa a nova senha criptografada
$respositorioUsuario->atualizarUsuario($id_usuario, $nome, $email, $nova_senha_cripto);

// Atualiza a sessão
$_SESSION['nome'] = $nome;
$_SESSION['email'] = $email;

echo "<script>alert('Dados atualizados com sucesso!'); window.location.href='trocarperfil.php';</script>";