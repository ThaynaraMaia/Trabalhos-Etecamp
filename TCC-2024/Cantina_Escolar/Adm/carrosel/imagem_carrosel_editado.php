<?php
include_once '../../conn/classes/class_IRepositorioCarrosel.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $repositorioCarrosel = new ReposiorioCarroselMYSQL();
    $caminhoImagens = '../../img/';

    $imagem1 = $_FILES['imagem1'];
    $imagem2 = $_FILES['imagem2'];
    $imagem3 = $_FILES['imagem3'];

    $nomeImagem1 = $repositorioCarrosel->salvarImagem($imagem1, $caminhoImagens);
    $nomeImagem2 = $repositorioCarrosel->salvarImagem($imagem2, $caminhoImagens);
    $nomeImagem3 = $repositorioCarrosel->salvarImagem($imagem3, $caminhoImagens);

    if ($nomeImagem1 && $nomeImagem2 && $nomeImagem3) {
        $repositorioCarrosel->atualizarImagensNoBanco($nomeImagem1, $nomeImagem2, $nomeImagem3);

        header('Location: ../home_adm.php');
    } else {
        echo "Erro ao salvar as imagens.";
    }
}
?>
