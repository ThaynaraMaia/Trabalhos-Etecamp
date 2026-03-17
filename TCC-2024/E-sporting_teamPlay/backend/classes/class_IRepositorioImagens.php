<?php
    
include_once 'class_Conexao.php';
include_once 'class_Imagem.php';



interface IRepositorioImagem {
    public function adicionar_imagem($arquivo,$caminho);
    public function alterarImagem($Imagem);
    public function listarTodosImagems();
    public function removerImagem($id);
    public function atualizarImagem($id);
}

class RepositorioImagemMYSQL implements IRepositorioImagem {

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost","root","","teamplay");
        if($this->conexao->conectar() == false) 
        {
            echo "Erro".mysqli_connect_error();
        }
    }
    
   
    function adicionar_imagem($arquivo, $caminho){
        #pfp: "../../frontend/public/imagens/Imagems/"
        $explode = explode(".",$arquivo['name']);
        // print_r($explode);
        $tamanhoPermitido = 2097152;
        $diretorio = $caminho;
        if ($arquivo['error'] == 0) {
            $extensao = $explode['1'];
            if(in_array($extensao, array('jpg', 'jpeg', 'png'))){
                if ($arquivo['size'] > $tamanhoPermitido){
                    $msg = "Arquivo Enviado muito Grande";
                } else {
                    $novo_nome = md5(time()).".".$extensao;
                    // echo "Nome Novo: ".$novo_nome;
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


    public function exibirDados($Imagem){
        $sql = "SELECT * FROM tbl_Imagems WHERE id = '$Imagem'";
    }

    public function alterarImagem($Imagem)
    {
        $id = $Imagem->getId();
        $nome = $Imagem->getNome();
        $email = $Imagem->getEmail();
        $foto = $Imagem->getFoto();
        
              $sql = "UPDATE users SET picture = '$foto' WHERE user_id = $id;";

        $this->conexao->executarQuery($sql);
        // $res = $this->conexao->executarQuery($sql);
        // print_r($res);
    }

    public function listarTodosImagems()
    {
        
    }

    public function atualizarImagem($id)
    {
        
            $sql = 'SELECT * FROM tbl_Imagems WHERE id LIKE "'.$id.'"';
            $res = $this->conexao -> executarQuery($sql);
            $linha = $res -> fetch_assoc();
            // session_name('s1');           
    
            $_SESSION['nome'] = $linha['nome'];
            $_SESSION['email'] = $linha['email'];
            $_SESSION['senha'] = $linha['senha'];
            $_SESSION['foto'] = $linha['foto'];
        
    }

    public function removerImagem($id)
    {
        
    }

}

$respositorioImagem = new RepositorioImagemMYSQL(); 