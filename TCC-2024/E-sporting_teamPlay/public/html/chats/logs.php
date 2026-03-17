<?php
$con = mysqli_connect('localhost', 'root', '');
mysqli_select_db($con, 'teamplay');

$group = $_GET['group'];

// Consulta para juntar as tabelas de chats e users
$query = mysqli_query($con, "
    SELECT c.message, u.username, u.nickname, u.picture
    FROM chats c
    JOIN users u ON c.user_id = u.id 
    WHERE c.group_id = '$group' 
    ORDER BY c.chat_id ASC
");

while ($row = mysqli_fetch_array($query)) {
    echo "<div class='msg'>";
    echo "<img src='../".$row['picture']."' alt='Usuário' style='height: 3vh; width: 3vh; border-radius: 2vw'></img> ";
    echo "<strong><span class='at'>@ </span>" . htmlspecialchars($row['nickname']) . ":</strong> " .
    "<p>".htmlspecialchars($row['message']);
    echo "</p></div>";
}
?>
