<?php
session_start();

// 🔐 Verificação de segurança
header('Content-Type: application/json');
if (!isset($_SESSION['tipo']) || !$_SESSION['logado']) {
    echo json_encode(['status' => 'error', 'message' => 'Acesso não autorizado.']);
    exit();
}

include_once '../../../classes/class_IRepositorioInstamar.php';

// 🧠 Resposta padrão
$response = ['status' => 'error', 'message' => 'Requisição inválida.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_comentario'])) {
    $id_comentario = intval($_POST['id_comentario']);

    $repositorio = new ReposiorioInstamarMYSQL();
    $conn = $repositorio->getConexao();

    // 1️⃣ Verifica se já foi notificado
    $sqlCheck = "SELECT id_notificacao FROM notificacoes WHERE id_comentario = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $id_comentario);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        $response = [
            'status' => 'info',
            'message' => 'A notificação para este comentário já foi enviada anteriormente.'
        ];
    } else {
        // 2️⃣ Busca o autor do comentário
        $sqlAutor = "SELECT id_usuario FROM comentarios WHERE id = ?";
        $stmtAutor = $conn->prepare($sqlAutor);
        $stmtAutor->bind_param("i", $id_comentario);
        $stmtAutor->execute();
        $resultado = $stmtAutor->get_result();

        if ($row = $resultado->fetch_assoc()) {
            $id_usuario = intval($row['id_usuario']);
            $mensagem = "Seu comentário foi denunciado e está sob análise por violar as diretrizes da comunidade.";

            // 3️⃣ Insere a notificação
            $sqlInsert = "INSERT INTO notificacoes (id_usuario, mensagem, id_comentario) VALUES (?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("isi", $id_usuario, $mensagem, $id_comentario);

            if ($stmtInsert->execute()) {
                $response = [
                    'status' => 'success',
                    'message' => 'Notificação enviada com sucesso ao autor do comentário!'
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Erro ao registrar a notificação.'
                ];
            }
            $stmtInsert->close();
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Comentário não encontrado.'
            ];
        }
        $stmtAutor->close();
    }

    $stmtCheck->close();
    $conn->close();
}

echo json_encode($response);
?>
