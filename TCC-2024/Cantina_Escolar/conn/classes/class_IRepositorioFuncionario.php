<?php

include_once 'conexao.php';
include_once 'classe_funcionario.php';

interface IRepositorioFuncionario
{
    public function listarAdministrador();
    public function verificaSenha($senha);
    public function verificaLogin_funcionario($email, $senha);
    public function alterarStatus($id, $status);
    public function excluir_usuarios($id_usuario);
    public function verifica_email($email);
    public function alterarTipo($id, $tipo);
    public function buscarFuncionario($id_funcionario);
    public function adicionarFuncionario($nome_completo, $cpf, $email, $senha);
    public function listarFuncionarios();
    public function adicionarAdm($nome_completo, $cpf, $email, $senha);
    public function pesquisarAdm($pesquisar);
    public function pesquisarFuncio($pesquisar);
}

class ReposiorioFuncionarioMYSQL implements IRepositorioFuncionario
{

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost", "root", "", "cantina_escolar");
        if ($this->conexao->conectar() == false) {
            echo "Erro" . mysqli_connect_error();
        }
    }

    public function verificaSenha($senha)
    {
        $sql = "SELECT * FROM tbl_funcionarios WHERE senha = '$senha'";
        $encontrou_senha = $this->conexao->executarQuery($sql);
        return $encontrou_senha;
    }

    public function verificaLogin_funcionario($email, $senha)
    {
        $sql = "SELECT * FROM tbl_funcionarios WHERE email = '$email' AND senha = '$senha' AND status = '1'";
        $encontrou_funcio = $this->conexao->executarQuery($sql);
        return $encontrou_funcio;
    }

    public function pesquisarAdm($pesquisar)
    {
        $sql = "SELECT * FROM tbl_funcionarios WHERE tipo = '1' AND nome_completo LIKE '%$pesquisar%' LIMIT 5";
        $resultado = $this->conexao->executarQuery($sql);
        return $resultado;
    }

    public function pesquisarFuncio($pesquisar)
    {
        $sql = "SELECT * FROM tbl_funcionarios WHERE tipo = '2' AND nome_completo LIKE '%$pesquisar%' LIMIT 5";
        $resultado = $this->conexao->executarQuery($sql);
        return $resultado;
    }

    public function listarAdministrador()
    {
        $sql = "SELECT * FROM tbl_funcionarios WHERE tipo = '1' ";
        $registro = $this->conexao->executarQuery($sql);
        return $registro;
    }

    public function listarFuncionarios()
    {
        $sql = "SELECT * FROM tbl_funcionarios WHERE tipo = '2' ";
        $registro = $this->conexao->executarQuery($sql);
        return $registro;
    }

    public function alterarStatus($id, $status)
    {
        $sql = "UPDATE tbl_funcionarios SET status = '$status' WHERE id= '$id'";
        $altera = $this->conexao->executarQuery($sql);
        return $altera;
    }

    public function alterarTipo($id, $tipo)
    {
        $sql = "UPDATE tbl_funcionarios SET tipo = '$tipo' WHERE id= '$id'";
        $altera = $this->conexao->executarQuery($sql);
        return $altera;
    }

    public function excluir_usuarios($id_usuario)
    {
        $sql = "DELETE FROM tbl_funcionarios WHERE id = '$id_usuario'";
        $resultado = $this->conexao->executarQuery($sql);
        return $resultado;
    }

    public function adicionarAdm($nome_completo, $cpf, $email, $senha)
    {
        $sql = "INSERT INTO tbl_funcionarios  (id, nome_completo, cpf, email, senha, tipo, status , foto)
         VALUES ('','$nome_completo', '$cpf', '$email', '$senha', '1', '1', 'perfilimg.png')";
        $this->conexao->executarQuery($sql);
    }

    public function adicionarFuncionario($nome_completo, $cpf, $email, $senha)
    {
        $sql = "INSERT INTO tbl_funcionarios  (id, nome_completo, cpf, email, senha, tipo, status , foto)
        VALUES ('','$nome_completo', '$cpf', '$email', '$senha', '2', '1', 'perfilimg.png')";
        $this->conexao->executarQuery($sql);
    }

    public function verifica_email($email)
    {
        $sql = "SELECT * FROM tbl_funcionarios WHERE email = '$email'";
        $encontrou = $this->conexao->executarQuery($sql);
        return $encontrou;
    }

    public function buscarFuncionario($id_funcionario)
    {
        $sql = "SELECT * FROM tbl_funcionarios WHERE id = '$id_funcionario'";
        $registro = $this->conexao->executarQuery($sql);
        return $registro;
    }
}
$respositorioFuncionario = new ReposiorioFuncionarioMYSQL();
?>
