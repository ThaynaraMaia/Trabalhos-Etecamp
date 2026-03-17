<?php
include '../classes/conn.php';

if(isset($_POST['Alterar'])){
        $sql_altera_saldo='UPDATE tbl_usuarios SET saldo='.$_POST["saldo"].' WHERE id='.$_POST["userid"];
        $res_altera_saldo = $conn -> query($sql_altera_saldo); 
        header('Location: users_adm.php');
}
?>