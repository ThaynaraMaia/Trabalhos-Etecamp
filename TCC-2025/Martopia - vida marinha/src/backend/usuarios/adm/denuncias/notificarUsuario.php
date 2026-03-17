<?php
// notificardenuncias.php

session_start();
// Adicione uma verificação de segurança para garantir que apenas admins logados acessem
if (!isset($_SESSION['tipo']) || !$_SESSION['logado']) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Acesso não autorizado.']);
    exit();
}

include_once '../../../classes/class_IRepositorioInstamar.php';

// Define o cabeçalho da resposta como JSON
header('Content-Type: application/json');

// Inicializa a resposta padrão
$response = ['status' => 'error', 'message' => 'Requisição inválida.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_post'])) {
    $id_post = intval($_POST['id_post']);
    
    // Supondo que a classe ReposiorioInstamarMYSQL está sendo instanciada corretamente
    $respositorioInstamar = new ReposiorioInstamarMYSQL();
    $conn = $respositorioInstamar->getConexao();

    // 1. VERIFICA SE A NOTIFICAÇÃO JÁ FOI ENVIADA
    $sqlCheck = "SELECT id_notificacao FROM notificacoes WHERE id_post = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $id_post);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        $response = ['status' => 'info', 'message' => 'A notificação para este post já foi enviada anteriormente.'];
    } else {
        // 2. BUSCA O DONO DO POST
        $sqlUsuario = "SELECT id_usuario FROM postagens WHERE id = ?";
        $stmt = $conn->prepare($sqlUsuario);
        $stmt->bind_param("i", $id_post);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($row = $resultado->fetch_assoc()) {
            $id_usuario = intval($row['id_usuario']);
            $mensagem = "Sua postagem foi denunciada e está sob análise por violar as diretrizes da comunidade.";

            // 3. INSERE A NOTIFICAÇÃO
            $sqlInsert = "INSERT INTO notificacoes (id_usuario, id_post, mensagem) VALUES (?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("iis", $id_usuario, $id_post, $mensagem);

            if ($stmtInsert->execute()) {
                $response = ['status' => 'success', 'message' => "Notificação enviada com sucesso para o autor do post!"];
            } else {
                $response = ['status' => 'error', 'message' => 'Erro ao registrar a notificação. Tente novamente.'];
            }
            $stmtInsert->close();
        } else {
            $response = ['status' => 'error', 'message' => 'Post não encontrado. Não foi possível notificar.'];
        }
        $stmt->close();
    }
    $stmtCheck->close();
    $conn->close();
}

// Retorna a resposta em formato JSON
echo json_encode($response);

// REMOVIDO: O redirecionamento header() e exit() que quebravam o fluxo AJAX.
?>