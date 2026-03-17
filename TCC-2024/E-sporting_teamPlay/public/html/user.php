<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php 
    include '../../backend/classes/conn.php';
    include 'checkban.php';
    
    $has_msg = $_SERVER['QUERY_STRING'];
    error_reporting(E_ALL & ~E_NOTICE); 
    session_start();
    if (isset($_SESSION['username'])) {
        $logged = true;
        if ($conn -> connect_error) {
            die("Connection failed: " . $conn -> connect_error);
        }

        if (isset($_GET['uid'])) {
            if ($_GET['uid'] == $_SESSION['id']) {
                header('Location: user.php');
            }
            $other = true;
            // echo 'Looking for user: '.$_GET['uid'];
            $sql_user = "SELECT * FROM users WHERE (id like ".$_GET['uid'].");";
            
            $res_user = $conn -> query($sql_user);
            if ($res_user->num_rows > 0) {
                $row_user = $res_user -> fetch_array();

                $usr_id = $row_user["id"];
                $usr_name = $row_user["username"];
                $usr_nick = $row_user["nickname"];
                $usr_mail = $row_user["email"];
                $usr_lvl = $row_user["level"];
                $usr_sts = $row_user["status"];
                $usr_ver = $row_user["verified"];
                $usr_bday = $row_user["birthday"];
                $usr_reg = $row_user["region"];
                $usr_pfp = $row_user["picture"];
                $usr_join = $row_user["join_date"];
                $usr_soc = $row_user["socials"];
                $usr_favgame_un = $row_user["favorite_game"];
                    $usr_favgame = "";
                $usr_games = $row_user["games"];
                $usr_friends_un = [];
                    $usr_friends = [];
                $usr_gameplay = $row_user["gameplay"];
                $usr_desc = $row_user["description"];
                $usr_rep = $row_user["reputation"];
            } else {
                header('Location: user.php');
            }

            $sql_has_rating = "SELECT * FROM rating WHERE rfrom = ".$_SESSION['id']."
            AND rating = ".$_GET['uid'].";";
            // echo '<br>'; // DEBUG
            // echo $sql_has_rating; // DEBUG
            // echo '<br>'; // DEBUG
            $res_has_rating = $conn -> query($sql_has_rating);
            // $has_rat = false;
            // echo '<br>'; // DEBUG
            // echo 'DJSAFHNJSAHFUY'; // DEBUG
            // echo '<br>'; // DEBUG
            
            if ($res_has_rating->num_rows > 0) {
                $row_has_rating = ($res_has_rating -> fetch_object());
                $has_rat = true;
            } else {
                $has_rat = false;
            }
            // echo '<br>'; // DEBUG
            // echo 'HAS: '.$has_rat; // DEBUG
            // echo '<br>'; // DEBUG

            
        } else {
            $other = false;
            $sql_me = "SELECT * FROM users WHERE (id like ".$_SESSION['id'].");";
            
            $res_me = $conn -> query($sql_me);
            $row = $res_me -> fetch_array();
            refresh_user($conn, $row);

            $usr_id = $row["id"];
            $usr_name = $row["username"];
            $usr_nick = $row["nickname"];
            $usr_mail = $row["email"];
            $usr_lvl = $row["level"];
            $usr_sts = $row["status"];
            $usr_ver = $row["verified"];
            $usr_bday = $row["birthday"];
            $usr_reg = $row["region"];
            $usr_pfp = $row["picture"];
            $usr_join = $row["join_date"];
            $usr_soc = $row["socials"];
            $usr_favgame_un = $row["favorite_game"];
                $usr_favgame = "";
            $usr_games = $row["games"];
                $usr_friends_un = [];
                $usr_friends = [];
            $usr_gameplay = $row["gameplay"];
            $usr_desc = $row["description"];
            $usr_rep = $row["reputation"];
        }



        if ($usr_bday == '0000-00-00') {
            $usr_age = 'Não informado.'; 
        } else {
            $date_diff = abs(strtotime(date("Y-m-d")) - strtotime($usr_bday));

            $usr_age = floor($date_diff / (365*60*60*24));
        }


        if ($usr_pfp == "" || $usr_pfp == "-" || $usr_pfp == " " || $usr_pfp == "..." || $usr_pfp == "../assets/profile_pics/") {
            $usr_pfp = "../../public/assets/user.png";
        }

        if ($usr_rep) {
            // echo 'REP: '.$usr_rep; // DEBUG
            $usr_rep_brute = number_format((float)$usr_rep, 2, '.', '');
            $usr_rep = round($usr_rep, 0, PHP_ROUND_HALF_DOWN);
        } else {
            $usr_rep_brute = 0;
            $usr_rep = 0;
        }


        if ($usr_favgame_un == 0) {
            $usr_favgame = 'Sem jogo favorito.';
        } else {
            $sql_favgame = "SELECT * FROM games WHERE id LIKE ".$usr_favgame_un;
            $res_favgame = $conn -> query($sql_favgame);
            $favgamerow = $res_favgame -> fetch_object();
            $usr_favgame = $favgamerow->name;
        }
        $myrep = 0;



        $sql_ufriends = "SELECT 
        f.user1,
        u1.username AS user1_username,
        f.user2,
        u2.username AS user2_username
        FROM friendship f
            JOIN users u1 ON f.user1 = u1.id
            JOIN users u2 ON f.user2 = u2.id
        WHERE f.status = 1
            AND ".$usr_id." IN (f.user1, f.user2);
        ";
        
        $res_ufriends = $conn -> query($sql_ufriends);

        for($t=0; $t<$res_ufriends->num_rows; $t++){

            $ufriendsrow = $res_ufriends -> fetch_object();
            if ($ufriendsrow->user1 == $usr_id) {
                array_push($usr_friends, [$ufriendsrow->user2, $ufriendsrow->user2_username]);
            } else {
                array_push($usr_friends, [$ufriendsrow->user1, $ufriendsrow->user1_username]);
            }
        }

        if (isset($_GET['rate'])) {
        


            if ($has_rat) {
                $sql_rate_user_add = "UPDATE rating SET
                rate = ".$_GET['rate']." WHERE rfrom LIKE ".$_SESSION['id']." AND rating LIKE ".$_GET['uid'].";";
                echo 'SQL RATE: '.$sql_rate_user_add; // DEBUG

            } else {
                $sql_rate_user_add = "INSERT INTO rating (rfrom, rating, rate)
                VALUES (".$_SESSION['id'].", ".$_GET['uid'].", ".$_GET['rate'].")";

            }
            // echo '<br>'; // DEBUG
            // echo $sql_rate_user_add; // DEBUG
            // echo '<br>'; // DEBUG
            $res_rate_user_add = $conn -> query($sql_rate_user_add);
            
            $sql_rep = "SELECT * FROM rating WHERE rating.rating LIKE ".$_GET['uid'].";"; // All ratings
            $res_rep = $conn -> query($sql_rep);
            $num_ratings = $res_rep -> num_rows;

            // echo '<br>'; // DEBUG
            // echo $sql_rep; // DEBUG
            // echo '<br>'; // DEBUG



            if ($num_ratings == 0) { // Se for a primeira rate
                $new_rate = $_GET['rate'];

            } else {
                // echo 'Num Ratings: '.$num_ratings;
                // echo 'Usr Rat: '.$usr_rep;

                if ($has_rat) {

                    $new_rate = ($num_ratings * $usr_rep_brute - $row_has_rating->rate + $_GET['rate']) / $num_ratings;
                    echo '<br>1Conta: ((('.$num_ratings.' * '.$usr_rep_brute.') - '.$row_has_rating->rate.') + '.$_GET['rate'].') / ('.$num_ratings.') = '.$new_rate;
                } else {
                    $new_rate = ($num_ratings * $usr_rep_brute + $_GET['rate']) / ($num_ratings + 1);
                    echo '<br>2Conta: (('.$num_ratings.' * '.$usr_rep_brute.') + '.$_GET['rate'].') / ( '.$num_ratings.' + 1 ) = '.$new_rate;
                }
            }

            // echo '<br>'; // DEBUG
            // echo $num_ratings; // DEBUG
            // echo '<br>'; // DEBUG

            // echo '<br>'; // DEBUG
            // echo $new_rate; // DEBUG
            // echo '<br>'; // DEBUG
            
            

            $sql_rate_rep = "UPDATE users SET
            reputation = ".$new_rate." WHERE id LIKE ".$_GET['uid'].";";
            $res_rate_rep = $conn -> query($sql_rate_rep);
            

            header('Location: user.php?uid='.$_GET['uid']);
            // echo '<br>'; // DEBUG
            // echo $sql_rate_rep; // DEBUG
            // echo '<br>'; // DEBUG

            // $sql_rate_user_add = "SELECT * FROM rating WHERE rfrom = ".$_SESSION['id']."
            // AND rating = ".$_GET['uid'].";";
            
            // $the_rate_user_add = ($res_rate_user_add -> fetch_object())->rate;
        }




    } else {
        header('Location: welcome.html');
    }
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@<?php echo $usr_nick ?> - TeamPlay</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/styleUser.css">
    <link rel="shortcut icon" href="../assets/Logo_Cor1.png" type="image/x-icon">
</head>


<body>
<span class="holder" id="uid"><?php echo $usr_id?></span>
<span class="holder" id="statusPhp"><?php echo $usr_sts?></span>

<form action="" method="post">
<div class="main"> 
<div class="content">
    

<div class="toolbar">
    <div class="logo">
        <a href="index.php">
            <img src="../assets/Logo_Full.png" style="width: 10vw;" alt="TeamPlay">
        </a>
    </div>    


    <div class="userarea" style="max-width: 60%; gap: 2wn">       
    <a href='logout.php'><div class="inp btn" id="pghome" style="width: 90%; margin-right: 10vw; background-color: var--sage2();"><h1>Desconectar</h1></div></a><br>

        <div class="pfpimg">
            <img src="<?php echo $_SESSION['pfp'] ?>" alt="User">
             
        </div>
           
        <a href='../../backend/'>
        <button class="toolbutton active" id="pghome"><h1><?php echo '<span class="at">@ </span>'.$_SESSION['nickname']?></h1></button></a> 
         

    </div>
</div>
</div>

    <div class="page">
        
        <div class="con1">
            <div class="con2 first">
            <div class="con2top">
                <div class="pfpimg2">
                    <img src="<?php echo $usr_pfp ?>" alt="User">
                </div>
                <h1><?php echo $usr_nick?></h1>
                    <?php if($usr_ver == '1') { ?>
                        <img src="../assets/icons/icon_ver.png" class="verif" alt="Verificado" title="Usuário Verificado">
                    <?php
                    } ?>
            </div>

            <div class="info">
                <ul>
                    
                    <li><strong>Username:</strong> <span class="at">@ </span><?php   echo $usr_name?></li>
                    <li><strong>Idade:</strong> <?php  echo $usr_age?></li>
                    <li><strong>Email:</strong> <?php  echo $usr_mail?></li>
                    <li><strong>Entrou em:</strong> <?php echo $usr_join?></li>
                </ul>       
                <?php 
                if($other) { ?>
                <div style="display: flex; align-items: center; gap: 1vh">
                    <p style="font-size: large"><strong>Avaliar Usuário: </strong></p>
                    <select class="inp" id="rating" name="favgame" required="true" class="inp" value<?php if(isset($_GET['rate'])) {if($_GET['rate'] == 1) {echo 'selected';}} else {echo '0';} ?>>
                        <option <?php if($has_rat) {if($row_has_rating->rate == 1) {echo 'selected';}} ?> value="[<?php echo $usr_id; ?>, 1]">1</option>
                        <option <?php if($has_rat) {if($row_has_rating->rate == 2) {echo 'selected';}} ?> value="[<?php echo $usr_id; ?>, 2]">2</option>
                        <option <?php if($has_rat) {if($row_has_rating->rate == 3) {echo 'selected';}} ?> value="[<?php echo $usr_id; ?>, 3]">3</option>
                        <option <?php if($has_rat) {if($row_has_rating->rate == 4) {echo 'selected';}} ?> value="[<?php echo $usr_id; ?>, 4]">4</option>
                        <option <?php if($has_rat) {if($row_has_rating->rate == 5) {echo 'selected';}} ?> value="[<?php echo $usr_id; ?>, 5]">5</option>
                    </select>
                    <div style="background-color: var(--black); border-radius: 1vh; padding: 1vh">
                    <img id="mystar" title="<?php echo $myrep; ?>" style="height: 4vh;" src="../assets/icons/rating_<?php echo $myrep; ?>.png" alt="Estrelas">
                    </div>
                </div>
                <?php } ?>
            </div>
            
            <div class="con2bottom">
            <?php
            if(!$other) { ?>
                <a href='edit.php'><div class="inp btn" id="pghome"><h1>Editar Perfil</h1></div></a>

            <?php if($_SESSION['level'] == 2) { ?>
                <a href='adm/adm.php?posts'><div class="inp btn config "id="pghome"><h1>Administrar <span style="color: var(--will);">TeamPlay</span></h1></div></a>
                <?php }
                } else {
                false;
                ?>
                <?php 
                }
            ?>
            </div>
        </div>
        <div style="display: flex; flex-direction: column; width: 62%; justify-content: space-between;">
        
        <?php if(isset($_GET['uid'])) { ?>
            <div style="display: flex; width: 100%; justify-content: space-between; gap: 3vw; align-content: center; margin-left: 1vw;">
                <?php if (!(in_array($usr_id, $_SESSION['userfriends']))) { ?>
                    <a href='friends.php?adicionar=<?php echo $usr_id ?>'><div class="inp btn sec" id="pghome"><h1>Adicionar</h1></div></a>
                <?php } ?>
                
            <?php if ($other) { ?>
                <a href='denuncia/fale.php?offender=<?php echo $usr_name ?>'><div class="inp btn config" id="pghome" style="width: 14vw"><h1>Denunciar Jogador</h1></div></a>
            <?php } ?>
            </div>
            <br>
            <?php } ?>
            
        <div class="con2 sec">
            <div class="info">
                <ul>
                <li><strong>Redes Sociais:</strong> <?php echo $usr_soc;?></li>
                    

                <li><strong>Jogo Favorito:</strong> <?php echo $usr_favgame?></li>


                <li><strong><a href="friends.php" style="color: var(--will); text-decoration: underline">Amigos:</a></strong>
                    <?php 
                    if (count($usr_friends) > 0) {
                        foreach ($usr_friends as $friend) { ?>


                        <a style="color: var(--mag); text-decoration: underline;" href="user.php?uid=<?php echo $friend[0];?>">
                        <span><strong><?php echo $friend[1];?></strong></span></a>, <?php } ?>...</li>

                        <?php } else { ?>
                            <span>Sem amigos adicionados...</span>
                        <?php } ?>

                        
                <li><strong>Gameplay:</strong> <?php echo $usr_gameplay?></li>
                <li><strong>Região:</strong> <?php echo $usr_reg?></li>
                <li><strong>Descrição:</strong> <?php echo $usr_desc?></li>
                <li>
                    <div style="display: flex; flex-direction: row; align-items: center; gap: 1vw;">
                        <strong>Reputação:</strong>
                        <div style="background-color: var(--black); border-radius: 1vh; padding: 1vh">
                            <img title="<?php echo $usr_rep_brute; ?>" style="height: 4vh;" src="../assets/icons/rating_<?php echo $usr_rep; ?>.png" alt="Estrelas">
                        </div>
                    </div>
                </li>
                </ul>
                </div>  
            </div>
        </div>
        </div><br>


        <div class="con1" style="height: fit-content; flex-direction:column">
            <h1>Jogos de <?php echo $usr_nick?></h1>
            <iframe src="mygames<?php if ($other) { echo 'Read'; }?>.php<?php if ($other) { echo '?uid='.$usr_id; }?>"
            frameborder="0" style="width: 100%;
            height: <?php if (isset($_GET['uid'])) { echo '70vh'; } else { echo '99vh'; } ?>;
            border-radius: 1vw;"></iframe>
        </div>

        </div>
    </div>
</div>

<div class="wrapper">

</div>
</div>
</div>
</div>

</form>
<script src="../js/rating.js"></script>
  
</body>
</html>
