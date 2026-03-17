<?php
session_start();
include_once '../../../classes/class_IRepositorioInstamar.php'; // ajuste o caminho se necessário

if (isset($_GET['excluir']) && isset($_SESSION['id_usuario'])) {
    $id_postagem = intval($_GET['excluir']);
    $id_usuario  = $_SESSION['id_usuario'];

    $respositorioInstamar = new ReposiorioInstamarMYSQL();
    $sucesso = $respositorioInstamar->removerPostagem($id_postagem, $id_usuario);

    if ($sucesso) {
        header("Location: meus_cont.php?msg=Post excluído com sucesso"); // volta pra listagem
        exit;
    } else {
        header("Location: meus_cont.php?msg=Erro ao excluir post");
        exit;
    }
} else {
    header("Location: meus_cont.php?msg=Operação inválida");
    exit;
}
