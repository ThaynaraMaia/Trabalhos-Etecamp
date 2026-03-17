<?php

    include_once "classConexao.php";
    include_once "classUsuario.php";

    interface IRepositorioUsuario{
        public function cadastrarUsuario($usuario);
        public function verificaFoto($foto); //Verifica tipo e tamanho da foto.
        public function editarUsuario($id, $nome);
        public function editarUsuarioComFoto($id, $nome, $foto);
        public function listarTodosUsuarios();
        public function verificaEmail($email); //Verifica se o email já existe.
        public function buscarUsuario($id);
        public function removerUsuario($id);
        public function alteraTipo($id, $tipo);
        public function alteraStatus($id, $status);
        public function verificaLogin($email, $senha);
        public function excluirUsuario($id);
        public function editarFotoPerfil($id, $foto);
    }

    class RepositorioUsuarioMySQL implements IRepositorioUsuario{

        private $conexao;

        public function __construct(){

            $this->conexao = new Conexao("localhost", "root", "", "mv");

            if($this->conexao->conectar() == false){
                echo "Erro.".mysqli_connect_error();
            }
        }


        public function cadastrarUsuario($usuario)
        {
            $id = $usuario->getId();
            $nome = $usuario->getNome();
            $email = $usuario->getEmail();
            $senha = $usuario->getSenha();
            $senha_cripto = sha1("Gtha@#$%!").sha1($senha).sha1("haHa123$#@!"); //Senha criptografada
            $tipo = $usuario->getTipo();
            $status = $usuario->getStatus();
            $foto = $usuario->getFoto();

            //Pega dados do arquivo que está na variável $foto:
            $nomeFoto = $_FILES['foto']['name'];
            $nomeTemporarioFoto = $_FILES['foto']['tmp_name'];

            $fotoNovoNome = uniqid() . "-" . $nomeFoto; //Gera um nome novo (nome único) para a foto, evitando, assim, que arquivos de mesmo nome sejam salvos na mesma pasta.

            move_uploaded_file($nomeTemporarioFoto, '../../frontend/uploadsImg/' . $fotoNovoNome); //Move o arquivo para uma pasta (diretório), onde ficarão todos os uploads.


            $sql = "INSERT INTO usuarios (id, nome, email, senha, tipo, status, foto) VALUES ('$id', '$nome', '$email', '$senha_cripto', '$tipo', '$status', '$foto')";

            $this->conexao->executarQuery($sql);
        }


        public function verificaFoto($foto){

            $arquivoRecebido = explode(".", $foto['name']); // Recebe a foto e separa pelo ".".
            $tamanhoArquivo = 2097152; // Tamanho máximo permitido (2MB).
            if ($foto['error'] == 0){
                $extensao = $arquivoRecebido['1'];
                if(in_array($extensao, array('jpg', 'jpeg', 'png'))) {
                    if($foto['size'] > $tamanhoArquivo) {
                        $mensagem = "<br> Erro. Tamanho de arquivo muito grande! <br> Tamanho máximo permitido: 2MB. <br>";
                        $_SESSION['mensagem'] = $mensagem;
                    } else {
                        $nomeArquivo = $_FILES['foto']['name'];
                        $nomeTemporarioArquivo = $_FILES['foto']['tmp_name'];

                        $fotoNovoNome = uniqid() . "-" . $nomeArquivo; //Gera um nome novo (nome único) para o arquivo, evitando, assim, que arquivos de mesmo nome sejam salvos na mesma pasta.

                        $moveuArquivo = move_uploaded_file($nomeTemporarioArquivo, '../../frontend/uploadsImg/' . $fotoNovoNome); //Move o arquivo para uma pasta (diretório), onde ficarão todos os uploads.

                        if($moveuArquivo) {
                            $mensagemFoto = "";
                            $_SESSION['mensagemFoto'] = $mensagemFoto;
                            return($fotoNovoNome);
                        } else {
                            return false;
                        }
                    }
                } else {
                    $mensagemFoto = "<br> Erro. Verifique o tipo de arquivo. <br> Observação: Somente arquivos do tipo jpg, jpeg, png são permitidos! <br>";
                    // echo $mensagem;
                    $_SESSION['mensagemFoto'] = $mensagemFoto;
                }
            } else {
                $mensagemFoto = "<br> Erro. <br>";
                $_SESSION['mensagemFoto'] = $mensagemFoto;
            }

        }


        public function editarUsuario($id, $nome){

            $sql = "UPDATE usuarios SET nome = '$nome' WHERE id ='$id'";
            
            $editar = $this->conexao->executarQuery($sql);

            return $editar;

        }


        public function editarUsuarioComFoto($id, $nome, $foto){
            
            $sql = "UPDATE usuarios SET nome = '$nome', foto = '$foto' WHERE id ='$id'";
            
            $editar = $this->conexao->executarQuery($sql);

            return $editar;

        }


        public function listarTodosUsuarios(){

            $sql = "SELECT * FROM usuarios ORDER BY nome ASC";
            
            $registro = $this->conexao->executarQuery($sql);

            return $registro;

        }


        //Função que verifica se o email já existe
        public function verificaEmail($email)
        {
            $sql = "SELECT * FROM usuarios WHERE email = '$email'";

            $encontrou = $this->conexao->executarQuery($sql);

            return $encontrou;
        }


        public function buscarUsuario($id){
            $sql = "SELECT * FROM usuarios WHERE id = '$id'";

            $encontrou = $this->conexao->executarQuery($sql);

            return $encontrou;
        }


        public function removerUsuario($id){

        }

        public function alteraTipo($id, $tipo){

            $sql = "UPDATE usuarios SET tipo = '$tipo' WHERE id= '$id'";

            $altera = $this->conexao->executarQuery($sql);
    
            return $altera;
        }

        public function alteraStatus($id, $status){

            $sql = "UPDATE usuarios SET status = '$status' WHERE id= '$id'";

            $altera = $this->conexao->executarQuery($sql);
    
            return $altera;
        }

        public function verificaLogin($email, $senha)
        {
            $sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";

            $encontrou = $this->conexao->executarQuery($sql);

            return $encontrou;
        }

        public function excluirUsuario($id){

            $sql = "DELETE FROM usuarios where id ='$id'";

            $excluir = $this->conexao->executarQuery($sql);

            return $excluir;

        }

        public function editarFotoPerfil($id, $foto){

            $sql = "UPDATE usuarios SET foto = '$foto' WHERE id= '$id'";

            $edita = $this->conexao->executarQuery($sql);
    
            return $edita;
        }

    }

    $repositorioUsuario = new RepositorioUsuarioMySQL();
   
?>