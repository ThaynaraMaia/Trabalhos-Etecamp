<?php 
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

    $st = $_SESSION['status'];

    if ($st == 3) {
        header('Location: warn.php');
    }
    
?>
