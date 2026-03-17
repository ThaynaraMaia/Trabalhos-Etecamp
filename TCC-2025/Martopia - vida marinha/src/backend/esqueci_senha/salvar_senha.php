<?php
session_start();
require_once "../classes/class_Conexao.php";

$token = $_POST['token'] ?? '';
$senha = $_POST['senha'] ?? '';
$confirma = $_POST['confirma'] ?? '';

// Validações
if (empty($token) || empty($senha) || empty($confirma)) {
    $_SESSION['mensagem_reset'] = " Todos os campos são obrigatórios!";
    $_SESSION['tipo_reset'] = "erro";
    header("Location: resetar_senha.php?token=" . urlencode($token));
    exit;
}

if ($senha !== $confirma) {
    $_SESSION['mensagem_reset'] = " As senhas não coincidem!";
    $_SESSION['tipo_reset'] = "erro";
    header("Location: resetar_senha.php?token=" . urlencode($token));
    exit;
}

if (strlen($senha) < 6) {
    $_SESSION['mensagem_reset'] = " A senha deve ter no mínimo 6 caracteres!";
    $_SESSION['tipo_reset'] = "erro";
    header("Location: resetar_senha.php?token=" . urlencode($token));
    exit;
}

// Conexão com banco
$con = new Conexao("localhost", "root", "", "vidamarinha");
$con->conectar();
$conn = $con->getConnection();

// Verificar se o token ainda é válido
$sqlVerifica = "SELECT id FROM usuarios WHERE token_recuperacao = ? AND token_expira > NOW()";
$resultVerifica = $con->executarQuery($sqlVerifica, [$token]);

if (mysqli_num_rows($resultVerifica) == 0) {
    $_SESSION['mensagem'] = " Link inválido ou expirado!";
    $_SESSION['tipo'] = "erro";
    header("Location: ../login/login.php");
    exit;
}

// Criptografa a senha da mesma forma que no cadastrarUsuario
$senha_cripto = sha1("Gtha@#$%!") . sha1($senha) . sha1("haHa123$#@!");

// Atualiza senha e limpa token
$sql = "UPDATE usuarios 
        SET senha = ?, token_recuperacao = NULL, token_expira = NULL 
        WHERE token_recuperacao = ?";
$result = $con->executarQuery($sql, [$senha_cripto, $token]);

if ($result) {
    $_SESSION['mensagem'] = " Senha alterada com sucesso! Faça login com sua nova senha.";
    $_SESSION['tipo'] = "sucesso";
} else {
    $_SESSION['mensagem'] = " Erro ao atualizar a senha. Tente novamente.";
    $_SESSION['tipo'] = "erro";
}

header("Location: ../login/login.php");
exit;
?>