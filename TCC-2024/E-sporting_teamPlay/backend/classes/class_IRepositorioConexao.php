<?php
    
include_once 'class_Conexao.php';

class RepositorioConexaoMYSQL {

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost","root","","teamplay");
        if($this->conexao->conectar() == false) 
        {
            echo "Erro".mysqli_connect_error();
        }
    }
}
