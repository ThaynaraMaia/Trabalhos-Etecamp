<?php

include_once '../../classes/class_IRepositorioUsuarios.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['removerUsuario'])) {
    $idUsuario = intval($_POST['idUsuario']);
    $repositorio = new RepositorioUsuarioMYSQL();
    $removido = $repositorio->removerUsuario($idUsuario);

    if ($removido) {
        header("Location: gusuarios.php?msg=remover_sucesso");
        exit;
    } else {
        echo "Erro ao remover usuário.";
    }
}
?>