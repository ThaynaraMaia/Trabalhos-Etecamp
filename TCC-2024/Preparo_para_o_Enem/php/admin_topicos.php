<?php
include('conexao.php');

// Adiciona um novo tópico
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adicionar'])) {
    $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
    $id_materia = intval($_POST['id_materia']);

    $sql = "INSERT INTO topicos (nome, id_materia) VALUES ('$nome', $id_materia)";
    if ($conexao->query($sql) === TRUE) {
        $mensagem = "Tópico adicionado com sucesso!";
    } else {
        $mensagem = "Erro ao adicionar tópico: " . $conexao->error;
    }
}

// Excluir um tópico
if (isset($_GET['excluir'])) {
    $id_topico = intval($_GET['excluir']);
    $sql = "DELETE FROM topicos WHERE id_topico = $id_topico";
    $conexao->query($sql);
    header("Location: admin_topicos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerenciar Tópicos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../img/logoxampp.png" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e6e6e6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
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
            margin-bottom: 30px;
        }
        form {
            margin-bottom: 30px;
        }
        input[type="text"], select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1.1em;
        }
        input[type="submit"] {
            padding: 12px 15px;
            background-color: #45624E;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 1.1em;
        }
        input[type="submit"]:hover {
            background-color: #273526;
        }
        .mensagem {
            text-align: center;
            font-size: 1.1em;
            margin-bottom: 20px;
            color: green;
        }
        .topico {
            border: 1px solid #ddd;
            padding: 12px;
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        .excluir {
            color: red;
            cursor: pointer;
            text-decoration: none;
            font-size: 1.1em;
            transition: color 0.3s ease;
        }
        .excluir:hover {
            color: darkred;
        }
        .icon-header {
            color: #6D8777;
            font-size: 1.5em;
            margin-right: 10px;
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
    <h1><i class="fas fa-tags icon-header"></i>Gerenciar Tópicos</h1>

    <?php if (isset($mensagem)) echo "<p class='mensagem'>$mensagem</p>"; ?>

    <form method="post">
        <input type="text" name="nome" placeholder="Nome do Tópico" required>
        <select name="id_materia" required>
            <option value="">Selecione a Matéria</option>
            <?php
            // Lista as matérias
            $sql_materias = "SELECT * FROM materias";
            $result_materias = $conexao->query($sql_materias);
            while ($materia = $result_materias->fetch_assoc()) {
                echo "<option value='" . $materia['id_materia'] . "'>" . $materia['nome'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" name="adicionar" value="Adicionar Tópico">
    </form>

    <h2>Tópicos Cadastrados</h2>
    <?php
    // Lista os tópicos
    $sql_topicos = "SELECT * FROM topicos";
    $result_topicos = $conexao->query($sql_topicos);

    if ($result_topicos->num_rows > 0) {
        while ($topico = $result_topicos->fetch_assoc()) {
            echo "
            <div class='topico'>
                <span>" . $topico['nome'] . "</span>
                <a href='admin_topicos.php?excluir=" . $topico['id_topico'] . "' class='excluir'><i class='fas fa-trash-alt'></i> Excluir</a>
            </div>";
        }
    } else {
        echo "<p>Nenhum tópico cadastrado.</p>";
    }
    ?>
</div>

</body>
</html>
