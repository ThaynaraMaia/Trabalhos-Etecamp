<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <?php error_reporting(E_ALL & ~E_NOTICE);
    if (!isset($_SESSION['username'])) {
        header('Location: index.php');
    };?>
</head>
<body>
    <?php 
        session_start();
        session_destroy();
        session_abort();
        
        header('Location: index.php');
    ?>
</body>
</html>