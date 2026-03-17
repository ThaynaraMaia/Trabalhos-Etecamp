<?php
include_once '../../../classes/class_IRepositorioConteudos.php'; // não esquece de incluir a implementação!
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = $_POST['id'];
    $tabela = $_POST['tabela'];

    $dados = [
        'titulo'    => $_POST['titulo'] ?? '',
        'texto'     => $_POST['texto'] ?? '',     // só se a tabela tiver
        'link'      => $_POST['link'] ?? '',
        'categoria' => $_POST['categoria'] ?? '',
        'tipo'      => $_POST['tipo'] ?? '',
    ];

    // Definir pasta conforme a origem
    $pastas = [
        'conteudos'       => "../../../../frontend/public/img_conteudos/",
        'conscientizacao' => "../../../../frontend/public/img_conscientizacao/"
    ];
    $destino = $pastas[$tabela] ?? NULL;

    // Upload de imagem
    $novaImagem = null;
    if (!empty($_FILES['imagem']['name'])) {
        $novaImagem = basename($_FILES['imagem']['name']);
        if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $destino . $novaImagem)) {
            echo json_encode(['success' => false, 'message' => 'Falha ao mover imagem.']);
            exit;
        }
    }

    if ($respositorioConteudo->editarConteudo($id, $tabela, $dados, $novaImagem)) {
        echo json_encode(['success' => true, 'message' => 'Conteúdo atualizado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Falha ao atualizar conteúdo.']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
