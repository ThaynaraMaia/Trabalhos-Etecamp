<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JundTask - Cadastro Trabalhador</title>
    <link rel="stylesheet" href="../css/styleCadastros.css"> <!-- Verifique se o caminho está correto -->
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
        <form id="formCadastroTrabalhador" enctype="multipart/form-data">
            <div class="row me-0">
                <div class="col">
                    <div class="tituloLogin">
                        <img src="../img/logo@2x.png" alt="Logo JundTask">
                        <h1>Cadastro Trabalhador</h1>
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

                    <div class="InputsLogin">
                        <input type="tel" name="contato" id="contato" placeholder="Número de Contato" required pattern="\d{11}">
                    </div>

                    <div class="InputsLogin">
                        <input type="date" name="data_nascimento" id="data_nascimento" required>
                    </div>

                    <div class="InputsLogin Senha">
                        <input type="password" name="senha" id="senha" placeholder="Senha" required>
                        <i class="bi bi-eye-slash" id="olho" onclick="mostrarSenha()"></i>
                    </div>

                    <div class="InputsLogin ConfirmaSenha">
                        <input type="password" name="ConfirmaSenha" id="ConfirmaSenha" placeholder="Confirmar senha" required>
                        <i class="bi bi-eye-slash" id="olho2" onclick="mostrarSenha2()"></i>
                    </div>

                    <div class="box">
                        <select name="id_area" id="id_area"> 
                            <option value="">Selecione uma área</option>
                        </select>
                    </div>

                    <div class="box marginteste">
                        <select name="id_categoria" id="id_categoria"> 
                            <option value="">Selecione uma categoria</option>
                        </select>
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
            document.getElementById('formCadastroTrabalhador').addEventListener('submit', function(event) {
                event.preventDefault();

                var formData = new FormData(this);

                fetch('../html/RegisterTrabalhador.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    var mensagemSucesso = document.getElementById('mensagemSucesso');
                    var mensagemErro = document.getElementById('mensagemErro');

                    if (data.sucesso) {
                        mensagemSucesso.innerText = data.mensagem;
                        mensagemSucesso.classList.add('show');
                        mensagemSucesso.style.display = 'block'; // Garante que a mensagem de sucesso seja exibida
                        mensagemErro.style.display = 'none'; // Oculta a mensagem de erro

                        // Redirecionar após 2 segundos
                        setTimeout(function() {
                            window.location.href = './LoginTrabalhador.php'; // Redireciona para a página de login
                        }, 2000); // Tempo em milissegundos
                    } else {
                        mensagemErro.innerText = data.mensagem;
                        mensagemErro.classList.add('show');
                        mensagemErro.style.display = 'block'; // Garante que a mensagem de erro seja exibida
                        mensagemSucesso.style.display = 'none'; // Oculta a mensagem de sucesso
                    }
                })
                .catch(error => {
                    console.error('Erro ao enviar o formulário:', error);
                    var mensagemErro = document.getElementById('mensagemErro');
                    mensagemErro.innerText = 'Erro ao enviar o formulário. Tente novamente.';
                    mensagemErro.classList.add('show');
                    mensagemErro.style.display = 'block'; // Garante que a mensagem de erro seja exibida
                });
            });

            function mostrarSenha() {
                var senhaInput = document.getElementById('senha');
                senhaInput.type = senhaInput.type === 'password' ? 'text' : 'password';
                var olhoIcon = document.getElementById('olho');
                olhoIcon.classList.toggle('bi-eye');
                olhoIcon.classList.toggle('bi-eye-slash');
            }

            function mostrarSenha2() {
                var confirmaSenhaInput = document.getElementById('ConfirmaSenha');
                confirmaSenhaInput.type = confirmaSenhaInput.type === 'password' ? 'text' : 'password';
                var olhoIcon2 = document.getElementById('olho2');
                olhoIcon2.classList.toggle('bi-eye');
                olhoIcon2.classList.toggle('bi-eye-slash');
            }

            document.addEventListener('DOMContentLoaded', function() {
                const areaSelect = document.getElementById('id_area');
                const categoriaSelect = document.getElementById('id_categoria');

                fetch('./getcidades.php')
                    .then(response => response.json())
                    .then(areas => {
                        areaSelect.innerHTML = '<option value="">Selecione uma área</option>';
                        areas.forEach(area => {
                            const option = document.createElement('option');
                            option.value = area.id_area;
                            option.textContent = area.cidade;
                            areaSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Erro ao carregar áreas:', error));

                fetch('./getcategoriacadastro.php')
                    .then(response => response.json())
                    .then(categorias => {
                        categoriaSelect.innerHTML = '<option value="">Selecione uma categoria</option>';
                        categorias.forEach(categoria => {
                            const option = document.createElement('option');
                            option.value = categoria.id_categoria;
                            option.textContent = categoria.nome_cat;
                            categoriaSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Erro ao carregar categorias:', error));
            });
        </script>
    </main>
    <footer class="d-flex justify-content-center">
        <p>Terms of Service</p>
        <p>Privacy Policy</p>
        <p>@2022yanliudesign</p>
    </footer>
</body>
</html>
