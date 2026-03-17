<?php
include '../../backend/adm/db.php';
session_start();
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM topico WHERE id = ?");
    $stmt->execute([$id]);

  
    $destino = 'Location: user.php?id='.$_SESSION["userid"];
    echo "Evento excluído com sucesso!<br>";
    echo $destino;
    header($destino);
}