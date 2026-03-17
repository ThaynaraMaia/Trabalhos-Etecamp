<?php

class Conexao {
    private $host;
    private $usuario;
    private $senha;
    private $banco;
    private $conexao;

    function __construct($host, $usuario, $senha, $banco){
        $this->host = $host;
        $this->usuario = $usuario;
        $this->senha = $senha;
        $this->banco = $banco;
    }

    function conectar(){
        $this->conexao = mysqli_connect(
            $this->host,
            $this->usuario,
            $this->senha,
            $this->banco);    
            
        if($this->conexao->connect_errno){
            echo "Falha de Conexão MySQL: " . $this->conexao->connect_error;
            return false;
        } else {
            return true;
        }
    }

    function executarQuery($sql) {
        return $this->conexao->query($sql);
    }

    function obtemPrimeiroRegistroQuery($query){
        $resultado = $this->executarQuery($query);
        if ($resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        } else {
            return null;
        }
    }
}
?>