<?php
session_start();
include_once '../../../classes/class_IRepositorioConteudos.php';
include_once '../../../classes/class_Conscientizacao.php';
include_once '../../../classes/class_ImgConscientizacao.php';

$id_autor = $_SESSION['id_usuario'];
$titulo = $_POST['titulo'];
$categoria  = $_POST['categoria'];
$tipo  = $_POST['tipo'];

switch ($categoria) {

    case 'Noticias':
        $link = $_POST['link_noticia'];
        $texto = null;
        $conscientizacao = new Conscientizacao('', $titulo, $link, $data_publicacao, $id_autor, $tipo, $categoria, $texto);
        $salvarConsc = $respositorioConteudo->cadastrarConscientizacao($conscientizacao);

        if ($salvarConsc) {
            // Pega o ID do conteúdo inserido
            $id_conteudo = $respositorioConteudo->ultimoIdInserido();

            if ($id_conteudo > 0) {
                // Cria objeto imagem
                $img1 = new ImgConscientizacao(
                    '', // ID vazio (auto increment)
                    $id_conteudo, // ID do artigo
                    '', // Caminho vazio (será preenchido)
                    $_POST['legenda_noticia'] ?? '', // Legenda
                    $_FILES['foto_noticia'] ?? null // Arquivo upload
                );

                // Salva a imagem usando o novo método
                $resultado_imagem = $respositorioConteudo->salvarImagemConscientizacao($img1);

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
        break;
    case 'Infograficos':
        $link = null;
        $texto = $_POST['conteudo_info'];
        $conscientizacao = new Conscientizacao('', $titulo, $link, $data_publicacao, $id_autor, $tipo, $categoria, $texto);
        $salvarConsc = $respositorioConteudo->cadastrarConscientizacao($conscientizacao);

        if ($salvarConsc) {
            // Pega o ID do conteúdo inserido
            $id_conteudo = $respositorioConteudo->ultimoIdInserido();

            if ($id_conteudo > 0) {
                // Cria objeto imagem
                $img1 = new ImgConscientizacao(
                    '', // ID vazio (auto increment)
                    $id_conteudo, // ID do artigo
                    '', // Caminho vazio (será preenchido)
                    $_POST['fonte_info'] ?? '', // Legenda
                    $_FILES['foto_info'] ?? null // Arquivo upload
                );

                // Salva a imagem usando o novo método
                $resultado_imagem = $respositorioConteudo->salvarImagemConscientizacao($img1);

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
        break;
    case 'Texto':
        $link = null;
        $texto = $_POST['conteudo'];
        $conscientizacao = new Conscientizacao('', $titulo, $link, $data_publicacao, $id_autor, $tipo, $categoria, $texto);
        $salvarConsc = $respositorioConteudo->cadastrarConscientizacao($conscientizacao);

        if ($salvarConsc) {
            // Pega o ID do conteúdo inserido
            $id_conteudo = $respositorioConteudo->ultimoIdInserido();

            if ($id_conteudo > 0) {
                // Cria objeto imagem
                $img1 = new ImgConscientizacao(
                    '', // ID vazio (auto increment)
                    $id_conteudo, // ID do artigo
                    '', // Caminho vazio (será preenchido)
                    $_POST['fonte_texto'] ?? '', // Legenda
                    $_FILES['foto_text'] ?? null // Arquivo upload
                );

                // Salva a imagem usando o novo método
                $resultado_imagem = $respositorioConteudo->salvarImagemConscientizacao($img1);

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
        break;
    case 'Videos':
        $texto = null;
        $link = $_POST['link_video'];
        $conscientizacao = new Conscientizacao('', $titulo, $link, $data_publicacao, $id_autor, $tipo, $categoria, $texto);
        $salvarConsc = $respositorioConteudo->cadastrarConscientizacao($conscientizacao);

        break;
}

// // 1️⃣ Cria objeto conscientização
// $conscientizacao = new Conscientizacao(null, $titulo, $link, null, $id_autor, $tipo, $categoria, $texto);

// // 2️⃣ Salva no banco
// $salvarConsc = $respositorioConteudo->cadastrarConscientizacao($conscientizacao);

// // 3️⃣ Pega o último ID inserido
// $id_consc = $respositorioConteudo->ultimoIdInserido();

// // 4️⃣ Descobre qual input de imagem usar conforme categoria
// $campoImagem = null;
// $campoFonte  = null;

// switch ($categoria) {
//     case 'noticia':
//         $campoImagem = 'foto_noticia';
//         $campoFonte  = $_POST['fonte_noticia'] ?? '';
//         break;
//     case 'texto':
//         $campoImagem = 'foto_texto';
//         $campoFonte  = $_POST['fonte_texto'] ?? '';
//         break;
//     case 'video':
//         $campoImagem = 'foto_video';
//         $campoFonte  = $_POST['fonte_video'] ?? '';
//         $link        = $_POST['link_video'] ?? null;
//         $conscientizacao->setLink($link);
//         break;
//     case 'pdf':
//         $campoImagem = 'foto_pdf';
//         $campoFonte  = $_POST['fonte_pdf'] ?? '';
//         // aqui você ainda pode salvar o arquivo PDF em outra tabela se quiser
//         break;
// }

// if ($campoImagem && isset($_FILES[$campoImagem]) && $_FILES[$campoImagem]['error'] == 0) {
//     $imgConsc = new ImgConscientizacao(null, $id_consc, null, $campoFonte, $_FILES[$campoImagem]);
//     $salvarImagem = $respositorioConteudo->salvarImagemConscientizacao($imgConsc);

//     if ($salvarImagem !== true) {
//         $_SESSION['mensagem'] = "Conteúdo salvo, mas houve erro ao salvar a imagem: $salvarImagem";
//         header('Location: cadastrar_conteudo.php');
//         exit;
//     }
// }

// // 5️⃣ Redireciona com sucesso
// if ($salvarConsc) {
//     $_SESSION['mensagem'] = "Conscientização cadastrada com sucesso!";
// } else {
//     $_SESSION['mensagem'] = "Erro ao cadastrar conscientização.";
// }

header('Location: ../homeAdm.php');
exit;
