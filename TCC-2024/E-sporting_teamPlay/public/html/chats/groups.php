<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>TeamPlay - Meus Grupos</title>
	<link rel="stylesheet" href="../../css/global.css">
	<link rel="stylesheet" href="../../css/style.css">
	<link rel="stylesheet" href="../../css/styleChats.css">
	<link rel="shortcut icon" href="../../assets/Logo_Cor1.png" type="image/x-icon">
<?php 
    include '../../../backend/classes/conn.php';
    session_start();

    $st = $_SESSION['status'];
    if ($st == 3) {
        header('Location: ../warn.php');
    }



    $logged = false;
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);
    if (isset($_SESSION['id'])) {
        $logged = true;
    } else {
        header('Location: ../welcome.html');
    }


$con = mysqli_connect('localhost', 'root', '');
mysqli_select_db($con, 'teamplay');

$userName = $_GET['name'];

?>

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
        <a href="../chats/index.php" style="position: absolute; right: 19vw">
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
        
    <h1>Meus Grupos:</h1>



<?php
    $query2 = mysqli_query($con, "SELECT * FROM users WHERE username = '".$userName."'");
    $new = false;
    while ($row1 = mysqli_fetch_array($query2)) {
        $query = "SELECT * FROM `users_groups` WHERE user_id = ".$row1['id']."";
    
        $result = mysqli_query($con, $query);
        while ($row = mysqli_fetch_array($result)) {
            $sql = mysqli_query($con, "SELECT * FROM groups WHERE group_id = ".$row['group_id']."");
            while($row2 = mysqli_fetch_array($sql)) {
                // Calcular chats não lidos
                $unread = $row2['total_chats'] - $row['read_chats'];
                if ($unread > 0) {
                    $new = true;
                }
                

                // Exibir o grupo com um link
                ?>


                <div class='post'>
                    <img src="../../assets/icons/chat_gc.png" alt="Chat">
                <a href='chats.php?user=<?php echo urlencode($row1['id']) ?>&group=<?php echo urlencode($row2['group_id']) ?>'>
                    <h1><?php echo htmlspecialchars($row2['group_name']); if($new) { ?> <div class="notif"></div><?php } ?></h1>
                    <p><strong><?php echo ''; ?></strong></p>
                </a>
                <br>
                </div><br>
                <?php
            }
        }
    }
    ?>
 
<br>


<form action='NewGroup.php?nname=<?php echo urlencode($userName); ?>' method="POST">
<button type="submit" class="toolbutton"><h1><strong>Novo Grupo</strong></h1></button>
</form>
    

</div>

</div>
</div>

</body>
</html>
