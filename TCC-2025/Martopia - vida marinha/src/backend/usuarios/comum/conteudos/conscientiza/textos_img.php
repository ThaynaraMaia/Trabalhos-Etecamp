<!DOCTYPE html>
<html lang="pt-br">

<?php

session_start();
include_once '../../../../classes/class_IRepositorioUsuarios.php';
include_once '../../../../classes/class_IRepositorioConteudos.php';
$tipo = "Conscientização";
$categoria = $_GET['categoria'];
$id = $_SESSION['id_usuario'];
// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);
$listext = $respositorioConteudo->listarConscientizacaoportipo($tipo, $categoria);
// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Textos Imagem - Projeto Martopia</title>

    <link rel="stylesheet" href="./videos.css">
    <link rel="stylesheet" href="../../../../../frontend/public/css/baseGeral.css">
    <link rel="stylesheet" href="../../../../../frontend/public/css/logo.css">
    <link rel="stylesheet" href="../../../../../frontend/public/css/footer.css">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <script src="https://code.jscharting.com/latest/jscharting.js"></script>


</head>

<style>
    /* Reset para garantir largura total */
    html,
    body {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        width: 100%;
    }

    .header {
        box-shadow: 0 40px 60px rgba(0, 0, 0, 0.2);
    }

    body {
        background: #045A94;
        background: radial-gradient(circle, rgba(4, 90, 148, 1) 0%, rgba(129, 192, 233, 1) 50%, #c6e1fe);
    }

    /* Ajustes para o footer ocupar toda a largura */
    .page-content {
        width: 100%;
        overflow-x: hidden;
    }

    footer {
        width: 100%;

        box-sizing: border-box;
        display: grid !important;
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 3rem !important;
        align-items: start !important;
        background: #045a94;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, .3);
    }

    .cards-container {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        flex-wrap: wrap;
        gap: 20px;
        max-width: 1200px;
        margin: 0 auto;
        justify-content: center;
        align-items: center;
        margin-top: 10rem;
    }

    .card {
        flex: 0 0 calc(50% - 20px);
        background-color: #f0f8ff;
        border-radius: 20px;
        padding: 50px;
        box-shadow: 0 0 15px 3px #81c0e9;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 15px;
        cursor: pointer;
        transition: transform 0.2s ease;
        width: 100%;
        max-width: 1300px;
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

    .text {
        font-family: 'Texto';
        font-size: 18px;
        margin-top: 3rem;
        letter-spacing: 2px;
        color: #333;
    }

    .text-content {
        font-family: 'Texto', Arial, sans-serif;
        font-size: 18px;
        letter-spacing: 2px;
        color: #333;
        line-height: 1.6;
        text-align: justify;
        width: 100%;
        white-space: pre-wrap;
        word-wrap: break-word;
        overflow-wrap: break-word;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 10px;
    }

    /* Responsivo para cards */
    @media (max-width: 768px) {
        .text-content {
            font-size: 16px;
            padding: 15px;
        }

        .card {
            margin-left: 0;
            padding: 30px;
        }

        .cards-container {
            padding: 0 10px;
        }
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
        top: 10rem;
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

        <div class="iconeCentral">

            <img id="inicio" src="../IMG/imagem-textoM.png" alt="artigoIcon">

            <div class="centraliza">

                <h2>Textos Explicativos - Conscientização</h2>

                <br><br>

                <div>
                    <button onclick="history.back()" class="btn-voltar"> Voltar </button>
                </div>

            </div>

        </div>



        <div class="cards-container">

            <div class="card">

                <h3>
                    A poluição plástica nos oceanos
                </h3>

                <img src="../IMG/plásticos.png" alt="imgagem-plasticoOceano">


                <p style="text-align: center; font-size: 15px; font-family: 'Texto';">Fonte: conexaoplaneta.com.br</p>

                <div class="text">
                    <p>O oceano cobre mais de 70% do planeta e desempenha um papel essencial no equilíbrio climático e na manutenção da vida. <br>
                        A poluição plástica é um dos maiores desafios ambientais do século XXI e representa uma grave ameaça à vida marinha, aos ecossistemas costeiros e à própria humanidade. Estima-se que milhões de toneladas de plásticos sejam descartadas no ambiente todos os anos, sendo que uma parte significativa acaba chegando aos oceanos por meio de rios, esgoto mal gerenciado, descarte irregular e até pela ação do vento. <br>

                        Uma característica preocupante do plástico é sua durabilidade. Diferente de outros resíduos, ele não se decompõe de forma rápida, mas se fragmenta em pedaços cada vez menores, conhecidos como microplásticos (com menos de 5 mm) e nanoplásticos (invisíveis a olho nu). Essas partículas são ingeridas por organismos marinhos de todos os níveis da cadeia alimentar, desde plâncton até grandes predadores, acumulando-se nos tecidos e podendo causar obstruções, intoxicações e até a morte. <br>

                        Estudos mostram que aves marinhas, tartarugas, mamíferos e peixes ingerem resíduos plásticos confundindo-os com alimento, como águas-vivas ou pequenos crustáceos. Além disso, o plástico atua como vetor de poluentes químicos e microrganismos, aumentando os riscos ecológicos e de saúde. <br>

                        Do ponto de vista socioeconômico, a poluição plástica também afeta a pesca, o turismo e comunidades costeiras que dependem diretamente da saúde dos mares. O impacto econômico global associado à poluição marinha por plásticos é estimado em bilhões de dólares por ano. <br>

                        Portanto, combater a poluição plástica não é apenas uma questão ambiental, mas também uma necessidade vital para o futuro da humanidade. <br> <br>

                        Algumas formas de como você pode ajudar nessa causa: <br> <br>

                    <ul>
                        <li>Reduzir o consumo de plástico descartável, evitando itens como canudos, copos, talheres, sacolas plásticas e embalagens de uso único.</li>
                        <li>Reciclar corretamente, separando o seu lixo em orgânico e em reciclável. Deposite o que for reciclável em pontos de coleta (Como indicado na página de <a href="#">"Pontos de Coleta Reciclável em SP"</a> ).</li>
                        <li>Reutilizar embalagens, tranformando garrafas e outros objetos, em itens úteis do dia a dia, como organizadores ou vasos de planta.</li>
                    </ul>
                    </p>
                </div>

            </div>

            <?php
            if (empty($listext)) {
            ?>
                <h2>Nenhum texto explicativo postado pelo Biologo</h2>
                <?php
            } else {
                foreach ($listext as $posicao => $linha): ?>
                    <div class="card">

                        <h3>
                            <?php echo htmlspecialchars($linha['titulo']); ?>
                        </h3>
                        <img src="../../../../../frontend/public/img_conscientizacao/<?php echo htmlspecialchars($linha['caminho_img']) ? $linha['caminho_img'] :  "artigo.png" ?>"
                            alt="artigoIcon">

                        <p style="text-align: center; font-size: 15px; font-family: 'Texto';">Fonte: <?php echo htmlspecialchars($linha['legenda']); ?></p>
                        <div class="text">

                            <div class="text-content">
                                <?php echo htmlspecialchars($linha['texto']); ?>
                            </div>
                        </div>
                    </div>
            <?php endforeach;
            } ?>

        </div>

    </div>

<br> <br> <br>

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
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3666.2168153595317!2d-46.766872!3d-23.235196!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94cede166027baab%3A0x566fc4df5821546c!2sEscola%20T%C3%A9cnica%20Estadual%20de%20Campo%20Limpo%20Paulista!5e0!3m2!1spt-BR!2sbr!4v1756695006929!5m2!1spt-BR!2sbr"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Mapa do local">
            </iframe>
        </div>

        <div class="copyright">
            <p>&copy; 2025 Projeto Martopia. Todos os direitos reservados.</p>
        </div>
    </footer>


    <script src="../../../../frontend/js/conteudo.js"></script>


</body>

</html>