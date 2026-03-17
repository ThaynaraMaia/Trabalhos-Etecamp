<?php
session_start();
include_once('Conexao.php');

// Receber os dados do formulário
$id_atualizacao = $_POST['id_atualizacao'];
$acao = $_POST['acao'];

// Buscar as informações da solicitação pendente
$sql = "SELECT * FROM atualizacoes_pendentes WHERE id_atualizacoes_pendentes = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_atualizacao);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $atualizacao = $result->fetch_assoc();

    if ($acao == 'aprovar') {
        // Aprovar a solicitação: Atualizar a tabela `trabalhador`
        $sql_update = "UPDATE trabalhador 
                       SET nome = ?, email = ?, contato = ?, data_nasc = ?, descricao = ?, 
                           foto_perfil = ?, foto_banner = ?, foto_trabalho1 = ?, foto_trabalho2 = ?, foto_trabalho3 = ?
                       WHERE id_trabalhador = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param(
            "ssssssssssi",
            $atualizacao['nome'],
            $atualizacao['email'],
            $atualizacao['contato'],
            $atualizacao['data_nasc'],
            $atualizacao['descricao'],
            $atualizacao['foto_perfil'],
            $atualizacao['foto_banner'],
            $atualizacao['foto_trabalho1'],
            $atualizacao['foto_trabalho2'],
            $atualizacao['foto_trabalho3'],
            $atualizacao['id_trabalhador']
        );
        if ($stmt_update->execute()) {
            // Marcar a solicitação como aprovada
            $sql_aprovar = "UPDATE atualizacoes_pendentes SET aprovado = 1 WHERE id_atualizacoes_pendentes = ?";
            $stmt_aprovar = $conn->prepare($sql_aprovar);
            $stmt_aprovar->bind_param("i", $id_atualizacao);
            $stmt_aprovar->execute();
        }
        $_SESSION['mensagem'] = "Atualização aprovada com sucesso!";
    } elseif ($acao == 'rejeitar') {
        // Rejeitar a solicitação: Apenas marcar como rejeitada
        $sql_rejeitar = "UPDATE atualizacoes_pendentes SET aprovado = -1 WHERE id_atualizacoes_pendentes = ?";
        $stmt_rejeitar = $conn->prepare($sql_rejeitar);
        $stmt_rejeitar->bind_param("i", $id_atualizacao);
        $stmt_rejeitar->execute();
        $_SESSION['mensagem'] = "Atualização rejeitada.";
    }

    // Redirecionar de volta para a tela de solicitações
    header("Location: ../html/admin/solicitacoes.php");
} else {
    // Caso não encontre a atualização
    $_SESSION['erro'] = "Solicitação não encontrada.";
    header("Location: ../html/admin/solicitacoes.php");
}

$conn->close();
?>
