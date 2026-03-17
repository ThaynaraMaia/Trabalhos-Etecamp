<?php include('conexao.php'); ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tópicos - Estudo Interativo</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../img/logoxampp.png" type="image/x-icon">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e6e6e6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h1 {
            text-align: center;
            color: #273526;
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        .topic-list {
            list-style: none;
            padding: 0;
        }
        .topic-list li {
            background-color: #6D8777;
            margin-bottom: 15px;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s ease;
        }
        .topic-list li:hover {
            background-color: #45624E;
        }
        .topic-list li h2 {
            color: #fff;
            font-size: 1.5em;
        }
        .topic-list li a {
            color: #fff;
            text-decoration: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        .topic-list li i {
            color: #fff;
            font-size: 1.5em;
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
    <a href="../php/materias.php" class="back-btn"><i class="fas fa-arrow-left"></i> Voltar</a>

    <h1>Tópicos da Matéria</h1>

    <ul class="topic-list">
        <?php
        if (isset($_GET['id'])) {
            $id_materia = intval($_GET['id']);
            $sql = "SELECT * FROM topicos WHERE id_materia = $id_materia";
            $result = $conexao->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <li>
                        <a href='usuario_conteudos.php?id_topico=" . $row['id_topico'] . "'>
                            <h2>" . $row['nome'] . "</h2>
                            <i class='fas fa-arrow-right'></i>
                        </a>
                    </li>";
                }
            } else {
                echo "<p>Nenhum tópico cadastrado para esta matéria.</p>";
            }
        }
        ?>
    </ul>
</div>

</body>
</html>
