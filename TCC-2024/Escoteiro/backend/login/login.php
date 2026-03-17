<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../frontend/public/css/style1.css">
    <link rel="icon" href="../../frontend/public/imagens/recursos/logo.png">
    <title>Login</title>  
</head>

<body>
    <div class="log">
                <h1>Login</h1>
                <form class="mx-auto" action="verifica_login.php" method="POST">
                        <input type="text" placeholder="Insira seu email" name="email" class="inp" id="exampleInputNome" aria-describedby="NomeHelp"><br><br>
                        <input type="password" placeholder="Insira sua senha" id="senha" name="senha" class="inp" aria-describedby="SenhaInicioHelp"><br><br>
                        <input type="submit" name="Enviar" value="Enviar" class="btn"><br><br>
                        <a href="../../frontend/usuarios/inserir_usuario.php">
                            Não tem uma conta? Cadastre-se!
                        </a><br>
                        <a href="../../backend/login/indexredef.php">
                            Esqueceu sua senha?
                        </a>
                </form>
            <script>
                if(err==true){
                    alert('Email ou senha incorretos!')
                }
            </script>
        </div>
</body>
</html>