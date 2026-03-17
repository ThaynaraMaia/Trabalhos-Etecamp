<?php include('conexao.php'); ?> 

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuário - Minhas Provas</title>
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
        .back-btn {
            background-color: #45624E;
            padding: 10px 20px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
            text-align: center;
        }
        .back-btn:hover {
            background-color: #273526;
        }
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 20px;
        }
        .card {
            background-color: transparent; /* Fundo transparente */
            border: 3px solid; /* Borda com espessura */
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            text-align: center;
            transition: transform 0.2s ease-in-out;
        }
        .card h2 {
            color: #273526; /* Cor do texto do título */
            font-size: 1.5em;
            margin-bottom: 10px;
        }
        .view-btn {
            margin-top: 10px;
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #45624E;
        }
        .view-btn:hover {
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
    <h1>Provas Anteriores</h1>

    <div class="card-container">
        <?php
        // Exibir todas as provas
        $sql = "SELECT * FROM provas";
        $result = $conexao->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Use a cor do card da tabela
                $cor_card = $row['cor_card'];
                echo "
                <div class='card' style='border-color: $cor_card;'> <!-- Aplicar cor à borda -->
                    <h2>" . $row['nome_prova'] . "</h2>
                    <p>Caderno: " . $row['caderno'] . "</p> <!-- Exibir o caderno -->
                    <a href='uploads/" . $row['arquivo_prova'] . "' target='_blank' class='view-btn'>Ver Prova</a>
                    <a href='uploads/" . $row['arquivo_gabarito'] . "' target='_blank' class='view-btn'>Ver Gabarito</a>
                </div>";
            }
        } else {
            echo "<p>Nenhuma prova cadastrada.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>
