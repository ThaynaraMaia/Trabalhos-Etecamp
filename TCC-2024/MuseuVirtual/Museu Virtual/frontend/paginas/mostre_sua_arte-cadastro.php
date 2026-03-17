<!DOCTYPE html>
<html lang="pt-br">

<?php
    session_start();
    // $mensagem = $_SESSION['mensagem'];

    if(isset($_SESSION['mensagemCadastro'])){
        $mensagemCadastro = $_SESSION['mensagemCadastro'];
    } else {
        $mensagemCadastro = "";
    }
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/mostre_sua_arte-cadastro.css">
    <link rel="stylesheet" href="../bootstrap/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="../js/mostre_sua_arte-cadastro.js"></script>
    <title>Cadastro - Mostre sua arte</title>
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
            <a href="mostre_sua_arte-login.php">MOSTRE SUA ARTE</a>
            <a href="sobre.php">SOBRE</a>
         </div>  
        </div>

    </header>

    <container>

        <!-- Formulário em que o usuário fará cadastro -->
        <form action="../../backend/usuarios/cadastroUsuario.php" method="post" enctype="multipart/form-data"  onsubmit="return verificaSenha()">

            <h1>Cadastrar-se</h1>

            <div class="mb-3">
                <label for="InputNome" class="form-label">Nome</label>
                <input type="text" name = "nome" class="form-control" id="InputNome" aria-describedby="nameHelp" placeholder="Digite seu nome" required>
            </div>

            <div class="mb-3">
                <label for="InputEmail" class="form-label">Email</label>
                <input type="email" name = "email" class="form-control" id="InputEmail" aria-describedby="emailHelp" placeholder="Digite seu email" required>
            </div>

            <div class="mb-3">
                <label for="InputPassword" class="form-label">Senha</label>
                <input type="password" name = "senha" class="form-control" id="senha" placeholder="Digite sua senha" required>
            </div>

            <div class="mb-3">
                <label for="InputConfirmPassword" class="form-label">Confirme a senha</label>
                <input type="password" name = "confirmaSenha" class="form-control" id="confirmaSenha" placeholder="Digite novamente sua senha" required>
            </div>
            
            <div class="mb-3">
                <label for="InputFile" class="form-label">Foto</label>
                <input type="file" name = "foto" class="form-control" id="InputFile" aria-describedby="fileHelp" required>
            </div>

            <!-- <div class="opcoesUser">
                <label for="typeUser">Tipo de usuário: </label>
                <select name="typeUser" id="typeUser">
                    <option value="">Selecione</option>
                    <option value="aluno">Aluno</option>
                    <option value="outroUsuario">Outro</option>
                </select>
            </div> -->
            <span><?php echo $mensagemCadastro;?></span>

            <div class="button">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </div>

            <p>Já tem uma conta? <a href="mostre_sua_arte-login.php">Faça login</a></p>
            
        </form>
        <script src="../paginas/Js/verificaSenha.js"></script>
        
    </container>
</body>
</html>