<?php
    session_start();
    include_once '../../backend/classes/class_IRepositorioUsuarios.php';
    include_once '../../backend/classes/class_IRepositorioImagens.php';
    echo $_SESSION['userid'], $_POST['nome'],$_POST['email'];
    $caminho = "../../frontend/public/imagens/usuarios/";
    $foto = (new RepositorioImagemMYSQL)->adicionar_imagem($_FILES['foto'],$caminho);
    echo('<img src="../public/imagens/usuarios/'.$foto.'" alt="" width="150px" height="150px" srcset="">');
    $usuarioEdit = new usuario($_SESSION['userid'], $_POST['nome'],$_POST['email'],'',$_POST['descricao'],1,0,$foto, 0);

    $respositorioUsuario->alterarUsuario($usuarioEdit);
    $respositorioUsuario->atualizarUsuario($_SESSION['userid']);
    
    header('Location: ../principal/user.php?id='.$_SESSION['userid']);
    
    exit;

?>