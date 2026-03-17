<?php
include_once 'class_Conexao.php';
include_once 'class_Hobby.php';



interface IRepositorioHobby {
    public function cadastrarHobby($hobbie);
    public function listarHobbiesPorUsuarioEStatus($id_usuarios, $status);
    public function atualizarHobby($id, $status);
    public function contarSentimentos($id_usuarios);
    public function removerHobby($id);
}

class RepositorioHobbyMYSQL implements IRepositorioHobby {

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost", "root", "", "mercury");
        if ($this->conexao->conectar() == false) 
        {
            echo "Erro: " . mysqli_connect_error();
        }
    }

    public function cadastrarHobby($hobbie)
    {
        $id = $hobbie->getId();
        $id_usuarios = $hobbie-> getIdUsuarios();
        $nome = $hobbie->getNome();
        $status = $hobbie->getStatus();
        $descricao = $hobbie->getDescricao();
        $sentimento = $hobbie->getSentimento();

        $sql = "INSERT INTO hobbies (id, id_usuarios, nome, status, descricao, sentimento)
                VALUES ('$id', '$id_usuarios', '$nome', '$status', '$descricao', '$sentimento')";

                echo $sql;
                
                $this->conexao->executarQuery($sql);

    }

    public function listarHobbiesPorUsuarioEStatus($id_usuarios, $status)
    {
        $sql = "SELECT * FROM hobbies WHERE id_usuarios = '$id_usuarios' AND status = '$status'";
        $registro = $this->conexao->executarQuery($sql);
        return $registro;
    }

    public function atualizarHobby($id, $status) {
        $sql = "UPDATE hobbies SET status = '$status' WHERE id = '$id'";
        return $this->conexao->executarQuery($sql); // Verificar se a query foi executada
    }
    
    public function cadastrarSentimento($status, $sentimento, $id){

        $sql = "UPDATE hobbies SET status= '$status', sentimento='$sentimento' WHERE id='$id' ";
        $registro = $this->conexao->executarQuery($sql);
        return $registro;
    }

    public function contarSentimentos($id_usuarios) {
        $sql = "SELECT sentimento, COUNT(*) as total FROM hobbies WHERE id_usuarios = '$id_usuarios' GROUP BY sentimento";
        $registro = $this->conexao->executarQuery($sql);
        return $registro;
    }
    

    public function removerHobby($id){
        $sql = "DELETE FROM hobbies WHERE id = $id";
        return $this->conexao->executarQuery($sql); // Retorna o resultado da execução
    }

}

$repositorioHobby = new RepositorioHobbyMYSQL();

?>