<?php
session_start();
include_once '../../../classes/class_IRepositorioInstamar.php'; // ajuste o caminho se necessário

if (isset($_GET['id_coment']) && isset($_SESSION['id_usuario'])) {
    $id = intval($_GET['id_coment']);
    $id_usuario  = $_SESSION['id_usuario'];

    $sucesso = $respositorioInstamar->removerComentarios($id,$id_usuario);

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
