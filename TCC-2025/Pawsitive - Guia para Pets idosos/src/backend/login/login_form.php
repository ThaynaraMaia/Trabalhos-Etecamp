<?php

session_start();
include('../classes/class_conexao.php');

$mensagem_erro = '';

if (isset($_POST['email']) && isset($_POST['senha'])) {
    if (strlen($_POST['email']) == 0) {
        $mensagem_erro = "Preencha seu e-mail";
    } else if (strlen($_POST['senha']) == 0) {
        $mensagem_erro = "Preencha sua senha";
    } else {
        $email = $mysqli->real_escape_string($_POST['email']);
        $senha_digitada = $_POST['senha']; // sem alterar a senha

        // Buscar o usuário pelo email
        $sql_code = "SELECT * FROM tblusuarios WHERE email_usuario = '$email'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

        if ($sql_query->num_rows == 1) {
            $usuario = $sql_query->fetch_assoc();

            // var_dump($senha_digitada);
            // var_dump($usuario['senha_usuario']);


            // Verificar a senha com password_verify
            if (password_verify($senha_digitada, $usuario['senha_usuario'])) {
                if (!isset($_SESSION)) {
                    session_start();
                }
                $_SESSION['user'] = $usuario;
                $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['logado'] = true;
                $tipo_usuario = $usuario['tipo_usuario'];

                if ($tipo_usuario === 'administrador') {
                    header("Location: ../usuarios/adm/pgAdm.php");
                    exit;
                } elseif ($tipo_usuario === 'tutor/adotante') {
                    header("Location: ../../frontend/pgInicial.php");
                    exit;
                }
            } else {
                $mensagem_erro = "Senha incorreta";
                $_SESSION['logado'] = false;
            }
        } else {
            $mensagem_erro = "Email não encontrado";
            $_SESSION['logado'] = false;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário</title>
    <script src="login_form.js" defer></script>
    <link rel="stylesheet" href="../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="login_form.css">
</head>

<body>
    <section class="divPai">
        <div class="card loginActive" style="background-color: #efebce;">
            <div class="esquerda">
                <div class="formLogin">
                    <h2>Fazer Login</h2>
                    <form method="POST">
                        <input type="email" name="email" placeholder="Email">
                        <input type="password" name="senha" placeholder="Senha">
                        <button type="submit">Entrar</button>

                        <!-- Mensagem de erro -->
                        <?php if ($mensagem_erro != ''): ?>
                            <div style="color: #7a4100; text-align: center; margin-top: 20px;"><?php echo htmlspecialchars($mensagem_erro); ?></div>
                        <?php endif; ?>

                        <a style="color: #7a4100; margin-top: 20px; text-decoration: none;" href="../../frontend/pgInicial.php">Esqueci minha senha</a>

                        <a style="color: #4E6422; text-align: center; margin-top: 20px;" href="../../frontend/pgInicial.php">Voltar para a Página Inicial</a>
                    </form>
                </div>
                <div class="facaLogin">
                    <h2>Já tem </br> uma conta?</h2>
                    <p>Faça login para ter acesso exclusivo de usuário</p>
                    <button class="loginBtn">Faça login</button>
                </div>
            </div>
            <div class="direita">
                <div class="formCadastro">
                    <h2>Cadastro</h2>
                    <form method="POST" action="cadastrar.php">
                        <input type="text" name="nome" placeholder="Nome">
                        <input type="email" name="email" placeholder="Email">
                        <input type="password" name="senha" placeholder="Senha">
                        <!-- <input type="image" name="foto" placeholder="Foto de perfil"> -->
                        <button type="submit">Cadastrar</button>
                    </form>
                </div>
                <div class="facaCadastro">
                    <h2>Não tem </br> uma conta?</h2>
                    <p>Crie uma conta para ter acesso exclusivo de usuário</p>
                    <button class="cadastroBtn">Cadastre-se</button>
                </div>
            </div>
        </div>
    </section>

    <script src="../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.min.js"></script>
</body>

</html>