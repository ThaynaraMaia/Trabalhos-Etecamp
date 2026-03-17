<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JundTask - Cadastro Administrador</title>
    <link rel="stylesheet" href="../../css/styleCadastros.css">
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="shortcut icon" href="../../img/logo@2x.png" type="image/x-icon">
</head>
<body>
    <header>
        <nav class="BarraNav">
            <img src="../../img/JUNDTASK.png" alt="Logo JundTask">
            <a href="../../html/home.php">Sair</a>
        </nav>
    </header>
    <main class="LoginGeral">
        <form id="formCadastroAdm" enctype="multipart/form-data">
            <div class="row me-0">
                <div class="col">
                    <div class="tituloLogin">
                        <img src="../../img/logo@2x.png" alt="Logo JundTask">
                        <h1>Cadastro Administrador</h1>
                    </div>

                    <div id="mensagemErro" class="alert alert-danger" style="display:none;">
                        <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
                    </div>

                    <div id="mensagemSucesso" class="alert alert-success" style="display:none;">
                        <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
                    </div>

                    <div class="InputsLogin">
                        <input type="text" id="nome" name="nome" placeholder="Nome" required><br>
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
                        <i class="bi bi-eye-slash" id="olho2" onclick="mostrarSenha2()"></i>
                    </div>

                    <div class="InputsLogin FotodePerfil">
                        <input type="file" name="foto_de_perfil" id="foto_de_perfil" required>
                    </div>

                    <div class="BotaoCadastro">
                        <input type="submit" value="Cadastrar">
                    </div>
                </div>
            </div>
        </form>

        <script>
            document.getElementById('formCadastroAdm').addEventListener('submit', function(event) {
                event.preventDefault();

                var formData = new FormData(this);

                fetch('RegisterAdm.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    var mensagemSucesso = document.getElementById('mensagemSucesso');
                    var mensagemErro = document.getElementById('mensagemErro');

                    if (data.sucesso) {
                        mensagemSucesso.innerText = data.mensagem;
                        mensagemSucesso.style.display = 'block';
                        mensagemErro.style.display = 'none';
                    } else {
                        mensagemErro.innerText = data.mensagem;
                        mensagemErro.style.display = 'block';
                        mensagemSucesso.style.display = 'none';
                    }
                })
                .catch(error => {
                    var mensagemErro = document.getElementById('mensagemErro');
                    mensagemErro.innerText = 'Erro ao enviar o formulário. Tente novamente.';
                    mensagemErro.style.display = 'block';
                });
            });

            function mostrarSenha() {
                var senhaInput = document.getElementById('senha');
                senhaInput.type = senhaInput.type === 'password' ? 'text' : 'password';
                document.getElementById('olho').classList.toggle('bi-eye-slash');
                document.getElementById('olho').classList.toggle('bi-eye');
            }

            function mostrarSenha2() {
                var confirmaSenhaInput = document.getElementById('ConfirmaSenha');
                confirmaSenhaInput.type = confirmaSenhaInput.type === 'password' ? 'text' : 'password';
                document.getElementById('olho2').classList.toggle('bi-eye-slash');
                document.getElementById('olho2').classList.toggle('bi-eye');
            }
        </script>

    </main>
    <footer class="d-flex justify-content-center">
        <p>Terms of Service</p>
        <p>Privacy Policy</p>
        <p>@2022yanliudesign</p>
    </footer>
</body>
</html>
