<?php 
    include '../../backend/classes/conn.php';
    $logged = false;
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);
    if (isset($_SESSION['id'])) {
        $logged = true;
    } else {
        header('Location: welcome.html');
    }


$con = mysqli_connect('localhost', 'root', '');
mysqli_select_db($con, 'teamplay');

$userName = $_GET['name'];

$con = mysqli_connect('localhost', 'root', '');
mysqli_select_db($con, 'teamplay');


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>TeamPlay - Criar Novo Grupo</title>
	<link rel="stylesheet" href="../../css/global.css">
	<link rel="stylesheet" href="../../css/styleTour.css">
	<link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/styleChats.css">
	<link rel="shortcut icon" href="../../assets/Logo_Cor1.png" type="image/x-icon">

<body>
<div class="main">


<div class="content">
    
<div class="toolbar">
    <div class="logo">
        <a href="../index.php">
            <img src="../../assets/Logo_Full.png" style="width: 10vw;" alt="TeamPlay">
        </a>
    </div>    


    <div class="pages">
        <a href="../index.php">
        <button class="toolbutton" id="pghome"><h1>Home</h1></button></a>
        
        <a href="../tournaments.php">
        <button class="toolbutton" id="pgtrn"><h1>Torneios</h1></button></a>    
        
        <a href="../friends.php">
        <button class="toolbutton" id="pgfrn"><h1>Usuários</h1></button></a>
    </div>
 

    <div class="userarea">
        <?php if ($logged) { ?> 
        <a href="../post.php" style="position: absolute; right: 23vw">
            <button class="toolbutton" id="pghome" style="width: 3vw"><h1 style="font-size: x-large;">+</h1></button></a> 
        <a href="../index.php" style="position: absolute; right: 19vw">
        <button class="toolbutton active" id="pghome" style="width: 3vw"><img src="../../assets/icons/chat.png" style="width: 2.2vw; filter: brightness(0);"></button></a> 

        <div class="pfpimg">
        <img src="../<?php echo $_SESSION['pfp']; ?>" alt="User">
             
        </div>
        <?php } ?>

        <a href="<?php echo $logged ? '../user.php' : 'login.php'?>">
            <button class="toolbutton active" id="pghome"><h1><?php echo '<span class="at">@ </span><span>'.$_SESSION["nickname"].'</span>'?></h1></button></a> 
         

        
        
    </div>
</div>



<div class="page">
    <div class="con1">




<?php
// Verifica se os parâmetros 'nname' e 'insertGroup' estão presentes
if (isset($_POST['insertGroup']) && isset($_GET['nname'])) {
    $userName = $_GET['nname']; // Nome do usuário que está criando o grupo
    $groupName = $_POST['groupName'];
    // if (isset($_GET['isdm'])) {
    //     $groupName = $_GET['isdm'];
    //     $isdm = true;
    // }
    if (isset($_GET['dmwith'])) {
        $groupName = $_GET['dmwith'];
        $dmwith = $_GET['dmwith'];
    }



    if (!empty($groupName) && !empty($userName)) {
        // Verificar se o usuário existe
        $userQuery = mysqli_query($con, "SELECT id FROM users WHERE username = '$userName'");
        
        if (mysqli_num_rows($userQuery) > 0) {
            $dmQuery = mysqli_query($con, "SELECT * FROM users_groups");
            // Inserir o novo grupo
            $query1 = mysqli_query($con, "INSERT INTO groups (group_name, creator_id, total_chats) VALUES ('$groupName', (SELECT id FROM users WHERE username = '$userName'), 0)");
            
            if ($query1) {
                $newGroupId = mysqli_insert_id($con); // Obter o ID do novo grupo

                // Adicionar o criador do grupo à tabela users_groups
                mysqli_query($con, "INSERT INTO users_groups (user_id, group_id, read_chats) VALUES ((SELECT id FROM users WHERE username = '$userName'), '$newGroupId', 0)");

                echo "Grupo '$groupName' criado com sucesso!<br>";
                echo "ID do novo grupo: " . $newGroupId . "<br>";
                echo "<strong><a style='color: var(--mag);' href='addMembers.php?group=" . urlencode($newGroupId) . "&user=" . urlencode($userName) . "'>Adicionar Membros ao Grupo</a></strong><br>";
            } else {
                echo "Erro ao criar grupo: " . mysqli_error($con);
            }
        } else {
            echo "Erro: Usuário '$userName' não encontrado.";
        }
    } else {
        echo "Nome do grupo ou nome do usuário faltando.";
    }
}
    
    ?>
 

    <form method="POST">
        <label>Digite o Nome do Grupo:</label><br><br>
        <input type="text" name="groupName" required><br><br>
        <button name="insertGroup" type="submit" class="toolbutton active" style="font-size: large;">Criar Grupo</button>
    </form>

</div>

</div>
</div>

</body>
</html>










