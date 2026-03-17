<?php
session_start();
include_once '../../../classes/class_IRepositorioInstamar.php'; // ajuste o caminho se necessário

if (isset($_GET['excluir'])) {
    $id_postagem = intval($_GET['excluir']);

    $respositorioInstamar = new ReposiorioInstamarMYSQL();
    $sucesso = $respositorioInstamar->removerPostagemADM($id_postagem);

    if ($sucesso) {
        header("Location: denuncias.php?msg=Post excluído com sucesso"); // volta pra listagem
        exit;
    } else {
        header("Location: denuncias.php?msg=Erro ao excluir post");
        exit;
    }
} else {
    header("Location: denuncias.php?msg=Operação inválida");
    exit;
}
