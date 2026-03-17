<?php

include_once 'class_conexao.php';
include_once 'class_Usuario.php';

interface IRepositorioUsuario
{
    public function listarTodosUsuarios();
    public function removerUsuario($id);
    public function alteraTipo($id, $tipo);
    // public function verificaFoto($fotoUsuario);
}

class RepositorioUsuarioMYSQL implements IRepositorioUsuario
{

    private $conexao;

    public function __construct()
    {
        $this->conexao = new mysqli("localhost", "root", "", "pawsitive");
        if ($this->conexao->connect_errno) {
            echo "Erro ao conectar: " . $this->conexao->connect_error;
        }
    }

    public function listarTodosUsuarios()
    {
        $sql = "SELECT * FROM tblusuarios";
        $registros = $this->conexao->query($sql);
        return $registros;
    }

    public function removerUsuario($id)
    {
        // Preparar a instrução SQL para evitar SQL Injection
        $stmt = $this->conexao->prepare("DELETE FROM tblusuarios WHERE id = ?");
        if (!$stmt) {
            echo "Erro na preparação: " . $this->conexao->error;
            return false;
        }
        // Liga o parâmetro $id ao statement (i indica que é inteiro)
        $stmt->bind_param("i", $id);

        // Executa o comando
        $executou = $stmt->execute();

        if (!$executou) {
            echo "Erro na execução: " . $stmt->error;
        }

        $stmt->close();

        return $executou;
    }

    public function alteraTipo($id, $tipo)
    {
        // Preparar statement para evitar injeção SQL
        $stmt = $this->conexao->prepare("UPDATE tblusuarios SET tipo_usuario = ? WHERE id = ?");
        if (!$stmt) {
            echo "Erro na preparação: " . $this->conexao->error;
            return false;
        }
        $stmt->bind_param("si", $tipo, $id); // tipo string, id inteiro
        $executou = $stmt->execute();
        if (!$executou) {
            echo "Erro na execução: " . $stmt->error;
        }
        $stmt->close();
        return $executou;
    }

    // public function verificaFoto($fotoUsuario)
    // {
    //     $fotoRecebida = explode(".", $fotoUsuario['name']); // receba a foto e separa pelo "."
    //     $tamanhoArquivo = 2097152; // Tamanho máximo permitido
    //     $pastaFotoDestino = "../../imgUsuarios/";
    //     if ($fotoUsuario['error'] == 0){
    //         $extensao = $fotoRecebida['1'];
    //         if(in_array($extensao, array('jpg', 'jpeg', 'gif', 'png'))) {
    //             if ($fotoUsuario['size'] > $tamanhoArquivo) {
    //                 $mensagem = "Arquivo Enviado é muito Grande";
    //                 $_SESSION['mensagem'] = $mensagem;
    //             } else {
    //                 $novoNome = md5(time()). "." . $extensao;
    //                 echo $_FILES['fotoUsuario']['tmp_name'];
    //                 echo "<br>";
    //                 echo $fotoUsuario['tmp_name'];
    //                 $enviou = move_uploaded_file($_FILES['fotoUsuario']['tmp_name'], $pastaFotoDestino . $novoNome);
    //                 if ($enviou) {
    //                     return ($novoNome);
    //                 } else {
    //                     return false;
    //                 }
    //             }
    //         } else {
    //             $mensagem = "Somente arquivos do tipo 'jpg', 'jpeg', 'gif', 'png' são permitidos!!!";
    //             $_SESSION['mensagem'] = $mensagem;
    //         }
    //     } else {
    //         $mensagem = "Um arquivo deve ser enviado!!!!";
    //         $_SESSION['mensagem'] = $mensagem;
    //     }
    // }
}

$respositorioUsuario = new RepositorioUsuarioMYSQL(); // criar na classe pois assim não é preciso criar em todas as scripts.
