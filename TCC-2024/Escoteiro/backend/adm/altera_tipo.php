<?php
include '../classes/conn.php';

if(isset($_POST['Alterar'])){
    $valor = $_POST["type"]-1;
        $sql_altera_saldo='UPDATE tbl_usuarios SET tipo='.$valor.' WHERE id='.$_POST["userid"];
        $res_altera_saldo = $conn -> query($sql_altera_saldo); 
        header('Location: users_adm.php');
}
?>