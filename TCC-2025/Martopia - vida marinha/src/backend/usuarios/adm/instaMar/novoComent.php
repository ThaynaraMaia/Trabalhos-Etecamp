<?php
session_start();
include_once '../../../classes/class_IRepositorioUsuarios.php';

$repositorio = new ReposiorioUsuarioMYSQL();

// Dados recebidos via POST
$id_postagem = $_POST['id_postagem'];
$id_usuario  = $_SESSION['id_usuario'];
$texto       = $_POST['texto'];

if ($repositorio->comentar($id_postagem, $id_usuario, $texto)) {
    echo "ok";
} else {
    echo "erro";
}
?>
