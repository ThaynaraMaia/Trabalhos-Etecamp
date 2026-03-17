<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecionando...</title>
</head>
<body>

<?php
include '../../classes/conn.php';
$logged = false;
session_start();
error_reporting(E_ALL & ~E_NOTICE);
if (isset($_SESSION['username'])) {
    $logged = true;
} 
$games = $_GET['Games'];    

echo $games;

$sql_games = "UPDATE users SET games ='".$games."' WHERE id = ".$_SESSION['id'].";";

$res_games = $conn->query($sql_games);

$_SESSION['usergames'] = $_GET['Games'];   

// header('Location: ../../../public/html/index.php');
header('Location: ../../../public/html/mygames.php');
?>
</body>
</html>
