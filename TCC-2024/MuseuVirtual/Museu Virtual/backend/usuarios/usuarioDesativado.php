<?php
    $mensagemDesativado = '<script>alert("Sua conta não está ativada!");</script>';

    $_SESSION['mensagemDesativado'] = $mensagemDesativado;
    echo $mensagemDesativado;

    exit;
   
?>