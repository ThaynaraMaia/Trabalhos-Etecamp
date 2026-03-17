<?php

include_once '../classes/class_IRepositorioUsuarios.php';

session_start();

$email = $_POST['email'];
$senha = sha1("Gtha@#$%!").sha1($_POST['senha']).sha1("haHa123$#@!");

$encontrou = $respositorioUsuario->verificaLogin($email,$senha);

$registroUsuario = $encontrou->fetch_object();

$linhas = $encontrou->num_rows;

if($linhas>0){
    $mensagem = "Usuário Logado com sucesso!!!!";
    $_SESSION['mensagem'] = $mensagem;
    $_SESSION['id_usuario'] = $registroUsuario->id;
    $_SESSION['nome']=$registroUsuario->nome;
    $_SESSION['email']=$registroUsuario->email;
    $_SESSION['senha']=$registroUsuario->senha;
    $_SESSION['tipo']=$registroUsuario->tipo;
    $_SESSION['status']=$registroUsuario->status;
    $_SESSION['logado']=true;
} 

else

{
    $mensagem="Usuário não econtrado!!!!!";
    $_SESSION['mensagem']=$mensagem;
    $_SESSION['logado']=false;
    header('Location:login.php');
}

if($_SESSION['tipo']==1 && $linhas > 0){
    $mensagem="";
    header('Location:../usuarios/adm/homeAdm.php');
} elseif($_SESSION['tipo']==0 && $linhas > 0){
    header('Location:../usuarios/comum/homeUsuario.php');
} else {
    $mensagem="Acesso não permitido!!!!!";
    $_SESSION['mensagem']=$mensagem;
}

?>