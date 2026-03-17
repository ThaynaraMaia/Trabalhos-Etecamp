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
<span class="holder" id="holder" style="color: var(--will); z-index: 10;"><?php
    if ($_SESSION['usergames'] != '') { 
        echo $_SESSION['usergames'];
    } 
?> </span>
<span class="holder" id="fav" style="color: var(--will); z-index: 10;"><?php
    echo $_SESSION['favgame_un'];
?> </span>

<body>
<div class="main">

</div>
<!-- Descrição -->

<div class="content">
    <div class="sideicon back" style="display: none"></div>
<div class="filters">
        <div id="" class="sideicon jc" style="display: none;"><p>...</p></div>
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
    
<div class="page">

<div class="con1">
</div>

<div class="all">
    <div class="con2">
    </div><br><br>
    <form action='mygames.php' method="post" id="save" style="display: flex; gap: 1vw;">
        <input type="submit" class="toolbutton" value="Resetar Todos" onclick="resetGame()"/>
        <input type="submit" class="toolbutton active" value="Salvar e Sair"/>
        </form>
    </div>


    </div>
</div>
    
</div>
</div>

<script src="../js/filtersProfile.js"></script>

</body>
</html>