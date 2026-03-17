<?php
ob_start(); // Inicia o buffer de saída
error_reporting(E_ALL); // Reporta todos os erros
ini_set('display_errors', 1); // Exibe os erros
include('conexao.php'); ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerenciar Provas</title>
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
        form input[type="text"], form input[type="file"], form input[type="color"] {
            width: 80%;
            padding: 10px;
            margin-bottom: 10px;
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
            margin-top: 20px;
        }
        .card {
            background-color: #fff;
            border: 5px solid transparent;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            text-align: center;
            transition: transform 0.2s ease-in-out;
        }
        .card h2 {
            color: #273526;
            font-size: 1.5em;
            margin-bottom: 10px;
        }
        .delete-btn, .edit-btn, .view-btn {
            margin-top: 10px;
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #45624E;
        }
        .delete-btn:hover, .edit-btn:hover, .view-btn:hover {
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
    <h1>Gerenciar Provas</h1>

    <!-- Formulário para adicionar uma nova prova -->
    <form method="POST" enctype="multipart/form-data" action="">
        <input type="text" name="nome_prova" placeholder="Digite o nome da prova" required>
        <input type="text" name="caderno" placeholder="Digite o caderno da prova" required> <!-- Novo campo para o caderno -->
        <input type="file" name="arquivo_prova" required>
        <input type="file" name="arquivo_gabarito" required>
        <input type="color" name="cor_card" value="#6D8777"> <!-- Novo campo para a cor do card -->
        <input type="submit" name="adicionar" value="Adicionar Prova">
    </form>

    <!-- Mensagens de feedback -->
    <?php
    // Adicionar uma prova
    if (isset($_POST['adicionar'])) {
        $nome_prova = $_POST['nome_prova'];
        $caderno = $_POST['caderno']; // Capturando o caderno da prova
        $arquivo_prova = $_FILES['arquivo_prova']['name'];
        $arquivo_gabarito = $_FILES['arquivo_gabarito']['name'];
        $cor_card = $_POST['cor_card']; // Capturando a cor do card

        $destino_prova = "uploads/" . $arquivo_prova;
        $destino_gabarito = "uploads/" . $arquivo_gabarito;

        // Move os arquivos para o diretório de uploads
        if (move_uploaded_file($_FILES['arquivo_prova']['tmp_name'], $destino_prova) && 
            move_uploaded_file($_FILES['arquivo_gabarito']['tmp_name'], $destino_gabarito)) {
            
            $sql = "INSERT INTO provas (nome_prova, caderno, arquivo_prova, arquivo_gabarito, cor_card) VALUES ('$nome_prova', '$caderno', '$arquivo_prova', '$arquivo_gabarito', '$cor_card')";
            if ($conexao->query($sql) === TRUE) {
                echo "<div class='message'>Prova adicionada com sucesso!</div>";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "<div class='message'>Erro: " . $sql . "<br>" . $conexao->error . "</div>";
            }
        } else {
            echo "<div class='message'>Erro ao fazer upload dos arquivos.</div>";
        }
    }

    // Deletar uma prova
    if (isset($_GET['delete'])) {
        $id_prova = $_GET['delete'];
        $sql = "DELETE FROM provas WHERE id_prova=$id_prova";
        if ($conexao->query($sql) === TRUE) {
            echo "<div class='message'>Prova excluída com sucesso!</div>";
        } else {
            echo "<div class='message'>Erro ao excluir: " . $conexao->error . "</div>";
        }
    }

    // Editar uma prova
    if (isset($_POST['update'])) {
        $id_prova = intval($_POST['id_prova']);
        $nome_prova = $_POST['nome_prova'];
        $caderno = $_POST['caderno']; // Capturando o caderno da prova
        $cor_card = $_POST['cor_card'];
        
        $arquivo_prova = $_FILES['arquivo_prova']['name'] ? $_FILES['arquivo_prova']['name'] : $_POST['arquivo_prova_existente'];
        $arquivo_gabarito = $_FILES['arquivo_gabarito']['name'] ? $_FILES['arquivo_gabarito']['name'] : $_POST['arquivo_gabarito_existente'];

        if ($_FILES['arquivo_prova']['name']) {
            move_uploaded_file($_FILES['arquivo_prova']['tmp_name'], "uploads/" . $arquivo_prova);
        }
        if ($_FILES['arquivo_gabarito']['name']) {
            move_uploaded_file($_FILES['arquivo_gabarito']['tmp_name'], "uploads/" . $arquivo_gabarito);
        }

        $sql = "UPDATE provas SET nome_prova='$nome_prova', caderno='$caderno', arquivo_prova='$arquivo_prova', arquivo_gabarito='$arquivo_gabarito', cor_card='$cor_card' WHERE id_prova=$id_prova";
        
        if ($conexao->query($sql) === TRUE) {
            echo "<div class='message'>Prova atualizada com sucesso!</div>";
            // Remover o parâmetro de edição da URL
            header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
            exit();
        } else {
            echo "<div class='message'>Erro ao atualizar: " . $conexao->error . "</div>";
        }
    }
    ?>

    <div class="card-container">
        <?php
        // Buscar todas as provas
        $sql = "SELECT * FROM provas";
        $resultado = $conexao->query($sql);
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                echo "<div class='card' style='border-color: " . $row['cor_card'] . ";'>
                        <h2>" . $row['nome_prova'] . "</h2>
                        <p>Caderno: " . $row['caderno'] . "</p>
                        <a class='edit-btn' href='?edit=" . $row['id_prova'] . "'>Editar</a>
                        <a class='delete-btn' href='?delete=" . $row['id_prova'] . "'>Deletar</a>
                        <a class='view-btn' href='uploads/" . $row['arquivo_prova'] . "' target='_blank'>Visualizar Prova</a>
                        <a class='view-btn' href='uploads/" . $row['arquivo_gabarito'] . "' target='_blank'>Visualizar Gabarito</a>
                      </div>";
            }
        } else {
            echo "<div class='message'>Nenhuma prova encontrada.</div>";
        }
        ?>
    </div>

    <?php
    // Formulário para editar uma prova
    if (isset($_GET['edit'])) {
        $id_prova = $_GET['edit'];
        $sql = "SELECT * FROM provas WHERE id_prova=$id_prova";
        $resultado = $conexao->query($sql);
        $prova = $resultado->fetch_assoc();
        ?>
        <h2>Editar Prova</h2>
        <form method="POST" enctype="multipart/form-data" action="">
            <input type="hidden" name="id_prova" value="<?php echo $prova['id_prova']; ?>">
            <input type="text" name="nome_prova" value="<?php echo $prova['nome_prova']; ?>" required>
            <input type="text" name="caderno" value="<?php echo $prova['caderno']; ?>" required> <!-- Novo campo para o caderno -->
            <input type="file" name="arquivo_prova">
            <input type="hidden" name="arquivo_prova_existente" value="<?php echo $prova['arquivo_prova']; ?>">
            <input type="file" name="arquivo_gabarito">
            <input type="hidden" name="arquivo_gabarito_existente" value="<?php echo $prova['arquivo_gabarito']; ?>">
            <input type="color" name="cor_card" value="<?php echo $prova['cor_card']; ?>">
            <input type="submit" name="update" value="Atualizar Prova">
        </form>
        <?php
    }
    ?>
</div>


</body>
</html>
