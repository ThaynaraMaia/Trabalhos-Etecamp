<?php
session_start();
require_once "../classes/class_Conexao.php";  

$token = $_GET['token'];

$con = new Conexao("localhost", "root", "", "vidamarinha");
$con->conectar();
$conn = $con->getConnection();

$sql = "SELECT * FROM usuarios WHERE token_recuperacao = ? AND token_expira > NOW()";
$result = $con->executarQuery($sql, [$token]);

if(mysqli_num_rows($result) == 0){
    $_SESSION['mensagem'] = " Link inválido ou expirado!";
    $_SESSION['tipo'] = "erro";
    header("Location: ../login/login.php");
    exit;
}

// Pegar mensagens da sessão se existirem
$mensagem = "";
$tipo = "";
if (isset($_SESSION['mensagem_reset'])) {
    $mensagem = $_SESSION['mensagem_reset'];
    $tipo = $_SESSION['tipo_reset'] ?? "erro";
    unset($_SESSION['mensagem_reset'], $_SESSION['tipo_reset']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - Projeto Martopia</title>

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

        body, input, button, textarea, select {
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

        .alert.show {
            visibility: visible;
            opacity: 1;
            bottom: 50px;
        }

        /* Ajustes específicos para centralizar o formulário */
        .formulario {
            min-height: calc(100vh - 200px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-box-reset {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
        }

        .form-box-reset h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        .form-box-reset .subtitle {
            text-align: center;
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        .password-strength {
            margin-top: 5px;
            font-size: 0.9rem;
            color: #666;
        }

        .password-strength.weak {
            color: #f44336;
        }

        .password-strength.medium {
            color: #ff9800;
        }

        .password-strength.strong {
            color: #4CAF50;
        }
    </style>

    <!-- INICIANDO O NAVBAR -->
    <header class="header">
        <div class="logo-marca">
            <a href="../../frontend/home.php" class="logo">
                <img src="../../frontend/public/img/Logo.png" alt="Logo-Projeto Martopia">
            </a>
            <p>Projeto Martopia</p>
        </div>

        <input type="checkbox" id="check">
        <label for="check" class="icone">
            <i class="bi bi-list" id="menu-icone"></i>
            <i class="bi bi-x" id="sair-icone"></i>
        </label>

    </header>

    <div id="customAlert"></div>

    <!-- FORMULÁRIO DE REDEFINIÇÃO DE SENHA -->
    <main>
        <div class="formulario">
            <div class="form-box-reset">
                <form action="salvar_senha.php" method="POST" onsubmit="return validarSenhas()">
                    <h1 style="font-size: 2.5rem; color: #525151ff">Redefinir Senha</h1>
                    <p class="subtitle">Digite sua nova senha abaixo</p>

                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    
                    <div class="input-box">
                        <input type="password" 
                               name="senha" 
                               id="senha" 
                               placeholder="Nova senha" 
                               required
                               minlength="6"
                               onkeyup="verificarForcaSenha()">
                        <i class="bi bi-lock"></i>
                    </div>
                    <div id="forcaSenha" class="password-strength"></div>

                    <div class="input-box" style="margin-top: 20px;">
                        <input type="password" 
                               name="confirma" 
                               id="confirma" 
                               placeholder="Confirmar senha" 
                               required
                               minlength="6">
                        <i class="bi bi-lock-fill"></i>
                    </div>

                    <button class="btn" type="submit" style="font-size: 1.3rem; margin-top: 20px;">
                        <i class="bi bi-check-circle"></i> Salvar Nova Senha
                    </button>

                    <div style="text-align: center; margin-top: 20px;">
                        <a href="../login/login.php" style="color: #045a94; font-size: 1.1rem;">
                            <i class="bi bi-arrow-left"></i> Voltar ao Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer style="background: #045a94; text-shadow: 2px 2px 4px rgba(0, 0, 0, .3);">
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
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3666.2168153595317!2d-46.766872!3d-23.235196!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94cede166027baab%3A0x566fc4df5821546c!2sEscola%20T%C3%A9cnica%20Estadual%20de%20Campo%20Limpo%20Paulista!5e0!3m2!1spt-BR!2sbr!4v1756695006929!5m2!1spt-BR!2sbr" 
                    width="600" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade" 
                    aria-label="Mapa interativo"></iframe>
        </div>

        <div class="copyright">
            <p>&copy; 2025 Projeto Martopia. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
        function showAlert(mensagem, tipo = "erro") {
            let alertBox = document.getElementById("customAlert");
            alertBox.innerText = mensagem;
            alertBox.className = "alert " + tipo + " show";

            setTimeout(() => {
                alertBox.classList.remove("show");
            }, 3000);
        }

        function verificarForcaSenha() {
            const senha = document.getElementById("senha").value;
            const forcaSenha = document.getElementById("forcaSenha");
            
            if (senha.length === 0) {
                forcaSenha.textContent = "";
                return;
            }

            let forca = 0;
            
            if (senha.length >= 8) forca++;
            if (senha.length >= 12) forca++;
            if (/[a-z]/.test(senha) && /[A-Z]/.test(senha)) forca++;
            if (/\d/.test(senha)) forca++;
            if (/[^a-zA-Z0-9]/.test(senha)) forca++;

            if (forca <= 2) {
                forcaSenha.textContent = "Senha fraca";
                forcaSenha.className = "password-strength weak";
            } else if (forca <= 3) {
                forcaSenha.textContent = "Senha média";
                forcaSenha.className = "password-strength medium";
            } else {
                forcaSenha.textContent = "Senha forte";
                forcaSenha.className = "password-strength strong";
            }
        }

        function validarSenhas() {
            const senha = document.getElementById("senha").value;
            const confirma = document.getElementById("confirma").value;

            if (senha.length < 6) {
                showAlert("A senha deve ter no mínimo 6 caracteres", "erro");
                return false;
            }

            if (senha !== confirma) {
                showAlert("As senhas não coincidem", "erro");
                return false;
            }

            return true;
        }
    </script>

</body>
</html>