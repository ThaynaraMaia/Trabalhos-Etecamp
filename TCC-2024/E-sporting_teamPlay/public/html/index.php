<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamPlay - Home</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/styleTour.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../assets/Logo_Cor1.png" type="image/x-icon">
    <?php 
    include '../../backend/classes/conn.php';
    include 'checkban.php';
    $logged = false;
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);
    if (isset($_SESSION['id'])) {
        $logged = true;
    } else {
        header('Location: welcome.html');
        exit();
    }
    $sql_me = "SELECT * FROM users WHERE (id like ".$_SESSION['id'].");";
            
    $res_me = $conn -> query($sql_me);
    $row = $res_me -> fetch_array();
    refresh_user($conn, $row);


    ?>
</head>


<body>
<div class="holder" id="sel">
    <?php 
    if (isset($_GET['game'])) {
        echo $_GET['game']; 
    }
    ?>
</div>

<div class="holder" id="order">
<?php
    if (isset($_GET['order'])) {
    $ordering = true;
    $order = $_GET['order'];
    echo $_GET['order']; 
} else {
    $ordering = false ;
}
if (isset($_GET['deletepost'])) {
    $sql_delpost = "DELETE FROM posts WHERE id_post = ".$_GET['deletepost'].";";
    $res_delpost = $conn -> query($sql_delpost);
    header('Location: index.php');
    exit();
} else {
    false;
}
?>
</div>



<div class="main">
<div class="sidebar">
        <a href="#" class="sideicon back">
            <img src="../assets/arrow.png">
        </a>
    <div class="filters">
        <a href="index.php?game=0" class="sideicon jc" style="color: white"><p>Todos</p></a>
        <div class="sidedesc desc1">Tudo</div>
        
        <a href="index.php?game=1" class="sideicon j2"><img src="../assets/sideicons/cod_warzone_logo.png" alt="CoD: Warzone"></a> <!-- COD WZ -->
        <div class="sidedesc desc2">CoD: Warzone</div>
        
        <a href="index.php?game=2" class="sideicon j3"><img src="../assets/sideicons/overwatch2_logo.png"  alt="Overwatch 2"></a> <!-- OVERWATCH 2  -->
        <div class="sidedesc desc3">Overwatch 2</div> 
        
        <a href="index.php?game=3" class="sideicon j4"><img src="../assets/sideicons/valorant_logo.png"    alt="Valorant"></a> <!-- Valorant -->
        <div class="sidedesc desc4">Valorant</div> 
        
        <a href="index.php?game=4" class="sideicon j5"><img src="../assets/sideicons/fortnite_logo.png"    alt="Fortnite"></a> <!-- Fortnite -->
        <div class="sidedesc desc5">Fortnite</div> 
        
        <a href="index.php?game=5" class="sideicon j6"><img src="../assets/sideicons/lol_logo.png"         alt="League of Legends"></a> <!-- LOL -->
        <div class="sidedesc desc6">League of Legends</div> 
        
        <a href="index.php?game=6" class="sideicon j7"><img src="../assets/sideicons/eafc24_logo.png"      alt="EA FC24"></a> <!-- EA FC 24 -->
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


<div class="page">
    <div class="con1">
        <h1 style="margin-bottom: 1vh;">Postagens</h1>
        <div style="display: inline-flex; justify-content: space-between; background-color: var(--shade1); align-items: center; width: 100%; padding: 1vh; border-radius: 1vw;">
            <p><strong>Filtrando por:</strong></p>
                <select id="menu1" name="favgame" required="true" class="inp"> 
                    <option value="byDate">Data de postagem</option>
                    <option value="byFriend">Amigos</option>
                </select>
        </div>
<?php
    $selwhere = '';
    if (isset($_GET['game'])) {
        $selgame = $_GET['game'];

        if ($selgame == 0) {
            $selwhere = '';
        } else {
            $selwhere = 'AND id_jogo = '.$selgame;
        }
    }


    if($ordering) {
        switch($order) {
            case 'byDate': $sql_post = "SELECT posts.*, users.nickname, games.name FROM posts, users, games
                    WHERE posts.id_usuario = users.id ".$selwhere." AND posts.id_jogo = games.id ORDER BY dat_criacao DESC"; 
                break;

            // case 'byLike': $sql_post = "SELECT posts.*, users.nickname, games.name FROM posts, users, games
            //         WHERE posts.id_usuario = users.id ".$selwhere." AND posts.id_jogo = games.id ORDER BY likes DESC"; 
            //     break;

            case 'byFriend': 
                if (count($_SESSION['userfriends']) <= 0) {
                    $sql_post = "SELECT posts.*, users.nickname, games.name FROM posts, users, games
                    WHERE posts.id_usuario = users.id ".$selwhere." AND posts.id_jogo = games.id ORDER BY RAND()";
                    echo '<br>Sem amigos para filtrar. <a href="friends.php">Adicione</a> amigos agora!';
                }
                else {
                    $sql_post = "SELECT posts.*, users.nickname, games.name FROM posts, users, games
                    WHERE posts.id_usuario IN ( ".ids().") ".$selwhere." AND posts.id_jogo = games.id AND users.id = posts.id_usuario ORDER BY dat_criacao DESC"; 
                }
                break;
        }
    } else {
        $sql_post = "SELECT posts.*, users.nickname, games.name FROM posts, users, games WHERE posts.id_usuario = users.id ".$selwhere." AND posts.id_jogo = games.id ORDER BY dat_criacao DESC";
    }
    $res_post = $conn -> query($sql_post);
    $row = $res_post -> fetch_object();
    
    // print_r($res_post);
    for ($t=0; $t<$res_post->num_rows; $t++) {
        if ($row->imagem == '') {
            switch ($row->id_jogo) {
                case 0: $row->imagem = "../assets/Logo_CorBW.png"; break;
                case 1: $row->imagem = "../assets/sideicons/cod_warzone_logo.png"; break;
                case 2: $row->imagem = "../assets/sideicons/overwatch2_logo.png"; break;
                case 3: $row->imagem = "../assets/sideicons/valorant_logo.png"; break;
                case 4: $row->imagem = "../assets/sideicons/fortnite_logo.png"; break;
                case 5: $row->imagem = "../assets/sideicons/lol_logo.png"; break;
                case 6: $row->imagem = "../assets/sideicons/eafc24_logo.png"; break;
            }
        } 

    ?>


<div class="post" name="post" postid="<?php echo $row->id_post; ?>" style="min-height: fit-content;" onclick="showHide(this)">
        <div style="display: inline-flex; gap: 1vw; align-items: center;">
        <img src="<?php echo $row->imagem ?>" alt="Postagem">
        <div class="postCon" style="width: 90%;">
            <h1>
                <?php echo $row->titulo; 
                if ($row->id_usuario == $_SESSION['id']) {
                    ?>
                    <span><a href="index.php?deletepost=<?php echo $row->id_post ?>">
                    <button class="toolbutton active" style="height: fit-content; font-size: medium; width: fit-content; padding: .2vw">Apagar</button>
                    </a></span>
                <?php }
                ?>
            </h1>


            <a href="user.php?uid=<?php echo $row->id_usuario; ?>">
                <span>
                    <strong style="color: var(--black); font-size: x-large;"><span class="at" style="color: var(--shade2)">@ </span><?php echo ($row->nickname); ?></strong>
                </span>
            </a>
            <span>
                <strong style="color: var(--black); font-size: x-large;"> - <?php echo ($row->name); ?></strong>
            </span>


            <a title="Clique para expandir" style="overflow: hidden; width: 100%; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; color: var(--will);">
            <p> <?php echo $row->descricao; ?> </p>
            </a>
        </div>
        </div>
    </div>

    

    <?php
    $row = $res_post -> fetch_object();
    };

    ?>



    </div>
    
    
    <div class="con2">
        <div class="ad">
            <img src="../assets/Logo_CorBW.png">
            <h1>TeamPlay</h1>
            <p>TeamPlay é uma rede social focada para o público gamer, especialmente competitivo, onde há uma facilidade em encontrar colegas de equipe e torneios amadores, explore os jogos suportados pela TeamPlay, onde campeões ascendem!</p>
        </div>

        <h1>Recomendações</h1>
        <div class="ad users">

            <?php
            if (ids() != false) {
                $sql_users = "SELECT * FROM users WHERE id NOT IN (".ids().") ORDER BY RAND()";
            }
            $sql_users = "SELECT * FROM users ORDER BY RAND()";
            $res_users = $conn -> query($sql_users);
            $row = $res_users -> fetch_object();
            
            // print_r($res_users);
            for ($t=0; $t<4; $t++) {
                if ($row->id == $_SESSION['id']) {
                    if ($t == $res_users->num_rows) {
                        break;
                    } else {
                        $row = $res_users -> fetch_object();
                    }
                }
                if ($row->picture == '' || $row->picture == '-' || $row->picture == '...') { $row->picture = '../assets/icons/default_user.png'; }
            
            ?>

            <a href="user.php?uid=<?php echo $row->id; ?>">
            <div class="tab-user">
                <div class="divimg">
                    <img src="<?php echo $row->picture; ?>" alt="@<?php echo $row->username; ?>">
                </div>
                <p><span class="at">@</span><?php echo $row->username; ?></p>
                <a href="friends.php?adicionar=<?php echo $row->id; ?>">
                <button class="toolbutton" id="pghome"><h1>Adicionar</h1></button></a>
            </div>
            </a>

            <?php
            $row = $res_users -> fetch_object();
            };

            ?>
        </div>


    </div>
</div>


</div>
</div>




<script src="../js/filtersIndex.js"></script>
  
</body>
</html>