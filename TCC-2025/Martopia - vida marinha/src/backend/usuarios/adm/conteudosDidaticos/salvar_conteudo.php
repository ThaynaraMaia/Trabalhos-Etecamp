<?php

// if (!$_SESSION['tipo']  && !$_SESSION['logado']) {
//     header('Location:../../../frontend/home.php');
// }
include_once '../../../classes/class_IRepositorioConteudos.php';
include_once '../../../classes/class_ImgConteudo.php'; // Inclua a nova classe

session_start();

// pega os dados
$titulo = $_POST['titulo'];
$link = $_POST['link'];
$id_autor = $_SESSION['id_usuario'];
$tipo = $_POST['tipo'];
$categoria = $_POST['categoria'] ?? null;



// Criando objeto conteudo 
$conteudos = new conteudo('', $titulo,$link, $data_publicacao, $id_autor, $tipo,$categoria);

// Cadastra o Conteudo
$salvarconteudo = $respositorioConteudo->cadastrarConteudo($conteudos);
if($salvarconteudo){
        $_SESSION['mensagem'] = $mensagem;
}
if ($salvarconteudo) {
    // Pega o ID do conteúdo inserido
    $id_conteudo = $respositorioConteudo->ultimoIdInserido();
    
    if ($id_conteudo > 0) {
        // Cria objeto imagem
        $img = new ImgConteudo(
            '', // ID vazio (auto increment)
            $id_conteudo, // ID do artigo
            '', // Caminho vazio (será preenchido)
            $_POST['legenda'] ?? '', // Legenda
            $_FILES['foto'] ?? null // Arquivo upload
        );
        
        // Salva a imagem usando o novo método
        $resultado_imagem = $respositorioConteudo->salvarImagemConteudo($img);
        
        if ($resultado_imagem === true) {
            $_SESSION['mensagem'] = "Conteudo e imagem postados com sucesso";
        } else if ($resultado_imagem === "Nenhuma imagem enviada") {
            $_SESSION['mensagem'] = "Conteudo postado com sucesso (sem imagem)";
        } else {
            $_SESSION['mensagem'] = "Conteudo postado, mas erro na imagem: " . $resultado_imagem;
        }
    }
    
    header('Location:../homeAdm.php');
    exit();
}


?>