<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>/ <?php $_GET['group'] ?> - TeamPlay</title>
	<link rel="stylesheet" href="../../css/global.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/styleChat.css">
	<link rel="shortcut icon" href="../../assets/Logo_Cor1.png" type="image/x-icon">
    
<?php 

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

// Verifica se os parâmetros 'user' e 'group' estão presentes
if (!isset($_GET['user']) || !isset($_GET['group'])) {
    echo "Erro: parâmetros 'user' ou 'group' não foram fornecidos.";
    exit();
}

$user = $_GET['user'];
$group = $_GET['group'];
$isdm = false;
if (isset($_GET['isdm'])) {
    $isdm = true;
}

// Verifica se o usuário ainda está no grupo
$userCheckQuery = mysqli_query($con, "SELECT * FROM users_groups WHERE user_id = '$user' AND group_id = '$group'");
if (mysqli_num_rows($userCheckQuery) == 0) {
    echo "Erro: Você foi removido deste grupo e não pode mais interagir.";
    exit();
}

// Atualiza o número de chats lidos
$sql = mysqli_query($con, "SELECT total_chats FROM groups WHERE group_id = '".$group."'");
while ($row = mysqli_fetch_array($sql)) {
    $total = $row['total_chats'];
    mysqli_query($con, "UPDATE users_groups SET read_chats = $total WHERE user_id = '$user' AND group_id = '".$group."'");
}

// Consulta para obter o nome do grupo
$groupQuery = mysqli_query($con, "SELECT group_name FROM groups WHERE group_id = '$group'");
$groupName = '';
if ($groupRow = mysqli_fetch_array($groupQuery)) {
    $groupName = $groupRow['group_name'];
}

// Consulta para obter os membros do grupo e o criador
$queryMembers = mysqli_query($con, "SELECT u.username, u.id, g.creator_id 
    FROM users_groups ug 
    JOIN users u ON ug.user_id = u.id 
    JOIN groups g ON ug.group_id = g.group_id 
    WHERE ug.group_id = '$group'");

// Armazenar os nomes dos membros e o criador do grupo
$members = [];
$creatorId = '';

// Obtendo o ID do criador
$creatorQuery = mysqli_query($con, "SELECT creator_id FROM groups WHERE group_id = '$group'");
if ($creatorRow = mysqli_fetch_array($creatorQuery)) {
    $creatorId = $creatorRow['creator_id'];
}

// Armazenar membros
while ($row = mysqli_fetch_array($queryMembers)) {
    $members[] = [
        'name' => $row['username'],
        'id' => $row['id']
    ];
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

        <a href="<?php echo $logged ? '../user.php' : '../login.php'?>">
            <button class="toolbutton active" id="pghome"><h1><?php echo '<span class="at">@ </span><span>'.$_SESSION["nickname"].'</span>'?></h1></button></a> 
                 
        
    </div>
</div>



<div class="page">
<div class="con1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript">
        function confirmAction(action, userName, userId) {
            var message = action === 'remove' ? 'remover' : 'sair do grupo';
            if (confirm("Você realmente deseja " + message + " " + userName + "?")) {
                if (action === 'remove') {
                    window.location.href = 'removeMember.php?group=<?php echo $group; ?>&userId=' + userId + '&creatorId=<?php echo $creatorId; ?>';
                } else {
                    window.location.href = 'leaveGroup.php?group=<?php echo $group; ?>&userId=<?php echo $user; ?>';
                }
            }
        }

        function chat() {
            var message = chatForm.message.value;
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById('chatlogs').innerHTML = xmlhttp.responseText;
                }
            }

            xmlhttp.open('GET', 'insertChat.php?message=' + encodeURIComponent(message) + '&user=<?php echo $user; ?>&group=<?php echo $group; ?>', true);
            xmlhttp.send();
        }

        $(document).ready(function(e) {
            $.ajax({cache: false});
            setInterval(function() {
                $('#chatlogs').load('logs.php?group=<?php echo $group; ?>');
            }, 200);
        });
    </script>

<h2><span class="at"># </span><?php echo htmlspecialchars($groupName); ?></h2>
<h3>Membros</h3>
<ul>
    <?php foreach ($members as $member): ?>
        <a href="../user.php?uid=<?php echo htmlspecialchars($member['id']); ?>">
            <li class="<?php echo $member['id'] === $creatorId ? 'creator' : ''; ?>">
                <?php echo htmlspecialchars($member['name']); ?>
                <!-- Verificar se o usuário é o criador -->
                <?php if ($user === $creatorId): ?>
                    <!-- O criador pode remover outros membros -->
                    <?php if ($member['id'] !== $creatorId): ?>
                        <button class="toolbutton active mini" onclick="confirmAction('remove', '<?php echo htmlspecialchars($member['name']); ?>', '<?php echo $member['id']; ?>')">Remover</button>
                    <?php else: ?>
                        <!-- O criador vê a opção de sair apenas para ele -->
                        <button class="toolbutton active mini" onclick="confirmAction('leave', '<?php echo htmlspecialchars($member['name']); ?>')">Sair</button>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Membro comum pode apenas sair do grupo -->
                    <?php if ($member['id'] === $user): ?>
                        <button class="toolbutton active mini" onclick="confirmAction('leave', '<?php echo htmlspecialchars($member['name']); ?>')">Sair</button>
                    <?php endif; ?>
                <?php endif; ?>
            </li>
        </a>
    <?php endforeach; ?>
</ul>

<!-- Botão para redirecionar para a página de adicionar novos membros, visível apenas para o criador -->

    <?php if(!$isdm){ ?>
    <button class="toolbutton active" style="font-size: larger;" onclick="window.location.href='addMembers.php?group=<?php echo $group; ?>&creatorId=<?php echo $creatorId; ?>'">Adicionar Membro</button><br><br>
    <?php }; ?>

<div id="chatlogs" class="chatlogs">
    Carregando conteúdo dos chats. . . 
</div><br>


<form action="" method="POST" name="chatForm">
    <label for="message">Digite uma mensagem:</label>
    <input type="text" name="message" required>
    <button class="toolbutton active" style="font-size: large;" name="sendMessage" type="button" onclick="chat()">Enviar</button>
</form>


</div>

</div>
</div>

</body>
</html>


