<?php
include_once '../conn/classes/class_IRepositorioUsuario.php';

session_start();

$id = $_SESSION['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

$respositorioUsuario->atualizaPerfil($id, $nome, $email, $senha);
header('location: ../html/usuario.php'); 

?>