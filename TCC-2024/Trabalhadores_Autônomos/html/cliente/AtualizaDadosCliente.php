<?php
session_start();
include_once('../../backend/Conexao.php');
// Verificar se o cliente está logado
if (!isset($_SESSION['id_cliente']) || !$_SESSION['logado']) {
    $_SESSION['mensagem'] = "Você precisa estar logado para atualizar suas informações.";
    header('Location: ../LoginUsuario.php');
    exit();
}

// Pegar o ID do trabalhador logado
$id_cliente = $_SESSION['id_cliente'];

// Capturar dados do formulário
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$contato = $_POST['contato'] ?? '';
$data_nasc = $_POST['data_nasc'] ?? ''; 
$id_area = $_POST['id_area'] ?? '';

$foto_perfil ='';

function uploadArquivo($campoNome) {
    global $conn;
    if (isset($_FILES[$campoNome]) && $_FILES[$campoNome]['error'] == 0) {
        $diretorio = '../../uploads/';
        $nomeArquivo = basename($_FILES[$campoNome]['name']);
        $caminhoCompleto = $diretorio . $nomeArquivo;
        $tipoArquivo = strtolower(pathinfo($caminhoCompleto, PATHINFO_EXTENSION));
        $tiposPermitidos = array("jpg", "jpeg", "png");
        
        if (in_array($tipoArquivo, $tiposPermitidos)) {
            if (move_uploaded_file($_FILES[$campoNome]['tmp_name'], $caminhoCompleto)) {
                return $nomeArquivo;
            } else {
                $_SESSION['mensagem'] = "Erro ao mover o arquivo de upload.";
            }
        } else {
            $_SESSION['mensagem'] = "Formato de arquivo não suportado. Use JPG, JPEG, ou PNG.";
        }
    }
    return '';
}

// Atualizar variáveis de fotos
$foto_perfil = uploadArquivo('foto_perfil');

// Verificar se os campos obrigatórios foram preenchidos
if (empty($nome) || empty($email) || empty($senha) || empty($id_area)) {
    $_SESSION['mensagem'] = "Preencha todos os campos obrigatórios.";
    header('Location: ./EditarPerfilCliente.php');
    exit();
}

// Iniciar a query de atualização
$sql = "UPDATE cliente SET nome = ?, email = ?, contato = ?, id_area = ?";

// Verificar se a senha foi preenchida e se a confirmação está correta
if (!empty($senha)) {    
    // Hashear a nova senha
    $senhaHasheada = password_hash($senha, PASSWORD_DEFAULT);
    $sql .= ", senha = ?"; // Adiciona o campo de senha na query
}
if (!empty($foto_perfil)) {
    $sql .= ", foto_perfil = ?";
}

// Finaliza a query
$sql .= " WHERE id_cliente = ?";

// Preparar a declaração
$stmt = $conn->prepare($sql);

// Verifica se a senha foi atualizada ou não
$parametros = [$nome, $email, $contato, $id_area];

if (!empty($senha)) {
    $parametros[] = $senhaHasheada;
}
if (!empty($foto_perfil)) {
    $parametros[] = $foto_perfil;
}

$parametros[] = $id_cliente;

// Definir os tipos de parâmetros dinamicamente
$tipos = str_repeat('s', count($parametros) - 1) . 'i';
$stmt->bind_param($tipos, ...$parametros);

// Executa a query
if ($stmt->execute()) {
    $_SESSION['mensagem'] = "Perfil atualizado com sucesso!";
    
    // Atualizar dados da sessão com as novas informações
    $_SESSION['nome'] = $nome;
    $_SESSION['email'] = $email;
    $_SESSION['senha'] = $senha;
    $_SESSION['contato'] = $contato;
    $_SESSION['data_nasc'] = $data_nasc;
    $_SESSION['id_area'] = $id_area;
    $_SESSION['foto_perfil'] = $foto_perfil;

} else {
    $_SESSION['mensagem'] = "Erro ao atualizar o perfil. Tente novamente.";
}

$stmt->close();
$conn->close();

// Redireciona para o perfil
header('Location: ./EditarPerfilCliente.php');
