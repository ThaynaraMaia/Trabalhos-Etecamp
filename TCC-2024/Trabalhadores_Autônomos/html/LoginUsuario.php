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
        <form id="formLogin" method="POST">
            <div class="row me-0">
                <div class="col">
                    <div class="tituloLogin">
                        <img src="../img/logo@2x.png" alt="Logo JundTask">
                        <h1>Login Usuario</h1>
                    </div>

                    <div class="login-container">
                        <div id="mensagemErro" class="alert alert-danger" style="display: none;">
                            <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
                        </div>

                        <div id="mensagemSucesso" class="alert alert-success" style="display: none;">
                            <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
                        </div>

                        <div class="InputsLogin">
                            <input type="text" name="email" id="email" placeholder="Email" required>
                        </div>

                        <div class="InputsLogin Senha">
                            <input type="password" name="senha" id="senha" placeholder="Senha" required>
                            <i class="bi bi-eye-slash" id="olho" onclick="mostrarSenha()"></i>
                        </div>

                        <div class="InputsLogin ConfirmaSenha">
                            <input type="password" name="ConfirmaSenha" id="ConfirmaSenha" placeholder="Confirmar senha" required>
                            <i class="bi bi-eye-slash" id="olho2" onclick="mostrarSenhaConfirma()"></i>  
                        </div>

                        <div class="BotaoLogin">
                            <input type="submit" value="Login">
                        </div>
                    </div>
                </div>
            </div>
        </form> 


        <script>
            document.getElementById('formLogin').addEventListener('submit', function(event) {
                event.preventDefault(); // Previne o comportamento padrão do formulário

                var senha = document.getElementById('senha').value;
                var confirmaSenha = document.getElementById('ConfirmaSenha').value;

                if (senha !== confirmaSenha) {
                    var mensagemErro = document.getElementById('mensagemErro');
                    mensagemErro.innerText = 'As senhas não coincidem.';
                    mensagemErro.style.display = 'block';
                    return;
                }

                var formData = new FormData(this); 

                fetch('validaCliente.php', { 
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json()) 
                .then(data => {
                    var mensagemSucesso = document.getElementById('mensagemSucesso');
                    var mensagemErro = document.getElementById('mensagemErro');

                    if (data.sucesso) {
                        
                        mensagemSucesso.innerText = 'Login realizado com sucesso!';
                        mensagemSucesso.style.display = 'block';
                        mensagemErro.style.display = 'none';

                       
                        window.location.href = data.redirect; 
                    } else {
                        
                        mensagemErro.innerText = data.mensagem;
                        mensagemErro.style.display = 'block';
                        mensagemSucesso.style.display = 'none';
                    }
                })
                .catch(error => {
                    
                    console.error('Erro ao enviar o formulário:', error);
                    var mensagemErro = document.getElementById('mensagemErro');
                    mensagemErro.innerText = 'Erro ao enviar o formulário. Tente novamente.';
                    mensagemErro.style.display = 'block';
                });
            });

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
    </main>

    <footer class="d-flex justify-content-center">
        <p>N</p>
        <p>Terms of Service</p>
        <p>Privacy Policy</p>
        <p>@2022yanliudesign</p>
    </footer>
    
</body>
</html>
