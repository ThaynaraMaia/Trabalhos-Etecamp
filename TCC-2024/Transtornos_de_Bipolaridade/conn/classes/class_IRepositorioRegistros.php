<?php

include_once 'conn.php';
include_once 'classRegistros.php';

interface IRepositorioRegistros {
    public function cadastrarHumor($id_usuario, $humor);
    // public function cadastrarUtilidade($id_usuario, $utilidade);
    public function cadastrarRegistros($descricao,$id_usuario,$humor,$tipo);
    public function alterarRegistros($registros);
    public function listarTodosRegistros($id_usuario);
    public function atualizaRegistros($id, $descricao);
    // public function verificaDescricao($descricao_humor); método verificar email já existente
    public function buscarRegistros($id);
    public function removerRegistros($id);
    public function verificaRegistros($id,$descricao);
    public function obterUmmensagemAleatorio($tipo);
}

class ReposiorioRegistrosMYSQL implements IRepositorioRegistros  {

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost","root","","equilibrio");
        if($this->conexao->conectar() == false) 
        {
            echo "Erro".mysqli_connect_error();
        }
    }

    public function cadastrarHumor($id_usuario, $humor)
    {
        $data = date('Y-m-d ');
        echo "Data: " . $data;

        $sql = "INSERT INTO registros (id,descricao,id_usuario,humor,data)
         VALUES ('','','$id_usuario','$humor')";

        $this->conexao->executarQuery($sql);

    }

    public function cadastrarRegistros($descricao,$id_usuario,$humor,$tipo)
    {
        $data = date('Y-m-d ');
        echo "Data: " . $data;

        $sql = "INSERT INTO registros (id,descricao,id_usuario,data,humor)
         VALUES ('','$descricao','$id_usuario','$data','$humor')";
    // echo $sql;
    // exit;
        $this->conexao->executarQuery($sql);
        header("Location: ../php/autoajuda.php?tipo=" . $tipo);
        exit;
    }

    public function alterarRegistros($registros)
    {
        
    }

    public function listarTodosRegistros($id_usuario)
    {
        $sql = "SELECT * FROM registros WHERE id_usuario = '$id_usuario'";

        $encontrou = $this->conexao->executarQuery($sql);

        return $encontrou;
    }

    public function atualizaRegistros($id,$descricao)
    {
        $sql = "UPDATE registros SET descricao = '$descricao' WHERE id = '$id' ";
        $alterar = $this->conexao->executarQuery($sql);

        return $alterar;
    }

    public function verificaId($id)
    {

        print_r($id);

        $sql = "SELECT * FROM registros WHERE id = '$id'";

        $encontrou = $this->conexao->executarQuery($sql);

        return $encontrou;

    }

    public function buscarRegistros($id)
    {
        $sql = "SELECT * FROM registros WHERE id = '$id'";

        $encontrou = $this->conexao->executarQuery($sql);

        return $encontrou;

    }

    public function removerRegistros($id)
    {
        
    }

    public function verificaRegistros($id,$descricao)
    {

        $sql = "SELECT * FROM registros
        WHERE id = '$id' AND descricao = '$descricao'";

        $encontrou = $this->conexao->executarQuery($sql);

        return $encontrou;

    }

    public function obterUmmensagemAleatorio($tipo) {
        $sql = "SELECT mensagens FROM mensagens WHERE tipo = $tipo ORDER BY RAND() LIMIT 1";
        
        $resultado = $this->conexao->executarQuery($sql);
    
        if ($resultado->num_rows > 0) {
            // Retorna o array associativo com o resultado (neste caso, apenas a mensagem)
            return $resultado->fetch_assoc();
        } else {
            return null; // Retorna null se não houver resultados
        }
    }

}
$respositorioRegistros = new ReposiorioRegistrosMYSQL(); // criar na classe pois assim não é preciso criar em todas as scripts.

?>