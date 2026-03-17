<?php

include 'config.php';
session_start();

if(isset($_POST['submit'])){
    // $email=mysqli_real_escape_string($conn,$_POST['email']);
    $email=$_POST['email_form'];
    $senha=md5($_POST['senha']);

    // $select="SELECT * FROM usuario_form WHERE email='$email' && senha='$senha'";
    $select="SELECT * FROM usuario_form WHERE email = '$email' AND senha = '$senha'";
    
    // $resultado=mysqli_query($conn,$select);

    $resultado = mysqli_query($conn,$select);

    $linhas = mysqli_num_rows($resultado);
    
    if(mysqli_num_rows($resultado)>=0){
       $row=mysqli_fetch_array($resultado);

        $_SESSION['usuario_id']= $row['usuario_id'];


       if($row['tipo']== 0){
        $_SESSION['user_nome']= $row['nome'];
        header('location:usuario_page.php');
        
        }elseif($row['tipo']== 1){
        $_SESSION['admin_name']= $row['nome'];
        header('location:admin_page.php');
       }

    //    if($row['usuario_tipo']== 0){
    //     $_SESSION['user_nome']= $row['nome'];
    //     header('location:usuario_page.php');
        
    //     }elseif($row['usuario_tipo']== 1){
    //     $_SESSION['admin_name']= $row['nome'];
    //     header('location:admin_page.php');
    //    }


    
    }else{
        $error[] = 'Email ou senha incorretos!';
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login form</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="shortcut icon" href="../img/logoxampp.png" type="image/x-icon">
</head>
<body>
    <div class="form-container">
    <div class="form-img">
            <img src="../img/login.png" alt="imagem login">
        </div>
        <form action=""method="POST">
        <div>
            <img src="../img/enorme.png" alt="logo principal">
        </div>
        
        <h3>Login</h3>
        <?php
        if(isset( $error )){
            foreach($error as $error){
                echo '<span class="error-msg">'.$error.'</span>';
            }
        }
        ?>
        <input type="text" name="email_form" required placeholder="E-mail">
        <input type="password" name="senha" required placeholder="Senha">
        <input type ="submit" name="submit" value="logar agora" class="form-btn"></a></input>
        <p>Não tem cadastro? <a href="../php/registro_form.php">Registre agora</a></p>
    </form>

    </div>
</body>
</html>