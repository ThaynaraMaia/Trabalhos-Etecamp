<?php
$con = mysqli_connect('localhost', 'root', '');
mysqli_select_db($con, 'teamplay');

if (isset($_GET['group']) && isset($_GET['userId']) && isset($_GET['creatorId'])) {
    $groupId = $_GET['group'];
    $userId = $_GET['userId'];
    $creatorId = $_GET['creatorId'];

    // Verifica se o criador está removendo outro usuário
    if ($userId !== $creatorId) {
        // Remove o usuário do grupo
        $deleteQuery = "DELETE FROM users_groups WHERE group_id = '$groupId' AND user_id = '$userId'";
        mysqli_query($con, $deleteQuery);

        // Obtenha o nome do usuário removido e do criador
        $userQuery = mysqli_query($con, "SELECT username FROM users WHERE id = '$userId'");
        $creatorQuery = mysqli_query($con, "SELECT username FROM users WHERE id = '$creatorId'");
        $userName = mysqli_fetch_assoc($userQuery)['username'];
        $creatorName = mysqli_fetch_assoc($creatorQuery)['username'];

        // Insira uma mensagem no chat informando que o usuário foi removido
        $message = "<span class='at'>@ </span>".$userName." foi removido do grupo por <span class='at'>@ </span>".$creatorName;
        mysqli_query($con, "INSERT INTO chats (group_id, user_id, message) VALUES ('$groupId', '$creatorId', '$message')");
    }

    // Redireciona de volta para o chat
    header("Location: chats.php?group=$groupId&user=" . $creatorId);
}
?>
