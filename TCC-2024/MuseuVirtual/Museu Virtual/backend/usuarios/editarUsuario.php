<?php

    include_once "../classes/class_repositorioUsuarios.php";

    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $foto = $_FILES['foto'];

    if(isset($_FILES['foto']) && !empty($_FILES['foto']['name'])){

        $verificouFoto = $repositorioUsuario->verificaFoto($foto);

        $editar = $repositorioUsuario->editarUsuarioComFoto($id, $nome, $verificouFoto);

        header('Location:../../frontend/paginas/perfil.php');
    }
    else{

        $editar = $repositorioUsuario->editarUsuario($id, $nome);
        header('Location:../../frontend/paginas/perfil.php');
    }
    
    
    exit;
?>