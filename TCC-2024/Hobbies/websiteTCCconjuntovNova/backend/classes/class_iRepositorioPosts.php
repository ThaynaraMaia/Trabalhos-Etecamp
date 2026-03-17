<?php

include_once 'class_Conexao.php';
include_once 'class_Posts.php';

interface IRepositorioPosts {
    public function cadastrarPosts($id);
    public function listarTodosPosts();
    public function removerPosts($id);
}

class RepositorioPostsMYSQL implements IRepositorioPosts {

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost","root","","mercury");
        if($this->conexao->conectar() == false) 
        {
            echo "Erro".mysqli_connect_error();
        }
    }


    public function cadastrarPosts($id)
    {
        
        $id = $posts->getId();
        $usuario_id = $posts->getIdUsuario();
        $conteudo = $post->getConteudo();
        $foto_post = $post->getFotoPost();
        $data_postagem = $post->getDataPostagem();

        $sql = "INSERT INTO posts (id,usuario_id, conteudo, foto_post,data_postagem)
         VALUES ('$id', '$usuario_id', '$conteudo', '$foto_post', '$data_postagem'";

        $this->conexao->executarQuery($sql);

    }

    public function listarTodosPosts()
    {
        $sql = "SELECT * FROM posts";

        $registro = $this->conexao->executarQuery($sql);

        return $registro;
    }

    public function removerPosts($id)
    {
       $sql = "DELETE FROM posts WHERE id = $id";

       $resultado = $this->conexao->executarQuery($sql);

       header('Location: ../../administrador/tblposts.php');
    }

}

$respositorioPosts = new RepositorioPostsMYSQL();

?>