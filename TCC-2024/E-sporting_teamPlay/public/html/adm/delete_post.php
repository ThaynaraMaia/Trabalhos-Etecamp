<?php

include '../../../backend/classes/conn.php';

$id = $_GET['id'];
$sql_delete = "DELETE FROM posts WHERE id_post = ".$id;
$res = $conn -> query($sql_delete);

// Obter o nome do arquivo
// $stmt = $pdo->prepare("SELECT picture FROM user WHERE id = ?");
// $stmt->execute([$id]);
// $image = $stmt->fetch(PDO::FETCH_ASSOC);

// if ($image) {
    // $filename = $image['filename'];
    // $file_path = '../' . $filename;

    // // Excluir o arquivo do diretório
    // if (file_exists($file_path)) {
    //     unlink($file_path);
    // }

header('Location: adm.php?posts');