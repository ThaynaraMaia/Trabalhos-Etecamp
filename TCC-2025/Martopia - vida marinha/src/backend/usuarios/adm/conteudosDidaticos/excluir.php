<?php
include_once '../../../classes/class_IRepositorioConteudos.php';

if (isset($_GET['id']) && isset($_GET['tabela'])) {
    $id = $_GET['id'];
    $tabela = $_GET['tabela'];

    if ($respositorioConteudo->deletarConteudo($id, $tabela)) {
        header("Location: meusConteudos.php?msg=excluido");
        exit;
    } else {
        echo "Erro ao excluir conteúdo.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>
