<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$database = "enemplatd_bd";

$conexao = mysqli_connect($host, $usuario, $senha, $database);

if (!$conexao) {
    die("Conexão falhou: " . mysqli_connect_error());
}
?>