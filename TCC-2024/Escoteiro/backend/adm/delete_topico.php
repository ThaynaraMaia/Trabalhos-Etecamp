<?php
include 'db.php';
include '../classes/conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM topico WHERE id = ?");
    $stmt->execute([$id]);

    $sql_coment = "SELECT * FROM comentarios WHERE post_id =".$id;
    $res = $conn -> query($sql_coment);
    for($i = 0; $i < $res ->num_rows; $i++){
        $linha = $res -> fetch_assoc();
        $sql_delete = "DELETE FROM comentarios WHERE id=".$linha['id'];
        $res_delete = $conn -> query($sql_delete);
        $sql_resp = "SELECT * FROM respostas WHERE comentario_id=".$linha['id'];
        $res_resp = $conn -> query($sql_resp);
        for($i = 0; $i < $res ->num_rows; $i++){
            $linha = $res_resp -> fetch_assoc();
            $sql_delete_resp = "DELETE FROM resposta WHERE id=".$linha['id'];
            $res_delete_resp = $conn -> query($sql_delete_resp);
        }
    }
    // Excluir o evento

    echo "Evento excluído com sucesso!";
    header('Location: topicos_adm.php');
}