<?php
    
    include_once "../classes/class_repositorioObras.php";

    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $trabalhoArtistico = $_FILES['trabalhoArtistico'];
    $conteudo = $_POST['textoObra'];

    if(isset($_FILES['trabalhoArtistico']) && !empty($_FILES['trabalhoArtistico']['name']) && isset($_POST['textoObra']) && !empty($_POST['textoObra'])){
        //Se o usuário editar o arquivo e o texto
        $verificouObra = $repositorioObra->verificaObra($trabalhoArtistico);

        $editar = $repositorioObra->editarObraComArquivoTexto($id, $titulo, $descricao, $verificouObra, $conteudo);

        header('Location:../../frontend/paginas/paginasAluno/mostre_sua_arte-obras.php');
    }
    else if(isset($_FILES['trabalhoArtistico']) && !empty($_FILES['trabalhoArtistico']['name'])){
        
        //Se o usuário editar o arquivo

        $arquivoRecebido = explode(".", $trabalhoArtistico['name']); // Recebe o arquivo e separa pelo ".".
        if ($trabalhoArtistico['error'] == 0){
            $extensao = $arquivoRecebido['1'];
            if(in_array($extensao, array('jpg', 'jpeg', 'png'))) {

                $verificouObra = $repositorioObra->verificaObra($trabalhoArtistico);
                $editar = $repositorioObra->editarObraComArquivo($id, $titulo, $descricao, $verificouObra);

            }else if(in_array($extensao, array('mp3', 'mp4', 'gif'))){

                $verificouObraVideo = $repositorioObra->verificaObraVideo($trabalhoArtistico);
                $editar = $repositorioObra->editarObraComArquivo($id, $titulo, $descricao, $verificouObraVideo);
            }

            header('Location:../../frontend/paginas/paginasAluno/mostre_sua_arte-obras.php');
        }
    }
    else if(isset($_POST['textoObra']) && !empty($_POST['textoObra'])){
        
        //Se editar o texto

        $editar = $repositorioObra->editarObrasTexto($id, $titulo, $descricao, $conteudo);

        header('Location:../../frontend/paginas/paginasAluno/mostre_sua_arte-obras.php');

    }
    else
    {
        //Se não editar o arquivo, nem o texto.
        $editar = $repositorioObra->editarObras($id, $titulo, $descricao);
        header('Location:../../frontend/paginas/paginasAluno/mostre_sua_arte-obras.php');

    }

   
    exit;

?>