<?php
session_start();
include_once('../../backend/Conexao.php');

// Evitar espaços em branco antes da saída de JSON
ob_start();

header('Content-Type: application/json');

// Verifica se o usuário está logado
if (!isset($_SESSION['id_cliente'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não está logado.']);
    exit;
}

$id_cliente = $_SESSION['id_cliente'];
$data = json_decode(file_get_contents('php://input'), true);

// Verifica se os dados esperados foram enviados
if (!isset($data['id_trabalhador']) || !isset($data['action'])) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
    exit;
}

$id_trabalhador = $data['id_trabalhador'];
$action = $data['action'];

if ($action === 'adicionar') {
    // Adiciona o trabalhador aos favoritos
    $sql = "INSERT INTO favoritos (id_trabalhador, id_cliente) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $id_trabalhador, $id_cliente);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Adicionado aos favoritos.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao adicionar aos favoritos: ' . $stmt->error]);
    }

    $stmt->close();

} elseif ($action === 'remover') {
    // Remove o trabalhador dos favoritos
    $sql = "DELETE FROM favoritos WHERE id_trabalhador = ? AND id_cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $id_trabalhador, $id_cliente);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Removido dos favoritos.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao remover dos favoritos: ' . $stmt->error]);
    }

    $stmt->close();

} else {
    echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
}

// Fecha a conexão com o banco de dados
$conn->close();
ob_end_flush();  // Finaliza o buffer de saída
?>
