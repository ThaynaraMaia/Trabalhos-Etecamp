<!DOCTYPE html>
<html lang="pt-br">

<?php

session_start();
include_once '../../../../classes/class_IRepositorioUsuarios.php';
include_once '../../../../classes/class_IRepositorioConteudos.php';
$tipo =  $_SESSION['tipo'];
$categoria = $_GET['categoria'];
$id = $_SESSION['id_usuario'];
// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);
// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';

$listarpotipo = $respositorioConteudo->listarConteudosportipo($tipo, $categoria);

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projetos - Projeto Martopia</title>

    <link rel="stylesheet" href="./contDidaticos.css">
    <link rel="stylesheet" href="../../../../../frontend/public/css/baseGeral.css">
    <link rel="stylesheet" href="../../../../../frontend/public/css/logo.css">
    <link rel="stylesheet" href="../../../../../frontend/public/css/footer.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Biblioteca Scroll -->
    <script src="https://unpkg.com/scrollreveal"></script>

</head>

<style>
    .header {
        box-shadow: 0 40px 60px rgba(0, 0, 0, 0.2);
    }

    body {
        background: #045A94;
        background: radial-gradient(circle, rgba(4, 90, 148, 1) 0%, rgba(129, 192, 233, 1) 50%, #c6e1fe);
    }

    .card-buttons button:disabled {
        background-color: #b0c4de;
        /* cinza azulado mais claro */
        color: #666;
        /* texto mais apagado */
        cursor: not-allowed;
        /* cursor de bloqueado */
        opacity: 0.6;
        /* um pouco transparente */
        box-shadow: none;
        /* sem sombra para parecer "inativo" */
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

    .iconeCentral {
        background: transparent;
        border-radius: 20px;
        width: 100%;
        max-width: 1200px;
        font-weight: bold;
        filter: blur(.2px);
        box-shadow: 0 0 15px 3px #81c0e9;
        height: 100vh;
        max-height: 250px;
        align-items: center;
        justify-content: center;
        position: relative;
        top: 3rem;
        left: auto;
        padding: 2rem;
        display: flex;
        gap: 2rem;
        margin: 5% 0;
        margin-bottom: 8rem;
        font-family: 'Texto';
    }

    .centraliza {
        display: flex;
        justify-content: center;
        text-align: center;
        flex-direction: column;
    }

    .iconeCentral h2 {
        font-size: 1.5rem;
        color: #fff;
    }

    .btn-voltar {
        padding: 0.8rem 1.4rem;
        background: linear-gradient(135deg, #c6e1f6, #9fcaec);
        color: #045a94;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 1.1rem;
        font-family: 'Texto', serif;
        border-radius: 25px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
    }

    .btn-voltar:hover {
        background: linear-gradient(135deg, #81C0E9, #38a0dd);
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.35);
    }
</style>

<body>

    <!-- <svg id="onda" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 318">
        <path fill="#045a94" fill-opacity="1" d="M0,192L40,197.3C80,203,160,213,240,186.7C320,160,400,96,480,101.3C560,107,640,181,720,224C800,267,880,277,960,245.3C1040,213,1120,139,1200,106.7C1280,75,1360,85,1400,90.7L1440,96L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z"></path>
    </svg> -->

    <!-- INICIANDO O NAVBAR -->



    <header class="header">

        <div class="logo-marca" style="margin-left: -3rem;">
            <a href="../../homeUsuario.php" class="logo"><img src="../../../../../frontend/public/img/Logo.png" alt="Logo-Projeto Martopia"></a>
            <p style="margin-left: -3rem;">Projeto <br> Martopia</p>
        </div>


        <input type="checkbox" id="check">
        <label for="check" class="icone">
            <i class="bi bi-list" id="menu-icone"></i>
            <i class="bi bi-x" id="sair-icone"></i>
        </label>


        <nav class="navbar">
            <a href="../../homeUsuario.php" style="--i:1;">Home</a>
            <a href="../../instamar/instamar.php" style="--i:1;">InstaMar</a>
            <a href="../../jogos/jogos.php" style="--i:2;">Jogos</a>
            <a href="../conteudo.php" style="--i:3;" class="active">Conteúdos Educativos</a>
            <a href="../../../../trocar/trocarperfil.php"><img src="../../../../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil" class="perfil"></a>
        </nav>
    </header>


    <div class="page-content">

        <div class="iconeCentral">

            <img id="inicio" src="./img/projetosM.png" alt="projetosIconIcon">

            <div class="centraliza">

                <h2>Projetos - Vida Marinha</h2>

                <br><br>

                <div>
                    <button onclick="history.back()" class="btn-voltar"> Voltar </button>
                </div>

            </div>

        </div>

        <div class="cards-container">

            <style>
                .cards-container {
                    display: grid;
                    grid-template-columns: repeat(1, 2fr);
                    flex-wrap: wrap;
                    gap: 20px;
                    max-width: 1200px;
                    margin: 0 auto;
                    justify-content: center;
                    align-items: center;
                    margin-top: 3rem;
                }

                .card {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    background-color: #f0f8ff;
                    border-radius: 20px;
                    padding: 20px;
                    box-shadow: 0 0 15px 3px #81c0e9;
                    gap: 20px;
                    width: 1200px;
                    margin-left: 2rem;
                    margin-top: 5rem;
                    cursor: pointer;
                    transition: transform 0.2s ease;
                }

                .card a {
                    text-decoration: none;
                    color: #fff;
                    font-family: 'Texto';
                    letter-spacing: 2px;
                }

                .card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 0 25px 5px #38a0dd;

                }

                .card img {
                    border-radius: 20px;

                }

                .img-text {
                    display: flex;
                    align-items: center;
                    gap: 20px;
                }

                .img-text h2 {
                    font-family: 'Texto';
                    font-size: 1.3em;
                    margin: 0;
                    width: 600px;
                    padding-left: 2rem;
                }

                /* Estilos adicionais para os botões */
                .card-buttons button {
                    padding: 10px 20px;
                    border: none;
                    border-radius: 8px;
                    background-color: #81c0e9;
                    color: white;
                    font-weight: bold;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    font-family: 'Texto';
                    font-size: 1rem;
                    letter-spacing: 2px;
                    position: relative;
                    overflow: hidden;
                }

                .card-buttons button:hover:not(:disabled) {
                    background-color: #2a78b4;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                }

                .card-buttons button:disabled {
                    background-color: #b0c4de;
                    color: #666;
                    cursor: not-allowed;
                    opacity: 0.7;
                    box-shadow: none;
                    transform: none;
                }

                /* Animação de loading */
                .card-buttons button.loading::after {
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 20px;
                    height: 20px;
                    margin: -10px 0 0 -10px;
                    border: 2px solid transparent;
                    border-top: 2px solid #fff;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                }

                @keyframes spin {
                    0% {
                        transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                    }
                }

                /* Estados específicos dos botões */
                .btn-lido.sucesso {
                    background-color: #28a745 !important;
                    color: white !important;
                }

                .btn-lido.aguardando {
                    background-color: #ffa500 !important;
                    color: white !important;
                }

                /* Efeito visual para botão "Ler Novamente" */
                .btn-ler.releitura {
                    background-color: #17a2b8 !important;
                    border: 2px solid #0f7a8a;
                }

                .btn-ler.releitura:hover {
                    background-color: #0f7a8a !important;
                    transform: translateY(-2px);
                }

                /* Notificações personalizadas */
                .custom-notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 15px 20px;
                    border-radius: 8px;
                    color: white;
                    font-weight: bold;
                    z-index: 9999;
                    font-size: 16px;
                    max-width: 350px;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                    transform: translateX(400px);
                    transition: transform 0.3s ease-in-out;
                }

                .custom-notification.success {
                    background-color: #28a745;
                }

                .custom-notification.error {
                    background-color: #dc3545;
                }

                .custom-notification.info {
                    background-color: #17a2b8;
                }

                /* Efeito de pulse para chamar atenção */
                @keyframes pulse {
                    0% {
                        box-shadow: 0 0 0 0 rgba(129, 192, 233, 0.7);
                    }

                    70% {
                        box-shadow: 0 0 0 10px rgba(129, 192, 233, 0);
                    }

                    100% {
                        box-shadow: 0 0 0 0 rgba(129, 192, 233, 0);
                    }
                }

                .btn-lido:not(:disabled):not(.ja-lido) {
                    animation: pulse 2s infinite;
                }
            </style>


            <!-- 
            <div class="card">

                <div class="img-text">

                    <img src="./img/images.png" alt="artigoIcon" style="width: 150px; height:120px; object-fit:contain; border-radius:20px;">
                    <h2>"Projeto Maui"</h2>

                </div>

                <div class="card-buttons">

                    <button> <a href="https://projetomaui.com.br/"> Conhecer Projeto </a> </button>

                    <a href="../../../../../frontend/public/artigos/35_oceanografia.pdf" download="Descobertas-no-mar-paulista.pdf"> <button> Baixar Artigo </button> </a>

                </div>

            </div> -->
            <?php foreach ($listarpotipo as $posicao => $linha):  ?>
                <div class="card">

                    <div class="img-text">

                        <img src="../../../../../frontend/public/img_conteudos/<?php echo $linha['caminho_img'] ? $linha['caminho_img'] : "oceano.png" ?>" alt="artigoIcon" style="width: 150px; height:120px; object-fit:contain;">
                        <h2><?php echo $linha['titulo'];  ?></h2>

                    </div>
                    <div class="card-buttons">


                        <?php
                        // VERIFICAÇÃO SE O ARTIGO JÁ FOI LIDO
                        $jaLido = false;
                        if ($id) {
                            $jaLido = $respositorioConteudo->artigoJaLido($id, $linha['id']);
                        }
                        ?>
                        <?php if ($jaLido): ?>
                            <!-- Se já foi lido, apenas o botão "Marcar Como Lido" fica desabilitado -->
                            <button class="btn-ler" data-id-conteudo="<?php echo htmlspecialchars($linha['id']); ?>" data-tipo="educacao">
                                <a href="<?php echo htmlspecialchars($linha['link']); ?>"
                                    target="_blank"
                                    style="color: white; text-decoration: none;font-size: 1.2rem;">
                                    Ver Novamente
                                </a>
                            </button> <br> <br>
                            <button class="btn-lido" disabled style="background-color: #28a745; color: white; cursor: not-allowed;font-size: 1.2rem;">
                                ✓ Já visto
                            </button> <br> <br>
                        <?php else: ?>
                            <!-- Botões normais para conteúdos não lidos -->
                            <button class="btn-ler" data-id-conteudo="<?php echo htmlspecialchars($linha['id']); ?>" data-tipo="educacao">
                                <a href="<?php echo htmlspecialchars($linha['link']); ?>"
                                    target="_blank"
                                    style="color: white; text-decoration: none; font-size: 1.2rem;">
                                    Conhecer Projeto
                                </a>
                            </button> <br> <br>

                            <button class="btn-lido"
                                data-id-conteudo="<?php echo htmlspecialchars($linha['id']); ?>" data-tipo="educacao"
                                disabled
                                style="background-color: #b0c4de; color: #666; cursor: not-allowed; font-size: 1.2rem;">
                                Veja primeiro
                            </button>
                        <?php endif; ?>

                    </div>

                </div>
            <?php endforeach; ?>


        </div>

    </div>

    <br> <br> <br>

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
                <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
            </div>
        </div>

        <div class="mapa">
            <h3>Localização</h3>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3656.548882243676!2d-46.65639078446302!3d-23.564280468272937!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ce59cdbc1e56f5%3A0x5d7dbe91edb2db03!2sAv.%20Paulista%2C%20S%C3%A3o%20Paulo%20-%20SP%2C%20Brasil!5e0!3m2!1spt-BR!2sus!4v1600000000000!5m2!1spt-BR!2sus"
                allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Mapa do local">
            </iframe>
        </div>

        <div class="copyright">
            &copy; 2025 Projeto Martopia. Todos os direitos reservados.
        </div>
    </footer>
    <script src="lido.js"></script>
</body>

</html>