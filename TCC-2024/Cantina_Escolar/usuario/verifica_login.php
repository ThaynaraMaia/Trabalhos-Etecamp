<?php
session_start();
include_once '../conn/classes/class_IRepositorioUsuarios.php';
include_once '../conn/classes/class_IRepositorioFuncionario.php';

$email = $_POST['email'];
$senha = $_POST['senha'];

$encontrou = $respositorioUsuario->verificaLogin($email, $senha);
$registroUsuario = $encontrou->fetch_object();
$linhas = $encontrou->num_rows;

$encontrou_funcio = $respositorioFuncionario->verificaLogin_funcionario($email, $senha);
$registro_funcio = $encontrou_funcio->fetch_object();
$linhas_funcio = $encontrou_funcio->num_rows;

if ($linhas > 0) {
    $_SESSION['mensagem'] = $mensagem;
    $_SESSION['id'] = $registroUsuario->id;
    $_SESSION['nome_completo'] = $registroUsuario->nome_completo;
    $_SESSION['cpf'] = $registroUsuario->cpf;
    $z_SESSION['email'] = $registroUsuario->email;
    $_SESSION['senha'] = $registroUsuario->senha;
    $_SESSION['tipo'] = $registroUsuario->tipo;
    $_SESSION['status'] = $registroUsuario->status;
    $_SESSION['foto'] = $registroUsuario->foto;
    $_SESSION['logado'] = true;
    if ($_SESSION['tipo'] == 1 && $linhas > 0) {
        $mensagem = "";
        header('Location:../Adm/home_adm.php');
    } elseif ($_SESSION['tipo'] == 0 && $linhas > 0) {
        header('Location:../html/home.php');
    } else {
        $mensagem = "Acesso não permitido!!!!!";
        $_SESSION['mensagem'] = $mensagem;
    }

    if ($_SESSION['tipo'] == 2 && $linhas > 0) {
        $mensagem = "";
        header('Location:../Funcionarios/cardapio.funcio.php');
    } elseif ($_SESSION['tipo'] == 0 && $linhas > 0) {
        header('Location:../html/home.php');
    } else {
        $mensagem = "Acesso não permitido!!!!!";
        $_SESSION['mensagem'] = $mensagem;
    }
} elseif ($linhas_funcio > 0) {
    $_SESSION['id'] = $registro_funcio->id;
    $_SESSION['nome_completo'] = $registro_funcio->nome_completo;
    $_SESSION['cpf'] = $registro_funcio->cpf;
    $z_SESSION['email'] = $registro_funcio->email;
    $_SESSION['senha'] = $registro_funcio->senha;
    $_SESSION['tipo'] = $registro_funcio->tipo;
    $_SESSION['status'] = $registro_funcio->status;
    $_SESSION['foto'] = $registro_funcio->foto;
    $_SESSION['logado'] = true;
    if ($_SESSION['tipo'] == 1 && $linhas_funcio > 0) {
        $mensagem = "";
        header('Location:../Adm/home_adm.php');
    } elseif ($_SESSION['tipo'] == 0 && $linhas_funcio > 0) {
        header('Location:../html/home.php');
    } else {
        $mensagem = "Acesso não permitido!!!!!";
        $_SESSION['mensagem'] = $mensagem;
    }
    if ($_SESSION['tipo'] == 2 && $linhas_funcio > 0) {
        $mensagem = "";
        header('Location:../Funcionarios/cardapio.funcio.php');
    } elseif ($_SESSION['tipo'] == 0 && $linhas_funcio > 0) {
        header('Location:../html/home.php');
    } else {
        $mensagem = "Acesso não permitido!!!!!";
        $_SESSION['mensagem'] = $mensagem;
    }
} else {
    echo "<script>alert('Usuário não encontrado . Tente novamente.'); window.history.back();</script>";
    $_SESSION['logado'] = false;
    exit;
}
?>
