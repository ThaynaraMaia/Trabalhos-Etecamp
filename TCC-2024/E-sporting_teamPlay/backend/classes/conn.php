<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "teamplay";


$conn = new mysqli($host, $user, $pass, $db);

// Criar conn;
if ($conn -> connect_error) {
    die("Connection failed: " . $conn -> connect_error);
}
 
$sql = "SELECT * FROM users;";

$res = $conn -> query($sql);

function refresh_user($conn, $row) {
    $_SESSION['id'] = $row['id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['nickname'] = $row['nickname'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['level'] = $row['level'];
    $_SESSION['status'] = $row['status'];
    $_SESSION['verified'] = $row['verified'];
    $_SESSION['birthday'] = $row['birthday'];
    $_SESSION['region'] = $row['region'];
    $_SESSION['pfp'] = $row['picture'];
    $_SESSION['join_date'] = $row['join_date'];
    $_SESSION['usersocials_un'] = $row['socials'];
        // $_SESSION['usersocials'] = [];
    $_SESSION['favgame_un'] = $row['favorite_game'];
        $_SESSION['favgame'] = "";
    $_SESSION['usergames'] = $row['games'];
    $_SESSION['userfriends'] = [];
    $_SESSION['userfriendreqs'] = [];
    $_SESSION['usergameplay'] = $row['gameplay'];
    $_SESSION['userdesc'] = $row['description'];
    $_SESSION['filters'] = [];
    $_SESSION['notif'] = false;
    
    $date_diff = abs(strtotime(date('Y-m-d')) - strtotime($_SESSION['birthday']));

    $_SESSION['age'] = floor($date_diff / (365*60*60*24));


    if ($_SESSION['pfp'] == '' || $_SESSION['pfp'] == '-' || $_SESSION['pfp'] == ' ' || $_SESSION['pfp'] == '...' || $_SESSION['pfp'] == '../assets/profile_pics/') {
        $_SESSION['pfp'] = '../../public/assets/user.png';
    }
    

    // Amigos
    $sql_ufriends = "SELECT 
    f.user1,
    u1.username AS user1_username,
    f.user2,
    u2.username AS user2_username
    FROM friendship f
        JOIN users u1 ON f.user1 = u1.id
        JOIN users u2 ON f.user2 = u2.id
    WHERE f.status = 1
        AND ".$_SESSION['id']." IN (f.user1, f.user2);
    ";
    
    $res_ufriends = $conn -> query($sql_ufriends);

    for($t=0; $t<$res_ufriends->num_rows; $t++){

        $ufriendsrow = $res_ufriends -> fetch_object();
        if ($ufriendsrow->user1 == $_SESSION['id']) {
            array_push($_SESSION['userfriends'], [$ufriendsrow->user2, $ufriendsrow->user2_username]);
        } else {
            array_push($_SESSION['userfriends'], [$ufriendsrow->user1, $ufriendsrow->user1_username]);
        }
    }
    


    // Reqs
    $sql_ureqs = "SELECT 
    f.user1,
    u1.username AS user1_username,
    f.user2,
    u2.username AS user2_username
    FROM friendship f
        JOIN users u1 ON f.user1 = u1.id
        JOIN users u2 ON f.user2 = u2.id
    WHERE f.status = 0
        AND  f.user2 = ".$_SESSION['id'].";";
    
    $res_ureqs = $conn -> query($sql_ureqs);

    for($t=0; $t<$res_ureqs->num_rows; $t++){

    $ureqsrow = $res_ureqs -> fetch_object();
        array_push($_SESSION['userfriendreqs'], [$ureqsrow->user2, $ureqsrow->user2_username]);
    }


    // Jogo fav
    $sql_favgame = "SELECT * FROM games WHERE id LIKE ".$_SESSION['favgame_un'];
    $res_favgame = $conn -> query($sql_favgame);
    $favgamerow = $res_favgame -> fetch_object();
    $_SESSION['favgame'] = $favgamerow->name;

    $sql_req= "SELECT * FROM friendship WHERE user2 = ".$_SESSION['id']." AND status = 0";
    $res_req = $conn -> query($sql_req);

    if ($res_req->num_rows > 0) {
        $_SESSION['notif'] = true;
    } else {
        $_SESSION['notif'] = false;
    }

}




function ids() {
    $f_ids = [];
    if ($_SESSION['userfriends']) {
        foreach ($_SESSION['userfriends'] as $f) {
            array_push($f_ids,$f[0]);
        }
        return implode(", ", $f_ids);
    } else {
        return false;
    }

};


