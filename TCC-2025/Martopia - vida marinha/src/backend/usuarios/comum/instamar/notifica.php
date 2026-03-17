<!DOCTYPE html>
<html lang="pt-br">

<?php
session_start();

include_once '../../../classes/class_IRepositorioUsuarios.php';
include_once '../../../classes/class_IRepositorioInstamar.php';

$id = $_SESSION['id_usuario'];

$dados = $respositorioUsuario->buscarUsuario($id);
$notificacoes = $respositorioInstamar->buscarNotificacoesUsuario($id);

$foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Conteúdos - Projeto Martopia</title>

    <link rel="stylesheet" href="../../../../frontend/public/css/base.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/instamar.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/footer.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

</head>

<body>

    <!-- NAVBAR  -->
    <header class="header">
        <style>
            @font-face {
                font-family: 'Titulo';
                src: url('../../../../frontend/fontes/Título.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
            }

            @font-face {
                font-family: 'Texto';
                src: url('../../../../frontend/fontes/Texto.otf') format('truetype');
                font-weight: normal;
                font-style: normal;
            }

            .header {
                height: 120px;
                box-shadow: 0 40px 60px rgba(0, 0, 0, 0.2);
                font-family: 'Texto';
            }

            body {
                background: #c6e1fe;
                background: linear-gradient(132deg, #9fcaec 0%, #81c0e9 50%, #045a94 100%);
            }

            .notificacoes {
                background-color: #f9fbfd;
                border-radius: 16px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
                padding: 30px 40px;
                margin: 40px auto;
                max-width: 1200px;
                width: 100%;
                transition: box-shadow 0.3s ease;
            }

            .notificacoes:hover {
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5);
            }

            .notificacoes h2 {
                color: #045a94;
                text-align: center;
                margin-bottom: 30px;
                font-family: 'Titulo', sans-serif;
                font-size: 2.4rem;
                letter-spacing: 1px;
                text-transform: uppercase;
            }

            .notificacoes-container {
                max-height: 450px;
                overflow-y: auto;
                padding-right: 10px;
            }

            .notificacao-item {
                background-color: #ffffff;
                border-radius: 12px;
                padding: 20px 25px;
                margin-bottom: 20px;
                box-shadow: 0 4px 12px rgba(30, 136, 229, 0.1);
                transition: background-color 0.3s ease;
                cursor: default;
            }

            .notificacao-item:hover {
                background-color: #e3f2fd;
            }

            .notificacao-item p {
                margin: 0 0 8px 0;
                font-family: 'Texto', sans-serif;
                font-size: 1.15rem;
                line-height: 1.6;
                color: #333;
            }

            .notificacao-item small {
                display: block;
                color: #666;
                font-size: 0.9rem;
                font-style: italic;
            }

            .notificacoes-container::-webkit-scrollbar {
                width: 8px;
            }

            .notificacoes-container::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 8px;
            }

            .notificacoes-container::-webkit-scrollbar-thumb {
                background-color: #1e88e5;
                border-radius: 8px;
                border: 2px solid #f1f1f1;
            }

            .navbar a {
                font-size: 1.5rem;
            }

            .perfil {
                width: 80px;
                height: 80px;
                margin-left: -3rem;
                border: 1.5px solid #e18451;
                /* color: #81c0e9; */
            }

            .header {
                left: 0;
                width: 100%;
                padding: 1.6rem 1rem;
            }

            nav a.active {
                color: #c6e1fe;
                font-weight: bold;
                text-shadow: 0px 3px 6px #045a94;
            }
        </style>

        <div class="logo-marca" style="margin-left: -3rem;">
            <a href="./home.php" class="logo"><img src="../../../../frontend/public/img/Logo.png" alt="Logo-Projeto Martopia"></a>
            <p style="margin-left: -3rem;">Projeto <br> Martopia</p>
        </div>

        <input type="checkbox" id="check">
        <label for="check" class="icone">
            <i class="bi bi-list" id="menu-icone"></i>
            <i class="bi bi-x" id="sair-icone"></i>
        </label>

        <nav class="navbar">
            <a href="../homeUsuario.php" style="--i:1;">Home</a>
            <a href="./instamar.php" style="--i:1;" class="active">InstaMar</a>
            <a href="../jogos/jogos.php" style="--i:2;">Jogos</a>
            <a href="../conteudos/conteudo.php" style="--i:3;">Conteúdos Educativos</a>
            <a href="../../../trocar/trocarperfil.php"><img src="../../../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil" class="perfil"></a>
        </nav>
    </header>

    <div class="page-content">
        <div class="perfil_card">
            <div class="perfil-user">
                <img src="../../../../<?php echo htmlspecialchars($foto); ?>" alt="Imagem de Peril" id="img-perfil">
                <div class="perfil-btn">
                    <h2 style="font-family: 'Texto';"> <?php echo $_SESSION['nome']; ?> </h2>
                    <br>
                    <a href="./instamar.php" class="botao" style="margin-left:42px; font-family: 'Texto'; font-size: 1.4rem;"> Voltar </a>
                </div>
            </div>
        </div>



        <div class="notificacoes">
            <h2>Notificações</h2>

            <div class="notificacoes-container">
                <?php
                // Verifica se há notificações para exibir
                if (!empty($notificacoes)) {
                    foreach ($notificacoes as $notificacao) {
                        // Formata a data para um formato mais legível (d/m/Y H:i:s)
                        $data_formatada = date('d/m/Y H:i:s', strtotime($notificacao['data_envio'])); ?>

                        <div class="notificacao-item">
                            <p><strong>Mensagem:</strong> <?php echo htmlspecialchars($notificacao['mensagem']) ?></p>
                            <small>Enviada em:<?php echo $data_formatada ?></small>
                        </div>
                    <?php
                    }
                } else { ?>
                    <p>Nenhuma notificação encontrada.</p>
                <?php
                }
                ?>
            </div>
        </div>


    </div>

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
            <p> &copy; 2025 Projeto Martopia. Todos os direitos reservados.</p>
        </div>
    </footer>


</body>

</html>