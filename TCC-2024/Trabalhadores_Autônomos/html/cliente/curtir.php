<?php
session_start();
include_once('../../backend/Conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['id_cliente'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não está logado.']);
    exit;
}

$id_cliente = $_SESSION['id_cliente'];

// Inicializa as variáveis
$totalCurtidas = 0; // Contagem inicial das curtidas
$hasLiked = false; // Inicializa como não curtido

// Consulta para obter o total de curtidas
$sqlCurtidas = "SELECT COUNT(*) as total FROM curtidas WHERE id_trabalhador = '$id_trabalhador'";
$resultCurtidas = mysqli_query($conn, $sqlCurtidas);
if ($rowCurtidas = mysqli_fetch_assoc($resultCurtidas)) {
    $totalCurtidas = $rowCurtidas['total']; // Armazena a contagem de curtidas
}

// Verifica se o trabalhador foi curtido pelo cliente
$sqlLike = "SELECT * FROM curtidas WHERE id_trabalhador = '$id_trabalhador' AND id_cliente = '$id_cliente'";
$resultLike = mysqli_query($conn, $sqlLike);
if (mysqli_num_rows($resultLike) > 0) {
    $hasLiked = true; // Define como true se já curtiu
}


// Recebe os dados JSON
$data = json_decode(file_get_contents("php://input"), true);
$id_trabalhador = $data['id'];
$action = $data['action'];

if ($action === 'curtir') {
    // Adiciona a curtida
    $sql = "INSERT INTO curtidas (id_cliente, id_trabalhador) VALUES ('$id_cliente', '$id_trabalhador')";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao curtir.']);
    }
} elseif ($action === 'descurtir') {
    // Remove a curtida
    $sql = "DELETE FROM curtidas WHERE id_cliente = '$id_cliente' AND id_trabalhador = '$id_trabalhador'";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao descurtir.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
}
?>
