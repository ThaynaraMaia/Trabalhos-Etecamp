<?php 
include_once '../conn/classes/class_IRepositorioUsuario.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/css/css.min.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="shortcut icon" href="../img/logoazul (1).png" type="image/x-icon">
    <title>Equilibrio</title>

</head>
<body>

    <div class="container">
        <div class="form">
            <form action="../usuario/verificaLogin.php" method="POST">
                <div class="form-header">
                    <div class="title">
                        <h1>Faça seu login</h1>
                    </div>
                    <div class="btn-login">
                        <a href="cadastro.php">Cadastrar-se</a>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <label for="name">E-mail</label>
                        <input type="email" name="email" placeholder="Digite seu email " required>
                    </div>

                    <div class="input-box">
                        <label for="senha">Senha</label>
                        <input type="password" name="senha" placeholder="Digite sua senha" required>
                    </div>

                    <div class="btn-entrar">
                        <input value="Enviar" name="enviar" type="submit">
                    </div>    
            
                </div>

            </form>
        </div>
    </div>

</body>
</html>