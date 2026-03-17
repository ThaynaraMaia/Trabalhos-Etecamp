<?php include('conexao.php'); ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matérias - Estudo Interativo</title>
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
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
        }
        .card {
            background-color: #6D8777;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            text-align: center;
            transition: transform 0.2s ease-in-out;
            cursor: pointer;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }
        .card h2 {
            color: #fff;
            font-size: 1.5em;
            margin-bottom: 10px;
        }
        .card-icon {
            font-size: 2.5em;
            color: #C0CFB2;
            margin-bottom: 15px;
        }
        .card a {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10;
            text-decoration: none;
            color: inherit;
        }
        .card-description {
            color: #C0CFB2;
            font-size: 1.1em;
        }
        .header-image {
            width: 100%;
            height: 150px;
            background: url('https://www.example.com/image.jpg') center/cover no-repeat;
            border-radius: 10px 10px 0 0;
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
        footer {
            text-align: center;
            margin-top: 30px;
            color: #273526;
        }
    </style>
</head>
<body>

<div class="container">
<a href="../php/usuario_page.php" class="back-btn"><i class="fas fa-arrow-left"></i> Voltar</a>
    <h1>Escolha sua Matéria</h1>

    <div class="card-container">
        <?php
        $sql = "SELECT * FROM materias";
        $result = $conexao->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "
                <div class='card'>
                    <a href='usuario_topicos.php?id=" . $row['id_materia'] . "'></a>
                    <i class='fas fa-book card-icon'></i>
                    <h2>" . $row['nome'] . "</h2>
                    <p class='card-description'>Explore tópicos de " . $row['nome'] . "</p>
                </div>";
            }
        } else {
            echo "<p>Nenhuma matéria cadastrada.</p>";
        }
        ?>
    </div>
</div>

<footer>
    <p>&copy; EnemPlatD</p>
</footer>

</body>
</html>
