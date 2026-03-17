<?php
include_once '../../backend/classes/class_IRepositorioAnimaisAdocao.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit;
}

$idUsuario = $_SESSION['id'];
$idAnimal = filter_input(INPUT_POST, 'id_animal', FILTER_VALIDATE_INT);

if (!$idAnimal) {
    echo json_encode(['success' => false, 'message' => 'ID do animal inválido']);
    exit;
}

try {
    $repositorio = new RepositorioAnimaisAdocaoMYSQL();
    
    // Verifica se o usuário já favoritou este animal
    $jaFavoritou = $repositorio->usuarioJaFavoritou($idAnimal, $idUsuario);
    
    if ($jaFavoritou) {
        // Remove dos favoritos
        $sucesso = $repositorio->removerFavorito($idAnimal, $idUsuario);
        $acao = 'removido';
    } else {
        // Adiciona aos favoritos
        $sucesso = $repositorio->adicionarFavorito($idAnimal, $idUsuario);
        $acao = 'adicionado';
    }
    
    if ($sucesso) {
        $totalFavoritos = $repositorio->contarFavoritos($idAnimal);
        echo json_encode([
            'success' => true, 
            'acao' => $acao,
            'total_favoritos' => $totalFavoritos
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao processar favorito']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}