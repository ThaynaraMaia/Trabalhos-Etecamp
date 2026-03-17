<?php

include_once '../conn/classes/class_IRepositorioUsuarios.php';

session_start(); 

$id_usuario = $_SESSION['id'];
$nome_completo = $_POST['nome_completo'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$confirmesenha = $_POST['confirmesenha'];
$foto = $_FILES['foto'];
$encontrou=$respositorioUsuario->buscarUsuario($id_usuario);
$usuario = $encontrou->fetch_object();

if ($id_usuario > 0) {

    $altera = $respositorioUsuario->alterar_nome($id_usuario, $nome_completo);

    if ($senha !== $confirmesenha) {
        echo "<script>alert('As senhas não coincidem. Tente novamente.'); window.history.back();</script>";
        exit();
    }else if (strlen($senha) < 6) {
        echo "<script>alert('A senha deve ter pelo menos 6 caracteres. Tente novamente.'); window.history.back();</script>";
        exit();
    } 
    
    if($senha !== $usuario->senha){
        $encontrou_senha = $respositorioUsuario->verifica_senha_editar($id_usuario, $senha);
        $linha = $encontrou_senha->num_rows;
        echo "<script>alert('Essa senha já está em uso. Tente novamente.'); window.history.back();</script>";
        exit();
    }else {
        $altera = $respositorioUsuario->alterar_senha($id_usuario, $senha);
    }

    if($email !== $usuario->email){
        $encontrou_email = $respositorioUsuario->verifica_email_editar($id_usuario, $email);
        $linha = $encontrou_email->num_rows;
        echo "<script>alert('Esse email já está em uso. Tente novamente.'); window.history.back();</script>";
        exit();  
    }else{
        $altera = $respositorioUsuario->alterar_email($id_usuario, $email);
    }

    if (isset($_FILES['foto']) ) {
        $encontrou = $respositorioUsuario->verificaFoto($foto);
        $respositorioUsuario->atualizarPerfil($id_usuario, $encontrou);
        echo "<script>alert('Perfil atualizado com sucesso!'); window.location.href='../html/perfil.php';</script>";
        exit();
    } else {
        echo "<script>alert('Perfil atualizado com sucesso!'); window.location.href='../html/perfil.php';</script>";
        exit();
    }
}
?>




