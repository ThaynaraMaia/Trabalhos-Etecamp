<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamPlay - Área do Administrador</title>
    <link rel="stylesheet" href="../../css/global.css">
    <link rel="stylesheet" href="../../css/styleTour.css">
    <link rel="stylesheet" href="../../css/styleADM.css">
    <link rel="shortcut icon" href="../../assets/Logo_Cor1.png" type="image/x-icon">
    <?php 
    include '../../../backend/classes/conn.php';
    $logged = false;
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);
    if (isset($_SESSION['username'])) {
        $logged = true;
    } 
    if($_SESSION['level'] != 2){
        header("Location: ../index.php");
    }
    
    if(isset($_GET['verifica'])){
                    if($_GET['verifica']==0){
                    $sql_verifica="UPDATE `users` SET `verified` = '1' WHERE `users`.`id` = ".$_GET['id'];
                    $res = $conn -> query($sql_verifica);
                    header ("Location: adm.php?users");
                    }
                    else{
                        $sql_verifica="UPDATE `users` SET `verified` = '0' WHERE `users`.`id` = ".$_GET['id'];
                        $res = $conn -> query($sql_verifica);
                    header ("Location: adm.php?users");
                    }
                }
    if(isset($_GET['type'])){
            $sql_status="UPDATE `users` SET `status` = '".$_GET['type']."' WHERE `users`.`id` = ".$_GET['id'];
            $res = $conn -> query($sql_status);
            header ("Location: adm.php?users"); 
        }    
    ?>
</head>


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
        <a href="adm.php?posts">
        <button class="toolbutton  <?php if(isset($_GET['posts'])){ echo 'active'; }?>" id="pghome"><h1>Postagens</h1></button></a>
        
        <a href="adm.php?torneios">
        <button class="toolbutton  <?php if(isset($_GET['torneios'])){ echo 'active'; }?>" id="pgtrn"><h1>Torneios</h1></button></a>    
        
        <a href="adm.php?users">
        <button class="toolbutton  <?php if(isset($_GET['users'])){ echo 'active'; }?>" id="pgfrn"><h1>Usuários</h1></button></a>
    </div>


    <div class="userarea">
        <?php if ($logged) { ?> 

        <div class="pfpimg" style="background-color: var(--mag-a);">
        <img src="../<?php echo $_SESSION['pfp']; ?>" alt="User">
             
        </div>
        <?php } ?>

        <a href="../<?php echo $logged ? 'user.php' : 'login.php'?>">
    <button class="toolbutton active" id="pghome"><h1><?php echo '<span class="at">@ </span><span>'.$_SESSION["nickname"].'</span>'?></h1></button></a> 
         

        
    </div>
</div>


<div class="page">
    <div class="con_adm">
        <h1>Painel de Controle
    <?php
        if(isset($_GET['users'])){
            ?> - Usuários</h1>
                <table class="tabela">       
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Nome de Usuário</th>
                    <th>Nickname</th>
                    <th>Aniversário</th>
                    <th>Reputação</th>
                    <th>Verificado</th>
                    <th>Status</th>
                    <th>Excluir</th>
                </tr>
                <?php 
                $sql_users = "SELECT * FROM users"; 
                $res = $conn -> query($sql_users);
                for($i = 0; $i < $res ->num_rows; $i++){
                $linha = $res -> fetch_assoc();
                if($linha['verified']==0){
                    $verificado= "<button class='toolbutton mini'>Sim</button>";
                    $verifica=0;
                } 
                else{
                    $verificado= "<button class='toolbutton mini'>Não</button>";
                    $verifica = 1;
                }
                $t1="value='1'";
                $t2="value='2'";
                $t3="value='3'";
                $t4="value='4'";

                if ($linha['picture'] == '' || $linha['picture'] == '-' || $linha['picture'] == ' ' || $linha['picture'] == '...' || $linha['picture'] == '../assets/profile_pics/') {
                    $pfp = '../assets/user.png';
                } else {
                    $pfp = $linha['picture'];
                }
                if ($linha['reputation'] == '') {
                    $linha['reputation'] = 0;
                    
                }

                if($linha['status']==1){
                    $t1="value='1' selected";      
                }
                if($linha['status']==2){
                    $t2="value='2' selected";      
                }
                if($linha['status']==3){
                    $t3="value='3' selected";      
                }
                echo "<tr class='center'><td style='background-color: var(--black);' class='center' style='width: 4vw'>".$linha['id']."</td>";
                echo "<td><img src='../".$pfp."' style='width: 6vw; height: auto' padding='0px' height='120px'></td>";
                echo "<td class='center' style='width: 10vw'><a href='../user.php?uid=".$linha['id']."'><span class='at'>@ </span>".$linha['username']."</a></td>";
                echo "<td class='center' style='width: 10vw'>".$linha['nickname']."</td>";
                echo "<td>".$linha['birthday']."</td>";
                echo "<td style='background-color: var(--shade1)'><img title=".$linha['reputation']." style='height: 4vh;' src='../../assets/icons/rating_".round($linha['reputation'], 0, PHP_ROUND_HALF_DOWN).".png' alt='Estrelas'></td>";
                echo "<td class='center'><a href='adm.php?id=".$linha['id']."&verifica=".$verifica."'>".$verificado."</a></td>";
                echo "
                <td class='center' style='width: 10vw'>
                <form type='post'><select id='menu1' name='type' value='3' required='true' class='sel'>
                <option ".$t1."><strong>Normal</strong></option>
                <option ".$t2."><strong>Mutado</strong></option>
                <option ".$t3."><strong>Banido</strong></option>
                </select><br><input class='toolbutton mini active' type='submit' name='id' value='".$linha['id']."'></form></td>";
                echo "<td style='background-color: var(--black)'><a href='delete_user.php?id=".$linha['id']."'>Remover</a></td>";
                
                }
                
                ?>
                </table>
            </div>
            <?php
        }
        else if(isset($_GET['posts'])){
            ?> - Postagens</h1>
            <table class="tabela">       
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Descrição</th>
                    <th>Excluir</th>
                </tr>
                <?php 
                $sql_users = "SELECT * FROM posts, users WHERE id = id_usuario"; 
                $res = $conn -> query($sql_users);
                for($i = 0; $i < $res ->num_rows; $i++){
                $linha = $res -> fetch_assoc();

                echo "<tr class='center'><td  style='background-color: var(--black)'>".$linha['id_post']."</td>";
                echo "<td>".$linha['titulo']."</td>";
                echo "<td><a href='../user.php?uid=".$linha['id_usuario']."'><span class='at'>@ </span>".$linha['username']."</a></td>";
                echo "<td style='max-width: 60vw; white-space: nowrap;'>".$linha['descricao']."</td>";
                echo "<td style='background-color: var(--black)'><a href='delete_post.php?id=".$linha['id_post']."'>Deletar</a></td>";
                }
                ?>
                </table>
            </div>
            <?php
        }
        else if(isset($_GET['torneios'])){
            ?> - Torneios</h1>
             <table class="tabela" style="width: 80vw">       
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Organizador</th>
                    <th>Data de Postagem</th>
                    <th>Descrição</th>
                    <th>Excluir</th>
                </tr>
                <?php 
                $sql_users = "SELECT tournaments.*, users.username FROM tournaments, users WHERE users.id = organizer ORDER BY date_creation DESC"; 
                $res = $conn -> query($sql_users);
                for($i = 0; $i < $res ->num_rows; $i++){
                $linha = $res -> fetch_assoc();

                echo "<tr class='center'><td style='background-color: var(--black)'>".$linha['id']."</td>";
                echo "<td style='max-width: 20vw;'>".$linha['title']."</td>";
                echo "<td style='width: 10vw;'><a href='../user.php?uid=".$linha['organizer']."'><span class='at'>@ </span>".$linha['username']."</a></td>";
                echo "<td style='width: 6vw;'>".$linha['date_creation']."</td>";
                echo "<td style='max-width: 50vw; white-space: nowrap;'>".$linha['description']."</td>";
                echo "<td style='background-color: var(--black)'><a href='delete_torneio.php?id=".$linha['id']."'>Deletar</a></td>";
                }
                ?>
                </table>
            </div>
            <?php
        }


    ?>

</body>
</html>