<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamPlay - Torneios</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/styleTour.css">
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
        $sql_tour = "SELECT tournaments.*, users.username FROM tournaments, users WHERE tournaments.organizer = users.id ORDER BY date_creation DESC;";
        $res_tour = $conn -> query($sql_tour);
        $row = $res_tour -> fetch_object();
        
    ?>




</head>


<body>
<div class="holder" id="alltourns">
<?php 
for ($t=0; $t<$res_tour->num_rows; $t++) { ?>
<?php echo $row->id; ?><br>
<?php echo $row->title; ?><br>
<?php echo $row->type; ?><br>
<?php echo $row->organizer; ?><br>
<?php echo $row->description; ?><br>
<?php echo $row->date_start; ?><br>
<?php echo $row->date_end; ?><br>
<?php echo $row->date_creation; ?><br>
<?php echo $row->current_score; ?><br>
<?php echo $row->status; ?><br>
<?php echo $row->players; ?><br>
<?php echo $row->picture; ?><br>
<?php echo $row->game; ?><br>
<?php echo $row->region; ?><br>
<?php echo $row->winner; ?><br>
<?php echo $row->username; ?><br class="ignore">
<?php $row = $res_tour -> fetch_object(); 
}
?>
</div>
<div class="holder" id="myid">
<?php echo $_SESSION['id'] ?>
</div>
<div class="holder" id="selection">
<?php if(isset($_GET['seltour'])) {
    echo $_GET['seltour'];
} ?>
</div>
<div class="holder" id="sel">
    <?php 
    if (isset($_GET['game'])) {
        echo $_GET['game']; 
    }
    ?>
</div>

<div class="main">
<div class="sidebar">
        <a href="#" class="sideicon back">
            <img src="../assets/arrow.png">
        </a>
    <div class="filters">
    <a href="tournaments.php?game=0" class="sideicon jc" style="color: white"><p>Todos</p></a>
        <div class="sidedesc desc1">Tudo</div>
        
        <a href="tournaments.php?game=1" class="sideicon j2"><img src="../assets/sideicons/cod_warzone_logo.png" alt="CoD: Warzone"></a> <!-- COD WZ -->
        <div class="sidedesc desc2">CoD: Warzone</div>
        
        <a href="tournaments.php?game=2" class="sideicon j3"><img src="../assets/sideicons/overwatch2_logo.png"  alt="Overwatch 2"></a> <!-- OVERWATCH 2  -->
        <div class="sidedesc desc3">Overwatch 2</div> 
        
        <a href="tournaments.php?game=3" class="sideicon j4"><img src="../assets/sideicons/valorant_logo.png"    alt="Valorant"></a> <!-- Valorant -->
        <div class="sidedesc desc4">Valorant</div> 
        
        <a href="tournaments.php?game=4" class="sideicon j5"><img src="../assets/sideicons/fortnite_logo.png"    alt="Fortnite"></a> <!-- Fortnite -->
        <div class="sidedesc desc5">Fortnite</div> 
        
        <a href="tournaments.php?game=5" class="sideicon j6"><img src="../assets/sideicons/lol_logo.png"         alt="League of Legends"></a> <!-- LOL -->
        <div class="sidedesc desc6">League of Legends</div> 
        
        <a href="tournaments.php?game=6" class="sideicon j7"><img src="../assets/sideicons/eafc24_logo.png"      alt="EA FC24"></a> <!-- EA FC 24 -->
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
        <button class="toolbutton" id="pghome"><h1>Home</h1></button></a>
        
        <a href="tournaments.php">
        <button class="toolbutton active" id="pgtrn"><h1>Torneios</h1></button></a>    
        
        <a href="friends.php">
        <button class="toolbutton" id="pgfrn"><h1>Usuários</h1></button></a>
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



<div class="page">
    <div class="con1">


    <?php
        $selwhere = '';
        if (isset($_GET['game'])) {
            $selgame = $_GET['game'];
    
            if ($selgame == 0) {
                $selwhere = '';
            } else {
                $selwhere = 'WHERE game LIKE '.$selgame;
            }
        };


        $sql_tour = "SELECT * FROM tournaments ".$selwhere." ORDER BY date_creation DESC";
        $res_tour = $conn -> query($sql_tour);
        $row = $res_tour -> fetch_object();
        
        // print_r($res_tour);
        for ($t=0; $t<$res_tour->num_rows; $t++) { 
            if ($row->picture != '') {
                $img = $row->picture;
            } else {
                $img = $row->type == 1 ? '../assets/icons/tour_official.png' : '../assets/icons/tour_casual.png';
            }
            ?>

        <div class="post" name="tour" postid="<?php echo $row->id; ?>" style="min-height: fit-content;">
            <div style="display: inline-flex; gap: 1vw; align-items: center; width: 80%">
            <img src="<?php echo $img ?>" alt="Torneio">
            <div class="postCon" style="width: 90%;">
                <h1>
                    <?php echo $row->title; ?>
                </h1><span>
                    <strong style="color: var(--black); font-size: x-large;"><?php echo ($row->current_score ? $row->current_score : 'Sem Placar'); ?></strong>
                </span>
                <p style="overflow: hidden; width: 100%; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                    <?php echo $row->description; ?>
                </p>
            </div>
            </div>
            <div class="postCon">
                <div class="box">
                    <div class="tourstatus" style="background: <?php
                        switch ($row->status) {
                            case '0': echo 'rgb(231, 217, 24)'; break; // Vai começar
                            case '1': echo 'rgb(0, 255, 153)'; break; // Em andamento 
                            case '2': echo 'var(--mag)'; break; // Finalizado
                            default: echo 'var(--shade1)'; break;
                        };
                    ?>"></div>
                </div>
            </div>
        </div>


        <?php
        $row = $res_tour -> fetch_object();
        };

        ?>
        
    </div>
    
    <div class="con2">
        <div class="post info" id="info">
            <h1>Selecione um Torneio</h1>
            Selecione um torneio ao lado para exibir suas informações.<br><br>
        </div>
    </div>

</div>


</div>
</div>



<script src="../js/filtersIndexT.js"></script>
  
<script src="../js/tourns.js"></script>
</body>
</html>