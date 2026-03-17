<?php

include_once '../../../classes/class_IRepositorioUsuarios.php';


session_start();

// pega os dados
$id_Post = $_SESSION['id_usuario'];
$legenda = $_POST['legenda'];
// $data_publicacao = $_POST['data_post'];
$foto1 = $_FILES['foto'];

$encontrou = $respositorioUsuario->verificaFoto($foto1);

if ($encontrou) {
    $PostIMG = new MarImg ('', $id_Post, $legenda,$data_publicacao, $encontrou);   
     $salvarPost = $respositorioUsuario->adicionarPostIMG($PostIMG);
          $_SESSION['mensagem'] = "Conteudo postado com sucesso";
            header('Location:instamar.php');
} else {
    header('Location:instamar.php');
}


?>