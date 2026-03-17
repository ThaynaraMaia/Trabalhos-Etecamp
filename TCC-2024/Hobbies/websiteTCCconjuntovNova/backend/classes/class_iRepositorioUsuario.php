<?php

include_once 'class_Conexao.php';
include_once 'class_Usuario.php';

interface IRepositorioUsuario {
    public function cadastrarUsuario($usuario);
    public function listarTodosUsuarios();
    public function verificaEmail($email); // método verificar email já existente
    public function verificaLogin($email,$senha);
    public function alteraTipo($id,$tipo);
    public function alterarStatus($id, $status);
}

class RepositorioUsuarioMYSQL implements IRepositorioUsuario {

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost","root","","mercury");
        if($this->conexao->conectar() == false) 
        {
            echo "Erro".mysqli_connect_error();
        }
    }


    public function cadastrarUsuario($usuario)
    {
        
        $id = $usuario->getId();
        $nome = $usuario->getNome();
        $email = $usuario->getEmail();
        $senha_cripto = sha1("Gtha@#$%!").sha1($_POST['senha']).sha1("haHa123$#@!");
        $tipo = $usuario->getTipo();
        $status = $usuario->getStatus();

        $sql = "INSERT INTO usuarios (id,nome,email,senha,tipo,status)
         VALUES ('$id','$nome','$email','$senha_cripto', '$tipo', '$status')";

        $this->conexao->executarQuery($sql);

    }

    public function listarTodosUsuarios()
    {
        $sql = "SELECT * FROM usuarios ORDER BY nome ASC";

        $registro = $this->conexao->executarQuery($sql);

        return $registro;
    }

    public function verificaEmail($email)
    {

        print_r($email);

        $sql = "SELECT * FROM usuarios WHERE email = '$email'";

        $encontrou = $this->conexao->executarQuery($sql);

        return $encontrou;

    }

    public function verificaLogin($email,$senha)
    {

        $sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";

        $encontrou = $this->conexao->executarQuery($sql);

        return $encontrou;
    }

    public function alteraTipo($id,$tipo)
    {
        $sql = "UPDATE usuarios SET tipo = '$tipo' WHERE id= '$id'";

        $altera = $this->conexao->executarQuery($sql);

        return $altera;
    }

    public function alterarStatus($id,$status)
    {
        $sql = "UPDATE usuarios SET status = '$status' WHERE id= '$id'";

        $alterar = $this->conexao->executarQuery($sql);

        return $alterar;
    }
}

$respositorioUsuario = new RepositorioUsuarioMYSQL(); // criar na classe pois assim não é preciso criar em todas as scripts.

?>