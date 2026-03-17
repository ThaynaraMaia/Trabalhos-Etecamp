<?php

include_once 'class_Conexao.php';
include_once 'class_Usuario.php';
include_once 'class_InstaMar.php';
include_once 'class_Comentario.php';

interface IRepositorioUsuario {
    public function cadastrarUsuario($usuario);
    public function atualizarUsuario($id, $nome, $email,  $senha_criptografada = null);
    public function listarTodosUsuarios();
    public function listarUsuarios($id);
    public function verificaEmail($email); // método verificar email já existente
    public function buscarUsuario($id);
    public function removerUsuario($id);
    public function verificaLogin($email,$senha);
    public function alteraTipo($id,$tipo);
    public function alteraStatus($id,$status);
    public function alteraFoto($id,$foto);
    public function adicionarPostIMG($PostIMG);
    public function listarTodasPostagens($id_usuario);
    public function MostrarTodasPostagem();
    public function toggleCurtida($id_usuario, $id_postagem);
    public function contarCurtidas($id_postagem);
    public function verificaFoto($foto1);
    public function comentar($id_postagem, $id_usuario, $texto);
    public function listarComentarios($id_postagem);
}

class ReposiorioUsuarioMYSQL implements IRepositorioUsuario {

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost","root","","vidamarinha");
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
        $senha_cripto = sha1("Gtha@#$%!").sha1($senha).sha1("haHa123$#@!");
        $tipo = $usuario->getTipo();
        $status = $usuario->getStatus();
        $foto = $usuario->getFoto();

        $sql = "INSERT INTO usuarios (id,nome,email,senha,tipo,status,foto)
         VALUES ('$id','$nome','$email','$senha_cripto','$tipo','$status','$foto')";
        
        $salvaUsuario = $this->conexao->executarQuery($sql);

        return $salvaUsuario;

    }

//     public function atualizarUsuario($id, $nome, $email, $senha = null)
// {
//     if ($senha) {
//         $sql = "UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?";
//         $params = [$nome, $email, $senha, $id];
//     } else {
//         $sql = "UPDATE usuarios SET nome = ?, email = ? WHERE id = ?";
//         $params = [$nome, $email, $id];
//     }

//     return $this->conexao->executarQuery($sql, $params);
// }
public function atualizarUsuario($id, $nome, $email, $senha_criptografada = null)
{
    // A função de atualização deve receber a senha já criptografada pelo código que a chama
    if ($senha_criptografada) {
        $sql = "UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?";
        $params = [$nome, $email, $senha_criptografada, $id];
    } else {
        $sql = "UPDATE usuarios SET nome = ?, email = ? WHERE id = ?";
        $params = [$nome, $email, $id];
    }

    return $this->conexao->executarQuery($sql, $params);
}
    public function listarTodosUsuarios()
    {
        $sql = "SELECT * FROM usuarios ORDER BY nome ASC";

        $registro = $this->conexao->executarQuery($sql);

        return $registro;
    }
    public function listarUsuarios($id)
    {
    $sql = "SELECT * 
            FROM usuarios 
            WHERE id != '$id' 
            AND tipo != 1
            ORDER BY id ASC";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
    }


    public function verificaEmail($email)
    {

        $sql = "SELECT * FROM usuarios WHERE email = '$email'";

        $encontrou = $this->conexao->executarQuery($sql);

        return $encontrou;

    }

    // public function buscarUsuario($id)
    // {
    //     $sql = "SELECT * FROM usuarios WHERE id = '$id'";
    // $resultado = $this->conexao->executarQuery($sql);

    // if ($resultado && mysqli_num_rows($resultado) > 0) {
    //     return mysqli_fetch_assoc($resultado); // já retorna o array
    // } else {
    //     return null; // retorna null se não encontrar
    // }
    // }
    public function buscarUsuario($id)
{
    // Usando prepared statement para evitar injeção de SQL
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $resultado = $this->conexao->executarQuery($sql, [$id]); // O segundo parâmetro deve ser um array com os valores

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        return mysqli_fetch_assoc($resultado);
    } else {
        return null;
    }
}

    public function removerUsuario($id)
    {
          // Deleta o usuário normalmente
    $sql = "DELETE FROM usuarios WHERE id = $id";
    $delete = $this->conexao->executarQuery($sql);

    return $delete;
    }

    public function verificaLogin($email,$senha)
    {

        $sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";

        $encontrou = $this->conexao->executarQuery($sql);

        return $encontrou;

    }

    public function alteraStatus($id,$status)
    {
        $sql = "UPDATE usuarios SET status = '$status' WHERE id= '$id'";

        $altera = $this->conexao->executarQuery($sql);

        return $altera;
    }

    public function alteraTipo($id,$tipo)
    {
        $sql = "UPDATE usuarios SET tipo = '$tipo' WHERE id= '$id'";

        $altera = $this->conexao->executarQuery($sql);

        return $altera;
    }

    public function alteraFoto($id,$foto)
    {
         $sql = "UPDATE usuarios SET foto = '$foto' WHERE id= '$id'";

        $altera = $this->conexao->executarQuery($sql);

        return $altera;
    }
        public function adicionarPostIMG($PostIMG)
    {
        $id = $PostIMG->getId();
        $id_usuario = $PostIMG->getId_usuario();   
        $legenda = $PostIMG->getLegenda();
        $foto1 = $PostIMG->getFoto();

        $sql = "INSERT INTO postagens (id,id_usuario,legenda,img)
         VALUES ('$id','$id_usuario','$legenda','$foto1')";
        
        $salvaPost = $this->conexao->executarQuery($sql);

        return $salvaPost;
    }

    public function listarTodasPostagens($id_usuario)
    {
         $id = intval($id_usuario);
    $id_logado = isset($_SESSION['id_usuario']) ? intval($_SESSION['id_usuario']) : 0;

    $sql = "SELECT p.id, p.legenda, p.img AS imagem_post,
                   p.data_postagem,
                   u.nome AS autor, u.foto AS foto_usuario, cm.id AS id_coment,
                   COUNT(DISTINCT c.id) AS total_curtidas,
                   COUNT(DISTINCT cm.id) AS total_comentarios,
                   EXISTS(
                       SELECT 1 FROM curtidas 
                       WHERE curtidas.id_postagem = p.id 
                       AND curtidas.id_usuario = $id_logado
                   ) AS usuario_curtiu
            FROM postagens p
            INNER JOIN usuarios u ON p.id_usuario = u.id
            LEFT JOIN curtidas c ON p.id = c.id_postagem
            LEFT JOIN comentarios cm ON p.id = cm.id_postagem
            WHERE p.id_usuario = $id
            GROUP BY p.id
            ORDER BY p.data_postagem ASC";

    $listar = $this->conexao->executarQuery($sql);
    return $listar;
    }

public function MostrarTodasPostagem()
{
    $id_usuario = isset($_SESSION['id_usuario']) ? intval($_SESSION['id_usuario']) : 0;

    $sql = "SELECT p.id, p.legenda, p.img AS imagem_post,
                   p.data_postagem,
                   u.id AS id_usuario_post,  -- ID do autor da postagem
                   u.nome AS autor, 
                   u.foto AS foto_usuario,
                   u.tipo AS tipo_usuario,
                   COUNT(c.id) AS total_curtidas,
                   EXISTS(
                       SELECT 1 
                       FROM curtidas 
                       WHERE curtidas.id_postagem = p.id 
                       AND curtidas.id_usuario = $id_usuario
                   ) AS usuario_curtiu
            FROM postagens p
            INNER JOIN usuarios u ON p.id_usuario = u.id
            LEFT JOIN curtidas c ON p.id = c.id_postagem
            GROUP BY p.id
            ORDER BY p.data_postagem DESC";

    $registro = $this->conexao->executarQuery($sql);
    return $registro;
}

     public function toggleCurtida($id_usuario, $id_postagem) {
    $id_usuario = intval($id_usuario);
    $id_postagem = intval($id_postagem);
    
    // Verifica se já curtiu
    $sql = "SELECT id FROM curtidas WHERE id_usuario = $id_usuario AND id_postagem = $id_postagem";
    $result = $this->conexao->executarQuery($sql);

    if (mysqli_num_rows($result) > 0) {
        // Remove curtida
        $sql = "DELETE FROM curtidas WHERE id_usuario = $id_usuario AND id_postagem = $id_postagem";
        $this->conexao->executarQuery($sql);
        $status = "descurtido";
    } else {
        // Adiciona curtida (data_curtida será automaticamente preenchida)
        $sql = "INSERT INTO curtidas (id_usuario, id_postagem) VALUES ($id_usuario, $id_postagem)";
        $this->conexao->executarQuery($sql);
        $status = "curtido";
    }

    return $status;
    }

    // Contar curtidas
    public function contarCurtidas($id_postagem) {
        $sql = "SELECT COUNT(*) AS total FROM curtidas WHERE id_postagem = $id_postagem";
        $result = $this->conexao->executarQuery($sql);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    public function verificaFoto($foto1)
    {
        $fotoRecebida = explode(".", $foto1['name']); // receba a foto e separa pelo "."
        $tamanhoArquivo = 5242880; // Tamanho máximo permitido
        $pastaFotoDestino = "../../../../frontend/public/img_instamar/";
        if ($foto1['error'] == 0){
            $extensao = $fotoRecebida['1'];
            if(in_array($extensao, array('jpg', 'jpeg', 'gif', 'png'))) {
                if ($foto1['size'] > $tamanhoArquivo) {
                    $mensagem = "Arquivo Enviado é muito Grande";
                    $_SESSION['mensagem'] = $mensagem;
                } else {
                    $novoNome = md5(time()). "." . $extensao;
                    // echo $_FILES['foto']['tmp_name'];
                    // echo "<br>";
                    // echo $foto1['tmp_name'];
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

     // ---------------------------
    // SALVAR COMENTÁRIO
    // ---------------------------
    public function comentar($id_postagem, $id_usuario, $texto) {
        $db = $this->conexao->getConnection();

        $id_postagem = mysqli_real_escape_string($db, $id_postagem);
        $id_usuario  = mysqli_real_escape_string($db, $id_usuario);
        $texto       = mysqli_real_escape_string($db, $texto);

        $sql = "INSERT INTO comentarios (id_postagem, id_usuario, texto) 
                VALUES ('$id_postagem', '$id_usuario', '$texto')";

        return mysqli_query($db, $sql);
    }

    // ---------------------------
    // LISTAR COMENTÁRIOS
    // ---------------------------
    public function listarComentarios($id_postagem) {
        $db = $this->conexao->getConnection();
        $id_postagem = mysqli_real_escape_string($db, $id_postagem);

        $sql = "SELECT c.id,c.texto, c.data_comentario, u.nome,u.id as id_usuario, u.foto 
                FROM comentarios c
                INNER JOIN usuarios u ON c.id_usuario = u.id
                WHERE c.id_postagem = '$id_postagem'
                ORDER BY c.data_comentario DESC";

        $resultado = mysqli_query($db, $sql);
        $comentarios = [];

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($linha = mysqli_fetch_assoc($resultado)) {
                $comentarios[] = $linha;
            }
        }

        return $comentarios;
    }

}
$respositorioUsuario = new ReposiorioUsuarioMYSQL(); // criar na classe pois assim não é preciso criar em todas as scripts.

?>