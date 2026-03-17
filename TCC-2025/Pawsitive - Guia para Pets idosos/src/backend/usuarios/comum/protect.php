<?php

if(!isset($_SESSION)){
    session_start();
}

if(!isset($_SESSION['id'])){
    die("Acesso negado. Você precisa estar logado para acessar esta página. <p> <a href=\"../../login/login_form.php\"> Faça login</a></p>");
}

?>

<!-- 
Nas páginas protegidas, que exigem o login, coloque no topo o código de proteção que você mandou:

php
session_start();

if (!isset($_SESSION['user'])) {
    die("Acesso negado. Você precisa estar logado para acessar esta página. <p> <a href='../../login/login_form.php'>Faça login</a></p>");
} -->