<?php
$con = mysqli_connect('localhost', 'root', '');
mysqli_select_db($con, 'teamplay');

if (isset($_GET['group']) && isset($_GET['userId'])) {
    $groupId = $_GET['group'];
    $userId = $_GET['userId'];

    // Consulta para obter o nome do usuário usando o userId
    $userQuery = mysqli_query($con, "SELECT username FROM users WHERE id = '$userId'");
    $userName = '';
    if ($userRow = mysqli_fetch_array($userQuery)) {
        $userName = $userRow['username'];
    }

    // Remove o usuário do grupo
    $deleteQuery = "DELETE FROM users_groups WHERE group_id = '$groupId' AND user_id = '$userId'";
    mysqli_query($con, $deleteQuery);

    // Registrar uma mensagem informando que o usuário saiu do grupo
    $message = "$userName deixou o grupo";
    mysqli_query($con, "INSERT INTO chats (group_id, user_id, message) VALUES ('$groupId', '$userId', '$message')");

    // Redireciona para a página de grupos do usuário, usando o nome em vez do ID
    header("Location: groups.php?name=" . urlencode($userName)); // Passando o nome do usuário para a página de grupos
    exit(); // Encerra o script após o redirecionamento
}

// Fecha a conexão
mysqli_close($con);
?>
