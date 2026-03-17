<?php

    class Conexao {
        private $host;
        private $usuario;
        private $senha;
        private $bd;
        private $conexao;

        function __construct($host, $usuario, $senha, $bd){
            $this->host = $host;
            $this->usuario = $usuario;
            $this->senha = $senha;
            $this->bd = $bd;
        }

        function conectar(){
            $this->conexao = mysqli_connect(
                $this->host,
                $this->usuario,
                $this->senha,
                $this->bd
            );

             if($this->conexao->connect_errno){
                echo "Falha de conexão MySQL: ". $this->conexao->connect_error;
                return false;
            }else{
                return true;
            }
        }

        function executarQuery($sql){
            return mysqli_query($this->conexao, $sql);
        }

        function obtemPrimeiroRegistroQuery($query){
            $linhas = $this->executarQuery($query);
        }
    }

?>