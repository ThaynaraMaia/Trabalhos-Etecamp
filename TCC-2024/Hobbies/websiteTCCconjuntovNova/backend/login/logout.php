<?php

session_start();

if (
    $_SESSION['mensagem']
    && $_SESSION['email']
    && $_SESSION['nome']
) {

session_destroy();

}

header('Location:../../frontend/html/index.php');

?>