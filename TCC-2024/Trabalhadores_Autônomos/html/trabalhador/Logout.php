<?php
// Inicia a sessão
session_start();

// Limpa os dados da sessão
$_SESSION = [];

// Destrói a sessão
session_destroy();

// Redireciona para a página de login ou homepage
header("Location: ../home.php");
exit();
?>
