<!DOCTYPE html>
<html lang="pt-br">
<?php
$mensagem = "";
$tipo = "";
$textcadastro = "";
session_start();

if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];

    // Verificar se existe o tipo definido na sessão
    if (isset($_SESSION['tipo'])) {
        $tipo = $_SESSION['tipo'];
    } else {
        // Se não tiver tipo, usar a lógica antiga como fallback
        $tipo = ($_SESSION['logado'] ?? false) ? "sucesso" : "erro";
    }

    unset($_SESSION['mensagem'], $_SESSION['logado'], $_SESSION['tipo']);
}

if (isset($_SESSION['cadastro'])) {
    $textcadastro = $_SESSION['cadastro'];
    unset($_SESSION['cadastro']);
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Projeto Martopia</title>

    <link rel="stylesheet" href="../../frontend/public/css/loginCad.css">
    <link rel="stylesheet" href="../../frontend/public/css/baseGeral.css">
    <link rel="stylesheet" href="../../frontend/public/css/logo.css">
    <link rel="stylesheet" href="../../frontend/public/css/footer.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

</head>

<body>

    <style>
        @font-face {
            font-family: 'Texto';
            src: url('../../frontend/fontes/Texto.otf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        * {
            font-family: 'Texto', Arial, sans-serif;
        }

        body,
        input,
        button,
        textarea,
        select {
            font-family: 'Texto', Arial, sans-serif;
        }

        .header {
            box-shadow: 0 40px 60px rgba(0, 0, 0, 0.2);
        }

        .alert {
            visibility: hidden;
            min-width: 250px;
            text-align: center;
            border-radius: 8px;
            padding: 15px;
            position: fixed;
            z-index: 9999;
            left: 50%;
            bottom: 30px;
            transform: translateX(-50%);
            font-size: 16px;
            opacity: 0;
            transition: opacity 0.5s, bottom 0.5s;
        }

        .alert.sucesso {
            background-color: #4CAF50;
            color: white;
        }

        .alert.erro {
            background-color: #f44336;
            color: white;
        }

        .alert.aviso {
            background-color: #ff9800;
            color: white;
        }

        .alert.show {
            visibility: visible;
            opacity: 1;
            bottom: 50px;
        }

        /* Estilos do Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 10000;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background: white;
            padding: 40px;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            position: relative;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            transform: scale(0.7);
            transition: transform 0.3s ease;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 28px;
            cursor: pointer;
            background: none;
            border: none;
            transition: color 0.3s;
            z-index: 1;
        }

        .modal-content h2 {
            font-size: 2rem;
            color: #525151;
            margin-bottom: 10px;
            font-family: 'Titulo';
            text-align: center;
        }

        .modal-content p {
            color: #666;
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.1rem;
        }

        .modal-content .input-box {
            position: relative;
            margin-bottom: 20px;
        }

        .modal-content .input-box input {
            width: 100%;
            padding: 15px 45px 15px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1.1rem;
            transition: border-color 0.3s;
        }

        .modal-content .input-box input:focus {
            outline: none;
            border-color: #045a94;
        }

        .modal-content .input-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #045a94;
            font-size: 1.2rem;
        }

        .modal-content .btn {
            width: 100%;
            padding: 15px;
            background: #045a94;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.3rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        .modal-content .btn:hover {
            background: #034a7a;
        }

        .modal-content hr {
            border: none;
            border-top: 2px solid #e0e0e0;
            margin: 20px 0;
        }

        .modal-content .btn i {
            margin-right: 8px;
        }

        /* Ícone de ajuda */
        .help-icon {
            display: inline-block;
            width: 24px;
            height: 24px;
            background: #045a94;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 24px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s;
            margin-left: 10px;
            vertical-align: middle;
        }

        .help-icon:hover {
            background: #034a7a;
            transform: scale(1.1);
        }

        /* Modal Tutorial */
        .modal-tutorial {
            max-width: 700px;
        }

        .modal-tutorial h2 {
            color: #045a94;
            margin-bottom: 20px;
        }

        .tutorial-steps {
            text-align: left;
            margin: 20px 0;
        }

        .tutorial-step {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            border-left: 4px solid #045a94;
        }

        .tutorial-step h3 {
            color: #045a94;
            font-size: 1.2rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .tutorial-step h3 i {
            margin-right: 10px;
            font-size: 1.4rem;
        }

        .tutorial-step p {
            color: #333;
            font-size: 1rem;
            line-height: 1.6;
            margin: 0;
            text-align: left;
        }

        .tutorial-note {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .tutorial-note i {
            color: #ff9800;
            margin-right: 8px;
        }

        .tutorial-note p {
            color: #856404;
            margin: 0;
            font-size: 0.95rem;
        }

        .close-btn {
            /* top: 20px;  */
            right: 20px;
            background: #ff4757;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            transform: rotate(90deg);
            background: #ff6b81;
        }
    </style>

    <!-- INICIANDO O NAVBAR -->
    <header class="header">
        <div class="logo-marca" style="margin-left: -3rem;">
            <a href="./home.php" class="logo"><img src="../../frontend/public/img/Logo.png" alt="Logo-Projeto Martopia"></a>
            <p style="margin-left: -3rem;">Projeto <br> Martopia</p>
        </div>

        <input type="checkbox" id="check">
        <label for="check" class="icone">
            <i class="bi bi-list" id="menu-icone"></i>
            <i class="bi bi-x" id="sair-icone"></i>
        </label>

        <a href="../../frontend/home.php" class="btn-login" style="height: 60px; font-size: 1.3em;">Voltar</a>
    </header>

    <div id="customAlert"></div>

    <!-- Modal Recuperar Senha -->
    <div class="modal-overlay" id="modalRecuperarSenha">
        <div class="modal-content">

            <button class="modal-close close-btn" onclick="fecharModal('modalRecuperarSenha')" style="font-size: 2rem;"><i class="bi bi-x"></i></button>
            <h2 style="font-size: 2rem; color:#045a94;">Recuperar Senha</h2>
            <p>
                Configure seu Gmail para enviar o link de recuperação
                <span class="help-icon" onclick="abrirModalTutorial(); event.stopPropagation();" title="Como configurar senha de app?">
                    <i class="bi bi-question-lg"></i>
                </span>
            </p>
            <form action="../esqueci_senha/processa_senha.php" method="POST" id="formRecuperarSenha">
                <div class="input-box">
                    <input type="email" name="emailRecu" id="emailGmail" placeholder="Seu Gmail" required>
                    <i class="bi bi-google"></i>
                </div>

                <div class="input-box">
                    <input type="password" name="senha_app" id="senhaApp" placeholder="Senha de App do Gmail" required minlength="16">
                    <i class="bi bi-key-fill"></i>
                </div>

                <p style="font-size: 0.85rem; color: #666; margin-top: -10px; margin-bottom: 15px;">
                    <i class="bi bi-info-circle"></i> Use a senha de 16 caracteres gerada pelo Google
                </p>

                <button type="submit" class="btn">
                    <i class="bi bi-send-fill"></i> Enviar Link de Recuperação
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Tutorial Senha de App Google -->
    <div class="modal-overlay" id="modalTutorial">
        <div class="modal-content modal-tutorial">
            <button class="modal-close close-btn" onclick="fecharModal('modalTutorial')" style="font-size: 2rem;"><i class="bi bi-x"></i></button>
            <h2> Como Criar uma Senha de App no Google</h2>
            <p style="color: #666; font-size: 1rem;">Siga o passo a passo abaixo para gerar uma senha de aplicativo:</p>

            <div class="tutorial-steps">
                <div class="tutorial-step">
                    <h3><i class="bi bi-1-circle-fill"></i> Acesse sua Conta Google</h3>
                    <p>Vá para <strong>myaccount.google.com</strong> e faça login com sua conta.</p>
                </div>

                <div class="tutorial-step">
                    <h3><i class="bi bi-2-circle-fill"></i> Navegue até Segurança</h3>
                    <p>No menu lateral, clique em <strong>"Segurança"</strong>.</p>
                </div>

                <div class="tutorial-step">
                    <h3><i class="bi bi-3-circle-fill"></i> Ative a Verificação em Duas Etapas</h3>
                    <p>Role até encontrar <strong>"Verificação em duas etapas"</strong> e ative-a (necessário para senhas de app).</p>
                </div>

                <div class="tutorial-step">
                    <h3><i class="bi bi-4-circle-fill"></i> Acesse Senhas de App</h3>
                    <p>Após ativar a verificação em duas etapas,pesquise na Conta do Google <strong>"Senhas de app"</strong> e clique nela.</p>
                </div>

                <div class="tutorial-step">
                    <h3><i class="bi bi-5-circle-fill"></i> Gere uma Nova Senha</h3>
                    <p>Selecione o aplicativo (escolha "Outro") e digite um nome como <strong>"Projeto Martopia"</strong>. Clique em <strong>"Gerar"</strong>.</p>
                </div>

                <div class="tutorial-step">
                    <h3><i class="bi bi-6-circle-fill"></i> Copie a Senha Gerada</h3>
                    <p>O Google exibirá uma senha de 16 caracteres. <strong>Copie essa senha</strong> e use-a na configuração do sistema de email.</p>
                </div>
            </div>

            <div class="tutorial-note">
                <p><i class="bi bi-exclamation-triangle-fill"></i> <strong>Importante:</strong> Esta senha será exibida apenas uma vez! Guarde-a em local seguro. Você precisará usar esta senha no lugar da senha normal do Gmail nas configurações de SMTP.</p>
            </div>
        </div>
    </div>

    <!-- INICIANDO O MAIN COM O FORMULÁRIO -->
    <main>
        <div class="formulario">
            <div class="container">

                <div class="form-box login">
                    <form method="POST" action="valida_login.php">
                        <h1 style="font-size: 2.5rem; color: #525151ff">Login</h1>
                        <div class="input-box">
                            <input type="email" name="email" placeholder="Email" aria-describedby="emailHelp" required>
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="input-box">
                            <input type="password" name="senha" placeholder="Senha" required>
                            <i class="bi bi-lock"></i>
                        </div>

                        <div>
                            <p style="margin-bottom:5px;">
                                <a href="#" onclick="abrirModal('modalRecuperarSenha'); return false;" style="font-size: 1.2rem; color:#045a94;">Esqueci minha senha</a>
                            </p>
                        </div>

                        <button type="submit" class="btn" onclick="login()" style="font-size: 1.3rem;">Login</button>
                        <div class="mt-2">
                            <p class="text-danger"></p>
                        </div>
                    </form>
                </div>

                <div class="form-box register">
                    <form action="../usuarios/comum/novoUsuario.php" method="POST" onsubmit="return verificaSenha()" id="formCadastro">
                        <h1 style="font-size: 2.5rem; color: #525151ff">Cadastrar</h1>
                        <div class="input-box">
                            <input type="text" name="nome" placeholder="Nome de Usuário" required id="nome">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div class="input-box">
                            <input type="email" name="email" placeholder="Email" required id="email">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="input-box">
                            <input type="password" name="senha" id="senha" placeholder="Senha" required>
                            <i class="bi bi-lock"></i>
                        </div>
                        <div class="input-box">
                            <input type="password" name="confirmaSenha" id="confirmaSenha" placeholder="Confirma Senha" required>
                            <i class="bi bi-lock"></i>
                        </div>
                        <button type="submit" class="btn" onclick="cadastro()" style="font-size: 1.3rem;">Cadastrar</button>
                        <br><br>
                        <button type="reset" class="btn" style="font-size: 1.3rem;">Limpar</button>
                    </form>
                </div>

                <div class="toggle-box">
                    <div class="toggle-panel toggle-left">
                        <h1>Bem-Vindo de Volta!</h1>
                        <p style="font-size: 1.5rem;">Não possui uma conta?</p>
                        <button class="btn register-btn" style="font-size: 1.3rem;">Cadastrar</button>
                    </div>
                    <div class="toggle-panel toggle-right">
                        <h1>Olá, Seja Bem-Vindo!</h1>
                        <p style="font-size: 1.5rem;">Já tem uma conta?</p>
                        <button class="btn login-btn" style="font-size: 1.3rem;">Login</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- INICIO FOOTER -->
    <footer style="background: #045a94;text-shadow: 2px 2px 4px rgba(0, 0, 0, .3);">
        <div class="contatos">
            <h3>Contatos</h3>
            <p>Email: contato@martopia.com.br</p>
            <p>Telefone: +55 11 99999-9999</p>
            <p>Endereço: Rua do Oceano, 123, São Paulo, SP</p>
        </div>

        <div class="redes">
            <h3>Redes Sociais</h3>
            <div>
                <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
            </div>
        </div>

        <div class="mapa">
            <h3>Localização</h3>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3666.2168153595317!2d-46.766872!3d-23.235196!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94cede166027baab%3A0x566fc4df5821546c!2sEscola%20T%C3%A9cnica%20Estadual%20de%20Campo%20Limpo%20Paulista!5e0!3m2!1spt-BR!2sbr!4v1756695006929!5m2!1spt-BR!2sbr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" aria-label="Mapa interativo"></iframe>
        </div>

        <div class="copyright">
            <p>&copy; 2025 Projeto Martopia. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
        function showAlert(mensagem, tipo = "aviso") {
            let alertBox = document.getElementById("customAlert");
            alertBox.innerText = mensagem;
            alertBox.className = "alert " + tipo + " show";

            setTimeout(() => {
                alertBox.classList.remove("show");
            }, 3000);
        }

        function abrirModal(modalId) {
            document.getElementById(modalId).classList.add("active");
            document.body.style.overflow = "hidden";
        }

        function fecharModal(modalId) {
            document.getElementById(modalId).classList.remove("active");
            document.body.style.overflow = "auto";
        }

        function abrirModalTutorial() {
            fecharModal('modalRecuperarSenha');
            setTimeout(() => {
                abrirModal('modalTutorial');
            }, 300);
        }

        // Fechar modal ao clicar fora
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.addEventListener("click", function(e) {
                if (e.target === this) {
                    fecharModal(this.id);
                }
            });
        });

        // Fechar modal com ESC
        document.addEventListener("keydown", function(e) {
            if (e.key === "Escape") {
                document.querySelectorAll('.modal-overlay.active').forEach(modal => {
                    fecharModal(modal.id);
                });
            }
        });

        window.onload = function() {
            let msgLogin = "<?php echo $mensagem; ?>";
            let tipoLogin = "<?php echo $tipo; ?>";
            let msgCadastro = "<?php echo $textcadastro; ?>";

            // Corrigir tipo se estiver vazio
            if (msgLogin.trim() !== "") {
                // Se tipo estiver vazio, tentar detectar pelo conteúdo
                if (!tipoLogin || tipoLogin.trim() === "") {
                    if (msgLogin.includes("✅") || msgLogin.toLowerCase().includes("sucesso")) {
                        tipoLogin = "sucesso";
                    } else if (msgLogin.includes("❌") || msgLogin.toLowerCase().includes("erro")) {
                        tipoLogin = "erro";
                    } else {
                        tipoLogin = "aviso";
                    }
                }

                showAlert(msgLogin, tipoLogin);

                if (msgCadastro.trim() !== "") {
                    setTimeout(() => {
                        showAlert(msgCadastro, "sucesso");
                    }, 3500);
                }
            } else if (msgCadastro.trim() !== "") {
                showAlert(msgCadastro, "sucesso");
            }
        };
    </script>

    <script src="../../frontend/js/login.js"></script>
    <script src="../../frontend/js/form.js"></script>

</body>

</html>