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
            <h2>Recupere sua senha</h2>
            <p style="text-align: center; font-size: large;">Insira seu e-mail para receber o link para redefinir sua senha.</p>
            <div class="inps">
                <h3>E-mail</h3>
                <input name="logon" type="input" placeholder="Seu e-mail registrado" class="inp">
                <br><br>

                <input name="next" type="submit" value="Enviar E-mail" class="inp btn"><br><br><br>
            </div>
        </div>
    </div> 


    <div class="wrapper">
<?php 
        
        
if (isset($_POST['next'])) {
    $logon = $_POST['logon'];

    $sql_forgot = "SELECT * FROM users WHERE email LIKE '$logon'";
    
    $res_forgot = $conn -> query($sql_forgot);
    $row_forgot = $res_forgot -> fetch_array();
    print_r($row);

    if ($res_forgot -> num_rows > 0) {
        $id = $row_forgot['id'];
        header('Location: ../../backend/php/scripts/sendMail.php?para='.$logon.'&id='.$id);
        
    }
}
else {
    echo 'Insira o seu e-mail.';
}

?>
</div>
</div>


</div>
</div>

</form>
</body>
</html>