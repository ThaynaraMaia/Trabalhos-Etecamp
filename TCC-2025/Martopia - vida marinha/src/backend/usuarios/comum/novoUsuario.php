<?php
include_once '../../classes/class_IRepositorioUsuarios.php';

session_start();

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];
// $foto = $_FILES['foto'];

// $encontrou = $respositorioUsuario->verificaFoto($foto);
$foto = null; 
// Criando objeto usuário (ajuste o construtor se necessário)
$usuario = new usuario('', $nome, $email, $senha, $telefone, 0, 0, $foto);

// Verifica se o email já existe
$verificaUsuario   = $respositorioUsuario->verificaEmail($email);
$registroEncontrado = $verificaUsuario->num_rows;

if ($registroEncontrado > 0) {
    $_SESSION['cadastro'] = "Este email já está cadastrado";
    header('Location:../../login/login.php');
    exit();
}

// Cadastra o usuário
$salvaUsuario = $respositorioUsuario->cadastrarUsuario($usuario);
if ($salvaUsuario) {
    $_SESSION['cadastro'] = "Usuário cadastrado com sucesso";
    header('Location:../../login/login.php');
    exit();
} else {
    $_SESSION['cadastro'] = "Erro ao cadastrar usuário";
    header('Location:../../login/login.php');
    exit();
}

?>