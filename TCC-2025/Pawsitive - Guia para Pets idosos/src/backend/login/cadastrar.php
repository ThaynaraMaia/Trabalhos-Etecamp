<?php

session_start();
include('../classes/class_conexao.php');

$nome = mysqli_real_escape_string($mysqli, trim($_POST['nome']));
$email = mysqli_real_escape_string($mysqli, trim($_POST['email']));
$senha = password_hash(trim($_POST['senha']), PASSWORD_DEFAULT);


$tipo = 'tutor/adotante';
$status = 'ativo';
$fotoPadrao = '/imgUsuarios/user_padrao.png';

$sql = "SELECT COUNT(*) as total FROM tblusuarios WHERE email_usuario = '$email'";
$result = mysqli_query($mysqli, $sql);

if (!$result) {
    die("Erro na query: " . mysqli_error($mysqli));
}
$row = mysqli_fetch_assoc($result);

if ($row['total'] == 1) {
    $_SESSION['usuario_existe'] = true;
    header("Location: login_form.php");
    exit;
}

$sql = "INSERT INTO tblusuarios (nome_usuario, email_usuario, senha_usuario, tipo_usuario, status_usuario, foto_usuario)
VALUES ('$nome', '$email', '$senha', '$tipo', '$status', '$fotoPadrao')";
$result = mysqli_query($mysqli, $sql);


if ($result) {
    $_SESSION['status_cadastro'] = true;
    $_SESSION['nome_usuario'] = $_POST['nome'];
}


$mysqli->close();
header("Location: ../login/login_form.php");
exit;