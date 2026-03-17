<?php

include_once '../../conn/classes/class_IRepositorioCarrosel.php'; 
session_start();

$telefone = $_POST['telefone'];
$celular = $_POST['celular'];
$instagram = $_POST['instagram'];
$facebook = $_POST['facebook'];

$altera = $repositorioCarrosel->atualizar_rodape($telefone, $celular, $instagram,$facebook);

header('Location: ../home_adm.php');
?>
