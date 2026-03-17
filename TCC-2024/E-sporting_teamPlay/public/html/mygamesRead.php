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
    $sql_userU = "SELECT * FROM users WHERE (id like ".$_GET['uid'].");";
    $res_userU = $conn -> query($sql_userU);
    $row_userU = $res_userU -> fetch_object();
    ?>
</head>
<span class="holder" id="holder" style="color: var(--will); z-index: 10;"><?php
    if ($row_userU->games != '') { 
        echo $row_userU->games;
    } 
    ?> </span>
<span class="holder" id="fav" style="color: var(--will); z-index: 10;"><?php
    echo $_SESSION['favgame_un'];
?> </span>


<body style="overflow: hidden; height: fit-content;">
<div class="main">

</div>

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
    
<div class="page" style="height: fit-content;">

<div class="con1" style="width: 90%; height: fit-content;">
</div>

</div>
</div>
    
</div>
</div>

<script src="../js/filtersProfileRead.js"></script>

</body>
</html>