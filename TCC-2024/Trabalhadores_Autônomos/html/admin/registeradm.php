<?php
include_once('../backend/Conexao.php');

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $confirmaSenha = trim($_POST['ConfirmaSenha']);
    $fotoDePerfil = $_FILES['foto_de_perfil'];
    $tipo = 'A'; // Pode ser usado para identificar o tipo de usuário como 'Administrador'

    // Validação de campos
    if (empty($nome) || empty($email) || empty($senha) || empty($confirmaSenha)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Todos os campos são obrigatórios.']);
        exit;
    }

    if ($senha !== $confirmaSenha) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'As senhas não coincidem.']);
        exit;
    }

    // Verifica se o e-mail já está registrado
    $sql = "SELECT COUNT(*) FROM adm WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'O e-mail já está registrado.']);
        exit;
    }

    // Processa o upload da foto de perfil
    $diretorio = '../uploads/'; 
    $nomeArquivo = basename($fotoDePerfil['name']);
    $caminhoCompleto = $diretorio . $nomeArquivo;

    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0777, true); 
    }

    $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($fotoDePerfil['type'], $tiposPermitidos)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Tipo de arquivo não permitido.']);
        exit;
    }

    if (!move_uploaded_file($fotoDePerfil['tmp_name'], $caminhoCompleto)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao enviar a foto de perfil.']);
        exit;
    }

    // Criptografa a senha
    $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

    // Insere o novo administrador na base de dados
    $sql = "INSERT INTO adm (nome, email, senha, foto_perfil, tipo) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nome, $email, $senhaCriptografada, $caminhoCompleto, $tipo);

    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Cadastro de administrador realizado com sucesso!']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao realizar o cadastro: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método de requisição inválido.']);
}
?>
