<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamPlay - Editar Perfil</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/styleLogin.css">
    <link rel="stylesheet" href="../css/styleSign.css">
    <link rel="stylesheet" href="../css/styleEdit.css">
    <link rel="shortcut icon" href="../assets/Logo_Cor1.png" type="image/x-icon">
    <?php include '../../backend/classes/conn.php';
    include 'checkban.php';
    include '../../backend/php/scripts/imgupload.php';

    $logged = false;
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);
    if (isset($_SESSION['username'])) {
        $logged = true;
    } else {
        header('Location: welcome.html');
    }
    ?>
</head>


<body>
<form action="" method="post" enctype="multipart/form-data">
<div class="main">
<div class="content">
    <div class="toolbar">
        <div class="logo">
            <a href="index.php">
                <img src="../assets/Logo_Full.png" style="width: 10vw;" alt="TeamPlay">
            </a>
        </div>


    <div class="userarea">
        <?php if ($logged) { ?> 

        <div class="pfpimg">
        <img src="<?php echo $_SESSION['pfp']; ?>" alt="User">
             
        </div>
        <?php } ?>

        <a href="../html/index.php">
        <button class="toolbutton active" id="pghome"><h1><?php echo '<span class="at">@ </span><span>'.$_SESSION["nickname"].'</span>'?></h1></button></a> 
         

    </div>
    </div>
</div>



<div class="page">
    <div class="con1">

    
        <div class="con2 p1">
        <div style="height: 70vh; display: flex; flex-direction: column; justify-content: center;">
            <h2>Customize sua conta</h2>
                <div class="inps">
                    <p><strong>Nickname</strong></p>
                    <input name="nick" type="input" placeholder="Nickname" class="inp" value="<?php echo $_SESSION['nickname']; ?>">
                    <p><strong>Email</strong></p>
                    <input name="email" type="input" placeholder="E-mail" class="inp" value="<?php echo $_SESSION['email']; ?>">
                    <p><strong>Foto</strong></p>
                    <input name="pfp" type="file" placeholder="Carregar" class="inp pic">
                    <br><br><br>
                </div>
                <input name="next" type="submit" value="Atualizar" class="inp btn">
        </div>
        </div>

        <div class="con2 p2" style="display: flex; flex-direction: column;">
                <p><strong>Região</strong></p>
                <input name="region" type="dropdown" placeholder="SP" class="inp" maxlength="2" value="<?php echo $_SESSION['region']; ?>">
                <p><strong>Data de Nascimento</strong></p>
                <input name="birthd" type="date" placeholder="01-01=2001" class="inp" value="<?php echo $_SESSION['birthday']; ?>">
                <p><strong>Descrição</strong></p>
                <input name="desc" type="input" placeholder="Descrição" class="inp" value="<?php echo $_SESSION['userdesc']; ?>">
                <p><strong>Gameplay</strong></p>
                <input name="gameplay" type="input" placeholder="Gameplay" class="inp" value="<?php echo $_SESSION['usergameplay']; ?>">
                <p><strong>Redes Sociais</strong></p>
                <input name="socials" type="input" placeholder="Socials" class="inp" value="<?php print_r($_SESSION['usersocials_un']); ?>">
                <p><strong>Jogo Favorito</strong></p>
                <select id="menu1" name="favgame" required="true" class="inp">
                    <option value="1">Call of Duty: Warzone</option>
                    <option value="2">Overwatch 2</option>
                    <option value="3">Valorant</option>
                    <option value="4">Fortnite</option>
                    <option value="5">League of Legends</option>
                    <option value="6">EAFC24</option>
                </select>
            </div>
        </div>

    </div>

    

    <div class="wrapper">

        <?php
        if (isset($_POST['next'])) {
        include_once '../../backend/classes/class_IRepositorioUsuarios.php';
        include_once '../../backend/classes/class_IRepositorioImagens.php';
        // echo $_SESSION['id'], $_POST['name'],$_POST['email'];
        $path = "../assets/profile_pics/";
        if ($_FILES['pfp']['name']) {
            $_SESSION['pfp'] = "".$path.(new RepositorioImagemMYSQL)->adicionar_imagem($_FILES['pfp'], $path);
        } else {
            // echo 'Sem image';
            $_SESSION['pfp'] = "../assets/user.png";
        }

        // $usuarioEdit = new usuario($_SESSION['id'], $_SESSION['username'],
        // $_POST['nick'], $_POST['email'],'',1,0, $_SESSION['pfp'],
        // $_POST['desc'], $_POST['gameplay'], $socials, $_POST['region']);
        // echo('<img src="'.$_SESSION['pfp'].'" alt="" width="150px" height="150px" srcset="">');

        // $repositorioUsuario->alterarUsuario($usuarioEdit);
        // $repositorioUsuario->atualizarUsuario($_SESSION['id']);

        $sql_edit = "UPDATE users SET
        nickname = '".$_POST['nick']."',
        birthday = '".$_POST['birthd']."',
        email = '".$_POST['email']."',
        socials = '".$_POST['socials']."',
        favorite_game = ".$_POST['favgame'].",".
        'gameplay = "'.$_POST["gameplay"].'",'.
        "region = '".strtoupper($_POST['region'])."',
        description = '".$_POST['desc']."',
        picture = '".$_SESSION['pfp']."'

        WHERE id LIKE '".$_SESSION['id']."';";
        // echo $sql_edit;
        $res_edit = $conn->query($sql_edit);

        $sql_me = "SELECT * FROM users WHERE (id like ".$_SESSION['id'].");";
            
        $res_me = $conn -> query($sql_me);
        $row = $res_me -> fetch_array();
        refresh_user($conn, $row);
        header('Location: user.php');
        // $row = $res_edit->fetch_array();

        
    }
        //     if ($user and $email and $pass and $pass2) {
        //         $sql_check = "SELECT * FROM users WHERE (username LIKE '$user' AND email LIKE '$email' AND password LIKE '$pass');";
        //         $res_check = $conn->query($sql_check);
        //         $row = $res_check->fetch_array();


        //         if ($res_check->num_rows == 1) {
        //             echo 'Login já existe!';
        //         } else {
        //             $sql_sign = "INSERT INTO users (username, email, password, token, level, verified, status, join_date, favorite_game) 
        //         VALUES ('$user', '$email', '$pass', " . random_int(1000, 9999) . ", '1', '0', '1', '" . date('Y-m-d') . "', 0)";

        //             $res_sign = $conn->query($sql_sign);
        //             // $row = $res_log -> fetch_array();
        //             echo 'Conta criada com sucesso!';
        //         }
        //     } else {
        //         echo 'Preencha todos os campos para criar a sua conta!';
        //     }
        // }
        
    

                


?>


</div>
</div>
</div>
</div>

</form>
  
</body>

</html>