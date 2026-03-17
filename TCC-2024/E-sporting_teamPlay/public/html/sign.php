<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamPlay - Registro</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/styleLogin.css">
    <link rel="stylesheet" href="../css/styleSign.css">
    <link rel="shortcut icon" href="../assets/Logo_Cor1.png" type="image/x-icon">
    <?php include '../../backend/classes/conn.php';
    $has_msg = $_SERVER['QUERY_STRING'];
    error_reporting(E_ALL & ~E_NOTICE);
    session_start();
    if (isset($_SESSION['user'])) {
        header('Location: index.php');
    }
    ;
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
                    <div class="toolbutton active" id="pghome">
                        <h1>Login</h1>
                    </div>
                </a>

            </div>
        </div>
    </div>



    <div class="page">
        <div class="con1">
            <div class="con2">
                <?php 
                if(isset($_GET['edit'])) { ?>
                <br>
                <div class="inps" style="display: flex; flex-direction: column; justify-content: center;">
                    <h1>Conta criada com sucesso!</h1>
                    <p style="font-size: large;">Personalize sua experiência agora!</p><br>
                    <a href="<?php
                    echo 'login.php?edit&logon='.$_GET['email'].'&pass='.$_GET['pass'];
                    ?>"><input name="next" style="border: none; width:fit-content; text-align: center; padding: .1vh;" value="Editar perfil" class="inp btn"></a>
                </div>
                
                <?php } else {
                ?>
                <h2>Crie sua conta</h2>
                
                <div class="inps">
                    <h3>Nome</h3>
                    <input name="name" type="input" placeholder="Ex: NovoUsuario" class="inp" required="true">
                    <h3>Email</h3>
                    <input name="email" type="input" placeholder="Ex: novo@email.com" class="inp" required="true">
                    <h3>Senha</h3>
                    <input name="password" type="password" placeholder="Senha" class="inp" required="true">
                    <br><br>
                    <input name="password2" type="password" placeholder="Confirme sua senha" class="inp" required="true">
                    <br><br>
                    <input name="next" type="submit" style="border: none;" value="Registro" class="inp btn">
                </div>
            </div>
        </div>


        <div class="wrapper">

            <?php
            }

            if (isset($_POST['next'])) {
                $user = $_POST['name'];
                $email = $_POST['email'];
                $pass = $_POST['password'];
                $pass2 = $_POST['password2'];

                if ($user and $email and $pass and $pass2) {
                    $sql_check = "SELECT * FROM users WHERE (username LIKE '$user' AND email LIKE '$email' AND password LIKE '$pass');";
                    $res_check = $conn->query($sql_check);
                    $row = $res_check->fetch_array();


                    if ($res_check->num_rows == 1) {
                        echo 'Login já existe!';
                    } else {
                        $sql_sign = "INSERT INTO users (username, nickname, email, password, token, level,

                        verified, status, join_date, favorite_game, games, description, gameplay, region) 

                        VALUES ('".strtolower($user)."', '$user', '$email', 
                        '".md5($pass)."', " . random_int(1000, 9999) . ", '1',

                        '0', '1', '" . date(format: 'Y-m-d') . "', 0, '[]', '-', '-', '--')";


                        $res_sign = $conn->query($sql_sign);
                        // $row = $res_log -> fetch_array();
                        header('Location: sign.php?edit&email='.$email.'&pass='.$pass);
                        
                    }
                } else {
                    echo 'Preencha todos os campos para criar a sua conta!';
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