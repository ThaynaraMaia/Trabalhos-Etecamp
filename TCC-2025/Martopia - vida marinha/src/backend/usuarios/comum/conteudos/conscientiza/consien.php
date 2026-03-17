<!DOCTYPE html>
<html lang="pt-br">

<?php

session_start();
include_once '../../../../classes/class_IRepositorioUsuarios.php';
$id = $_SESSION['id_usuario'];
// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);
// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>logado - Projeto Martopia</title>

    <link rel="stylesheet" href="./videos.css">
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
        background: radial-gradient(circle, rgba(4, 90, 148, 1) 0%, rgba(129, 192, 233, 1) 50%, #9fcaec);
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

    <svg id="onda" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 318">
        <path fill="#045a94" fill-opacity="1" d="M0,192L40,197.3C80,203,160,213,240,186.7C320,160,400,96,480,101.3C560,107,640,181,720,224C800,267,880,277,960,245.3C1040,213,1120,139,1200,106.7C1280,75,1360,85,1400,90.7L1440,96L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z"></path>
    </svg>

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
            <a href="../../../../trocar/trocarperfil.php"><img src="../../../../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil" class="perfil" style="--i:4;"></a>
        </nav>
    </header>


    <div class="page-content">

        <h1 id="inicio" style="width: 100%; max-width: 1200px;"> Formas de Conscientização </h1>

        <p id="texto_in"> Escolha qual tipo de coteúdo coscientizador você prefere.</p>

        <div class="cards-container">

            <style>
                .cards-container {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    flex-wrap: wrap;
                    gap: 20px;
                    max-width: 1200px;
                    margin: 0 auto;
                    justify-content: center;
                    align-items: center;
                    margin-top: 3rem;
                }

                .card {
                    flex: 0 0 calc(50% - 20px);
                    background-color: #f0f8ff;
                    border-radius: 20px;
                    padding: 20px;
                    box-shadow: 0 0 15px 3px #81c0e9;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    gap: 15px;
                    cursor: pointer;
                    transition: transform 0.2s ease;
                    width: 600px;
                    margin-left: 2rem;
                    margin-top: 5rem;
                }

                .card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 0 25px 5px #38a0dd;

                }

                .card h3 {
                    margin: 0;
                    font-size: 1.5rem;
                    color: #38a0dd;
                    font-family: 'Texto';
                    font-size: 2rem;
                    letter-spacing: 2px;
                }

                .cards-container a {
                    text-decoration: none;
                }


                /* introdução */

                h1#inicio {
                    position: absolute;
                    top: 35%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    text-align: center;
                    font-size: 4rem;
                    z-index: 1;
                    text-transform: uppercase;
                    font-family: 'Titulo';
                    color: #fff;
                    letter-spacing: 5px;
                    letter-spacing: 2px;
                    text-shadow: 2px 2px 4px rgba(0, 0, 0, .5);
                }

                #texto_in {
                    position: absolute;
                    top: 41%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    text-align: center;
                    font-size: 1.8rem;
                    letter-spacing: 3px;
                    color: #fff;
                    font-family: 'Texto';
                    padding-top: 5%;
                    letter-spacing: 2px;
                    text-shadow: 2px 2px 4px rgba(0, 0, 0, .5);
                }
            </style>


            <a href="./noticias.php?categoria=Noticias">

                <div class="card">
                    <img src="../IMG/noticiaM.png" alt="tartaruga-marinha" style="width: 90px; height:90px;">
                    <h3>Notícias</h3>
                </div>

            </a>

            <a href="./graficos.php">

                <div class="card">
                    <img src="../IMG/graficoM.png" alt="tubarão-martelo" style="width: 90px; height:90px;">
                    <h3>Infográficos</h3>
                </div>

            </a>

            <a href="./textos_img.php?categoria=Texto">

                <div class="card">
                    <img src="../IMG/imagem-textoM.png" alt="baleia" style="width: 90px; height:90px;">
                    <h3>Textos Explicativos</h3>
                </div>

            </a>

            <a href="./videosC.php?categoria=Videos">

                <div class="card">
                    <img src="../IMG/videoM.png" alt="arraia" style="width: 90px; height:90px;">
                    <h3>Vídeos</h3>
                </div>

            </a>

        </div>

    </div>



    <footer style="margin-top:10rem; background: #045a94;text-shadow: 2px 2px 4px rgba(0, 0, 0, .3);">
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
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3666.2168153595317!2d-46.766872!3d-23.235196!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94cede166027baab%3A0x566fc4df5821546c!2sEscola%20T%C3%A9cnica%20Estadual%20de%20Campo%20Limpo%20Paulista!5e0!3m2!1spt-BR!2sbr!4v1756695006929!5m2!1spt-BR!2sbr                allowfullscreen="" loading=" lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Mapa do local">
            </iframe>
        </div>

        <div class="copyright">
            &copy; 2025 Projeto Martopia. Todos os direitos reservados.
        </div>
    </footer>

    <!-- COMEÇO JAVASCRIPT  -->

    <script src="../../../../frontend/js/conteudo.js"></script>


    <!-- <style>
    body{
        background: #dbf8ff;
    }
</style> -->

</body>

</html>