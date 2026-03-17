<?php

include_once 'conexao.php';
include_once 'classe_usuario.php';

interface IRepositorioUsuario
{
    public function cadastrarUsuario($usuario);
    public function verifica_email_editar($id_usuario, $email);
    public function verifica_cpf_editar($id_usuario, $cpf);
    public function verifica_senha_editar($id_usuario, $senha);
    public function listarTodosUsuarios();
    public function alterar_cpf($id_usuario, $cpf);
    public function alterar_email($id_usuario, $email);
    public function pesquisarCpf($pesquisar);
    public function excluir_usuario($id_usuario);
    public function verificaEmail($email);
    public function buscarUsuario($id_usuario);
    public function alteraTipo($id, $tipo);
    public function alteraStatus($id, $status);
    public function alterar_senha($id_usuario, $senha);
    public function alterar_nome($id_usuario, $nome);
    public function verificaLogin($email, $senha);
    public function verificaFoto($foto);
    public function listarAdm();
    public function pesquisarComum($pesquisar);
    public function listarClientes();
    public function listarFuncionario();
    public function alteraSaldo($id_usuario, $novo_saldo);
    public function alterarSaldo($is_usuario, $saldo_novo);
    public function atualizarPerfil($id_usuario, $foto);
    public function atualizarSaldo($id_usuario, $saldo_atualizado);
    public function verificaSenha($senha);
    public function verificaCpf($cpf);
}

class ReposiorioUsuarioMYSQL implements IRepositorioUsuario
{

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost", "root", "", "cantina_escolar");
        if ($this->conexao->conectar() == false) {
            echo "Erro" . mysqli_connect_error();
        }
    }

    public function verifica_email_editar($id_usuario, $email)
    {
        $sql = "SELECT * FROM tbl_usuarios WHERE email = '$email' AND id != '$id_usuario'";
        $ressultado = $this->conexao->executarQuery($sql);
        return $ressultado;
    }

    public function verifica_cpf_editar($id_usuario, $cpf)
    {
        $sql = "SELECT * FROM tbl_usuarios WHERE cpf = '$cpf' AND id !='$id_usuario'";
        $resultado_cpf = $this->conexao->executarQuery($sql);
        return $resultado_cpf;
    }

    public function verifica_senha_editar($id_usuario, $senha)
    {
        $sql = "SELECT * FROM tbl_usuarios WHERE senha = '$senha' AND id != '$id_usuario'";
        $resultado = $this->conexao->executarQuery($sql);
        return $resultado;
    }

    public function pesquisarComum($pesquisar)
    {
        $sql = "SELECT * FROM tbl_usuarios WHERE tipo = '0' AND nome_completo LIKE '%$pesquisar%' LIMIT 5";
        $resultado = $this->conexao->executarQuery($sql);
        return $resultado;
    }

    public function pesquisarCpf($pesquisar)
    {
        $sql = "SELECT * FROM tbl_usuarios WHERE tipo = '0' AND cpf LIKE '%$pesquisar%' LIMIT 5";
        $resultado = $this->conexao->executarQuery($sql);
        return $resultado;
    }

    public function alterar_senha($id_usuario, $senha)
    {
        $sql = "UPDATE tbl_usuarios SET senha = '$senha' WHERE id= '$id_usuario'";
        $altera = $this->conexao->executarQuery($sql);
        return $altera;
    }

    public function alterar_email($id_usuario, $email)
    {
        $sql = "UPDATE tbl_usuarios SET email = '$email' WHERE id= '$id_usuario'";
        $altera = $this->conexao->executarQuery($sql);
        return $altera;
    }

    public function alterar_nome($id_usuario, $nome)
    {
        $sql = "UPDATE tbl_usuarios SET nome_completo = '$nome' WHERE id= '$id_usuario'";
        $altera = $this->conexao->executarQuery($sql);
        return $altera;
    }

    public function alterar_cpf($id_usuario, $cpf)
    {
        $sql = "UPDATE tbl_usuarios SET cpf = '$cpf' WHERE id= '$id_usuario'";
        $altera = $this->conexao->executarQuery($sql);
        return $altera;
    }

    public function alterarSaldo($id_usuario, $saldo_novo)
    {
        $sql = "UPDATE tbl_usuarios SET saldo = '$saldo_novo' WHERE id= '$id_usuario'";
        $altera = $this->conexao->executarQuery($sql);
        return $altera;
    }

    public function verificaSenha($senha)
    {
        $sql = "SELECT * FROM tbl_usuarios WHERE senha = '$senha'";
        $encontrou_senha = $this->conexao->executarQuery($sql);
        return $encontrou_senha;
    }

    public function verificaCpf($cpf)
    {
        $sql = "SELECT * FROM tbl_usuarios WHERE cpf = '$cpf'";
        $encontrou_cpf = $this->conexao->executarQuery($sql);
        return $encontrou_cpf;
    }

    public function excluir_usuario($id_usuario)
    {
        $sql = "DELETE FROM tbl_itens_pedido WHERE id_usuario = '$id_usuario'";
        $result = $this->conexao->executarQuery($sql);

        $sql = "DELETE FROM tbl_pedidos WHERE id_usuario = '$id_usuario'";
        $resulta = $this->conexao->executarQuery($sql);

        $sql = "DELETE FROM tbl_usuarios WHERE id = '$id_usuario'";
        $resultado = $this->conexao->executarQuery($sql);
        return $resultado;
    }

    public function cadastrarUsuario($usuario)
    {
        $id = $usuario->getId();
        $nome_completo = $usuario->getNome_completo();
        $cpf = $usuario->getCpf();
        $email = $usuario->getEmail();
        $senha = $usuario->getSenha();
        $tipo = $usuario->getTipo();
        $status = $usuario->getStatus();
        $foto = $usuario->getFoto();
        $saldo = $usuario->getSaldo();

        $sql = "INSERT INTO tbl_usuarios (id, nome_completo, cpf, email, senha, tipo, status , foto, saldo)
         VALUES ('$id','$nome_completo', '$cpf', '$email', '$senha', '$tipo', '$status', '$foto', '$saldo')";

        $this->conexao->executarQuery($sql);
    }

    public function alteraStatus($id, $status)
    {
        $sql = "UPDATE tbl_usuarios SET status = '$status' WHERE id= '$id'";
        $altera = $this->conexao->executarQuery($sql);
        return $altera;
    }

    public function alteraSaldo($id_usuario, $novo_saldo)
    {
        $sql = "UPDATE tbl_usuarios SET saldo = '$novo_saldo' WHERE id= '$id_usuario'";
        $altera = $this->conexao->executarQuery($sql);
        return $altera;
    }

    public function atualizarSaldo($id_usuario, $saldo_atualizado)
    {
        $sql = "UPDATE tbl_usuarios SET saldo = '$saldo_atualizado' WHERE id= '$id_usuario'";
        $altera = $this->conexao->executarQuery($sql);
        return $altera;
    }

    public function listarTodosUsuarios()
    {
        $sql = "SELECT * FROM tbl_usuarios";
        $registro = $this->conexao->executarQuery($sql);
        return $registro;
    }

    public function listarClientes()
    {
        $sql = "SELECT * FROM tbl_usuarios WHERE tipo = '0' ";
        $registro = $this->conexao->executarQuery($sql);
        return $registro;
    }

    public function listarAdm()
    {
        $sql = "SELECT * FROM tbl_usuarios WHERE tipo = '1' ";
        $registro = $this->conexao->executarQuery($sql);
        return $registro;
    }

    public function listarFuncionario()
    {
        $sql = "SELECT * FROM tbl_usuarios WHERE tipo = '2' ";
        $registro = $this->conexao->executarQuery($sql);
        return $registro;
    }

    public function verificaEmail($email)
    {
        $sql = "SELECT * FROM tbl_usuarios WHERE email = '$email'";
        $encontrou = $this->conexao->executarQuery($sql);
        return $encontrou;
    }

    public function buscarUsuario($id_usuario)
    {
        $sql = "SELECT * FROM tbl_usuarios WHERE id = '$id_usuario'";
        $encontrou = $this->conexao->executarQuery($sql);
        return $encontrou;
    }

    public function alteraTipo($id, $tipo)
    {
        $sql = "UPDATE tbl_usuarios SET tipo = '$tipo' WHERE id= '$id'";
        $altera = $this->conexao->executarQuery($sql);
        return $altera;
    }

    public function verificaLogin($email, $senha)
    {
        $sql = "SELECT * FROM tbl_usuarios WHERE email = '$email' AND senha = '$senha' AND status = '1'";
        $encontrou = $this->conexao->executarQuery($sql);
        return $encontrou;
    }

    public function verificaFoto($foto)
    {
        $fotoRecebida = explode(".", $foto['name']);
        $tamanhoArquivo = 2097152;
        $pastaFotoDestino = "../img/perfil/";
        if ($foto['error'] == 0) {
            $extensao = $fotoRecebida['1'];
            if (in_array($extensao, array('jpg', 'jpeg', 'gif', 'png'))) {
                if ($foto['size'] > $tamanhoArquivo) {
                    $mensagem = "Arquivo Enviado é muito Grande";
                    $_SESSION['mensagem'] = $mensagem;
                } else {
                    $novoNome = md5(time()) . "." . $extensao;
                    $enviou = move_uploaded_file($_FILES['foto']['tmp_name'], $pastaFotoDestino . $novoNome);
                    if ($enviou) {
                        return ($novoNome);
                    } else {
                        return false;
                    }
                }
            } else {
                $mensagem = "Somente arquivos do tipo 'jpg', 'jpeg', 'gif', 'png' são permitidos!!!";
                $_SESSION['mensagem'] = $mensagem;
            }
        } else {
            $mensagem = "Um arquivo deve ser enviado!!!!";
            $_SESSION['mensagem'] = $mensagem;
        }
    }

    public function  atualizarPerfil($id_usuario, $foto)
    {
        $sql = "UPDATE tbl_usuarios  SET  foto = '$foto' WHERE id = '$id_usuario' ";
        $altera = $this->conexao->executarQuery($sql);
        return $altera;
    }
}

$respositorioUsuario = new ReposiorioUsuarioMYSQL();
?>

