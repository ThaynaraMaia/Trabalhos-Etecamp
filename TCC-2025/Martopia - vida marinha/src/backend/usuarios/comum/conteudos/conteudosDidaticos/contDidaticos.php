<!DOCTYPE html>
<html lang="pt-br">

<?php

session_start();
include_once '../../../../classes/class_IRepositorioUsuarios.php';
$_SESSION['tipo'] = $_GET['tipo'];
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
            <a href="../../../../trocar/trocarperfil.php"><img src="../../../../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil" class="perfil"></a>
        </nav>
    </header>


    <div class="page-content">

        <h1 id="inicio" style="width: 100%; max-width: 1200px;"> Conteúdos Didáticos para a sua aprendizagem! </h1>

        <p id="texto_in"> Clique na sua forma preferida de estudo. </p>

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
                    /* Alinha verticalmente */
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

                .card img {
                    width: 200px;
                    height: 200px;
                    color: #38a0dd;
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

                header {
                    box-shadow: 0 40px 60px rgba(0, 0, 0, 0.2);
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


            <a href="./artigos.php?categoria=Artigos">

                <div class="card">
                    <img src="./img/artigos.png" alt="tartaruga-marinha" style="width: 90px; height:90px;">
                    <h3>Artigos</h3>
                </div>

            </a>

            <a href="./livros.php?categoria=Livros">

                <div class="card">
                    <img src="./img/livro.png" alt="tubarão-martelo" style="width: 90px; height:90px;">
                    <h3>Livros</h3>
                </div>

            </a>

            <a href="./documentarios.php?categoria=Documentarios">

                <div class="card">
                    <img src="./img/documentario.png" alt="baleia" style="width: 90px; height:90px;">
                    <h3>Documentários</h3>
                </div>

            </a>

            <a href="./filmes.php?categoria=Filmes">

                <div class="card">
                    <img src="./img/filme.png" alt="arraia" style="width: 90px; height:90px;">
                    <h3>Filmes</h3>
                </div>

            </a>

            <a href="./podcasts.php?categoria=Podcasts">

                <div class="card">
                    <img src="./img/podacast.png" alt="golfinho" style="width: 90px; height:90px;">
                    <h3>Podcasts</h3>
                </div>

            </a>

            <a href="./guiaCampo.php?categoria=Guias de Campo">

                <div class="card">
                    <img src="./img/guiaCampo.png" alt="água-viva" style="width: 90px; height:90px;">
                    <h3>Guias de Campo</h3>
                </div>

            </a>

            <a href="./cursos.php?categoria=Cursos">

                <div class="card">
                    <img src="./img/curso.png" alt="polvo" style="width: 90px; height:90px;">
                    <h3>Cursos</h3>
                </div>

            </a>

            <a href="./projetos.php?categoria=Projetos para Conhecer">

                <div class="card">
                    <img src="./img/projetos.png" alt="Moreia" style="width: 90px; height:90px;">
                    <h3>Projetos para Conhecer</h3>
                </div>

            </a>

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