<?php
session_start();
include_once('./Conexao.php');

// Receber os dados via POST
$id_trabalhador = $_POST['id_trabalhador'] ?? null;
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$reclamacao = $_POST['reclamacao'] ?? '';

if ($id_trabalhador) {
    // Verifica se o trabalhador existe
    $sql = "SELECT * FROM trabalhador WHERE id_trabalhador = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_trabalhador); // Vincula o id_trabalhador como inteiro
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Preparar a query de inserção
        $sql_insert = "INSERT INTO reclamacao_trabalhador (id_trabalhador, nome, email, reclamacao) 
                       VALUES (?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        
        // Bind dos parâmetros (id_trabalhador como inteiro, os outros como strings)
        $stmt_insert->bind_param("isss", $id_trabalhador, $nome, $email, $reclamacao);
        
        // Executa a query e verifica se foi bem-sucedida
        if ($stmt_insert->execute()) {
            $_SESSION['mensagem'] = "Feedback enviado!";
        } else {
            $_SESSION['mensagem'] = "Erro ao enviar o feedback.";
        }
        
        $stmt_insert->close();
    } else {
        $_SESSION['mensagem'] = "Trabalhador não encontrado.";
    }

    $stmt->close();
} else {
    $_SESSION['mensagem'] = "ID do trabalhador não fornecido.";
}

// Fecha a conexão com o banco de dados
$conn->close();

header('Location: ../html/trabalhador/homeLogado.php');

?>
