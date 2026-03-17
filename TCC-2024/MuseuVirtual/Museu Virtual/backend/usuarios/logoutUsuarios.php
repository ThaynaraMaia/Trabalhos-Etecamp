<?php

    session_start();

    if($_SESSION['nome'] && $_SESSION['email']){
        session_destroy();
    }

    header('Location:../../frontend/paginas/mostre_sua_arte-login.php')
    
?>