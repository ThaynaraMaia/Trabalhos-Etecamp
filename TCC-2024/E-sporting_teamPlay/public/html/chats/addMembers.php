<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>TeamPlay - Meus Grupos</title>
	<link rel="stylesheet" href="../../css/global.css">
	<link rel="stylesheet" href="../../css/style.css">
	<link rel="stylesheet" href="../../css/styleEdit.css">  
	<link rel="stylesheet" href="../../css/styleChats.css">
	<link rel="shortcut icon" href="../../assets/Logo_Cor1.png" type="image/x-icon">
<?php 
include '../../../backend/classes/conn.php';
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

// Verifica se os parâmetros 'group' estão presentes
if (!isset($_GET['group'])) {
    echo "Erro: parâmetro 'group' não foi fornecido.";
    exit();
}

$group = $_GET['group'];
$creatorId = $_GET['creatorId'];

// Listar todos os usuários


if (count($_SESSION['userfriends']) <= 0) {
    $result = mysqli_query($con, "SELECT * FROM users WHERE id = -1;"); //amigos
} else {
    $result = mysqli_query($con, "SELECT * FROM users WHERE id IN (".ids().");"); //amigos
};

if (isset($_POST['addUser'])) {
    $userName = $_POST['userName']; 

    // Verifica se o usuário já está no grupo
    $queryCheck = mysqli_query($con, "SELECT * FROM users_groups WHERE user_id = (SELECT id FROM users WHERE username = '$userName') AND group_id = '$group'");
    if (mysqli_num_rows($queryCheck) == 0) {
        // Adicionar o usuário ao grupo

        $insertQuery = "INSERT INTO users_groups (user_id, group_id, read_chats) VALUES ((SELECT id FROM users WHERE username = '$userName'), '$group', 0)";
        if (mysqli_query($con, $insertQuery)) {
            
            $message = "$userName foi adicionado ao grupo por ".$_SESSION['username'];
            echo $message;
            mysqli_query($con, "INSERT INTO chats (group_id, user_id, message) VALUES ('$group', '$creatorId', '$message')");


            header('Location: chats.php?user='.urlencode($creatorId).'&group='.urlencode($group));
                           
            
        } else {
            echo "Erro ao adicionar o usuário ao grupo: " . mysqli_error($con);
        }
    } else {
        echo "O usuário '$userName' já está neste grupo.";
    }
}
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


<!DOCTYPE html>
<html>
<head>
    <title>Adicionar Membros</title>
</head>
<body>
    <h2>Adicionar Membros ao Grupo</h2>
    <?php 
        if (count($_SESSION['userfriends']) <= 0) {
            echo 'Sem amigos adicionados.';
        } else { ?>
            <form method="POST">
                <label for="userName">Nome do Amigo:</label>
                <select name="userName" class="input" required>
                    <?php while ($row = mysqli_fetch_array($result)): ?>
                        <option value="<?php echo htmlspecialchars($row['username']); ?>"><?php echo htmlspecialchars($row['username']); ?></option>
                    <?php endwhile; ?>
                </select>
                <button class="toolbutton active" name="addUser" type="submit"  style="font-size: medium;">Adicionar Usuário</button>
            </form>

            <br>
            <a href="chats.php?user=<?php echo urlencode($_SESSION['id']); ?>&group=<?php echo urlencode($group); ?>"><div style="font-size: large;" class="toolbutton">Voltar ao Chat</div></a>
        <?php } ?>

</body>
</html>