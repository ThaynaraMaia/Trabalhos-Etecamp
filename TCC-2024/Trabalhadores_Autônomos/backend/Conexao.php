<?php
$servername = "localhost";
$username = "root";  // Usuário do banco de dados
$password = "";      // Senha do banco de dados (vazia)
$dbname = "banco_jundtask";  // Nome do banco de dados

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Definir o conjunto de caracteres
$conn->set_charset("utf8mb4");
?>
