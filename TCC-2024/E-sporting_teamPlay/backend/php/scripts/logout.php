<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecionando...</title>
    <link rel="stylesheet" href="../../../public/css/style.css">
    <link rel="shortcut icon" href="../../../public/assets/Para games.png" type="image/x-icon">
    <?php error_reporting(E_ALL & ~E_NOTICE);
    if (!isset($_SESSION['user'])) {
        header('Location: ../../../public/html/index.php');
    };?>
</head>
<body>
    <?php 
        session_start();
        session_destroy();
        header('Location: ../../../public/html/index.php');
    ?>
    <a href="http://localhost/system/backend/php/scriptsindexRedef.php?id=$ID&token=$TOKEN"></a>
<footer>
    <p class="text foot">® ParaGames 2007-2023</p>
</footer>
</body>
</html>