<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/mostre_sua_arte-login.css">
    <link rel="stylesheet" href="../bootstrap/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Museu Virtual - Login</title>
</head>
<body style="background-color: lightgoldenrodyellow;">
   
    <header>
        <div class="col-md-2">
            <div class="logo">
                <img src="../img/Logo - ETECAMP CV.png">
            </div>
        </div>

        <div class="col-md-9">
         <div class="navbar">
            <a href="inicio.php">INÍCIO</a>
            <a href="exposições.php">EXPOSIÇÕES</a>
            <a href="">MOSTRE SUA ARTE</a>
            <a href="sobre.php">SOBRE</a>
         </div>  
        </div>
        
        <?php
            // session_start();

            // if(isset($_SESSION['nome'])){
            //  echo "<div class=\"col-md-1\">";
            //   echo "<div class=\"foto-perfil\">";
            //     echo "<a href=\"perfil.php\"><img src=\"../img/icon-perfil-cinza.png\" alt=\"Foto de perfil do usuário\"></a>";
            //   echo "</div>";
            //  echo "</div>";
            // }
        ?>

    </header>

    <container>

        <!-- Formulário em que o usuário fará login -->
        <form action="../../backend/usuarios/loginUsuario.php" method="post">

            <h1>Fazer login</h1>

            <div class="mb-3">
                <label for="InputEmail" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="InputEmail" aria-describedby="emailHelp" placeholder="Digite seu email">
            </div>

            <div class="mb-3">
                <label for="InputPassword" class="form-label">Senha</label>
                <input type="password" name="senha" class="form-control" id="InputPassword" placeholder="Digite sua senha">
            </div>

            <div class="button">
                <button type="submit" class="btn btn-primary">Entrar</button>
            </div>
            
            <p>Ainda não tem uma conta? <a href="mostre_sua_arte-cadastro.php">Cadastre-se</a></p>
        </form>

    </container>
</body>
</html>