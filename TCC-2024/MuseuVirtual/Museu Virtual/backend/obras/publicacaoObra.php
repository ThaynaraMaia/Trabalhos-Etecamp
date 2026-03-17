<?php
 
    include_once "../classes/class_repositorioObras.php";

    //Recebe os dados do formulário que está no arquivo "publicarObra.php".
    session_start();

    $trabalhoArtistico = $_FILES['trabalhoArtistico'];

    $arquivoRecebido = explode(".", $trabalhoArtistico['name']); // Recebe o arquivo e separa pelo ".".
    if ($trabalhoArtistico['error'] == 0){

        $extensao = $arquivoRecebido['1'];
        if(in_array($extensao, array('jpg', 'jpeg', 'png'))) {

            $verificouObra = $repositorioObra->verificaObra($trabalhoArtistico);
    
            if($verificouObra){

                $id = $_SESSION['id'];
                $data_atual = date('Y-m-d');
                $conteudo = $_POST['textoObra'];

                if(isset($conteudo) && !empty($conteudo)){
                    //Se tiver arquivo de texto
                    
                    $novaObra = new Obra('', $_POST['titulo'], $_POST['categoria'], $_POST['descricao'], $verificouObra, $id, $data_atual, 0, $conteudo);

                    $repositorioObra->publicarObra($novaObra);

                    header('Location:../../frontend/paginas/paginasAluno/mostre_sua_arte-obras.php');

                }else if(!isset($conteudo) || empty($conteudo) && $_POST['categoria'] == 8){
                    //Se for categoria de texto, mas o usuário deixar vazio.

                    $_SESSION['mensagemInsiraUmTexto'] = "<br>ERRO! Digite seu poema/poesia.";
                    header('Location:../../frontend/paginas/paginasAluno/publicarObra.php');

                }else{
                    //Senão, é publicada a obra de "categoria comum".
                    $novaObra = new Obra('', $_POST['titulo'], $_POST['categoria'], $_POST['descricao'], $verificouObra, $id, $data_atual, 0, '');

                    $repositorioObra->publicarObra($novaObra);

                    header('Location:../../frontend/paginas/paginasAluno/mostre_sua_arte-obras.php');
                }
            }else{

            // $mensagem = '<script>alert("Erro. Verfique formato e tamanho do arquivo. Observação: Somente arquivos do tipo jpg, jpeg, png são permitidos! Tamanho máximo permitido: 2MB.");</script>';
            // echo $mensagem;
                header('Location:../../frontend/paginas/paginasAluno/publicarObra.php');
            }
       
        }else if(in_array($extensao, array('mp3', 'mp4', 'gif'))){

            $verificouObra = $repositorioObra->verificaObraVideo($trabalhoArtistico);

            if($verificouObra){

                $id = $_SESSION['id'];
                $data_atual = date('Y-m-d');
                $conteudo = $_POST['textoObra'];

                if(isset($conteudo) && !empty($conteudo)){
                //Se tiver arquivo de texto
                
                    $_SESSION['mensagemInsiraUmTexto'] = "<br>ERRO! O vídeo não pode estar nessa categoria.";

                    header('Location:../../frontend/paginas/paginasAluno/mostre_sua_arte-obras.php');

                }else if(!isset($conteudo) || empty($conteudo) && $_POST['categoria'] == 8){
                    //Se for categoria de texto, mas o usuário deixar vazio.

                    $_SESSION['mensagemInsiraUmTexto'] = "<br>ERRO! O vídeo não pode estar nessa categoria.";
                    header('Location:../../frontend/paginas/paginasAluno/publicarObra.php');

                }else{
                    //Senão, é publicada a obra.
                    $novaObra = new Obra('', $_POST['titulo'], $_POST['categoria'], $_POST['descricao'], $verificouObra, $id, $data_atual, 0, '');

                    $repositorioObra->publicarObra($novaObra);

                    header('Location:../../frontend/paginas/paginasAluno/mostre_sua_arte-obras.php');
                }
            }else{

            // $mensagem = '<script>alert("Erro. Verfique formato e tamanho do arquivo. Observação: Somente arquivos do tipo jpg, jpeg, png são permitidos! Tamanho máximo permitido: 2MB.");</script>';
            // echo $mensagem;
                header('Location:../../frontend/paginas/paginasAluno/publicarObra.php');
            }
        }
    }else{

        // $mensagem = '<script>alert("Erro. Verfique formato e tamanho do arquivo. Observação: Somente arquivos do tipo jpg, jpeg, png são permitidos! Tamanho máximo permitido: 2MB.");</script>';
        // echo $mensagem;
            header('Location:../../frontend/paginas/paginasAluno/publicarObra.php');
    }
    
    exit;
?>