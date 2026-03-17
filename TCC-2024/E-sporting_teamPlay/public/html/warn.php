<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamPlay - Home</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/styleSign.css">
    <link rel="shortcut icon" href="../assets/Logo_Cor1.png" type="image/x-icon">
    <?php 
    include '../../backend/classes/conn.php';
    $logged = false;
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);
    if (isset($_SESSION['id'])) {
        $logged = true;
    } else {
        header('Location: welcome.html');
    }
    $sql_me = "SELECT * FROM users WHERE (id like ".$_SESSION['id'].");";
            
    $res_me = $conn -> query($sql_me);
    $row = $res_me -> fetch_array();
    refresh_user($conn, $row);

    $st = $_SESSION['status'];
    $state = 'Normal';
    switch ($st) {
        case 2: 
            $state = 'Mutado';
            break;
        case 3: 
            $state = 'Banido';
            break;
        default: 
            header('Location: index.php');
            break;
    }
    ?>
</head>


<body>
<div class="toolbar">
    <div class="logo">
        <a href="index.php">
            <img src="../assets/Logo_Full.png" style="width: 10vw;" alt="TeamPlay">
        </a>
    </div>    


    <div class="pages">
        <a href="index.php">
        <button class="toolbutton active" id="pghome"><h1>Home</h1></button></a>
        
        <a href="tournaments.php">
        <button class="toolbutton" id="pgtrn"><h1>Torneios</h1></button></a>    
        
        <a href="friends.php">
        <button class="toolbutton" id="pgfrn"><h1>Usuários<?php if ($_SESSION['notif']) {echo ' <div class="notif"></div>';} ?></h1></button></a>
    </div>
 

    <div class="userarea">
        <?php if ($logged) { ?> 
        <a href="post.php" style="position: absolute; right: 23vw">
            <button class="toolbutton" id="pghome" style="width: 3vw"><h1 style="font-size: x-large;">+</h1></button></a> 
        <a href="chats/index.php" style="position: absolute; right: 19vw">
        <button class="toolbutton" id="pghome" style="width: 3vw"><img src="../assets/icons/chat.png" style="width: 2.2vw; filter: brightness(0);"></button></a> 

        <div class="pfpimg">
        <img src="<?php echo $_SESSION['pfp']; ?>" alt="User">
             
        </div>
        <?php } ?>

        <a href="<?php echo $logged ? 'user.php' : 'login.php'?>">
    <button class="toolbutton active" id="pghome"><h1><?php echo '<span class="at">@ </span><span>'.$_SESSION["nickname"].'</span>'?></h1></button></a> 
         

        
        
    </div>
</div>

<div class="main" style="margin-top: 16vh; width: 98%; height: 80%">
<div class="con1" style="flex-direction: column; align-items: center">
    <h2>Atenção</h2>
    <h1>Você foi <u style="color: var(--mag);"><?php echo $state ?></>.</h1><br>
    <a href="
    <?php
        switch ($st) {
        case 2: 
            echo 'index.php';
            break;
        case 3: 
            echo 'logout.php';
            break;
        default: 
            header('Location: index.php');
            break;
    }
    ?>
    ">
        <div class="toolbutton active" style="font-size: large;">Voltar</div>
    </a>
</div>
</div>


  
</body>
</html>