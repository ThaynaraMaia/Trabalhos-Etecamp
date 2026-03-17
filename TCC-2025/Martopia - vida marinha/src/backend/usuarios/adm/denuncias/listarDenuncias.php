<?php
include_once '../../../classes/class_IRepositorioInstamar.php';
include_once '../../../classes/class_IRepositorioUsuarios.php';

session_start();

$pesquisaId = isset($_GET['pesquisa_id']) ? intval($_GET['pesquisa_id']) : 0;

if ($pesquisaId > 0) {
    // Busca a postagem completa denunciada pelo ID
    $listarDenuncias = $respositorioInstamar->buscarPostagemDenunciadaPorId($pesquisaId);
    header("Location: denuncias.php");
} else {
    // Lista todas as denúncias
    $listarDenuncias = $respositorioInstamar->listarDenuncias();
    header("Location: denuncias.php");
}
