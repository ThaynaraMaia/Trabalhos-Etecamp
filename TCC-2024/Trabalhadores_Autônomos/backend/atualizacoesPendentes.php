<?php
session_start();
include_once('../backend/Conexao.php');


$id_trabalhador = $_SESSION['id_trabalhador'];

// Receber os dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = !empty($_POST['senha']) ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : null;
$contato = $_POST['contato'];
$data_nasc = $_POST['data_nasc'];
$descricao = $_POST['descricao'];

// Manipulação de arquivos (fotos)
$foto_perfil = !empty($_FILES['foto_perfil']['name']) ? $_FILES['foto_perfil']['name'] : null;
$foto_banner = !empty($_FILES['foto_banner']['name']) ? $_FILES['foto_banner']['name'] : null;
$foto_trabalho1 = !empty($_FILES['foto_trabalho1']['name']) ? $_FILES['foto_trabalho1']['name'] : null;
$foto_trabalho2 = !empty($_FILES['foto_trabalho2']['name']) ? $_FILES['foto_trabalho2']['name'] : null;
$foto_trabalho3 = !empty($_FILES['foto_trabalho3']['name']) ? $_FILES['foto_trabalho3']['name'] : null;

// Salvar as fotos na pasta uploads
if ($foto_perfil) {
    move_uploaded_file($_FILES['foto_perfil']['tmp_name'], "../../uploads/" . $foto_perfil);
}
if ($foto_banner) {
    move_uploaded_file($_FILES['foto_banner']['tmp_name'], "../../uploads/" . $foto_banner);
}
if ($foto_trabalho1) {
    move_uploaded_file($_FILES['foto_trabalho1']['tmp_name'], "../../uploads/" . $foto_trabalho1);
}
if ($foto_trabalho2) {
    move_uploaded_file($_FILES['foto_trabalho2']['tmp_name'], "../../uploads/" . $foto_trabalho2);
}
if ($foto_trabalho3) {
    move_uploaded_file($_FILES['foto_trabalho3']['tmp_name'], "../../uploads/" . $foto_trabalho3);
}

// Inserir a atualização pendente na tabela `atualizacoes_pendentes`
$sql = "INSERT INTO atualizacoes_pendentes (id_trabalhador, nome, email, senha, contato, data_nasc, descricao, foto_perfil, foto_banner, foto_trabalho1, foto_trabalho2, foto_trabalho3)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isssssssssss", $id_trabalhador, $nome, $email, $senha, $contato, $data_nasc, $descricao, $foto_perfil, $foto_banner, $foto_trabalho1, $foto_trabalho2, $foto_trabalho3);

if ($stmt->execute()) {
    // Redirecionar com mensagem de sucesso
    $_SESSION['mensagem'] = "Atualização enviada para aprovação.";
    header("Location: ../html/trabalhador/EditarPerfil.php");
} else {
    // Redirecionar com mensagem de erro
    $_SESSION['erro'] = "Erro ao enviar atualização. Tente novamente.";
    header("Location: ../html/trabalhador/EditarPerfil.php");
}
?>
