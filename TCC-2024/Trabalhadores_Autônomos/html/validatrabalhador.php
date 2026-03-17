<?php
session_start();
include_once('../backend/Conexao.php');

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$confirmaSenha = $_POST['ConfirmaSenha'] ?? '';

if (empty($email) || empty($senha) || empty($confirmaSenha)) {
    $_SESSION['mensagem'] = "Preencha todos os campos.";
    header('Location: ./LoginTrabalhador.php');
    exit();
}

if ($senha !== $confirmaSenha) {
    $_SESSION['mensagem'] = "As senhas não coincidem.";
    header('Location: ./LoginTrabalhador.php');
    exit();
}


$sql_adm = "SELECT * FROM adm WHERE email = ?";
$stmt_adm = $conn->prepare($sql_adm);
$stmt_adm->bind_param("s", $email);
$stmt_adm->execute();
$result_adm = $stmt_adm->get_result();

if ($result_adm->num_rows > 0) {
    $row_adm = $result_adm->fetch_assoc();
    if (password_verify($senha, $row_adm['senha'])) {
        $_SESSION['email'] = $email;
        $_SESSION['logado'] = true;
        header('Location: ./admin/homeAdm.php');
        exit();
    } else {
        $_SESSION['mensagem'] = "Senha incorreta para administrador.";
        header('Location: ./LoginTrabalhador.php');
        exit();
    }
} else {
 
    $sql_trabalhador = "SELECT * FROM trabalhador WHERE email = ?";
    $stmt_trabalhador = $conn->prepare($sql_trabalhador);
    $stmt_trabalhador->bind_param("s", $email);
    $stmt_trabalhador->execute();
    $result_trabalhador = $stmt_trabalhador->get_result();

    if ($result_trabalhador->num_rows > 0) {
        $registroUsuario = $result_trabalhador->fetch_object();
        if (password_verify($senha, $registroUsuario->senha)) {
            $_SESSION['id_trabalhador'] = $registroUsuario->id_trabalhador;
            $_SESSION['tipo_usuario'] = 'trabalhador';
            $_SESSION['nome'] = $registroUsuario->nome;
            $_SESSION['email'] = $registroUsuario->email;
            $_SESSION['status'] = $registroUsuario->status;
            $_SESSION['permissao'] = $registroUsuario->permissao;
            $_SESSION['logado'] = true;
            header('Location: ./trabalhador/homeLogado.php');
            exit();
        } else {
            $_SESSION['mensagem'] = "Senha incorreta para trabalhador.";
            header('Location: ./LoginTrabalhador.php');
            exit();
        }
    } else {
        $_SESSION['mensagem'] = "Usuário não encontrado.";
        header('Location: ./LoginTrabalhador.php');
        exit();
    }

    $stmt_trabalhador->close();
}

$stmt_adm->close();
$conn->close();
?>
