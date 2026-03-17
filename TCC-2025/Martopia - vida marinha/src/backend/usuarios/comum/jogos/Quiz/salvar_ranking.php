<?php
session_start();
header('Content-Type: application/json');

// Inclui as classes necessárias
require_once '../../../../classes/class_IRepositorioQuiz.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

// Receber dados JSON
$input = json_decode(file_get_contents('php://input'), true);

// Validar dados
if (!isset($input['id_usuario'], $input['acertos'], $input['tempo_segundos'], $input['dificuldade'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

// Usar o repositório global já instanciado
global $respositorioQuiz;

try {
    $resultado = $respositorioQuiz->salvarResultadoQuiz(
        $input['id_usuario'],
        $input['acertos'],
        $input['tempo_segundos'],
        $input['dificuldade']
    );

    if ($resultado) {
        echo json_encode(['success' => true, 'message' => 'Ranking salvo com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar no banco']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}
?>