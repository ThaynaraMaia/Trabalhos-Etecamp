<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamPlay - Meus Jogos</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/styleNew.css">
    <link rel="shortcut icon" href="../assets/Logo_Cor1.png" type="image/x-icon">
    <?php 
    include '../../backend/classes/conn.php';
    include 'checkban.php';
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
<div class="main">
<div class="sidebar">
        <a href="#" class="sideicon back">
            <img src="../assets/arrow.png">
        </a>
    <div class="filters">
        <div id="" class="sideicon jc"><p>...</p></div>
        <div class="sidedesc desc1">Tudo</div>
        
        <div id="" class="sideicon j2"><img src="../assets/sideicons/cod_warzone_logo.png" alt="CoD: Warzone"></div> <!-- COD WZ -->
        <div class="sidedesc desc2">CoD: Warzone</div>
        
        <div id="" class="sideicon j3"><img src="../assets/sideicons/overwatch2_logo.png"  alt="Overwatch 2"></div> <!-- OVERWATCH 2  -->
         <div class="sidedesc desc3">Overwatch 2</div> 
        
        <div id="" class="sideicon j4"><img src="../assets/sideicons/valorant_logo.png"    alt="Valorant"></div> <!-- Valorant -->
        <div class="sidedesc desc4">Valorant</div> 
        
        <div id="" class="sideicon j5"><img src="../assets/sideicons/fortnite_logo.png"    alt="Fortnite"></div> <!-- Fortnite -->
        <div class="sidedesc desc5">Fortnite</div> 
        
        <div id="" class="sideicon j6"><img src="../assets/sideicons/lol_logo.png"         alt="League of Legends"></div> <!-- LOL -->
        <div class="sidedesc desc6">League of Legends</div> 
        
        <div id="" class="sideicon j7"><img src="../assets/sideicons/eafc24_logo.png"      alt="EA FC24"></div> <!-- EA FC 24 -->
        <div class="sidedesc desc7">EA FC24</div>  
        
        
    </div>

</div>

<div class="content">
    
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
        <button class="toolbutton" id="pgfrn"><h1>Usuários</h1></button></a>
    </div>


    <div class="userarea">
        <?php if ($logged) { ?> 
        <a href="post.php" style="position: absolute; right: 19vw">
        <button class="toolbutton" id="pghome" style="width: 3vw"><h1>+</h1></button></a> 

        <div class="pfpimg">
        <img src="<?php echo $_SESSION['pfp']; ?>" alt="User">
            
             
        </div>
        <?php } ?>

        <a href="<?php echo $logged ? 'user.php' : 'login.php'?>">
    <button class="toolbutton active" id="pghome"><h1><?php echo $logged ? '<span class="at">@ </span><span>'.$_SESSION["username"].'</span>' : 'Fazer login'?></h1></button></a> 
         

        
        
    </div>
</div>


<div class="page">

<div class="con1">
    <h1>Meus Jogos</h1>
    
    <div class="post off">
        Adicione seus jogos
    </div>


</div>

<div class="all">
    <div class="con2">
    <h1>Fortnite</h1>

        <label for="inputText"><strong>Nickname</strong></label>
            <input type="text" id="inputText" name="inputText">
        <br><br>

        <label for="menu1"><strong>Rank</strong></label>
        <select id="menu1" name="menu1">
            <option value="v1">Bronze</option>
            <option value="v2">Prata</option>
            <option value="v3">Ouro</option>
            <option value="v4">Platina</option>
            <option value="v5">Diamante</option>
            <option value="v6">Elite</option>
            <option value="v7">Lenda</option>
            <option value="v8">Surreal</option>
        </select>
        <br><br> 

        <label><strong>Papel</strong></label><br><br>
            <input type="checkbox" id="check1" name="checkGroup" value="check1">
            <label for="check1">IGL</label>
            <br>
            <input type="checkbox" id="check2" name="checkGroup" value="check2">
            <label for="check2">Fragger</label>
            <br>
            <input type="checkbox" id="check3" name="checkGroup" value="check3">
            <label for="check3">Support</label>
            <br><br>
            <br>
            <button class="toolbutton" id="update" onclick=""><h1>Atualizar</h1></button>
    </div><br><br>
    <form action='mygames.php' method="post" id="save">
            <input type="submit" class="toolbutton active" value="Salvar e Sair"/>
    </form>


    </div>
</div>
    
</div>
</div>

<script src="../js/filtersProfile.js"></script>
  
</body>
</html>