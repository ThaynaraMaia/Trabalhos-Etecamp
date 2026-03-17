<?php

include_once 'conn.php';
include_once 'classUsuario.php';

interface IRepositorioUsuario {
    public function cadastrarUsuario($usuario);
    public function alterarUsuario($usuario);
    public function alterarStatus($id, $status);
    public function alterarTipo($id, $tipo);
    public function listarTodosUsuarios();
    public function atualizaPerfil($id, $nome, $email, $senha);
    public function verificaEmail($email); // método verificar email já existente
    public function buscarUsuario($id);
    public function excluirUsuario($id);
    public function verificaLogin($email,$senha);
}

class ReposiorioUsuarioMYSQL implements IRepositorioUsuario {

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost","root","","equilibrio");
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
        $senha = $usuario->getSenha();
        $tipo = $usuario->getTipo();
        $status = $usuario->getStatus();
  
  

        $sql = "INSERT INTO usuarios (id,nome,email,senha,tipo,status)
         VALUES ('$id','$nome','$email','$senha','$tipo','$status')";

        $this->conexao->executarQuery($sql);

    }

    public function alterarUsuario($usuario)
    {
        
    }

    public function alterarTipo($id, $tipo)
    {
        $sql = "UPDATE usuarios SET tipo = '$tipo' WHERE id = '$id'";

        $alterar = $this->conexao->executarQuery($sql);

        return $alterar;
    }

    public function alterarStatus($id, $status)
    {
        $sql = "UPDATE usuarios SET status = '$status' WHERE id = '$id'";

        $alterar = $this->conexao->executarQuery($sql);

        return $alterar;
    }

    public function listarTodosUsuarios()
    {
        $sql = "SELECT * FROM usuarios";

        $listagem = $this->conexao->executarQuery($sql);

        return $listagem;
    }

    public function atualizaPerfil($id,$nome,$email,$senha)
    {
        $sql = "UPDATE usuarios SET nome = '$nome', email = '$email', senha = '$senha' WHERE id = '$id' ";
        $alterar = $this->conexao->executarQuery($sql);

        return $alterar;
    }

    public function verificaEmail($email)
    {

        print_r($email);

        $sql = "SELECT * FROM usuarios WHERE email = '$email'";

        $encontrou = $this->conexao->executarQuery($sql);

        return $encontrou;

    }

    public function buscarUsuario($id_usuario)
    {
        $sql = "SELECT * FROM usuarios WHERE id = '$id_usuario'";

        $encontrou = $this->conexao->executarQuery($sql);

        return $encontrou;

    }

    public function excluirUsuario($id)
    {
        $sql = "DELETE FROM usuarios WHERE id = '$id'";

        $resultado = $this->conexao->executarQuery($sql);

        return $resultado;
    }

    public function verificaLogin($email,$senha)
    {

        $sql = "SELECT * FROM usuarios
        WHERE email = '$email' AND senha = '$senha' AND status = '1'";

        $encontrou = $this->conexao->executarQuery($sql);

        return $encontrou;

    }

}


$respositorioUsuario = new ReposiorioUsuarioMYSQL(); // criar na classe pois assim não é preciso criar em todas as scripts.

?>