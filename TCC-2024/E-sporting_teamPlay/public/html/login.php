<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamPlay - Login</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/styleSign.css">
    <link rel="shortcut icon" href="../assets/Logo_Cor1.png" type="image/x-icon">
    <?php include '../../backend/classes/conn.php';
    $has_msg = $_SERVER['QUERY_STRING'];
    error_reporting(E_ALL & ~E_NOTICE); 
    session_start();
    if (isset($_SESSION['username'])) {
        header('Location: index.php'); };
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
             
        </div>    -->
        <a href="sign.php">
        <div class="toolbutton active" id="pghome"><h1>Criar Conta</h1></div>
        </a> 
        
        </div>
    </div>
</div>


<div class="page">
    <div class="con1">
        <div class="con2">
            <h2>Faça seu login</h2>
            <div class="inps">
                <h3>Nome</h3>
                <input name="logon" type="input" placeholder="@username ou E-mail" class="inp">
                <h3>Senha</h3>
                <input name="password" type="password" placeholder="Senha" class="inp">
                <br><br>
                <a href="forgot.php" class="link">Esqueci minha senha</a>
                <br><br>
                

                <input name="next" type="submit" value="Entrar" class="inp btn"><br>
            </div>
        </div>
    </div> 


    <div class="wrapper">
<?php 
// if ($has_msg) {
        //     switch ($has_msg) {
        //         case '1': echo 'Conta criada com sucesso!<br><br>'; break;
        //         case '2': echo 'Email para redefinição de senha enviado com sucesso! Verifique sua caixa de entrada.<br><br>'; break;
        //         case '3': echo 'Senha alterada com sucesso!<br><br>'; break;
        //         default: break;
        //     }
        // }
        
        
if (isset($_GET['edit'])) {
    $sql_log = "SELECT * FROM users WHERE (email LIKE '".$_GET['logon']."' OR username LIKE '".$_GET['logon']."') AND password LIKE '".md5($_GET['pass'])."';";
    echo $sql_log;
    $res_log = $conn -> query($sql_log);
    $row = $res_log -> fetch_array();
    print_r($row);

    if ($res_log -> num_rows == 1) {
        session_start();
        refresh_user($conn, $row);
        $_SESSION['logon'] = $logon;
        
        header('Location: edit.php');
    }
    else {
        echo 'Login ou senha incorretos!';
    }
}


if (isset($_POST['next'])) {
    $logon = $_POST['logon'];
    $pass = $_POST['password'];
    
    
    if ($logon and $pass) { 
        $sql_log = "SELECT * FROM users WHERE (((username LIKE '$logon') OR
        (email LIKE '$logon')) AND password LIKE '".md5($pass)."');";
        
        $res_log = $conn -> query($sql_log);
        $row = $res_log -> fetch_array();
        print_r($row);

        if ($res_log -> num_rows == 1) {
            session_start();
            refresh_user($conn, $row);
            $_SESSION['logon'] = $logon;
            
            header('Location: index.php');
        }
        else {
            echo 'Login ou senha incorretos!';
        }
    }
    else {
        echo 'Preencha todos os campos para fazer login!';
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