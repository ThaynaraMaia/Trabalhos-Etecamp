<?php
session_start();
include_once('../backend/Conexao.php');
// Verificar se o trabalhador está logado
if (!isset($_SESSION['id_trabalhador']) || !$_SESSION['logado']) {
    $_SESSION['mensagem'] = "Você precisa estar logado para atualizar suas informações.";
    header('Location: ./LoginTrabalhador.php');
    exit();
}

// Pegar o ID do trabalhador logado
$idTrabalhador = $_SESSION['id_trabalhador'];

// Capturar dados do formulário
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$contato = $_POST['contato'] ?? '';
$data_nasc = $_POST['data_nasc'] ?? '';
$cidade = $_POST['cidade'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$id_area = $_POST['id_area'] ?? '';
$id_categoria = $_POST['id_categoria'] ?? '';

$foto_perfil = '';
$foto_trabalho1 = '';
$foto_trabalho2 = '';
$foto_trabalho3 = '';
$foto_banner = '';

function uploadArquivo($campoNome) {
    global $conn;
    if (isset($_FILES[$campoNome]) && $_FILES[$campoNome]['error'] == 0) {
        $diretorio = '../uploads/';
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
$foto_trabalho1 = uploadArquivo('foto_trabalho1');
$foto_trabalho2 = uploadArquivo('foto_trabalho2');
$foto_trabalho3 = uploadArquivo('foto_trabalho3');
$foto_banner = uploadArquivo('foto_banner');


// Verificar se os campos obrigatórios foram preenchidos
if (empty($nome) || empty($email) || empty($senha) || empty($id_area) || empty($id_categoria)) {
    $_SESSION['mensagem'] = "Preencha todos os campos obrigatórios.";
    header('Location: ./EditarPerfil.php');
    exit();
}

// Iniciar a query de atualização
$sql = "UPDATE trabalhador SET nome = ?, email = ?, contato = ?, data_nasc = ?, descricao = ?, id_area = ?, id_categoria = ?";

// Verificar se a senha foi preenchida e se a confirmação está correta
if (!empty($senha)) {    
    // Hashear a nova senha
    $senhaHasheada = password_hash($senha, PASSWORD_DEFAULT);
    $sql .= ", senha = ?"; // Adiciona o campo de senha na query
}
if (!empty($foto_perfil)) {
    $sql .= ", foto_perfil = ?";
}
if (!empty($foto_trabalho1)) {
    $sql .= ", foto_trabalho1 = ?";
}
if (!empty($foto_trabalho2)) {
    $sql .= ", foto_trabalho2 = ?";
}
if (!empty($foto_trabalho3)) {
    $sql .= ", foto_trabalho3 = ?";
}
if (!empty($foto_banner)) {
    $sql .= ", foto_banner = ?";
}
// Finaliza a query
$sql = " WHERE id_trabalhador = ?";

// Preparar a declaração
$stmt = $conn->prepare($sql);

// Verifica se a senha foi atualizada ou não
$parametros = [$nome, $email, $contato, $data_nasc, $descricao, $id_area, $id_categoria];

if (!empty($senha)) {
    $parametros[] = $senhaHasheada;
}
if (!empty($foto_perfil)) {
    $parametros[] = $foto_perfil;
}
if (!empty($foto_trabalho1)) {
    $parametros[] = $foto_trabalho1;
}
if (!empty($foto_trabalho2)) {
    $parametros[] = $foto_trabalho2;
}
if (!empty($foto_trabalho3)) {
    $parametros[] = $foto_trabalho3;
}
if (!empty($foto_banner)) {
    $parametros[] = $foto_banner;
}
$parametros[] = $idTrabalhador;

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
    // $_SESSION['cidade'] = $cidade;
    $_SESSION['descricao'] = $descricao;
    $_SESSION['id_area'] = $id_area;
    $_SESSION['id_categoria'] = $id_categoria;
    $_SESSION['foto_perfil'] = $foto_perfil;
    $_SESSION['foto_trabalho1'] = $foto_trabalho1;
    $_SESSION['foto_trabalho2'] = $foto_trabalho2;
    $_SESSION['foto_trabalho3'] = $foto_trabalho3;
    $_SESSION['foto_banner'] = $foto_banner;
} else {
    $_SESSION['mensagem'] = "Erro ao atualizar o perfil. Tente novamente.";
}

$stmt->close();
$conn->close();

// Redireciona para o perfil
header('Location: ../html/EditarPerfil.php');
