<?php

    include_once '../conn/classes/class_IRepositorioRegistros.php';
    include_once '../conn/classes/class_IRepositorioUsuario.php';

    session_start();
    $humor=$_GET['humor'];
    $id_usuario=$_SESSION['id'];
    // echo $humor;
    // echo $id_usuario;
    // exit;

        $respositorioRegistros->cadastrarHumor($id_usuario, $humor); 

    header('Location:../html/apoio.php');
    

    exit;
?>