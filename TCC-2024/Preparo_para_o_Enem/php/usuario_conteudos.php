<?php
include('conexao.php');

// Verifica se o ID do tópico foi passado via GET
if (isset($_GET['id_topico'])) {
    $id_topico = intval($_GET['id_topico']);

    // Busca os conteúdos relacionados ao tópico
    $sql_conteudos = "SELECT * FROM conteudos WHERE id_topico = $id_topico";
    $result_conteudos = $conexao->query($sql_conteudos);

    // Busca os vídeos relacionados aos conteúdos
    $sql_videos = "SELECT v.url_videos, c.titulo FROM videos v 
                   JOIN conteudos c ON v.id_conteudos = c.id_conteudos 
                   WHERE c.id_topico = $id_topico";
    $result_videos = $conexao->query($sql_videos);
} else {
    echo "Tópico não selecionado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/logoxampp.png" type="image/x-icon">
    <title>Conteúdos e Vídeos</title>
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
            max-width: 1000px;
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
        .conteudo {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #eafaf1; /* Fundo claro */
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .conteudo:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .conteudo h3 {
            margin-top: 0;
            color: #45624E; /* Cor do título */
        }
        .video {
            margin-bottom: 20px;
        }
        iframe {
            width: 100%;
            height: 400px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
<a href="../php/usuario_topicos.php?id=20.php" class="back-btn"><i class="fas fa-arrow-left"></i> Voltar</a>
    <h1>Conteúdos e Vídeos</h1>

    <!-- Exibe os conteúdos relacionados ao tópico -->
    <h2>Conteúdos</h2>
    <?php
    if ($result_conteudos->num_rows > 0) {
        while ($conteudo = $result_conteudos->fetch_assoc()) {
            echo "<div class='conteudo'>
                    <h3>" . htmlspecialchars($conteudo['titulo']) . "</h3>
                    <p>" . htmlspecialchars($conteudo['descricao']) . "</p>
                  </div>";
        }
    } else {
        echo "<p>Nenhum conteúdo disponível para este tópico.</p>";
    }
    ?>

    <!-- Exibe os vídeos relacionados ao tópico -->
    <h2>Vídeos</h2>
    <?php
    if ($result_videos && $result_videos->num_rows > 0) {
        while ($video = $result_videos->fetch_assoc()) {
            // Extraindo o VIDEO_ID da URL do YouTube
            parse_str(parse_url($video['url_videos'], PHP_URL_QUERY), $video_id_array);
            $video_id = $video_id_array['v'] ?? '';

            // Exibir vídeo se o ID for encontrado
            if ($video_id) {
                echo "<div class='video'>
                        <h3>" . htmlspecialchars($video['titulo']) . "</h3>
                        <iframe src='https://www.youtube.com/embed/" . htmlspecialchars($video_id) . "' allowfullscreen></iframe>
                      </div>";
            } else {
                echo "<p>URL inválida para o vídeo: " . htmlspecialchars($video['url_videos']) . "</p>";
            }
        }
    } else {
        echo "<p>Nenhum vídeo disponível para este conteúdo.</p>";
    }
    ?>
</div>

<footer>
</footer>

</body>
</html>
