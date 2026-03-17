<?php
session_start();
include_once '../classes/class_IRepositorioUsuarios.php';
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/login.php");
    exit;
}

$tipo= $_SESSION['tipo'];
$id = $_SESSION['id_usuario'];
$status = $_SESSION['status'];

 if ($_SESSION['tipo'] == 1 && $_SESSION['status'] > 0) {
    header("Location: ../usuarios/adm/homeAdm.php");
    exit;
} else {
    header("Location:../usuarios/comum/instamar/instamar.php");
    exit;
}

?>