<?php
session_start();
include_once '../classes/class_IRepositorioInstamar.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuário não logado'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'] ?? 0;
    $id_comentario = intval($_POST['id_comentario'] ?? 0);

    if ($id_usuario <= 0 || $id_comentario <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'IDs inválidos'
        ]);
        exit;
    }

    $repositorio = new ReposiorioInstamarMYSQL();

    // Verificar se o comentário existe e não é do próprio usuário
    $comentario = $repositorio->buscarComentarioPorId($id_comentario);
    if (!$comentario) {
        echo json_encode([
            'success' => false,
            'message' => 'Comentário não encontrado'
        ]);
        exit;
    }

    if ($comentario['id_usuario'] == $id_usuario) {
        echo json_encode([
            'success' => false,
            'message' => 'Você não pode denunciar seu próprio comentário'
        ]);
        exit;
    }

    // Verificar se já denunciou
    if ($repositorio->verificarDenunciaComentarioExistente($id_usuario, $id_comentario)) {
        echo json_encode([
            'success' => false,
            'message' => 'Você já denunciou este comentário'
        ]);
        exit;
    }

    // Adicionar denúncia
    if ($repositorio->adicionarDenunciaComentario($id_usuario, $id_comentario)) {
        echo json_encode([
            'success' => true,
            'message' => 'Comentário denunciado com sucesso'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao registrar denúncia'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método não permitido'
    ]);
}
?>