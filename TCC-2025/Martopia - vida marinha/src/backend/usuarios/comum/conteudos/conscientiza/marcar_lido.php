<?php
session_start();
header('Content-Type: application/json'); // Garante que a resposta será JSON

include_once '../../../../classes/class_IRepositorioConteudos.php';
$respositorioConteudo = new ReposiorioConteudoMYSQL();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode([
        "status" => "erro", 
        "mensagem" => "Usuário não autenticado."
    ]);
    exit;
}

// Verifica se vieram os dados esperados via POST
if (isset($_POST['id_conteudo']) && isset($_POST['tipoConteudo'])) {

    $id_conteudo = intval($_POST['id_conteudo']);
    $tipoConteudo = trim($_POST['tipoConteudo']);
    $id_usuario = intval($_SESSION['id_usuario']);

    // Validação adicional
    if ($id_conteudo <= 0 || $id_usuario <= 0) {
        echo json_encode([
            "status" => "erro", 
            "mensagem" => "IDs inválidos fornecidos."
        ]);
        exit;
    }

    try {
        // ✅ CORRIGIDO: Usar o nome correto da variável
        $resultado = $respositorioConteudo->marcarComoLido($id_usuario, $id_conteudo, $tipoConteudo);

        echo json_encode($resultado);

    } catch (Exception $e) {
        echo json_encode([
            "status" => "erro", 
            "mensagem" => "Erro interno do servidor: " . $e->getMessage()
        ]);
    }

} else {
    echo json_encode([
        "status" => "erro", 
        "mensagem" => "Requisição inválida. Dados ausentes."
    ]);
}
?>