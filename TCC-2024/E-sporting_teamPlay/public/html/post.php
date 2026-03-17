<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamPlay - Criar Postagem</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/stylePost.css">
    <link rel="shortcut icon" href="../assets/Logo_Cor1.png" type="image/x-icon">
    <?php 
    include '../../backend/classes/conn.php';
    include 'checkban.php';
    $logged = false;
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);
    if (isset($_SESSION['id'])) {
        $logged = true;
    } else {
        header('Location: welcome.html');
    }

    $st = $_SESSION['status'];

    if ($st == 2) {
        header('Location: warn.php');
        exit();
    }

    ?>
</head>


<body>
<div class="main">


<div class="content">
    
<div class="toolbar">
    <div class="logo">
        <a href="index.php">
            <img src="../assets/Logo_Full.png" style="width: 10vw;" alt="TeamPlay">
        </a>
    </div>    
    

    <div class="pages">
        <a href="index.php">
        <button class="toolbutton" id="pghome"><h1>Home</h1></button></a>
        
        <a href="tournaments.php">
        <button class="toolbutton" id="pgtrn"><h1>Torneios</h1></button></a>    
        
        <a href="friends.php">
        <button class="toolbutton" id="pgfrn"><h1>Usuários</h1></button></a>
    </div>


    <div class="userarea">
        <?php if ($logged) { ?> 
        <a href="post.php" style="position: absolute; right: 23vw">
            <button class="toolbutton active" id="pghome" style="width: 3vw"><h1 style="font-size: x-large;">+</h1></button></a> 
        <a href="chats/index.php" style="position: absolute; right: 19vw">
        <button class="toolbutton" id="pghome" style="width: 3vw"><img src="../assets/icons/chat.png" style="width: 2.2vw; filter: brightness(0);"></button></a> 

        <div class="pfpimg">
        <img src="<?php echo $_SESSION['pfp']; ?>" alt="User">
             
        </div>
        <?php } ?>

        <a href="user.php">
        <button class="toolbutton active" id="pghome"><h1><?php echo '<span class="at">@ </span><span>'.$_SESSION["nickname"].'</span>'?>
        </h1></button></a> 
         

        
        
    </div>
</div>



<form action="" method="post">
<div class="page">
<div class="con1">
    <div style="display: flex; gap: 1vw; justify-content:center; align-items: center;">
        <h1>Criar</h1>
        <select id="menu1" name="type" required="true" class="inp" style="background: var(--black); border: none;">
            <option value="post" style="background: var(--void); border: none;">Postagem</option>
            <option value="tour" style="background: var(--void); border: none;">Torneio</option>
        </select>
    </div><br><br>

    <div id="type">
    <div style="display: flex; gap: 1vw; justify-content:center; align-items: center;">
            <label for="title"><strong>Título</strong></label>
            <input type="text" id="title" name="title" required placeholder="Título da postagem" class="tinps"><br>

        </div><br>
            <label for="title"><strong>Jogo</strong></label><br>
            <select id="menu1" name="game" required="true" class="inp" style="background: var(--black); border: none; height: fit-content; font-size: medium; width: 20vw">
                    <option value="0">----</option>
                    <option value="1">Call of Duty: Warzone</option>
                    <option value="2">Overwatch 2</option>
                    <option value="3">Valorant</option>
                    <option value="4">Fortnite</option>
                    <option value="5">League of Legends</option>
                    <option value="6">EAFC24</option>
                </select><br><br>

        <label for="desc"><strong>Descrição</strong></label><br>
        <textarea id="desc" name="desc" required placeholder="Conteúdo"></textarea><br>

        
        <label for="image"><strong>Imagem</strong></label><br>
        <div id="imageDropArea" class="drop-area">
            <input type="file" id="image" name="image" accept="image/*">
            <br><br><span>Arraste a imagem aqui ou clique para selecionar</span>
            <img id="imagePreview" class="hidden" alt="Pré-visualização da Imagem">
        </div>
        <br><br>
    </div>

    
    <div style="display: flex; justify-content: center">
        <button type="submit" name="next" class="toolbutton active" style="font-size: larger; width: fit-content">Adicionar Post</button>
    </div>
</div>


<?php 
if (isset($_POST['next'])) {
$type = $_POST['type'];
$title = $_POST['title'];
$game = $_POST['game'];
$desc = $_POST['desc'];


    if ($title and $desc) {
        if ($type == 'tour') {
            $typeT = $_POST['typeTour'];
            $no = $_POST['number'];
            // $image = $_POST['image'];
            $dates = $_POST['dates'];
            $datee = $_POST['datee'];

            $sql_post = "INSERT INTO tournaments (title, organizer, description, date_start, date_end, date_creation,
            status, game, region, type, players) VALUES (
            '".$title."', '".$_SESSION['id']."', '".$desc."', '".$dates."', '".$datee."', '".date('Y-m-d')."', '0', '".$game."', '".$_SESSION['region']."', '".$typeT."', '0, ".$no."');";
            $res_post = $conn->query($sql_post);
        } else {
            $sql_post = "INSERT INTO posts (titulo, descricao, id_usuario, dat_criacao, id_jogo) VALUES (
            '".$title."', '".$desc."', '".$_SESSION['id']."', '".date('Y-m-d')."', '".$game."');";
            $res_post = $conn->query($sql_post);
            
        }
    } else {
        echo 'Preencha todos os campos para postar!';
    }
}
?>


</div>
</div>


</div>
</div>


</form>
<script src="../js/filtersPost.js"></script>
  

</body>
</html>