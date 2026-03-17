<?php
$bdhost = "localhost";
$bduser = "root";
$bdpass = "12345678";
$bdname = "quiz";

$conection = mysqli_connect($bdhost,$bduser,$bdpass,$bdname);

if (mysqli_connect_errno()){
    die("Database_Conection_Failed". mysqli_connect_error() . "(". mysqli_connect().")");
}
    

?>