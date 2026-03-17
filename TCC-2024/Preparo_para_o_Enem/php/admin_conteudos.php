<?php
include('conexao.php');

// Adiciona novo conteúdo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adicionar_conteudo'])) {
    $titulo = mysqli_real_escape_string($conexao, $_POST['titulo']);
    $descricao = mysqli_real_escape_string($conexao, $_POST['descricao']);
    $id_topico = intval($_POST['id_topico']); // Certifique-se de que este campo está vindo corretamente do formulário

    // Inserir o conteúdo com o id_topico correto
    $sql = "INSERT INTO conteudos (titulo, descricao, id_topico) VALUES ('$titulo', '$descricao', $id_topico)";
    $conexao->query($sql);
}

// Excluir conteúdo
if (isset($_GET['excluir'])) {
    $id_conteudo = intval($_GET['excluir']);

    // Excluir os vídeos relacionados ao conteúdo
    $conexao->query("DELETE FROM videos WHERE id_conteudos = $id_conteudo");

    // Agora, exclua o conteúdo
    $conexao->query("DELETE FROM conteudos WHERE id_conteudos = $id_conteudo");
    header('Location: admin_conteudos.php');
}

// Adiciona novo vídeo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adicionar_video'])) {
    $url_video = mysqli_real_escape_string($conexao, $_POST['url_video']);
    $id_conteudo = intval($_POST['id_conteudo']);

    $sql = "INSERT INTO videos (url_videos, id_conteudos) VALUES ('$url_video', $id_conteudo)";
    $conexao->query($sql);
}

// Excluir vídeo
if (isset($_GET['excluir_video'])) {
    $id_video = intval($_GET['excluir_video']);
    $conexao->query("DELETE FROM videos WHERE id_videos = $id_video");
    header('Location: admin_conteudos.php');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/logoxampp.png" type="image/x-icon">
    
    <title>Admin - Gerenciar Conteúdos</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
         body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa; /* Cor de fundo neutra */
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            max-width: 800px;
            width: 100%;
            background: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        .container:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        h1, h2 {
            text-align: center;
            color: #45624E; /* Cor de texto */
        }
        form {
            margin-bottom: 30px;
          
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], textarea, select {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #c1c1c1;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus, textarea:focus, select:focus {
            border-color: #45624E; /* Cor de foco */
            outline: none;
        }
        input[type="submit"] {
            padding: 10px 15px;
            background-color: #45624E; /* Cor do botão */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            font-size: 1em;
        }
        input[type="submit"]:hover {
            background-color: #273526; /* Cor de hover do botão */
            transform: translateY(-2px);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #45624E; /* Cor do cabeçalho da tabela */
            color: white;
        }
        .btn-excluir {
            color: #e74c3c; /* Cor do link de excluir */
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        .btn-excluir:hover {
            color: #c0392b; /* Cor de hover do link de excluir */
        }
        footer {
            text-align: center;
            margin-top: 30px;
            color: #45624E; /* Cor do rodapé */
            font-weight: bold;
        }
        .back-btn {
            background-color: #45624E;
            padding: 10px 20px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .back-btn:hover {
            background-color: #273526;
        }
    </style>
</head>
<body>

<div class="container">
<a href="../php/admin_page.php" class="back-btn"><i class="fas fa-arrow-left"></i> Voltar</a>
    <h1>Gerenciar Conteúdos</h1>

    <h2>Adicionar Conteúdo</h2>
    <form method="POST">
        <input type="text" name="titulo" placeholder="Título do Conteúdo" required>
        <textarea name="descricao" placeholder="Descrição do Conteúdo" required></textarea>
        <select name="id_topico" required>
            <option value="">Selecione o Tópico</option>
            <?php
            $result_topicos = $conexao->query("SELECT * FROM topicos");
            while ($topico = $result_topicos->fetch_assoc()) {
                echo "<option value='" . $topico['id_topico'] . "'>" . $topico['nome'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" name="adicionar_conteudo" value="Adicionar Conteúdo">
    </form>

    <h2>Conteúdos Cadastrados</h2>
    <table>
        <tr><th>Título</th><th>Descrição</th><th>Ação</th></tr>
        <?php
        $result_conteudos = $conexao->query("SELECT * FROM conteudos");
        while ($conteudo = $result_conteudos->fetch_assoc()) {
            echo "<tr>
                    <td>" . $conteudo['titulo'] . "</td>
                    <td>" . $conteudo['descricao'] . "</td>
                    <td>
                        <a href='admin_conteudos.php?excluir=" . $conteudo['id_conteudos'] . "' class='btn-excluir'>Excluir</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>

    <h2>Adicionar Vídeo</h2>
    <form method="POST">
        <input type="text" name="url_video" placeholder="URL do Vídeo" required>
        <select name="id_conteudo" required>
            <option value="">Selecione o Conteúdo</option>
            <?php
            $result_conteudos = $conexao->query("SELECT * FROM conteudos");
            while ($conteudo = $result_conteudos->fetch_assoc()) {
                echo "<option value='" . $conteudo['id_conteudos'] . "'>" . $conteudo['titulo'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" name="adicionar_video" value="Adicionar Vídeo">
    </form>

    <h2>Vídeos Cadastrados</h2>
    <table>
        <tr><th>URL do Vídeo</th><th>Conteúdo Relacionado</th><th>Ação</th></tr>
        <?php
        $sql_videos = "SELECT v.*, c.titulo FROM videos v JOIN conteudos c ON v.id_conteudos = c.id_conteudos";
        $result_videos = $conexao->query($sql_videos);
        while ($video = $result_videos->fetch_assoc()) {
            echo "<tr>
                    <td>" . $video['url_videos'] . "</td>
                    <td>" . $video['titulo'] . "</td>
                    <td>
                        <a href='admin_conteudos.php?excluir_video=" . $video['id_videos'] . "' class='btn-excluir'>Excluir</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>

</div>

<footer>
    <p>&copy; EnemPlatD 2024</p>
</footer>

</body>
</html>
