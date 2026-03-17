<?php

session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="../css/login.css">
  <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://unpkg.com/scrollreveal"></script>
  <link rel="shortcut icon" href="../img/logo2.png" />
  <title>Cadastro</title>
</head>

<body>
  <a href="../html/index.php" class="botao-voltar">Voltar</a>
  <section id="menu">
    <div class="login">
      <div class="login-container">
        <div class="login-card">
          <div class="logo-container">
            <img src="../img/logo.png" alt="Logo" class="logo">
          </div>
          <form action="novo_usuario.php" method="POST" style="width: 275px" ;>
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Nome Completo: </label>
              <input type="nome_completo" name="nome_completo" class="form-control" id="exampleInputNome_completo" aria-describedby="emailHelp" required>
            </div>
            <div class="mb-3">
              <label for="exampleInputCpf" class="form-label">CPF: </label>
              <input type="cpf" name="cpf" class="form-control" id="exampleInputCpf" aria-describedby="CpflHelp" required>
            </div>
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Email: </label>
              <input type="email" name="email" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp" required>
            </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Senha: </label>
              <input type="password" name="senha" class="form-control" id="senha" required>
            </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Confirme a senha: </label>
              <input type="password" name="confirmaSenha" class="form-control" id="confirmaSenha" required>
            </div>
            <input value="enviar" name="enviar" type="submit" class="btn btn-primary me-3">
            <a href="login.php">Já tenho conta</a>
          </form>
        </div>
      </div>
    </div>
  </section>
  </main>

  <link href="../bootstrap/js/bootstrap.min.js">
  </link>
</body>

</html>