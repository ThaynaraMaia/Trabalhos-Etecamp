<?php
session_start();
include_once '../../../classes/class_IRepositorioUsuarios.php';

// Verificar se usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuário não logado'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = intval($_POST['id_usuario'] ?? 0);
    $id_postagem = intval($_POST['id_postagem'] ?? 0);
    
    // Verificar se IDs são válidos
    if ($id_usuario <= 0 || $id_postagem <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'IDs inválidos'
        ]);
        exit;
    }
    
    // Verificar se o usuário da sessão corresponde ao enviado
    if ($id_usuario != $_SESSION['id_usuario']) {
        echo json_encode([
            'success' => false,
            'message' => 'Usuário não autorizado'
        ]);
        exit;
    }
    
    try {
        // Toggle curtida
        $status = $respositorioUsuario->toggleCurtida($id_usuario, $id_postagem);
        $total_curtidas = $respositorioUsuario->contarCurtidas($id_postagem);
        
        echo json_encode([
            'success' => true,
            'status' => $status,
            'total_curtidas' => $total_curtidas
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Erro interno: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método não permitido'
    ]);
}
?>