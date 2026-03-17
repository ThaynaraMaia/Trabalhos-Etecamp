

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Redirecionando...</title>
	<link rel="stylesheet" href="../css/global.css">
	<link rel="stylesheet" href="../css/styleTour.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="shortcut icon" href="../assets/Logo_Cor1.png" type="image/x-icon">

<?php

$con = mysqli_connect('localhost','root',password: '');

mysqli_select_db($con, 'teamplay');

$logged = false;
session_start();
error_reporting(E_ALL & ~E_NOTICE);
if (isset($_SESSION['id'])) {
	$logged = true;
} else {
	header('Location: ../welcome.html');
}


		$userName = $_SESSION['username'];
		
		$query = "SELECT * FROM users WHERE username = '".$userName."'";

		$result = mysqli_query($con, $query);

		if(mysqli_num_rows($result)>0)
		{
			header('Location: groups.php?name='.$userName);			
		}
    ?>
</head>



<body>
<div class="main"></div>

</body>
</html>