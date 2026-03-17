<?php
session_start();
include_once '../classes/class_IRepositorioUsuarios.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/login.php");
    exit;
}

$status = $_SESSION['status'];
$id = $_SESSION['id_usuario'];
$avatar = $_POST['avatar'] ?? null;
$avatar1 = $_POST['avatar1'] ?? null;
$tipo= $_SESSION['tipo'];

if($tipo>0){
    if ($avatar1) {
     // chama a função que criamos
     $respositorioUsuario->alteraFoto($id, $avatar1);
     $_SESSION['mensagem'] = "Avatar alterado com sucesso!";
 }

 if ($_SESSION['tipo'] == 1 && $_SESSION['status'] > 0) {
    header("Location: ../usuarios/adm/homeAdm.php");
    exit;
} else {
    header("Location: ../trocar/trocarperfil.php");
    exit;
}

}else {
if ($avatar) {
     // chama a função que criamos
     $respositorioUsuario->alteraFoto($id, $avatar);
     $_SESSION['mensagem'] = "Avatar alterado com sucesso!";
 }

 if ($_SESSION['tipo'] == 1 && $_SESSION['status'] > 0) {
    header("Location: ../usuarios/adm/homeAdm.php");
    exit;
} else {
    header("Location: ../trocar/trocarperfil.php");
    exit;
}
}




// Redireciona de volta para o perfil ou home
// header("Location: ../usuarios/comum/homeUsuario.php");
