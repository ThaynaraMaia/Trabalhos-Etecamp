<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../../backend/scripts/pesquisa.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poetsen+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../frontend/css/cadastro.css">
    <title>Mercury</title>
</head>
<body>
    <div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-container p-5">
                        <h2 class="text-center mb-4">Cadastro</h2>
                        <form action="novo_usuario.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="exampleInputNome" class="form-label">Nome de usuário</label>
                                <input type="text" name="nome" class="form-control" id="exampleInputNome" aria-describedby="NomeHelp" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail" class="form-label">Email do usuário</label>
                                <input type="email" name="email" class="form-control" id="exampleInputEmail" aria-describedby="EmailHelp" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputSenha" class="form-label">Crie uma senha</label>
                                <input type="password" id="senha" name="senha" class="form-control" aria-describedby="SenhaInicioHelp" required>
                            </div>
                            <input type="submit" name="Enviar" value="Cadastrar" class="btn btn-primary w-100">
                            <a class="btn btn-secondary w-100 mt-2" href="../../frontend/html/index.php">Voltar</a>
                            <h6 class="mt-2 text-center">Já é cadastrado? <a href="../../backend/login/login.php">Clique aqui!</a></h6>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="image-container">
                        <img src="../../frontend/img/logincadastro.jpg" alt="world" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>