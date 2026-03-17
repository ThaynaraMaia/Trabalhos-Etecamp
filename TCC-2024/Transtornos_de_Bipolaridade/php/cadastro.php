<?php 
include_once '../conn/classes/class_IRepositorioUsuario.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/css/css.min.css">
    <link rel="stylesheet" href="../css/cadastro.css">
    <link rel="shortcut icon" href="../img/logoazul (1).png" type="image/x-icon">    
    <title>Equilibrio</title>

</head>
<body>

    <div class="container">
        <div class="form">
            <form action="../usuario/novoUsuario.php" method="POST">
                <div class="form-header">
                    <div class="title">
                        <h1>Cadastre-se</h1>
                    </div>
                    <div class="btn-cad">
                        <a href="login.php">Já tem um login?</a>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <label for="name">Nome</label>
                        <input id="name" type="text" name="nome" placeholder="Digite seu primeiro nome" required>
                    </div>

                    <div class="input-box">
                        <label for="email">E-mail</label>
                        <input id="email" type="email" name="email" placeholder="Digite seu email" required>
                    </div>

                    <div class="input-box">
                        <label for="senha">Senha</label>
                        <input id="senha" type="password" name="senha" placeholder="Digite sua senha" required>
                    </div>

                    
                    <div class="input-box">
                        <label for="confirmeSenha">Confirme sua senha</label>
                        <input id="confirmeSenha" type="password" name="confirmeSenha" placeholder="Confirme sua senha" required>
                    </div>
                </div>
                
                <div class="btn-cont">
                    <input type="submit" name="Enviar" value="Enviar">
                </div>

            </form>
        </div>
    </div>

</body>
</html>

