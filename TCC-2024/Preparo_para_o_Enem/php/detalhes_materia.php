<?php include('conexao.php'); ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Matéria</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="../php/materias.php" class="back-btn">Voltar</a>

    <?php
    if (isset($_GET['id'])) {
        $id_materia = intval($_GET['id']); // Captura o ID da matéria da URL

        // Busca os detalhes da matéria pelo ID
        $sql = "SELECT * FROM materias WHERE id_materia = $id_materia";
        $result = $conexao->query($sql);

        if ($result->num_rows > 0) {
            $materia = $result->fetch_assoc();
            echo "<h1>" . $materia['nome'] . "</h1>";
        } else {
            echo "<p>Matéria não encontrada.</p>";
        }
    } else {
        echo "<p>ID da matéria não fornecido.</p>";
    }
    ?>

</div>

</body>
</html>
