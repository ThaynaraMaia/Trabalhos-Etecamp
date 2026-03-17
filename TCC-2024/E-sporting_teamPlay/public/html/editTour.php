<!DOCTYPE html>
<html lang="pt-br">

<head>    
    <?php include '../../backend/classes/conn.php';
    include 'checkban.php';
    include '../../backend/php/scripts/imguploadTour.php';

    $logged = false;
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);
    if (isset($_SESSION['username'])) {
        $logged = true;
    } else {
        header('Location: welcome.html');
    }

    $sql_tour = "SELECT * FROM tournaments WHERE id = '".$_GET['id']."';";
    $res_tour = $conn -> query($sql_tour);
    $row = $res_tour -> fetch_object();

    $n2 = explode(',', $row->players);



    if ($row->organizer != $_SESSION['id']) {
        header('Location: tournaments.php');
    } 
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamPlay - Editando Torneio - <?php echo $row->title ?></title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/stylePost.css">
    <link rel="shortcut icon" href="../assets/Logo_Cor1.png" type="image/x-icon">

</head><form action="" method="post">
<body style="background-color: var(--black);">
<div class="main">


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
        <a href="post.php" style="position: absolute; right: 19vw">
        <button class="toolbutton" id="pghome" style="width: 3vw"><h1>+</h1></button></a> 

        <div class="pfpimg">
        <img src="<?php echo $_SESSION['pfp']; ?>" alt="User">
             
        </div>
        <?php } ?>

        <a href="<?php echo $logged ? 'user.php' : 'login.php'?>">
    <button class="toolbutton active" id="pghome"><h1><?php echo '<span class="at">@ </span><span>'.$_SESSION["nickname"].'</span>'?></h1></button></a> 
         

        
        
    </div>
</div>




<div class="page" style="left: 0">
<div class="con1">
    <div style="display: flex; gap: 1vw; justify-content:center; align-items: center;">
        <h1>Editando Torneio - Status:
            <select id="tourstatus" name="tourstatus" required="true" class="inp" style="background: var(--black); border: none; height: fit-content; font-size: medium; width: 20vw">
                    <option <?php echo $row->status == 0 ? 'selected' : '' ?> value="0">Preparação</option>
                    <option <?php echo $row->status == 1 ? 'selected' : '' ?> value="1">Em Andamento</option>
                    <option <?php echo $row->status == 2 ? 'selected' : '' ?> value="2">Finalizado</option>
                </select>
    </button></h1>
    </div><br><br>

    <div id="type">
    <div style="display: flex; gap: 1vw; justify-content:center; align-items: center;">
            <label for="title"><strong>Título</strong></label>
            <input type="text" readonly style="user-select: none;" id="title" name="title" value="<?php echo $row->title ?>" class="tinps"><br>

        </div><br>

        <div style="display: flex; gap: 1vw; justify-content:center; align-items: center;">
            <label for="score"><strong>Placar</strong></label>
            <input type="text" style="user-select: none;" id="score" name="score" value="<?php echo $row->current_score ?>" class="tinps"><br>
        </div><br>

        <div style="display: flex; gap: 1vw; justify-content:center; align-items: center;">
            <label for="winner"><strong>Vencedor</strong></label>
            <input type="text" style="user-select: none;" id="winner" name="winner" value="<?php echo $row->winner ?>" class="tinps"><br>
        </div><br>


        <label for="desc"><strong>Descrição</strong></label><br>
        <textarea id="desc" name="desc" required="true" placeholder="Descrição do Torneio" rows="4" style="border-radius: 1.2vw"><?php echo $row->description ?></textarea><br>


        <div style="display: inline-flex; gap: 1vw; align-items: baseline;">
            <label for="number"><strong>Número de Jogadores</strong></label>
            <input type="number" value="<?php echo intval($n2[0]);?>" id="number" name="number" placeholder="Número" style="width: 8vw" class="tinps"><span style="font-size: x-large;">/</span>
            <input type="number" readonly name="number" value="<?php echo intval($n2[1]);?>" style="width: 8vw" class="tinps">
        </div>


            <label for="dates"><strong>Data Início</strong></label>
            <input type="date" id="dates" name="dates" value="<?php echo $row->date_start ?>" placeholder="Título da postagem" class="inp" style="height: fit-content; font-size: large">
        
        
        
            <label for="datee"><strong>Data Fim</strong></label>
            <input type="date" id="datee" name="datee" value="<?php echo $row->date_end ?>" placeholder="Título da postagem" class="inp" style="height: fit-content; font-size: large">
            <br><br>
    </div>
    
    
    <div style="display: flex; justify-content: center; gap: 1vw">
        <button type="submit" name="nextEnd" class="toolbutton active" style="font-size: larger; width: fit-content">Atualizar Dados</button>
    </div>
</div>


<?php 
if (isset($_POST['nextEnd'])) {

$tourstatus = $_POST['tourstatus'];
$desc = $_POST['desc'];
$no = $_POST['number'];
$currscore = $_POST['score'];
$winner = $_POST['winner'];
$dates = $_POST['dates'];
$datee = $_POST['datee'];
 
    // echo '<br>';
    // echo $tourstatus;
    // echo '<br>';
    // echo $desc;
    // echo '<br>';
    // echo $no;
    // echo '<br>';
    // echo $currscore;
    // echo '<br>';
    // echo $winner;
    // echo '<br>';
    // echo $dates;
    // echo '<br>';
    // echo $datee;
    // echo '<br>';


    $sql_upd_tour = "UPDATE tournaments
    SET description = '".$desc."',
    date_start = '".$dates."',
    date_end = '".$datee."',
    current_score = '".$currscore."',
    players = '".$row->id.",".$no."',
    winner = '".$winner."',
    status = '".$tourstatus."'
    WHERE tournaments.id = ".$row->id.";";

    $res_upd_tour = $conn->query($sql_upd_tour);
    // header('Location: tournaments.php');

}


?>

</div>
</div>


</div>
</div>


</form>
  

</body>