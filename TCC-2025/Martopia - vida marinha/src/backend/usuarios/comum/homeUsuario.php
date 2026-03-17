<!DOCTYPE html>
<html lang="pt-br">

<?php
session_start();

if (!$_SESSION['tipo']  && !$_SESSION['logado']) {
    header('Location:../../../frontend/home.php');
}
include_once '../../classes/class_IRepositorioUsuarios.php';
include_once '../../classes/class_IRepositorioMemoria.php';
include_once '../../classes/class_IRepositorioQuiz.php';
include_once '../../classes/class_IRepositorioInstamar.php';
include_once '../../classes/class_IRepositorioConteudos.php';
include_once '../../classes/class_IRepositorioTermo.php';

$id = $_SESSION['id_usuario'];
// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);
$listM = $respositorioMemoria->obterRankingMemoria();
$rankingGeral = $respositorioQuiz->obterEstatisticasGeraisPorUsuario(10);
$rankingGeralTermo = $respositorioTermo->obterRankingTermo(10);
$rankingConteudos = $respositorioConteudo->obterRankingConteudos(10);
$notificacoes = $respositorioInstamar->buscarNotificacoesUsuario($id);

// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>logado - Projeto Martopia</title>

    <link rel="stylesheet" href="./ranking.css">
    <link rel="stylesheet" href="./homeComum.css">
    <link rel="stylesheet" href="../../../frontend/public/css/baseGeral.css">
    <link rel="stylesheet" href="../../../frontend/public/css/footer.css">

    <!-- <link rel="stylesheet" href="./estilos/tela.css" media="screen"> -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">


    <!-- Biblioteca Scroll -->
    <script defer src="https://unpkg.com/scrollreveal"></script>
    <script defer src="../../../frontend/js/bolhas.js"></script>
    <script defer src="../../../frontend/js/homeComum.js"></script>

</head>

<body>

    <style>
        @font-face {
            font-family: 'Titulo';
            src: url('../../../frontend/fontes/Título.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Texto';
            src: url('../../../frontend/fontes/Texto.otf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        #conteudo {
            display: flex;
            padding: 5%;
        }

        .rankings-container {
            display: flex;
            gap: 20px;
            width: 100%;
            max-width: 1200px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .ranking-box {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex: 1 1 400px;
            min-width: 300px;
            margin: 5px;
            height: 400px;
        }

        .ranking-box h2 {
            color: #38a0dd;
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Titulo';
            font-size: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ccc;
            font-family: 'Texto';
            font-size: 1.1rem;
        }

        thead {
            background-color: #81c0e9;
            color: #fff;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #f0f0f0;
        }

        tbody tr:hover {
            background-color: #d0f0ff;
            transform: scale(1.02);
            transition: 0.2s;
        }

        /* Destaque top 3 */
        tbody tr:first-child td {
            background-color: gold;
            font-weight: bold;
        }

        tbody tr:nth-child(2) td {
            background-color: silver;
            font-weight: bold;
        }

        tbody tr:nth-child(3) td {
            background-color: #cd7f32;
            font-weight: bold;
        }

        .scroll-container {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .navbar a {
            font-size: 1.3rem;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, .3);
        }

        h2#inicio {
            position: absolute;
            top: 33%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        #texto_in {
            position: absolute;
            top: 40%;
            left: 50%;
            padding-top: 2%;
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

        .rankings-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1rem;
            height: 100%;
            margin-top: 3rem;
        }

        #conteudos {
            display: block;
            width: 100%;
            max-width: 1400px;
            margin: 2rem auto 0 auto;
        }

        @media (max-width: 1450px){
            .rankings-container {
                margin-top: 18rem;
                display: flex;
                justify-content: center;
                align-items: center;
                grid-template-columns: repeat(1, 3fr);
            }
        }

        /* Telas médias (notebooks ↘) */
        @media (max-width: 1100px) {
            #inicio {
                font-size: 2.8rem;
            }

            #texto_in {
                font-size: 1.6rem;
                margin-top: 2rem;
            }

            .rankings-container {
                margin-top: 18rem;
                display: flex;
                justify-content: center;
                align-items: center;
                grid-template-columns: repeat(1, 3fr);
            }

        }

        /* Tablets */
        @media (max-width: 900px) {
            #inicio {
                font-size: 2rem;
            }

            #texto_in {
                font-size: 1.4rem;
                margin-top: 5rem;
            }

            .rankings-container {
                margin-top: 18rem;
            }
        }
    </style>


    <!-- INICIANDO O NAVBAR -->


    <svg id="onda" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 318">
        <path fill="#045a94" fill-opacity="1" d="M0,192L40,197.3C80,203,160,213,240,186.7C320,160,400,96,480,101.3C560,107,640,181,720,224C800,267,880,277,960,245.3C1040,213,1120,139,1200,106.7C1280,75,1360,85,1400,90.7L1440,96L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z"></path>
    </svg>



    <header class="header" style="height: 120px;">

        <div class="logo-marca" style="margin-left: -3rem;">
            <a href="./home.php" class="logo"><img src=".../../../../../frontend/public/img/Logo.png" alt="Logo-Projeto Martopia"></a>
            <p style="margin-left: -3rem;">Projeto <br> Martopia</p>
        </div>


        <input type="checkbox" id="check">
        <label for="check" class="icone">
            <i class="bi bi-list" id="menu-icone"></i>
            <i class="bi bi-x" id="sair-icone"></i>
        </label>


        <nav class="navbar">
            <a href="homeUsuario.php" style="--i:1;" class="active">Home</a>
            <a href="./instamar/instamar.php" style="--i:1;">InstaMar</a>
            <a href="./jogos/jogos.php" style="--i:2;">Jogos</a>
            <a href="./conteudos/conteudo.php" style="--i:3;">Conteúdos Educativos</a>

            <a href="../../trocar/trocarperfil.php"><img src="../../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil" class="perfil" style="--i:4;"></a>
        </nav>
    </header>

    <section class="ocean-bottom" id="ocean-bottom"></section>


    <!-- Bloco de conteúdo -->

    <main class="page-content" style="margin-top: 5rem; margin-bottom: 5rem;">
        <!-- Ranking Quiz -->



        <h2 id="inicio">OLÁ <?php echo $_SESSION['nome']; ?> </h2>
        <p id="texto_in" style="padding-top: 4%;">Aprenda a salvar a vida marinha de São Paulo com Martopia!</p>

        <div class="rankings-container">

            <div class="ranking-box" style="background-color: #fff; width: 450px;">
                <h2 style>Quiz</h2>
                <div class="scroll-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Posição</th>
                                <th>Nome</th>
                                <th>Acertos</th>
                                <th>Tempo</th>
                            </tr>
                        </thead>
                        <tbody id="rankingQuiz">
                            <?php foreach ($rankingGeral as $posicao => $jogador1):  ?>
                                <tr>
                                    <td>
                                        <?php echo $posicao + 1; ?>
                                        <?php if ($posicao == 0): ?>🥇
                                        <?php elseif ($posicao == 1): ?>🥈
                                        <?php elseif ($posicao == 2): ?>🥉
                                    <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($jogador1['nome']); ?>
                                        <?php if ($jogador1['nome'] === $_SESSION['nome']): ?>
                                            <span style="color: #38a0dd; font-size: 0.9em;"> (Você)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($jogador1['total_acertos']); ?>
                                    </td>
                                    <td>
                                        <?php
                                        // Converter segundos para formato mm:ss
                                        $minutos = floor($jogador1['total_tempo']  / 60);
                                        $segundos = $jogador1['total_tempo']  % 60;
                                        echo sprintf('%d:%02d', $minutos, $segundos);
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ranking Memória -->
            <div class="ranking-box" style="background-color: #fff; width: 450px;">
                <h2>Jogo da Memória</h2>
                <div class="scroll-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Posição</th>
                                <th>Nome</th>
                                <th>Tempo</th>
                            </tr>
                        </thead>
                        <tbody id="rankingMemoria">
                            <?php foreach ($listM as $index => $jogador): ?>
                                <tr>
                                    <td>
                                        <?php echo $index + 1; ?>
                                        <?php if ($index == 0): ?>🥇
                                        <?php elseif ($index == 1): ?>🥈
                                        <?php elseif ($index == 2): ?>🥉
                                    <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($jogador['nome']); ?>
                                        <?php if ($jogador['nome'] === $_SESSION['nome']): ?>
                                            <span style="color: #38a0dd; font-size: 0.9em;"> (Você)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        // Converter segundos para formato mm:ss
                                        $minutos = floor($jogador['melhor_tempo'] / 60);
                                        $segundos = $jogador['melhor_tempo'] % 60;
                                        echo sprintf('%d:%02d', $minutos, $segundos);
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- Ranking Termo -->
            <div class="ranking-box" style="background-color: #fff; width: 450px;">
                <h2>TerMar</h2>
                <div class="scroll-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Posição</th>
                                <th>Nome</th>
                                <th>Pontuação</th>
                            </tr>
                        </thead>
                        <tbody id="rankingMemoria">
                            <?php foreach ($rankingGeralTermo as $index => $jogador): ?>
                                <tr>
                                    <td>
                                        <?php echo $index + 1; ?>
                                        <?php if ($index == 0): ?>🥇
                                        <?php elseif ($index == 1): ?>🥈
                                        <?php elseif ($index == 2): ?>🥉
                                    <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($jogador['nome']); ?>
                                        <?php if ($jogador['nome'] === $_SESSION['nome']): ?>
                                            <span style="color: #38a0dd; font-size: 0.9em;"> (Você)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $jogador['pontuacao']; // já vem no formato HH:MM:SS

                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>




        <!-- Ranking Conteudos -->
        <div class="ranking-box" style="background-color: #fff;" id="conteudos">
            <h2>Conteúdos Educativos</h2>
            <div class="scroll-container">
                <table>
                    <thead>
                        <tr>
                            <th>Posição</th>
                            <th>Nome</th>
                            <th>Pontuação</th>
                        </tr>
                    </thead>
                    <tbody id="rankingConteudos">
                        <?php if (empty($rankingConteudos)): ?>
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 20px; color: #666;">
                                    Nenhum usuário ainda pontuou em conteúdos educativos
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($rankingConteudos as $index => $jogador): ?>
                                <tr <?php echo ($jogador['nome'] === $_SESSION['nome']) ? 'style="background-color: #e6f3ff; font-weight: bold;"' : ''; ?>>
                                    <td>
                                        <?php echo $index + 1; ?>
                                        <?php if ($index == 0): ?>🥇
                                        <?php elseif ($index == 1): ?>🥈
                                        <?php elseif ($index == 2): ?>🥉
                                    <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($jogador['nome']); ?>
                                        <?php if ($jogador['nome'] === $_SESSION['nome']): ?>
                                            <span style="color: #38a0dd; font-size: 0.9em;"> (Você)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo number_format($jogador['total_pontos'], 0, ',', '.'); ?></strong>
                                        <span style="color: #666; font-size: 0.8em;">pts</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <!-- Legenda de pontuação -->
                    <!-- <div style="margin-top: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 8px; font-size: 0.9em;">
                        <h4 style="margin: 0 0 10px 0; color: #38a0dd; font-family: 'Texto'; ">Sistema de Pontuação:</h4>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 5px;">
                            <div> <strong>Educação:</strong> 5 pontos</div>
                            <div> <strong>Artigos:</strong> 2 pontos</div>
                            <div> <strong>Livros:</strong> 3 pontos</div>
                            <div> <strong>Outros:</strong> 1 ponto</div>
                        </div>
                    </div> -->
                </table>
            </div>

        </div>

    </main>


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

    <!-- COMEÇO JAVASCRIPT  -->



</body>

</html>