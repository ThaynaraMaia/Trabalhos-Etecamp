<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nova_senha = password_hash($_POST['nova_senha'], PASSWORD_BCRYPT);
    
    // Conexão com o banco de dados
    $conn = new mysqli('localhost', 'root', '', 'enemplatd_bd');
    
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Aqui você pode obter o ID do usuário de uma sessão
    $id_usuario = 0;  // Exemplo: ID do usuário

    $sql = "UPDATE users SET senha='$nova_senha' WHERE id=$id_usuario";

    if ($conn->query($sql) === TRUE) {
        echo "Senha alterada com sucesso!";
    } else {
        echo "Erro ao alterar a senha: " . $conn->error;
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="change_password.php" method="POST">
    <label for="nova_senha">Nova Senha:</label>
    <input type="password" id="nova_senha" name="nova_senha" required>

    <label for="repita_senha">Repita a Senha:</label>
    <input type="password" id="repita_senha" name="repita_senha" required>

    <button type="submit">Alterar Senha</button>
</form>

</body>
</html>
