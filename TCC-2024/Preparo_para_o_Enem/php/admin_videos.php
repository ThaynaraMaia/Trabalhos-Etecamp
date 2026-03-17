<?php

$conn = new mysqli('localhost', 'root', '', 'enemplatd_bd');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_conteudo = $_POST['id_conteudo'];
    $url_video = $_POST['url_video'];
    $sql = "INSERT INTO videos (id_conteudo, url_video) VALUES ('$id_conteudo', '$url_video')";
    $conn->query($sql);
}
?>

<form method="POST">
    <select name="id_conteudo" required>
        <?php
        $result = $conn->query("SELECT * FROM conteudos");
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['titulo']}</option>";
        }
        ?>
    </select>
    <input type="text" name="url_video" placeholder="URL do Vídeo" required>
    <button type="submit">Adicionar Vídeo</button>
</form>


<?php
if (isset($_POST['id_video'])) {
    $id_video = $_POST['id_video'];
    $sql = "DELETE FROM videos WHERE id = $id_video";
    $conn->query($sql);
}
?>

<form method="POST">
    <select name="id_video" required>
        <?php
        $result = $conn->query("SELECT * FROM videos");
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['url_video']}</option>";
        }
        ?>
    </select>
    <button type="submit">Excluir Vídeo</button>
</form>
