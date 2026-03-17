<?php

    include_once "classConexao.php";
    include_once "classObra.php";


    interface IRepositorioObra{
        public function publicarObra($obra);
        public function verificaObra($trabalhoArtistico); //Verifica tipo e tamanho do arquivo da obra.
        public function verificaObraVideo($trabalhoArtistico);
        public function listarTodasObras();
        public function listarObras($autor);
        public function listarMaisRecente();
        public function nomeAutor($id);
        public function buscarObra($id);
        public function editarObras($id, $titulo, $descricao);
        public function editarObraComArquivo($id, $titulo, $descricao, $trabalhoArtistico);
        public function editarObraComArquivoTexto($id, $titulo, $descricao, $trabalhoArtistico, $texto);
        public function editarObrasTexto($id, $titulo, $descricao, $texto);
        public function alteraStatus($id,$status);
        public function excluirObras($id);
        public function filtrarObra($categoria);
        public function atualizaAdicionarCurtidas($id);
        public function atualizaExcluirCurtidas($id);
        public function listarMaisCurtida();
    }

    class RepositorioObraMySQL implements IRepositorioObra{

        private $conexao;

        public function __construct(){

            $this->conexao = new Conexao("localhost", "root", "", "mv");

            if($this->conexao->conectar() == false){
                echo "Erro.".mysqli_connect_error();
            }
        }


        public function publicarObra($obra)
        {
            $id = $obra->getId();
            $titulo = $obra->getTitulo();
            $categoria = $obra->getCategoria();
            $descricao = $obra->getDescricao();
            $trabalhoArtistico = $obra->getTrabalhoArtistico();
            $autor = $obra->getAutor();
            $data = $obra->getData();
            $curtidas = $obra->getCurtidas();
            $texto = $obra->getTexto();
            
            //Pega dados do arquivo que está na variável $trabalhoArtístico:
            // $nomeArquivo = $_FILES['trabalhoArtistico']['name'];
            // $nomeTemporarioArquivo = $_FILES['trabalhoArtistico']['tmp_name'];

            // $trabalhoArtisticoNovoNome = uniqid() . "-" . $nomeArquivo; //Gera um nome novo (nome único) para o arquivo, evitando, assim, que arquivos de mesmo nome sejam salvos na mesma pasta.

            // move_uploaded_file($nomeTemporarioArquivo, '../../frontend/uploadsImg/' . $trabalhoArtisticoNovoNome); //Move o arquivo para uma pasta (diretório), onde ficarão todos os uploads.


            $sql = "INSERT INTO obras (id, titulo, categoria, descricao, trabalho_artistico, autor, data, curtidas, texto) VALUES ('$id', '$titulo', '$categoria', '$descricao', '$trabalhoArtistico', '$autor', '$data', '$curtidas', '$texto')";

            $this->conexao->executarQuery($sql);
        }


        public function verificaObra($trabalhoArtistico)
        {
            $arquivoRecebido = explode(".", $trabalhoArtistico['name']); // Recebe a foto e separa pelo ".".
            $tamanhoArquivo = 2097152; // Tamanho máximo permitido (2MB).
            if ($trabalhoArtistico['error'] == 0){
                $extensao = $arquivoRecebido['1'];
                if(in_array($extensao, array('jpg', 'jpeg', 'png'))) {
                    if($trabalhoArtistico['size'] > $tamanhoArquivo) {
                        $mensagem = "<br> Erro ao publicar obra. Tamanho de arquivo muito grande! <br> Tamanho máximo permitido: 2MB. <br>";
                        $_SESSION['mensagem'] = $mensagem;
                    } else {
                        $nomeArquivo = $_FILES['trabalhoArtistico']['name'];
                        $nomeTemporarioArquivo = $_FILES['trabalhoArtistico']['tmp_name'];

                        $trabalhoArtisticoNovoNome = uniqid() . "-" . $nomeArquivo; //Gera um nome novo (nome único) para o arquivo, evitando, assim, que arquivos de mesmo nome sejam salvos na mesma pasta.

                        $moveuArquivo = move_uploaded_file($nomeTemporarioArquivo, '../../frontend/uploadsImg/' . $trabalhoArtisticoNovoNome); //Move o arquivo para uma pasta (diretório), onde ficarão todos os uploads.

                        if($moveuArquivo) {
                            $mensagem = "";
                            $_SESSION['mensagem'] = $mensagem;
                            return($trabalhoArtisticoNovoNome);
                        } else {
                            return false;
                        }
                    }
                } else {
                    $mensagem = "<br> Erro ao publicar obra. Verifique o tipo de arquivo. <br> Observação: Somente arquivos do tipo jpg, jpeg, png são permitidos! <br>";
                    // echo $mensagem;
                    $_SESSION['mensagem'] = $mensagem;
                }
            } else {
                $mensagem = "<br> Erro. <br>";
                $_SESSION['mensagem'] = $mensagem;
            }
        }


        public function verificaObraVideo($trabalhoArtistico)
        {
            $arquivoRecebido = explode(".", $trabalhoArtistico['name']); // Recebe a foto e separa pelo ".".
            $tamanhoArquivo = 50000000; // Tamanho máximo permitido (50MB).
            if ($trabalhoArtistico['error'] == 0){
                $extensao = $arquivoRecebido['1'];
                if(in_array($extensao, array('mp3', 'mp4', 'gif'))) {
                    if($trabalhoArtistico['size'] > $tamanhoArquivo) {
                        $mensagem = "<br> Erro ao publicar obra. Tamanho de arquivo muito grande! <br> Tamanho máximo permitido: 50MB. <br>";
                        $_SESSION['mensagem'] = $mensagem;
                    } else {
                        $nomeArquivo = $_FILES['trabalhoArtistico']['name'];
                        $nomeTemporarioArquivo = $_FILES['trabalhoArtistico']['tmp_name'];

                        $trabalhoArtisticoNovoNome = uniqid() . "-" . $nomeArquivo; //Gera um nome novo (nome único) para o arquivo, evitando, assim, que arquivos de mesmo nome sejam salvos na mesma pasta.

                        $moveuArquivo = move_uploaded_file($nomeTemporarioArquivo, '../../frontend/uploadsVideos/' . $trabalhoArtisticoNovoNome); //Move o arquivo para uma pasta (diretório), onde ficarão todos os uploads de vídeos.

                        if($moveuArquivo) {
                            $mensagem = "";
                            $_SESSION['mensagem'] = $mensagem;
                            return($trabalhoArtisticoNovoNome);
                        } else {
                            return false;
                        }
                    }
                } else {
                    $mensagem = "<br> Erro ao publicar obra. Verifique o tipo de arquivo. <br> Observação: Somente arquivos do tipo mp4, mp3 e gif são permitidos! <br>";
                    // echo $mensagem;
                    $_SESSION['mensagem'] = $mensagem;
                }
            } else {
                $mensagem = "<br> Erro. <br>";
                $_SESSION['mensagem'] = $mensagem;
            }
        }


        public function listarTodasObras(){

            $sql = "SELECT * FROM obras";
            
            $registro = $this->conexao->executarQuery($sql);

            return $registro;

        }


        public function listarObras($autor){

            $sql = "SELECT * FROM obras where autor = '$autor'";
            
            $registro = $this->conexao->executarQuery($sql);

            return $registro;
           
        }

        public function listarMaisRecente(){

            $sql = "SELECT * FROM obras ORDER BY data DESC";
            
            $registro = $this->conexao->executarQuery($sql);

            return $registro;
           
        }


        public function nomeAutor($id){

        //     $sql = "SELECT nome FROM usuarios INNER JOIN obras ON usuarios.nome = obras.autor";

            $sql = "SELECT nome FROM usuarios WHERE id = '$id'";

            $registro = $this->conexao->executarQuery($sql);

            return $registro;
            
        }
        


        public function buscarObra($id){

            $sql = "SELECT * FROM obras where id ='$id'";

            $registro = $this->conexao->executarQuery($sql);

            return $registro;

        }


        public function editarObras($id, $titulo, $descricao){

        // public function editarObras($id, $titulo, $descricao, $trabalhoArtisticoNovoNome){

            $sql = "UPDATE obras SET titulo = '$titulo', descricao = '$descricao'  WHERE id ='$id'";
            
            $editar = $this->conexao->executarQuery($sql);

            return $editar;
        }


        public function editarObraComArquivo($id, $titulo, $descricao, $trabalhoArtistico){

            $sql = "UPDATE obras SET titulo = '$titulo', descricao = '$descricao', trabalho_artistico = '$trabalhoArtistico' WHERE id ='$id'";
            
            $editar = $this->conexao->executarQuery($sql);

            return $editar;
        }


        public function editarObraComArquivoTexto($id, $titulo, $descricao, $trabalhoArtistico, $texto){
            
            $sql = "UPDATE obras SET titulo = '$titulo', descricao = '$descricao', trabalho_artistico = '$trabalhoArtistico', texto = '$texto' WHERE id ='$id'";
            
            $editar = $this->conexao->executarQuery($sql);

            return $editar;
        }


        public function editarObrasTexto($id, $titulo, $descricao, $texto){

            $sql = "UPDATE obras SET titulo = '$titulo', descricao = '$descricao', texto = '$texto'  WHERE id ='$id'";
            
            $editar = $this->conexao->executarQuery($sql);

            return $editar;
        }


        public function alteraStatus($id,$status){
            
            $sql = "UPDATE obras SET status = '$status' WHERE id= '$id'";

            $altera = $this->conexao->executarQuery($sql);
    
            return $altera;
        }

        public function excluirObras($id){
            
            $sql = "DELETE FROM obras where id ='$id'";

            $registro = $this->conexao->executarQuery($sql);

            return $registro;

        }


        public function filtrarObra($categoria){

            $sql = "SELECT * FROM obras where categoria ='$categoria'";

            $registro = $this->conexao->executarQuery($sql);

            return $registro;
        }


        public function atualizaAdicionarCurtidas($id){

            $sql ="UPDATE obras SET curtidas = curtidas + 1 WHERE id = '$id'";

            $atualizou = $this->conexao->executarQuery($sql);

            return $atualizou;
        }

        public function atualizaExcluirCurtidas($id){

            $sql ="UPDATE obras SET curtidas = curtidas - 1 WHERE id = '$id'";

            $atualizou = $this->conexao->executarQuery($sql);

            return $atualizou;
        }

        public function listarMaisCurtida(){

            $sql = "SELECT * FROM obras ORDER BY curtidas DESC";
            
            $registro = $this->conexao->executarQuery($sql);

            return $registro;
        }

    }

    $repositorioObra = new RepositorioObraMySQL();
   
?>