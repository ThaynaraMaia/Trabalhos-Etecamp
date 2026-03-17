<?php

include '../../../backend/classes/conn.php';

$id = $_GET['id'];
$sql_delete = "DELETE FROM rating WHERE rating.rating = ".$id;
$res = $conn -> query($sql_delete);
echo 'deleted';

$sql_delete3 = "DELETE FROM friendship WHERE ".$id." IN (user1, user2)";
$res3= $conn -> query($sql_delete3);
echo 'deleted3';

$sql_delete2 = "DELETE FROM users WHERE id = ".$id;
$res2 = $conn -> query($sql_delete2);
echo 'deleted2';



header('Location: adm.php?users');