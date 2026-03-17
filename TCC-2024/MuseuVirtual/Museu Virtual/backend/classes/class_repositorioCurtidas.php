<?php

    include_once "classConexao.php";
    include_once "classCurtida.php";


    interface IRepositorioCurtida{
        public function incluirCurtida($curtida);
        public function mostrarCurtidas($usuario_id, $obra_id);
        public function verificarCurtidas($usuario_id, $obra_id);
        public function atualizarCurtidas($obra_id);
        public function excluirCurtidas($id);
        public function contarCurtidas($obra_id);
    }

    class RepositorioCurtidaMySQL implements IRepositorioCurtida{

        private $conexao;

        public function __construct(){

            $this->conexao = new Conexao("localhost", "root", "", "mv");

            if($this->conexao->conectar() == false){
                echo "Erro.".mysqli_connect_error();
            }
        }


        public function incluirCurtida($curtida)
        {
            $id = $curtida->getId();
            $usuario_id = $curtida->getUsuario_id();
            $obra_id = $curtida->getObra_id();

            $sql = "INSERT INTO curtidas (id, usuario_id, obra_id) VALUES ('$id', '$usuario_id' , '$obra_id')";

            $this->conexao->executarQuery($sql);
        }


        public function mostrarCurtidas($usuario_id, $obra_id)
        {
            $sql = "SELECT * FROM curtidas WHERE usuario_id = '$usuario_id' AND obra_id = '$obra_id'";

            $registro = $this->conexao->executarQuery($sql);

            return $registro;
        }


        public function verificarCurtidas($usuario_id, $obra_id)
        {
            $sql = "SELECT * FROM curtidas WHERE usuario_id = '$usuario_id' AND obra_id = '$obra_id'";

            $encontrou = $this->conexao->executarQuery($sql);

            return $encontrou;
        }


        public function atualizarCurtidas($obra_id){

            $sql ="UPDATE obras SET curtidas = curtidas + 1 WHERE obra_id = '$obra_id'";

            $atualizou = $this->conexao->executarQuery($sql);

            return $atualizou;
        }


        public function excluirCurtidas($id){
            
            $sql = "DELETE FROM curtidas where id ='$id'";

            $registro = $this->conexao->executarQuery($sql);

            return $registro;

        }


        public function contarCurtidas($obra_id){

            $sql = "SELECT * FROM curtidas WHERE obra_id = '$obra_id'";

            $totalCurtidas = $this->conexao->executarQuery($sql);
            
            $linhas = $totalCurtidas->num_rows;

            return $linhas;

        }

    }

    $repositorioCurtida = new RepositorioCurtidaMySQL();
   
?>