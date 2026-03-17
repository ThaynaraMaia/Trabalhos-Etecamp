<?php

// if (!$_SESSION['tipo']  && !$_SESSION['logado']) {
//     header('Location:../../../frontend/home.php');
// }
include_once '../../../classes/class_IRepositorioConteudos.php';
include_once '../../../classes/class_ImgConteudo.php'; // Inclua a nova classe

session_start();

// pega os dados

$link = $_POST['link'];
$id_autor = $_SESSION['id_usuario'];
$tipo = $_POST['tipo'];
$categoria = $_POST['categoria'] ?? null;



// Criando objeto conteudo 
$videos = new video('', $id_autor, $tipo, $categoria, $link, $data_publicacao, $titulo);

// Cadastra o Conteudo
$salvarvideo = $respositorioConteudo->cadastrarVideos($videos);
if($salvarvideo){
            $_SESSION['mensagem'] = "video postado com sucesso";
        }else {
            $_SESSION['mensagem'] = "video não postado";
        }
    
    header('Location:../homeAdm.php');
    exit();



?>