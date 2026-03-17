<?php
@include 'config.php'; // Incluir a conexão com o banco de dados
session_start();

// Verificar se a conexão com o banco de dados foi estabelecida
if (!$conn) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

// Excluir usuário
if (isset($_GET['delete'])) {
    $usuario_id = intval($_GET['delete']);  // Certificar que o ID é um número inteiro
    $sql = "DELETE FROM usuario_form WHERE usuario_id = $usuario_id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        header('Location: admin_alterar_usuario.php');  // Redirecionar após excluir
        exit();  // Parar o script após o redirecionamento
    } else {
        echo "Erro ao excluir o usuário: " . mysqli_error($conn);
    }
}

// Atualizar o tipo de usuário
if (isset($_POST['update'])) {
    $usuario_id = intval($_POST['usuario_id']);  // Garantir que o ID seja inteiro
    $tipo = intval($_POST['tipo']);  // Garantir que o tipo seja inteiro (0 ou 1)
    
    // Atualizar o tipo de usuário no banco de dados
    $sql = "UPDATE usuario_form SET tipo = $tipo WHERE usuario_id = $usuario_id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        header('Location: admin_alterar_usuario.php');  // Redirecionar após a atualização
        exit();  // Parar o script após o redirecionamento
    } else {
        echo "Erro ao atualizar o tipo de usuário: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
    <link rel="stylesheet" href="../css/admin_alterar_usuario.css">
    <link rel="shortcut icon" href="../img/logoxampp.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Gerencie os usuários do sistema abaixo:</h1>

        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>

            <?php
            // Obter usuários do banco de dados
            $sql = "SELECT * FROM usuario_form";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Mostra o tipo de usuário de forma legível
                    $tipo_usuario = $row['tipo'] == 1 ? 'Administrador' : 'Comum';
                    echo "<tr>";
                    echo "<td>".$row['usuario_id']."</td>";
                    echo "<td>".$row['nome']."</td>";
                    echo "<td>".$row['email']."</td>";
                    echo "<td>".$tipo_usuario."</td>";
                    echo "<td>
                        <a href='admin_alterar_usuario.php?edit=".$row['usuario_id']."' class='btn-edit'>
                            <i class='fas fa-pencil-alt'></i>
                        </a> | 
                        <a href='admin_alterar_usuario.php?delete=".$row['usuario_id']."' class='btn-delete' onclick=\"return confirm('Tem certeza que deseja excluir?');\">
                            <i class='fas fa-trash'></i>
                        </a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Nenhum usuário encontrado</td></tr>";
            }
            ?>
        </table>

        <?php
        // Exibir formulário de edição se o usuário clicar em 'Editar'
        if (isset($_GET['edit'])) {
            $id = intval($_GET['edit']);  // Certificar que o ID é inteiro
            $sql = "SELECT * FROM usuario_form WHERE usuario_id = $id";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                ?>
                <h3>Editar Tipo de Usuário</h3>
                <form method="POST" action="admin_alterar_usuario.php">
                    <input type="hidden" name="usuario_id" value="<?php echo $row['usuario_id']; ?>">
                    
                    <label for="tipo">Tipo de Usuário:</label>
                    <select name="tipo" required>
                        <option value="1" <?php if($row['tipo'] == 1) echo 'selected'; ?>>Administrador</option>
                        <option value="0" <?php if($row['tipo'] == 0) echo 'selected'; ?>>Comum</option>
                    </select>
                    
                    <button type="submit" name="update" class="btn-update">Atualizar Tipo</button>
                </form>
                <?php
            }
        }
        ?>

        <a href="../php/admin_page.php" class="btn-home">Voltar</a>

    </div>
</body>
</html>
