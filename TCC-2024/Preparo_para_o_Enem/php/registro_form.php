<?php

@include 'config.php';

if(isset($_POST['submit'])){
    $nome=$_POST['nome'];
    $sobrenome=$_POST['sobrenome'];
    $email=$_POST['email'];
    $senha=md5($_POST['senha']);
    $confirme_senha=md5($_POST['confirme_senha']);
    $nome_escola=$_POST['nome_escola'];
    $aniversario=$_POST['aniversario'];
    $telefone = $_POST['telefone'];
    $bio = $_POST['bio'];
    $foto_perfil = $_POST['foto_perfil'];
    $tipo = $_POST['tipo'];


    $select="SELECT * FROM usuario_form WHERE email='$email' AND senha='$senha'";

    $resultado=mysqli_query($conn,$select);

    if(mysqli_num_rows($resultado)>0){
        $error[]='Usuário existente!';
    }else{
        if($senha != $confirme_senha){
            $error[]='Senha indefinida!';
        }else{
            $insert= "INSERT INTO usuario_form(usuario_id,nome,sobrenome,email,senha,nome_escola,aniversario,telefone,bio,foto_perfil,tipo)
             VALUES ('','$nome','$sobrenome','$email','$senha','$nome_escola','$aniversario','$telefone','$bio','$foto_perfil','$tipo')";

            //  "INSERT INTO usuario_form(usuario_id,nome,email,senha,usuario_tipo)
            //  VALUES ('','$nome','$email','$senha','$usuario_tipo')";

            mysqli_query($conn,$insert);
            header('location:login_form.php');
        }
    }
};

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENEM PlatD</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../img/logoxampp.png" type="image/x-icon">
</head>
<body>
    <div class="form-container">
        <div class="form-img">
            <img src="../img/cadastro.png" alt="imagem cadastro">
        </div>

    <form action=""method="POST">
    <!-- <div>
            <img src="../img/enorme.png" alt="logo principal">
        </div> -->
        
        <h3>Registro</h3>


        <?php
        if(isset( $error )){
            foreach($error as $error){
                echo '<span class="error-msg">'.$error.'</span>';
            }
        }
        ?>

        <input type="text" name="nome"required placeholder="Nome">
        <input type="text" name="sobrenome"required placeholder="Sobrenome">
        <input type="email" name="email"required placeholder="E-mail">
        <input type="password" name="senha"required placeholder="Senha">
        <input type="password" name="confirme_senha"required placeholder="Confirme sua senha">
        <input type="text" name="nome_escola"required placeholder="Nome da Escola">
        <input type="date" name="aniversario"required placeholder="Aniversário">
        <input type="text" name="telefone"required placeholder="Telefone">
        <input type="text" name="bio"required placeholder="Bio">
        <input type="file" name="foto_perfil"required placeholder="Foto">
        <!-- <select name="user_type">
            <option value="usuario">Usuário</option>
            <option value="admin">Administrador</option>
        </select> -->
        <input type ="submit" name="submit" value="registro agora" class="form-btn"></input>
        <p>Já tem uma conta? <a href="../php/login_form.php">Logar agora</a></p>
    </form>

    </div>
</body>
</html>