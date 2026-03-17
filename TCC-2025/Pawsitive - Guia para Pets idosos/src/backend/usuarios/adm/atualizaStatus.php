<?php
include_once '../../classes/class_IRepositorioAnimaisAdocao.php'; // ajuste o caminho conforme necessário

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $status = $_POST['status'] ?? null;

    if ($id && $status) {
        $repositorio = new RepositorioAnimaisAdocaoMYSQL();
        $repositorio->atualizarStatus($id, $status);
    }
}

header('Location: ganimais.php'); // ajuste o nome da sua página de listagem
exit;
?>