<?php

include_once '../../../classes/class_IRepositorioUsuarios.php';


session_start();

// pega os dados
$id_Post = $_SESSION['id_usuario'];
$legenda = $_POST['legenda'];
// $data_publicacao = $_POST['data_post'];
$foto = null;


$PostIMG = new MarImg ('', $id_Post, $legenda,'', $foto); 
if ($PostIMG) {  
     $salvarPost = $respositorioUsuario->adicionarPostIMG($PostIMG);
          $_SESSION['mensagem'] = "Conteudo postado com sucesso";
            header('Location:insta.php');
} else {
    header('Location:insta.php');
}


?>