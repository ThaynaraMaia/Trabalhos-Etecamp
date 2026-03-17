<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/style1.css">
    <link rel="icon" href="../public/imagens/recursos/logo.png">

    <title>Cadastro</title>
</head>

<body>
    <div class="container-fluid cor_fundo">
        <div class="log">
            <h1> Cadastro</h1>
            
    <form action="novo_usuario.php" method="POST" enctype="multipart/form-data">
        
        <input type="text" name="nome" placeholder="Insira seu nome" class="inp"><br><br>
        <input type="text" name="email" placeholder="Insira seu email" class="inp"><br><br>
        <input type="password" name="senha" placeholder="Insira sua senha" class="inp"><br><br>
        <input type="submit" name="Enviar" value="Enviar" class="btn"><br><br>
        <a href="../../backend/login/login.php">Já tem uma conta? faça login!</a><br><br>
        

        </form>
    </div>
    </div>
</body>

</html>