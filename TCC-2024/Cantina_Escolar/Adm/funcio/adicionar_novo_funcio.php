<?php

session_start();

include_once '../../conn/classes/class_IRepositorioFuncionario.php';

$nome_completo = $_POST['nome_completo'];
$cpf = $_POST['cpf'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$confirmaSenha = $_POST['confirmaSenha'];

$encontrou_senha = $respositorioFuncionario->verificaSenha($_POST['senha']);
$linha_senha = $encontrou_senha->num_rows; 

if ($senha !== $confirmaSenha) {
    echo "<script>alert('As senhas não coincidem. Tente novamente.'); window.history.back();</script>";
    
}else if($linha_senha >0){
    echo "<script>alert('Essa senha já existe. Tente outra.'); window.history.back();</script>";

}else if(strlen($senha) < 6){
        echo "<script>alert('A senha deve ter pelo menos 6 caracteres. Tente novamente.'); window.history.back();</script>";
}else{
    $encontrou = $respositorioFuncionario->verifica_email($email);
    $linhas = $encontrou->num_rows; 
    if($linhas>0){
        echo "<script>alert('Email já cadastrado. Tente novamente.'); window.history.back();</script>";
    }else{
    $respositorioFuncionario->adicionarFuncionario($nome_completo, $cpf, $email,$senha);

    header('Location: ../gerenciar_funcionario.php');
}
}
?>
