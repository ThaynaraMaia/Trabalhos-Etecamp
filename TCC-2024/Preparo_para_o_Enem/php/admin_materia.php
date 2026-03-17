<?php include('conexao.php'); ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerenciar Matérias</title>
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
        form {
            margin-bottom: 30px;
        }
        form input[type="text"] {
            width: 80%;
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #6D8777;
            border-radius: 5px;
        }
        form input[type="submit"] {
            padding: 10px 20px;
            background-color: #45624E;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        form input[type="submit"]:hover {
            background-color: #273526;
        }
        .message {
            padding: 10px;
            background-color: #C0CFB2;
            color: #273526;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 20px; /* Adicionado para garantir que os cards fiquem mais abaixo */
        }
        .card {
            background-color: #6D8777;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            text-align: center;
            transition: transform 0.2s ease-in-out;
        }
        .card h2 {
            color: #fff;
            font-size: 1.5em;
            margin-bottom: 10px;
        }
        .delete-btn, .edit-btn {
            margin-top: 10px;
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #45624E; /* Cor do botão de deletar agora é igual ao de editar */
        }
        .delete-btn:hover, .edit-btn:hover {
            background-color: #273526;
        }
        footer {
            text-align: center;
            margin-top: 30px;
            color: #273526;
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
    <h1>Gerenciar Matérias</h1>

    <!-- Formulário para adicionar uma nova matéria -->
    <form method="POST" action="">
        <input type="text" name="nome_materia" placeholder="Digite o nome da matéria" required>
        <input type="submit" name="adicionar" value="Adicionar Matéria">
    </form>

    <!-- Mensagens de feedback -->
    <?php
    // Adicionar uma matéria
    if (isset($_POST['adicionar'])) {
        $nome_materia = $_POST['nome_materia'];
        $sql = "INSERT INTO materias (nome) VALUES ('$nome_materia')";
        if ($conexao->query($sql) === TRUE) {
            echo "<div class='message'>Matéria adicionada com sucesso!</div>";
        } else {
            echo "<div class='message'>Erro: " . $sql . "<br>" . $conexao->error . "</div>";
        }
    }

    // Deletar uma matéria
    if (isset($_GET['delete'])) {
        $id_materia = $_GET['delete'];
        $sql = "DELETE FROM materias WHERE id_materia=$id_materia";
        if ($conexao->query($sql) === TRUE) {
            echo "<div class='message'>Matéria excluída com sucesso!</div>";
        } else {
            echo "<div class='message'>Erro ao excluir: " . $conexao->error . "</div>";
        }
    }
    ?>

    <div class="card-container">
        <?php
        // Exibir todas as matérias
        $sql = "SELECT * FROM materias";
        $result = $conexao->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "
                <div class='card'>
                    <h2>" . $row['nome'] . "</h2>
                    <a href='?delete=" . $row['id_materia'] . "' class='delete-btn'>Deletar</a>
                </div>";
            }
        } else {
            echo "<p>Nenhuma matéria cadastrada.</p>";
        }
        ?>
    </div>
</div>

<footer>
    <p>&copy; EnemPlatD 2024</p>
</footer>

</body>
</html>
