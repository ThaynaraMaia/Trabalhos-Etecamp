<?php
session_start();
include_once '../classes/class_IRepositorioUsuarios.php';

// Proteção: só logado pode acessar
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$tipo = $_SESSION['tipo'];
$dados = $respositorioUsuario->buscarUsuario($id_usuario);
// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';

// Lista de avatares disponíveis (imagens na pasta /frontend/public/imagens/avatars/)
$avatars = [
    "baleia.jpg",
    "tartaruga.jpg",
    "tubaleia.jpg"
];
$avatarsAdm = [
    "perfil1.jpg",
    "perfil2.jpg",
    "perfil3.jpg"

];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Escolher Avatar</title>
    <!-- <link rel="stylesheet" href="../../frontend/public/css/Perfil.css">  -->
    <link rel="stylesheet" href="../../frontend/public/css/perfilUser.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="./t.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

</head>
<style>
    /* 
<!-- ==============
MODAL ALTERAR Dados
================ --> */

    @font-face {
        font-family: 'Texto';
        src: url('../../fontes/Texto.otf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
    }

    .modal-content {
        background: #fff;
        padding: 20px;
        margin: 10% auto;
        width: 400px;
        border-radius: 10px;
        text-align: left;
        position: relative;
    }

    .close {
        position: absolute;
        right: 15px;
        top: 10px;
        font-size: 22px;
        cursor: pointer;
    }

    .dados i {
        font-size: 24px;
        color: #333;
        cursor: pointer;
    }

    .dados i:hover {
        color: #38a0dd;
        transform: translateY(-2px);
    }

    /* Estilo para o fundo do modal */
    .modal {
        display: none;
        /* Escondido por padrão */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        /* Scroll se necessário */
        background-color: rgba(0, 0, 0, 0.5);
        /* Fundo escurecido */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Conteúdo do modal */
    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        /* Centralizado verticalmente e horizontalmente */
        padding: 30px 40px;
        border-radius: 12px;
        width: 90%;
        max-width: 450px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        position: relative;
        animation: slideDown 0.3s ease forwards;
    }

    /* Animação de entrada */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Botão fechar (X) */
    .close {
        position: absolute;
        top: 15px;
        right: 20px;
        color: #888;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close:hover {
        color: #e74c3c;
    }

    /* Título do modal */
    .modal-content h2 {
        margin-bottom: 25px;
        color: #333;
        font-weight: 700;
        text-align: center;
    }

    /* Labels */
    .modal-content label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #555;
        font-family: 'Texto';
        font-weight: bold;
    }

    /* Inputs */
    .modal-content input[type="text"],
    .modal-content input[type="email"],
    .modal-content input[type="password"] {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 20px;
        border: 1.8px solid #ccc;
        border-radius: 8px;
        font-size: 20px;
        transition: border-color 0.3s ease;
        font-family: 'Texto';
    }

    .modal-content input[type="text"]:focus,
    .modal-content input[type="email"]:focus,
    .modal-content input[type="password"]:focus {
        border-color: #9FD5D1;
        outline: none;
        box-shadow: 0 0 8px rgba(52, 152, 219, 0.3);
    }

    /* Botão salvar */
    .modal-content button[type="submit"] {
        width: 100%;
        padding: 14px 0;
        background-color: #9FD5D1;
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .modal-content button[type="submit"]:hover {
        background-color: #49bfb7ff;
    }

    /* Responsividade */
    @media (max-width: 480px) {
        .modal-content {
            padding: 25px 20px;
            width: 95%;
        }
    }


    /* Botão de fechar */

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



    /* <!-- ==============
MODAL ALTERAR Dados FINAL
================ --> */
</style>

<body>

    <div class="container" style="max-height: 630px;">

        <div class="main">
            <h1>Meu Perfil</h1>
            <div class="dados">
                <img src="../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil" class="avatar">

                <h2>Informações</h2>

                <div class="dados">
                    <h3 class="nome" style="font-size: 1.2rem;">
                        <p style="font-size: 1.2rem; font-family: 'Texto';">Nome: <?php echo $_SESSION['nome'] ?></p>
                    </h3>
                </div>

                <div class="dados">
                    <h3 class="nome">
                        <p style="font-size: 1.2rem; font-family: 'Texto';">Email: <?php echo $_SESSION['email'] ?></p>
                    </h3>
                </div>

                <div class="dados">
                    <i class="bi bi-pencil-square" onclick="abrirModal()" style="font-size: 2rem;"></i>
                </div>

            </div>

            <div class="botao">

                <a href="../login/logout.php" class="btn-login" style="font-size: 1.3rem;">logout</a>

                <button onclick="history.back()" class="btn-login" style="font-size: 1.3rem;"> Voltar </button>


            </div>

        </div>

        <!-- ==============
MODAL ALTERAR Dados
================ -->

        <!-- Modal escondido -->
        <div id="modal-editar" class="modal">
            <div class="modal-content">

                <button class="close close-btn" id="closeModalBtn" onclick="fecharModal()">
                    <i class="bi bi-x" style="font-size: 2rem;"></i>
                </button>

                <!-- <span class="close" onclick="fecharModal()">&times;</span>  -->

                <h2 style="font-family: 'Texto'; font-size:1.6rem; font-weight: bold;">Alterar Dados</h2>

                <form id="formEditar" method="POST" action="salvarDados.php">

                    <label for="nome" style="font-family: 'Texto'; font-size:1.2rem; font-weight: bold;">Nome:</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($_SESSION['nome']); ?>" required><br>

                    <label for="email" style="font-family: 'Texto'; font-size:1.2rem; font-weight: bold;">Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required><br>

                    <label for="senha_atual" style="font-family: 'Texto'; font-size:1.2rem; font-weight: bold;">Senha Atual:</label>
                    <input type="password" name="senha_atual" required><br>

                    <label for="nova_senha" style="font-family: 'Texto'; font-size:1.2rem; font-weight: bold;">Nova Senha:</label>
                    <input type="password" name="nova_senha" required><br>

                    <label for="confirmar_senha" style="font-family: 'Texto'; font-size:1.2rem; font-weight: bold;">Confirmar Nova Senha:</label>
                    <input type="password" name="confirmar_senha" required><br>

                    <button type="submit" style="font-family: 'Texto'; font-size:1.2rem; font-weight: bold;">Salvar</button>

                </form>
            </div>
        </div>

        <!-- ==============
MODAL ALTERAR Dados FINAL
================ -->

        <div class="troca-foto">

            <div class="troca">
                <h2>Trocar Foto de Perfil</h2>
            </div>

            <div class="avatar-container">


                <?php
                if ($tipo > 0) {
                    foreach ($avatarsAdm as $avatar1): ?>
                        <form method="POST" action="salvarFoto.php" style="display:inline;">
                            <input type="hidden" name="avatar1" value="frontend/public/img/<?php echo $avatar1; ?>">
                            <button type="submit" class="avatar">
                                <img src="../../frontend/public/img/<?php echo $avatar1; ?>" alt="Avatar">
                            </button>
                        </form>
                    <?php endforeach;
                } else {
                    foreach ($avatars as $avatar): ?>
                        <form method="POST" action="salvarFoto.php" style="display:inline;">
                            <input type="hidden" name="avatar" value="frontend/public/img/<?php echo $avatar; ?>">
                            <button type="submit" class="avatar">
                                <img src="../../frontend/public/img/<?php echo $avatar; ?>" alt="Avatar">
                            </button>
                        </form>
                <?php endforeach;
                } ?>

            </div>

            <div class="troca">
                <h2>Brinque com a Bubble</h2>
            </div>


            <!-- <div id="peixe">

                <div class="topo"></div>

                <div class="olho">
                    <div class="pupila"></div>
                </div>

                <div class="escama"></div>

                <div class="barba1"></div>
                <div class="barba2"></div>

            </div> -->

            <iframe src="index.html" width="100%" height="300" frameborder="0" title="Página 2" style="border: 2px solid #9FD5D1; border-radius: 20px;"></iframe>


            <!-- <p style="margin-top: 1rem; color:#333; font-family: 'Texto'; ">@diegoleme</p> -->

        </div>


    </div>

    <script>
        function abrirModal() {
            document.getElementById("modal-editar").style.display = "block";
        }

        function fecharModal() {
            document.getElementById("modal-editar").style.display = "none";
        }

        // Validação antes de enviar
        document.getElementById("formEditar").addEventListener("submit", function(e) {
            let senhaAtual = this.senha_atual.value;
            let nova = this.nova_senha.value;
            let confirmar = this.confirmar_senha.value;

            if (!senhaAtual) {
                alert("Digite sua senha atual.");
                e.preventDefault();
                return;
            }
            if (nova !== confirmar) {
                alert("A nova senha e a confirmação não coincidem!");
                e.preventDefault();
                return;
            }
        });

        const closeBtn = document.getElementById('closeModalBtn');

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
    </script>

</body>

</html>