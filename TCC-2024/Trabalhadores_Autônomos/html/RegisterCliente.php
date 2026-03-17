<?php
include_once('../backend/Conexao.php');

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $contato = trim($_POST['contato']);
    $data_nascimento = $_POST['data_nascimento'];
    $senha = trim($_POST['senha']);
    $confirmaSenha = trim($_POST['ConfirmaSenha']);
    $id_area = intval($_POST['id_area']); 
    $id_area = $_POST['id_area']; 
    $fotoDePerfil = $_FILES['foto_de_perfil'];

    $dataAtual = new DateTime();
    $dataNascimento = new DateTime($data_nascimento);
    $idade = $dataAtual->diff($dataNascimento)->y;

    // Validações
    if (empty($nome) || empty($email) || empty($senha) || empty($confirmaSenha) || empty($id_area) || empty($contato) || empty($data_nascimento)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Todos os campos são obrigatórios.']);
        exit;
    }

    if ($senha !== $confirmaSenha) {    
        echo json_encode(['sucesso' => false, 'mensagem' => 'As senhas não coincidem.']);
        exit;
    }

    if ($idade < 15) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Você deve ter pelo menos 15 anos para se cadastrar.']);
        exit;
    }
  // Verifica se o e-mail já está registrado
  $sql = "SELECT COUNT(*) FROM cliente WHERE email = ?";
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
        $erro = error_get_last();
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao enviar a foto de perfil: ' . $erro['message']]);
        exit;
    }

    // Criptografa a senha
    $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

    // Insere o novo cliente na base de dados
    try {
        $sql = "INSERT INTO cliente (nome, email, contato, data_nasc, senha, id_area, foto_perfil) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro na preparação da consulta: ' . $conn->error]);
            exit;
        }

        $stmt->bind_param("sssssis", $nome, $email, $contato, $data_nascimento, $senhaCriptografada, $id_area, $caminhoCompleto);

        if ($stmt->execute()) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Cadastro realizado com sucesso!', 'mostrarBotaoLogin' => true]);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao realizar o cadastro: ' . $stmt->error]);
        }
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro: ' . $e->getMessage()]);
    } finally {
        $stmt->close();
        $conn->close();
    }
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método de requisição inválido.']);
}
?>