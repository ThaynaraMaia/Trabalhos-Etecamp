<!DOCTYPE html>
<html lang="pt-br">
<head>
<?php 
include 'conn.php';

session_start();

echo 'User friends: ';
print_r($_SESSION['userfriends']);

$_SESSION['userfriends'] = [];
function br() {
    echo '<br>';
}



$sql_ufriends = "SELECT 
    f.user1,
    u1.username AS user1_username,
    f.user2,
    u2.username AS user2_username
FROM friendship f
    JOIN users u1 ON f.user1 = u1.id
    JOIN users u2 ON f.user2 = u2.id
WHERE f.status = 1
    AND ".$_SESSION['id']." In (f.user1, f.user2);
";


$res_ufriends = $conn -> query($sql_ufriends);

// print_r($res_ufriends);

// echo 'Meu id: '.$_SESSION['id']; 


for($t=0; $t<$res_ufriends->num_rows; $t++){
    $ufriendsrow = $res_ufriends -> fetch_object();
    // br(); br();

    // print_r($ufriendsrow);
    
    // br();
    
    // echo 'Amigo: ';
    if ($ufriendsrow->user1 == $_SESSION['id']) {
        br();
        array_push($_SESSION['userfriends'], [$ufriendsrow->user2, $ufriendsrow->user2_username]);
        // print_r($ufriendsrow->user2);
    } else {
        br();
        array_push($_SESSION['userfriends'], [$ufriendsrow->user1, $ufriendsrow->user1_username]);
        // print_r($ufriendsrow->user1);
    }
    
    // br();

}

br();

function l() {

    $f_ids = [];
    foreach ($_SESSION['userfriends'] as $f) {
        array_push($f_ids,$f[0]);
    };
    return implode(", ", $f_ids);
};
br();
br();
echo 'sdasd'.l();


br();
br();
print_r($_SESSION['userfriends']);
br();
br();

$sql_post = "SELECT posts.*, users.nickname, games.name FROM posts, users, games
                    WHERE posts.id_usuario IN ( ".function() {
                        $f_ids = [];
                        foreach ($_SESSION['userfriends'] as $f) {
                            array_push($f_ids,$f[0]);
                        };
                        return implode(", ", $f_ids);
                    }." ) ".$selwhere." AND posts.id_jogo = games.id AND users.id = posts.id_usuario ORDER BY dat_criacao DESC"; 

echo 'Sql: '.$sql_post;