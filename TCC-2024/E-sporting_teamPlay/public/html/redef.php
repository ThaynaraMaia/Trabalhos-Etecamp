<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamPlay - Esqueci a Senha</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/styleSign.css">
    <link rel="shortcut icon" href="../assets/Logo_Cor1.png" type="image/x-icon">
    <?php include '../../backend/classes/conn.php';
    $has_msg = $_SERVER['QUERY_STRING'];
    error_reporting(E_ALL & ~E_NOTICE); 
    $logged = false;
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);
    if (isset($_SESSION['id'])) {
        $logged = true;
        header('Location: index.php');
        exit();
    } else {
    }
    ?>
</head>


<body>
<form action="" method="post">
<div class="main">
<div class="content">
    
<div class="toolbar">
    <div class="logo">
        <a href="index.php">
            <img src="../assets/Logo_Full.png" style="width: 10vw;" alt="TeamPlay">
        </a>
    </div>    


    <div class="userarea">
        <a href="login.php">
        <div class="toolbutton active" id="pghome"><h1>Entrar</h1></div>
        </a> 
        
        </div>
    </div>
</div>


<div class="page">
    <div class="con1">
        <div class="con2">
            <h2>Redefina sua senha</h2>
            <div class="inps">
                <h3>Senha</h3>
                <input name="logon" type="password" placeholder="#NovaSenha123::" class="inp">
                <br>
                <h3>Confirmar senha</h3>
                <input name="logon2" type="password" placeholder="#NovaSenha123::" class="inp">
                <br><br>

                <input name="next" type="submit" value="Enviar E-mail" class="inp btn"><br><br>
            </div>
        </div>
    </div> 


    <div class="wrapper">
<?php 
        
        
if (isset($_POST['next'])) {
    if ($_POST['logon'] and $_POST['logon2']) {
        if ($_POST['logon'] == $_POST['logon2']) {
            $newpass = $_POST['logon'];

            $sql_pass = "UPDATE users SET password = '".md5($newpass)."' WHERE id LIKE '".$_GET['id']."';";
            // echo $sql_pass;
            $res_pass = $conn->query($sql_pass);
    
    
            
            echo 'Senha atualizada com sucesso! <a style="color: var(--mag)" href="login.php">Fazer login</a>';
        } else {
            echo 'Senhas não batem.';
        }
    } else {
        echo 'Preencha ambos os campos corretamente.';
    }
}

?>
</div>
</div>


</div>
</div>

</form>
</body>
</html>