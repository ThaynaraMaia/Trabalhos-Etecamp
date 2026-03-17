<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JundTask - Login</title>
    <link rel="stylesheet" href="../css/styleLoginGeral.css">
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="shortcut icon" href="../img/logo@2x.png" type="image/x-icon">
</head>
<body>
    <header>
        <nav class="BarraNav">
            <img src="../img/JUNDTASK.png" alt="Logo JundTask">
            <a href="../html/home.php">Sair</a>
        </nav>
    </header>
    <main class="LoginGeral">   
      
        <div class="row me-0">
            <div class="col">
                <div class="tituloLogin">
                    <img src="../img/logo@2x.png" alt="Logo JundTask">
                    <h1>Escolha como Logar</h1>
                </div>
		<div class="BotaoLogin">
                <a href="./LoginTrabalhador.php">
                    <input type="submit" name="submit" value="Login Trabalhador">
                </div>
                <div class="BotaoLogin">
                <a href="./LoginUsuario.php">
                    <input type="submit" name="submit" value="Login Cliente">
                </div>
                <div class="BotaoCadastro">
                <a href="./home.php#cadastro" >
                    <input type="submit" name="submit" value="Fazer Cadastro">
                </div>
            </div>
    </main>
    <footer class="d-flex justify-content-center">
        <p>Terms of Service</p>
        <p>Privacy Policy</p>
        <p>@2022yanliudesign</p>
    </footer>    
    </body>
</html>