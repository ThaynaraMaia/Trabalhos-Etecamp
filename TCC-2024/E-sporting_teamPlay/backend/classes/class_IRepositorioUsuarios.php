<?php
    
include_once 'conn.php';
include_once 'class_Usuario.php';
include_once 'class_Conexao.php';



interface class_IRepositorioUsuarios {
    public function cadastrarUsuario($Usuario);
    public function alterarUsuario($Usuario);
    public function listarTodosUsuarios();
    public function removerUsuario($id);
    public function atualizarUsuario($id);
}

class RepositorioUsuarioMYSQL implements class_IRepositorioUsuarios {

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost","root","","teamplay");
        if($this->conexao->conectar() == false) 
        {
            echo "Erro".mysqli_connect_error();
        }
    }
    
   
    public function cadastrarUsuario($Usuario)
    {

        $id = $Usuario->getId();
        $nome = $Usuario->getNome();
        $email = $Usuario->getEmail();
        $senha = $Usuario->getSenha();
        $tipo = $Usuario->getTipo();
        $status = $Usuario->getStatus();
        $foto = $Usuario->getFoto();
        
      
            $sql = "INSERT INTO users (id,name,email,password,tipo,status,foto)
             VALUES ('$id','$nome','$email','$senha','$tipo','$status','$foto')";
            
            $this->conexao->executarQuery($sql);
        
    

    }

    public function verificaUsuario($Usuario)
    {
        // $id = $Usuario->getId();
        $email = $Usuario->getEmail();
        $senha = $Usuario->getSenha();
        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$senha'";
        $var = false;
        $res = $this->conexao->executarQuery($sql);
        if($res -> num_rows == 1) {
            $dados =  $res -> fetch_assoc();
            $var = true;
        }

        return [$var, $dados];
    }

    public function exibirDados($Usuario){
        $sql = "SELECT * FROM users WHERE id = '$Usuario'";
    }

    public function alterarUsuario($Usuario)
    {
        $id = $Usuario->getId();
        $nome = $Usuario->getNome();
        $nick = $Usuario->getNick();
        $email = $Usuario->getEmail();
        $foto = $Usuario->getFoto();
        $desc = $Usuario->getDesc();
        $gameplay = $Usuario->getGameplay();
        $socials = $Usuario->getSocials();
        
              $sql = "UPDATE users SET username='".$nome."', nickname='".$nick."', email='".$email."',picture='".$foto."'
        WHERE id = ".$id.";";

        $this->conexao->executarQuery($sql);
        // $res = $this->conexao->executarQuery($sql);
        // print_r($res);
    }

    public function listarTodosUsuarios()
    {
        
    }

    public function atualizarUsuario($id)
    {
        
            $sql = 'SELECT * FROM users WHERE id LIKE "'.$id.'"';
            $res = $this->conexao -> executarQuery($sql);
            $linha = $res -> fetch_assoc();
            // session_name('s1');           
    
            $_SESSION['username'] = $linha['username'];
            $_SESSION['email'] = $linha['email'];
            
            // $_SESSION['passwp'] = $linha['senha'];
            $_SESSION['pfp'] = $linha['picture'];
        
    }

    public function removerUsuario($id)
    {
        
    }

    function imagem($arquivo){
    
    $explode = explode(".",$arquivo['name']);
    print_r($explode);
    $tamanhoPermitido = 2097152;
    $diretorio = "../../frontend/public/imagens/Usuarios/";
    if ($arquivo['error'] == 0) {
        $extensao = $explode['1'];
        if(in_array($extensao, array('jpg', 'jpeg', 'png'))){
            if ($arquivo['size'] > $tamanhoPermitido){
                $msg = "Arquivo Enviado muito Grande";
            } else {
                $novo_nome = md5(time()).".".$extensao;
                echo "Nome Novo: ".$novo_nome;
                $enviou = move_uploaded_file($_FILES['pfp']['tmp_name'],$diretorio.$novo_nome);
                if($enviou){
                    $msg = "<strong>Sucesso!</strong> Arquivo enviado corretamente.";
                    return($novo_nome);
                }else{
                    $msg = "<strong>Erro!</strong> Falha ao enviar o arquivo.";
                }
            }
        } else {
            $msg = "<strong>Erro!</strong> Somente arquivos tipo imagem 'jpg', 'jpeg', 'png' são permitidos.";
        }
    } else {
        $msg = "<strong>Atenção!</strong> Você deve enviar um arquivo.";
    }
}
}

$repositorioUsuario = new RepositorioUsuarioMYSQL(); 

