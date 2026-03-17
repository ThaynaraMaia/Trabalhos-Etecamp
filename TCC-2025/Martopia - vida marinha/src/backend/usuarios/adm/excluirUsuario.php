<?php
include_once '../../classes/class_IRepositorioUsuarios.php';



if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $excluir=$respositorioUsuario->removerUsuario($id);

    header("Location:Usuarios.php"); // volta para a lista depois de deletar
    exit;
}
