<?php
$conn = mysqli_connect("localhost", "root", "", "enemplatd_bd");

if(!$conn){
    die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}
?>
