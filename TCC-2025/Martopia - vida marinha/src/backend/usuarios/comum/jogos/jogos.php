<!DOCTYPE html>
<html lang="pt-br">
<?php


session_start();
if (!$_SESSION['tipo']  && !$_SESSION['logado']) {
    header('Location:../../../frontend/home.php');
}
include_once '../../../classes/class_IRepositorioUsuarios.php';
$id = $_SESSION['id_usuario'];
// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);
// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogos</title>

    <link rel="stylesheet" href="../../../../frontend/public/css/baseGeral.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/jogos.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/logo.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/footer.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Biblioteca Scroll -->
    <script src="https://unpkg.com/scrollreveal"></script>


</head>

<body>

    <style>
        .header {
            box-shadow: 0 40px 60px rgba(0, 0, 0, 0.2);
        }

        .efeito-card1,
        .efeito-card2 {
            visibility: hidden;
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

    <svg id="onda" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 318">
        <path fill="#045494" fill-opacity="1" d="M0,192L40,197.3C80,203,160,213,240,186.7C320,160,400,96,480,101.3C560,107,640,181,720,224C800,267,880,277,960,245.3C1040,213,1120,139,1200,106.7C1280,75,1360,85,1400,90.7L1440,96L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z"></path>
    </svg>

    <header class="header">

        <div class="logo-marca" style="margin-left: -3rem;">
            <a href="./home.php" class="logo"><img src=".../../../../../../frontend/public/img/Logo.png" alt="Logo-Projeto Martopia"></a>
            <p style="margin-left: -3rem;">Projeto <br> Martopia</p>
        </div>


        <input type="checkbox" id="check">
        <label for="check" class="icone">
            <i class="bi bi-list" id="menu-icone"></i>
            <i class="bi bi-x" id="sair-icone"></i>
        </label>


        <nav class="navbar">
            <a href="../homeUsuario.php" style="--i:1;">Home</a>
            <a href="../instamar/instamar.php" style="--i:1;">InstaMar</a>
            <a href="./jogos.php" style="--i:2;" class="active">Jogos</a>
            <a href="../conteudos/conteudo.php" style="--i:3;">Conteúdos Educativos</a>
            <a href="../../../trocar/trocarperfil.php"><img src="../../../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil" class="perfil" style="--i:4;"></a>
        </nav>

    </header>

    <div class="page-content">



        <h1 id="inicio">Bem -Vindo aos Jogos Martopia! </h1>

        <p id="texto_in">Conheça uma nova forma de estudar e conhecer sobre a vida marinha do litoral paulista, escolha um dos jogos e se divirta!</p>

        <main id="conteudo">

            <!-- <h1>Área de Jogos</h1>  -->

            <div>

                <style>
                    .card {
                        background: transparent;
                        filter: blur(.2px);
                        border-radius: 20px;
                        box-shadow: 0 0 15px 3px #81c0e9;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        cursor: default;
                        transition: transform 0.3s ease, box-shadow 0.3s ease;
                        height: 400px;
                        padding: 10px;
                        cursor: pointer;
                    }

                    .card i {
                        font-size: 4rem;
                        margin-bottom: 1rem;
                        color: #000;
                    }

                    .efeito-card3 {
                        visibility: hidden;
                    }
                </style>

                <div class="card-container efeito-card1">
                    <!-- Card 1 -->
                    <div class="card">

                        <i class="bi bi-question-circle"></i>

                        <div class="card-info">
                            <h2 style="font-family: 'Texto'; font-size: 1.5rem; letter-spacing: 2px;">Quiz</h2>
                            <br>
                            <a href="./Quiz/niveis.php" style="font-family: 'Texto'; font-size: 1.1rem; letter-spacing: 1px;">Jogue Agora!</a>
                        </div>

                    </div>

                    <!-- Card 2 -->
                    <div class="card efeito-card2">

                        <img src="./img/jogo_memoria.png" alt="Memoria" style="width: 90px; height: 90px">

                        <div class="card-info">
                            <h2 style="font-family: 'Texto'; font-size: 1.5rem; letter-spacing: 2px;">Jogo da Memória</h2>
                            <br>
                            <a href="./Memoria/niveisM.php" style="font-family: 'Texto'; font-size: 1.1rem; letter-spacing: 1px;">Jogue Agora!</a>
                        </div>

                    </div>


                    <!-- Card 3 -->
                    <div class="card efeito-card3">

                        <img src="./img/termo.png" alt="Memoria" style="width: 90px; height: 90px">

                        <div class="card-info">
                            <h2 style="font-family: 'Texto'; font-size: 1.5rem; letter-spacing: 2px;">TerMar</h2>
                            <br>
                            <a href="./termo/niveisT.php"style="font-family: 'Texto'; font-size: 1.1rem; letter-spacing: 1px;">Jogue Agora!</a>
                        </div>

                    </div>

                </div>


            </div>

        </main>

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


    <script src="../../../../frontend/js/jogos.js"></script>

    <script defer>
        window.efeitoScroll = ScrollReveal({
            reset: false
        }) // reset false para rodar só uma vez


        // RANKING

        efeitoScroll.reveal('.efeito-card1', {
            duration: 2000,
            distance: '100px',
            delay: 300,
            origin: 'top'
        })

        efeitoScroll.reveal('.efeito-card2', {
            duration: 2000,
            distance: '100px',
            delay: 700,
            origin: 'top'
        })

        efeitoScroll.reveal('.efeito-card3', {
            duration: 2000,
            distance: '100px',
            delay: 1100,
            origin: 'top'
        })
    </script>

</body>

</html>