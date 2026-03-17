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
        <form method="POST" action="./validatrabalhador.php" onsubmit="return verificaSenha()"> 
            <div class="row me-0 ">
                <div class="col">
                    <div class="tituloLogin">
                        <img src="../img/logo@2x.png" alt="Logo JundTask">
                        <h1>Login Trabalhador</h1>
                    </div>
            
                    <div class="InputsLogin">
                        <input type="text" name="email" id="email" placeholder="Email" required>
                        <label for="email"></label><br>
                    </div>

                    <div class="InputsLogin Senha">
                        <input type="password" name="senha" id="senha" placeholder="Senha" required>
                        <label for="senha"></label><br>
                        <i class="bi bi-eye-slash" id="olho" onclick="mostrarSenha()"></i>
                    </div>   

                    <div class="InputsLogin ConfirmaSenha">
                        <input type="password" name="ConfirmaSenha" id="ConfirmaSenha" placeholder="Confirmar senha" required>
                        <i class="bi bi-eye-slash" id="olho2" onclick="mostrarSenhaConfirma()"></i>  
                    </div>
            
                    <div class="BotaoLogin">
                        <input type="submit" name="submit" value="Login">
                    </div>
                </div>
            </div>
        </form>
        <p class="text-danger">
            <?php 
            if (isset($_SESSION['mensagem'])) {
                echo $_SESSION['mensagem'];
                unset($_SESSION['mensagem']); // Limpa a mensagem após exibição
            }
            ?>
        </p>
    </main>
    <footer class="d-flex justify-content-center">
        <p>N</p>
        <p>Terms of Service</p>
        <p>Privacy Policy</p>
        <p>@2022yanliudesign</p>
    </footer>

    <script>
        function verificaSenha() {
            var senha = document.getElementById('senha').value;
            var confirmaSenha = document.getElementById('ConfirmaSenha').value;

            if (senha !== confirmaSenha) {
                alert('As senhas não coincidem.');
                return false; // Impede o envio do formulário
            }
            return true; // Permite o envio do formulário
        }

        function mostrarSenha() {
            var senhaInput = document.getElementById('senha');
            senhaInput.type = senhaInput.type === 'password' ? 'text' : 'password';
            var olhoIcon = document.getElementById('olho');
            olhoIcon.classList.toggle('bi-eye');
            olhoIcon.classList.toggle('bi-eye-slash');
        }

        function mostrarSenhaConfirma() {
            var confirmaSenhaInput = document.getElementById('ConfirmaSenha');
            confirmaSenhaInput.type = confirmaSenhaInput.type === 'password' ? 'text' : 'password';
            var olhoIcon = document.getElementById('olho2');
            olhoIcon.classList.toggle('bi-eye');
            olhoIcon.classList.toggle('bi-eye-slash');
        }
    </script>
</body>
</html>
