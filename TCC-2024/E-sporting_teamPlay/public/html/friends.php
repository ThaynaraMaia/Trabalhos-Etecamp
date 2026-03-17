
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamPlay - Amigos</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/style.css"> 
    <link rel="stylesheet" href="../css/styleNew.css"> 
    <link rel="stylesheet" href="../css/styleFriends.css"> 
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
    $sql_me = "SELECT * FROM users WHERE (id like ".$_SESSION['id'].");";
            
    $res_me = $conn -> query($sql_me);
    $row = $res_me -> fetch_array();
    refresh_user($conn, $row);
    

   
    


    if (isset($_GET['adicionar'])){
        $sql_check = "SELECT * FROM friendship WHERE ".$_GET['adicionar']." IN (user1, user2)
        AND ".$_SESSION['id']." IN (user1, user2)";
        $res_check = $conn->query($sql_check);
        if ($res_check->num_rows > 0) {
            false;
            header('Location: friends.php?pendentes');
        } else {
            $sql_add = "INSERT INTO friendship (user1, user2, status)
            VALUES ( ".$_SESSION['id'].", ".$_GET['adicionar'].", 0);";        
            $res_add = $conn->query($sql_add);
            header('Location: friends.php?pendentes');
        }
        
    }
    if (isset($_GET['aceitar'])){
        $sql_aceitar = "UPDATE friendship SET status = 1, date_accepted = '".date('Y-m-d')."' WHERE user2 = ".$_SESSION['id']." AND user1 = ".$_GET['uid'].";";        
        $res_aceitar=$conn->query($sql_aceitar);
        

        $sql_friends2data = "SELECT * FROM users WHERE id = ".$_GET['uid'].";";
        $res_friends2data=$conn->query($sql_friends2data);
        $row_friends2data=$res_friends2data->fetch_object();

        $res_aceitar=$conn->query($sql_aceitar);


        array_push($_SESSION['userfriends'], [$row_friends2data->id, $row_friends2data->username]);

        // echo $sql_aceitar;
        // $res_aceitar=$conn->query($sql_aceitar);
        // header('Location: friends.php?pendentes');

    }
    if (isset($_GET['rejeitar'])){
        $sql_rejeita = "DELETE FROM friendship WHERE id = ".$_GET['remover'];
        $res_rejeita=$conn->query($sql_rejeita);
        header('Location: friends.php?pendentes');   
        
    }
    if (isset($_GET['remover'])){
        $sql_remove = "DELETE FROM friendship WHERE ".$_GET['remover']." IN (user1, user2)
        AND ".$_SESSION['id']." IN (user1, user2)";
        $res_remove=$conn->query($sql_remove);
        header('Location: friends.php');

    }
    
    ?>
</head>


<body>
<div class="main">


<!-- Descrição -->

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
        <button class="toolbutton" id="pgtrn"><h1>Torneios</h1></button></a>    
        
        <a href="friends.php">
        <button class="toolbutton active" id="pgfrn"><h1>Usuários</h1></button></a>
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

        <a href="user.php">
    <button class="toolbutton active" id="pghome"><h1><?php echo '<span class="at">@ </span><span>'.$_SESSION["nickname"].'</span>'?></h1></button></a> 
         

        
    </div>
</div>
    
<div class="page">

<div class="con1">
        <div class="procura">
<?php 
if (!isset($_GET['pendentes']) AND !isset($_GET['amigos'])) {
?>
    <form action="" method="get">
        <input type="text" name="username" placeholder="Buscar usuário"></input><input class="toolbutton active" style="width: 4vw; height: auto; font-size: larger" type="submit" value="🔍"></form>
    <br>
<?php } ?>

    <div class="tabs">
        <a href="friends.php"><strong>Usuários</strong></a>-
        <a href="friends.php?amigos"><strong>Amigos</strong></a>-
        <a href="friends.php?pendentes"><strong>Pendentes</strong><?php if ($_SESSION['notif']) {echo ' <div class="notif"></div>';} ?></a>

</div>
</div>
<?php

if (isset($_GET['pendentes'])){
    $sql_verifica="SELECT * FROM friendship WHERE ((user1=".$_SESSION['id'].") OR (user2=".$_SESSION['id'].")) AND status=0;";
    $res_verifica = $conn -> query($sql_verifica);
    $is2 = false;
    for($t=0;$t<$res_verifica->num_rows;$t++){
        $row = $res_verifica->fetch_object();

        if($row->user2==$_SESSION['id']){
            $sql_tour = "SELECT * FROM users WHERE id=".$row->user1;
            $res_tour = $conn -> query($sql_tour);
            $userrow = $res_tour -> fetch_object();
            if ($userrow->picture == '' or $userrow->picture == '-' or $userrow->picture == '...') {
                $pfp = '../assets/user.png';
                } else {
                    $pfp = $userrow->picture;
                }
            $is2 = true;
            // print_r($res_tour);

        }
            else{
            $is2 = false;
            $sql_tour = "SELECT * FROM users WHERE id=".$row->user2;
            $res_tour = $conn -> query($sql_tour);
            $userrow = $res_tour -> fetch_object();
            if ($userrow->picture == '' or $userrow->picture == '-' or $userrow->picture == '...') {
                $pfp = '../assets/user.png';
                } else {
                    $pfp = $userrow->picture;
                }
            // print_r($res_tour);
        }
                ?>
            
            <div class="post">
                <div class="sidepost">    
                <div style="display: flex; align-items: center; gap: 2vw">
                    <a class="postagem" style="display: flex;" href="user.php?uid=<?php echo $userrow->id; ?>">
                    <img src="<?php echo $pfp; ?>" width="40px" class="pfp">
                    <h1>
                        <span class="at">@ </span><?php echo $userrow->username; ?><?php if($userrow->verified == '1') { ?><img src="../assets/icons/icon_ver.png" class="verif" style="width: 3vw;" alt="Verificado">
                        <?php } ?>
                    </h1>
                    </a>
                </div>
                    <p>
                    <?php echo $userrow->description; ?>
                    </p>
                </div>
        
                <?php if ($is2) { ?>    
                <div style="display: flex; align-items: center; gap: 2vw">
                    <a href="friends.php?rejeitar=<?php echo $row->id; ?>">
                        <button class="toolbutton active" style="height: 8vh; font-size: x-large; width: 4vw;"><strong>X</strong></button>
                    </a>
                    <a href="friends.php?uid=<?php echo $row->user1; ?>&aceitar=1">
                        <button class="toolbutton " style="height: 8vh; font-size: x-large; width: 4vw; background-color: var(--will);"><strong>V</strong></button>
                    </a>
                </div>

                <?php } else { ?>    
                <div style="display: flex; align-items: center; gap: 2vw">
                    <a href="friends.php?rejeitar=<?php echo $row->id; ?>">
                        <button class="toolbutton active" style="font-size: large;">Cancelar</button>
                    </a>
                </div>
                <?php } ?>    
                </div>

            
            
                <?php 
        
    }
}


$sql_tour = "SELECT * FROM users";
$res_tour = $conn -> query($sql_tour);
$row = $res_tour -> fetch_object();

    
if(isset($_GET['username'])){
    // echo 'asdsadsadsad';
    $sql_tour = "SELECT * FROM users WHERE username LIKE '".$_GET['username']."' OR nickname LIKE '".$_GET['username']."'";
    $res_tour = $conn -> query($sql_tour);
    $row = $res_tour -> fetch_object();

}

    
if(isset($_GET['selAGame'])){
    $nick = '';
    $rank = '';
    $selFilters = [];
    if (isset($_GET['inputText'])) {

        $nick = 'users.games LIKE "%'.$_GET['inputText'].'%" ';
        
        array_push($selFilters, $nick);
    }
    if (isset($_GET['menu1'])) {

        $rank = 'OR users.games LIKE "%'.$_GET['menu1'].'%" ';

        array_push($selFilters, $rank);
    }
    array_push($selFilters, []);
    if (isset($_GET['checkGroup'])) {
        // print_r($_SESSION['checkGroup']);

        // foreach ($_POST['checkGroup'] as $check) {
            // array_push($selFilters[2], $check);
            // array_push($selFilters[2], $_GET['checkGroup']);
        // }
    }
             
    $_SESSION['filters'] = $selFilters;
    // print_r($_SESSION['filters']);

    // $sql_tour = "SELECT * FROM users WHERE users.games LIKE '%".$_SESSION["filters"][0]."%';";
    $sql_tour = "SELECT * FROM users WHERE ".$nick.$rank.";";
    // echo '<br>';
    // echo '<br>';
    // echo $sql_tour;
    // echo '<br>';
    // echo '<br>';
    $res_tour = $conn -> query($sql_tour);
    $row = $res_tour -> fetch_object();

}   




else if (isset($_GET['amigos'])){ 
    if (count($_SESSION['userfriends']) > 0) {
        $sql_tour = "SELECT * FROM users WHERE users.id in (".ids().")";
    } else {
        header('Location: friends.php');
    }
    $res_tour = $conn -> query($sql_tour);
    $row = $res_tour -> fetch_object();
}




if (!(isset($_GET['pendentes']) OR isset($_GET['gamefilter']))) {
    
    for ($t=0; $t<$res_tour->num_rows; $t++) {
        if ($row->picture == '' or $row->picture == '-' or $row->picture == '...') {
        $pfp = '../assets/user.png';
        } else {
            $pfp = $row->picture;
        }
        ?>


        
        <div class="post">
            <div class="sidepost">    
            <div style="display: flex; align-items: center; gap: 2vw">
                <a class="postagem" style="display: flex;" href="user.php?uid=<?php echo $row->id; ?>">
                <img src="<?php echo $pfp; ?>" width="40px" class="pfp">
                <h1>
                    <span class="at">@ </span><?php echo $row->username; ?><?php if($row->verified == '1') { ?><img src="../assets/icons/icon_ver.png" class="verif" style="width: 3vw;" alt="Verificado">
                    <?php } ?>
                </h1>
                </a>
            </div>
            <p>
            <?php echo $row->description; ?>
            </p>
            </div>

            <?php 
            if ($row->id != $_SESSION['id']) {   // Se não for o próprio user, não mostra botoes 
                
                    $isFriend = false;

                    foreach ($_SESSION['userfriends'] as $f) {
                        if ($row->id == $f[0]) { 
                            $isFriend = true;
                            break;
                        }
                    }
                    
                    if ($isFriend) { 
                        // Renderize o menu de amigos
                        ?>
                        <div style="display: flex; align-items: center; gap: 2vw">
                            <a href="friends.php?remover=<?php echo $row->id; ?>">
                                <button class="toolbutton config" style="height: 8vh; font-size: large">Remover</button>
                            </a>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div style="display: flex; align-items: center; gap: 2vw">
                            <a href="friends.php?adicionar=<?php echo $row->id; ?>">
                                <button class="toolbutton config" style="height: 8vh; font-size: x-large; width: 4vw;"><strong>+</strong></button>
                            </a>
                        </div>
                        <?php };

            } else { ?>

            <div style="display: flex; align-items: center; gap: 2vw">
            <a href="edit.php"><button class="toolbutton config" style="height: 8vh; font-size: large;">Editar Perfil</button></a>
            </div>


            <?php } ?>

        </div>
    <?php
    $row = $res_tour -> fetch_object();
    };
    };
    ?>
</div>

<?php 
if (!isset($_GET['pendentes']) AND !isset($_GET['amigos'])) {
    ?>

<div class="con1 fil">
    <h1><strong>Filtro por Jogo</strong></h1>
    <form action="" method="get" style="display: flex; gap: 2vh; flex-direction: column;">
    <select id="menuG" name="selAGame" required="true" class="inp">
        <option name="" value="0">Sem filtros</option>
        <option name="" value="1">Call of Duty: Warzone</option>
        <option name="" value="2">Overwatch 2</option>
        <option name="" value="3">Valorant</option>
        <option name="" value="4">Fortnite</option>
        <option name="" value="5">League of Legends</option>
        <option name="" value="6">EAFC24</option>
    </select>
    

    <div id="filterDiv">
        <div style="opacity: 40%;">
            <strong>Selecione um jogo para adicioná-lo à filtragem de usuários.</strong>
        </div>

    </div>

    
<?php
    if (isset($_POST['next'])) {
        $selFilters = [];
        // print_r($_POST);
        $nick = $_POST['inputText'];
        $rank = $_POST['menu1'];
    array_push($selFilters, $nick);
    array_push($selFilters, $rank);
    array_push($selFilters, []);
             

    foreach ($_POST['checkGroup'] as $check) {
        array_push($selFilters[2], $check);
    }
    $_SESSION['filters'] = $selFilters;

    // header('Location: friends.php?gamefilter');
    }
?>



    <input class="toolbutton" type="submit" name="next" id="update" value="Pesquisar" style="height: 6vh">
</form>

</div>
<?php 
}; 

?>



</div>

</div>
</div>
    

<script src="../js/filtersUsers.js"></script>
</body>
</html>