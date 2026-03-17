<?php
session_start();
include_once '../classes/class_IRepositorioInstamar.php';
include_once '../classes/class_Denuncia.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuário não logado'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$id_usuario = $_SESSION['id_usuario'] ?? 0;
$id_postagem = intval($_POST['id_postagem'] ?? 0);

if ($id_usuario <= 0 || $id_postagem <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'IDs inválidos'
    ]);
    exit;
}

$repositorio = new ReposiorioInstamarMYSQL();


$denuncia = new Denuncia(null, $id_usuario, $id_postagem);

if ($repositorio->verificarDenunciaExistente($id_usuario, $id_postagem)) {
    echo json_encode(['success' => false, 'message' => 'Você já denunciou este post']);
    exit;
}

if ($repositorio->adicionarDenuncia($denuncia)) {
    echo json_encode(['success' => true, 'message' => 'Denúncia registrada com sucesso']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao registrar denúncia']);
}
}
?>
