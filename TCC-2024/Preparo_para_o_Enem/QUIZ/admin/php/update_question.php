<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_id = $_POST['question_id'];
    $question_number = $_POST['question_number'];
    $question_text = $_POST['question']; // Aqui pegamos o texto do campo oculto
    $delete_image = isset($_POST['delete_image']) ? $_POST['delete_image'] : 0;

    // Verifica se o campo "photo" foi preenchido
    if (!empty($_FILES['photo']['name'])) {
        $photo = $_FILES['photo']['name'];
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($photo);

        // Move a nova imagem para o diretório
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            // Atualizar a questão com a nova imagem
            $stmt = $pdo->prepare('UPDATE questions SET question_number = ?, question_text = ?, photo = ? WHERE id = ?');
            $stmt->execute([$question_number, $question_text, $photo, $question_id]);
        }
    } else {
        // Se a imagem não foi atualizada, apenas atualiza o texto
        $stmt = $pdo->prepare('UPDATE questions SET question_number = ?, question_text = ? WHERE id = ?');
        $stmt->execute([$question_number, $question_text, $question_id]);
    }

    // Verificar se a imagem atual deve ser excluída
    if ($delete_image) {
        // Remove a imagem do diretório
        $stmt = $pdo->prepare('SELECT photo FROM questions WHERE id = ?');
        $stmt->execute([$question_id]);
        $photo = $stmt->fetchColumn();

        if ($photo && file_exists("../uploads/" . $photo)) {
            unlink("../uploads/" . $photo);
        }

        // Atualiza o campo "photo" no banco de dados
        $stmt = $pdo->prepare('UPDATE questions SET photo = NULL WHERE id = ?');
        $stmt->execute([$question_id]);
    }

    // Atualizar alternativas
    for ($i = 1; $i <= 5; $i++) {
        if (isset($_POST["alternative_id_$i"])) {
            $alternative_text = $_POST["alternative_$i"];
            $is_correct = isset($_POST["correct_$i"]) ? 1 : 0;
            $alternative_id = $_POST["alternative_id_$i"];

            $stmt = $pdo->prepare('UPDATE alternatives SET alternative_text = ?, is_correct = ? WHERE id = ?');
            $stmt->execute([$alternative_text, $is_correct, $alternative_id]);
        }
    }

    header('Location: ../index.php');
    exit();
}
?>
