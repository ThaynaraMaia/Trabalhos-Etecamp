<?php
include_once '../../classes/class_IRepositorioUsuarios.php';

$respositorioUsuario = new RepositorioUsuarioMYSQL();

if (isset($_POST['id']) && isset($_POST['tipo'])) {
    $id = (int) $_POST['id'];
    $tipo = $_POST['tipo'];

    $tiposValidos = ['administrador', 'tutor/adotante'];
    if (in_array($tipo, $tiposValidos)) {
        $alterou = $respositorioUsuario->alteraTipo($id, $tipo);
        if ($alterou) {
            header('Location: gusuarios.php');
            exit;
        } else {
            echo "Erro ao alterar tipo de usuário.";
        }
    } else {
        echo "Tipo inválido.";
    }
} else {
    echo "Parâmetros insuficientes.";
}
?>