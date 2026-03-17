<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParaGames - Redefinição de senha</title>
    <link rel="stylesheet" href="../../../public/css/style3.css">
    <link rel="shortcut icon" href="../../../public/assets/Para games.png" type="image/x-icon">
    <?php include '../../classes/conn.php';
    $id = $_GET['id'];
    $token = $_GET['token'];
    error_reporting(E_ALL & ~E_NOTICE);
    session_start();
    if (isset($_SESSION['user'])) {
        header('Location: ../../../public/html/index.php');
    };
    ?>
</head>
<body>
<form action="" method="post">
<div class="con">
    <div class="toolbar">
        <a href="../../../public/html/index.php">
        <div class="Btn home">
            <img src="../../../public/assets/home.png" class="image">    
            <div class="light"></div>
        </div>
        </a>
        <a href="http://localhost/system/backend/php/scripts/indexRedef.php?id=$ID&token=$TOKEN"></a>
    </div>
    <div class="main">
        <div class="light tit">
            <div class="light final"></div>
            <span class="title">Nova senha</span>
            <input name="pass" type="password" placeholder="Nova senha" class="inp">
            <input name="pass2" type="password" placeholder="Confirme a senha" class="inp two">
            <button name="next" value="" class="Btn next"></button>
            <div class="light shade m"></div>
        </div>
        <br>
        <div class="wrapper">
            <?php 
            $sql_reset_check = "SELECT * FROM usuarios WHERE (id LIKE '$id');";
            $res_reset_check = $conn -> query($sql_reset_check);
            $row = $res_reset_check -> fetch_array();

            
            if ($row['token'] == $token) {
                if (isset($_POST['next'])) {
                    $pass = $_POST['pass'];
                    $pass2 = $_POST['pass2'];
                    
                    
                    if ($pass and $pass2) {
                        if ($pass == $pass2) {
                        $sql_reset = "UPDATE usuarios SET senha = '$pass' where id = $id;";
                        

                        $res_reset = $conn -> query($sql_reset);
                        header("Location: ../../../public/html/indexLogin.php?3");
                        }
                        else {
                            echo 'Senhas não coincidem!';
                        }
                    }
                    else {
                        echo 'Preencha todos os campos para continuar!';
                    }
                }
            }
            ?>
        </div>
    </div>
</div>
<span class="auth" auth>0</span>
</form>


<footer>
    <p class="text foot">® ParaGames 2007-2023</p>
</footer>
</body>
</html>