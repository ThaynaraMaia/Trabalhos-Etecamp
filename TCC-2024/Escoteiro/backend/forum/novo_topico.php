<?php
session_start();
include_once '../classes/class_IRepositorioTopico.php';
include_once '../classes/conn.php';

// Capturando os dados do formulário
$nome = $_POST['nome'];
$body = $_POST['body'];
$restrito = $_POST['restrito'];
if($_FILES['anexo']!=''){

    $img = $_FILES['anexo']; // Agora $_FILES['anexo'] é tratado como um array
    
    // Configuração de upload
    $uploadDir = 'uploads/';
    $fileName = basename($img['name']);
    $uploadPath = $uploadDir . $fileName;
    $tmp_name = $img['tmp_name']; // Caminho temporário do arquivo
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    // Verificando extensões permitidas
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
    
    if (!empty($nome) && !empty($body)) {
        if (in_array($fileType, $allowedExtensions)) {
            // Verifica se o arquivo foi carregado com sucesso
            if (move_uploaded_file($tmp_name, $uploadPath)) {
                // Preparando o SQL para inserir os dados
                $sql_add = "INSERT INTO topico (nome, body, data, anexos, userid, restrito) VALUES (
                    '".$conn->real_escape_string($nome)."',
                    '".$conn->real_escape_string($body)."',
                    '".date('Y/m/d')."',
                    '".$fileName."',
                    ".$_SESSION['userid'].",
                    '".$restrito."'
                );";
    
                // Executando a query
                if ($conn->query($sql_add)) {
                    // Redireciona para a página principal do fórum
                    header('Location: ../../backend/forum/main.php');
                    exit();
                } else {
                    echo "Erro ao salvar o tópico no banco de dados: " . $conn->error;
                }
            } else {
                echo "Erro ao fazer upload do arquivo.";
            }
        } else {
            echo "Extensão de arquivo não permitida.";
        }
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}
else{
    $sql_add = "INSERT INTO topico (nome, body, data, anexos, userid, restrito) VALUES (
        '".$conn->real_escape_string($nome)."',
        '".$conn->real_escape_string($body)."',
        '".date('Y/m/d')."',
        '',
        ".$_SESSION['userid'].",
        '".$restrito."'
    );";

    // Executando a query
    if ($conn->query($sql_add)) {
        // Redireciona para a página principal do fórum
        header('Location: ../../backend/forum/main.php');
        exit();
    } else {
        echo "Erro ao salvar o tópico no banco de dados: " . $conn->error;
    }
}

exit();


?>