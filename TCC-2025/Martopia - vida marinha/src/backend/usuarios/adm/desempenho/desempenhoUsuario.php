<!DOCTYPE html>
<html lang="pt-br">
<?php
session_start();

if (!$_SESSION['tipo'] && !$_SESSION['logado']) {
    header('Location:../../../../frontend/home.php');
}

$usuario = $_SESSION['nome'];
include_once '../../../classes/class_IRepositorioUsuarios.php';
include_once '../../../classes/class_IRepositorioQuiz.php';
include_once '../../../classes/class_IRepositorioConteudos.php';

$id_usuario = $_GET['id'];
$id = $_SESSION['id_usuario'];

// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);
// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';

$registroUsuario = $respositorioUsuario->listarUsuarios($id);
$melhores_resultados = $respositorioQuiz->obterMelhorResultadoPorDificuldade($id_usuario);
$conteudosLidos = $respositorioConteudo->listarConteudoLidoPorUsuario($id_usuario);

// ✅ Aqui buscamos as estatísticas
$estatisticas = $respositorioConteudo->obterEstatisticasUsuario($id_usuario);

// Organizar resultados por dificuldade
$resultados_por_dificuldade = [];
foreach ($melhores_resultados as $resultado) {
    $dificuldade = $resultado['dificuldade'];
    if (!isset($resultados_por_dificuldade[$dificuldade])) {
        $resultados_por_dificuldade[$dificuldade] = [];
    }
    $resultados_por_dificuldade[$dificuldade][] = $resultado;
}

// Mapeamento para nomes de dificuldade
$nomes_dificuldade = [
    1 => 'Fácil',
    2 => 'Médio',
    3 => 'Difícil',
    4 => 'Enem'
];
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../homeAdm.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/baseGeral.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/logo.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/footer.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://unpkg.com/scrollreveal"></script>
    <title>Usuários do Sistema - Projeto Martopia</title>

    <style>
        body {
            background: #045A94;
            background: radial-gradient(circle, rgba(4, 90, 148, 1) 0%, rgba(129, 192, 233, 1) 50%, #9fcaec);
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        header {
            box-shadow: 0 40px 60px rgba(0, 0, 0, 0.2);
        }

        .person {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10rem;
        }

        footer {
            background: #045A94;
        }

        /* Container principal */
        .usuarios {
            display: flex;
            gap: 20px;
            padding: 20px;
            flex-wrap: wrap;
            justify-content: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Estatísticas - LINHA COMPLETA */
        .col-estatisticas {
            flex: 0 0 100%;
            width: 100%;
        }

        /* Dificuldades - LADO A LADO */
        .col-dificuldade {
            flex: 0 1 calc(25% - 20px);
            min-width: 250px;
        }

        /* Conteúdos Lidos - LINHA COMPLETA */
        .col-conteudos {
            flex: 0 0 100%;
            width: 100%;
        }

        /* Box individual */
        .ranking-box {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        /* Scroll container */
        .scroll-container {
            max-height: 400px;
            overflow-y: auto;
        }

        /* Título do ranking */
        .ranking-box h2 {
            color: #4A90E2;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }

        /* Tabela do ranking */
        .tabela {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 10px;
        }

        /* Cabeçalho da tabela */
        .tabela thead.nametable {
            background: #5DADE2;
        }

        .tabela th {
            color: #fff;
            padding: 12px;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
        }

        /* Linhas da tabela */
        .tabela tbody tr {
            text-align: center;
            background: #f8f9fa;
        }

        .tabela tbody tr:hover {
            background-color: #dcf1fa;
        }

        /* Células da tabela */
        .tabela td.info {
            padding: 12px;
            font-size: 15px;
            border-bottom: 1px solid #ddd;
        }

        /* Tabela de estatísticas */
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
        }

        .stats-table th {
            background-color: #5DADE2;
            color: #fff;
            padding: 12px;
            text-align: left;
            font-size: 16px;
        }

        .stats-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            font-size: 15px;
        }

        .stats-table tr:last-child td {
            border-bottom: none;
        }

        .stats-table tr:hover td {
            background-color: #e8f6ff;
        }

        /* Responsividade */
        @media (max-width: 1200px) {
            .col-dificuldade {
                flex: 0 1 calc(50% - 20px);
            }
        }

        @media (max-width: 600px) {
            .col-dificuldade {
                flex: 0 1 100%;
            }
        }

        .perfil {
            width: 80px;
            height: 80px;
            margin-left: -3rem;
            border: 1.5px solid #e18451;
            /* color: #81c0e9; */
        }

        tr th {
            font-family: 'Texto';
        }

        tr td {
            font-size: 1.2rem;
            font-family: 'Texto';
        }

        .iconeCentral {
            display: flex;
            align-items: center;
            /* centraliza verticalmente o ícone e o texto */
            justify-content: center;
            /* centraliza horizontalmente na tela */
            background: transparent;
            border-radius: 20px;
            width: 100%;
            max-width: 1000px;
            font-weight: bold;
            filter: blur(.2px);
            box-shadow: 0 0 15px 3px #81c0e9;
            height: auto;
            padding: 2rem;
            margin: 8rem auto;
            text-align: center;
            font-family: 'Texto';
            gap: 3rem;
            margin-top: 15rem;
        }

        .centraliza {
            display: flex;
            flex-direction: column;
            /* h2 e botão ficam um embaixo do outro */
            align-items: center;
            text-align: center;
        }

        .btn-voltar {
            transition: 0.3s;
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
</head>

<body>
    <div class="container-fluid">

        <header class="header">
            <input type="checkbox" id="check">
            <label for="check" class="icone">
                <i class="bi bi-list" id="menu-icone"></i>
                <i class="bi bi-x" id="sair-icone"></i>
            </label>

            <div class="logo-marca" style="margin-left: -3rem;">
                <a href="./homeAdm.php" class="logo"><img src="../../../../frontend/public/img/Logo.png" alt="Logo-Projeto Martopia"></a>
                <p style="margin-left: -3rem;">Projeto <br> Martopia</p>
            </div>

            <nav>
                <a href="../../../trocar/trocarperfil.php"><img src="../../../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil" class="perfil"></a>
            </nav>
        </header>

        <div class="iconeCentral">

            <div><i class="bi bi-person-fill-gear" style="color: #000; font-size: 7rem;"></i></div>

            <div class="centraliza">

                <h2 style="text-shadow: 2px 2px 4px rgba(0, 0, 0, .3);">Desempenho dos Usuários</h2>

                <br><br>

                <div>
                    <button onclick="history.back()" class="btn-voltar"> Voltar </button>
                </div>

            </div>

        </div>




        <main>
            <div class="usuarios">

                <div class="col-estatisticas">
                    <div class="ranking-box" style="color:#000">
                        <h2 style="font-size: 2rem; font-family: 'Titulo'">Interações de Leitura</h2>
                        <div class="scroll-container">
                            <table class="stats-table">
                                <tr>
                                    <th style="font-size: 1.3rem;">Indicador</th>
                                    <th style="font-size: 1.3rem;">Valor</th>
                                </tr>
                                <tr>
                                    <td style="font-size: 1.2rem;">Total de artigos lidos</td>
                                    <td style="font-size: 1.2rem;"><?php echo $estatisticas['total_artigos_lidos']; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 1.2rem;">Total de pontos</td>
                                    <td style="font-size: 1.2rem;"><?php echo $estatisticas['total_pontos']; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 1.2rem;">Artigos de Educação</td>
                                    <td style="font-size: 1.2rem;"><?php echo $estatisticas['artigos_educacao']; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 1.2rem;">Artigos de Conscientização</td>
                                    <td style="font-size: 1.2rem;"><?php echo $estatisticas['artigos_conscientizacao']; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 1.2rem;">Primeira leitura</td>
                                    <td style="font-size: 1.2rem;"><?php echo $estatisticas['primeira_leitura'] ? date("d/m/Y H:i", strtotime($estatisticas['primeira_leitura'])) : '-'; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 1.2rem;">Última leitura</td>
                                    <td style="font-size: 1.2rem;"><?php echo $estatisticas['ultima_leitura'] ? date("d/m/Y H:i", strtotime($estatisticas['ultima_leitura'])) : '-'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- LINHA 2: Dificuldades - LADO A LADO -->
                <?php if (empty($melhores_resultados)) { ?>
                    <div class="col-estatisticas">
                        <div class="ranking-box">
                            <p style="text-align:center; color:#666;font-size: 1.2rem;">Este usuário ainda não tem resultados registrados.</p>
                        </div>
                    </div>
                <?php } else { ?>
                    <?php foreach ($resultados_por_dificuldade as $dificuldade_id => $resultados_dificuldade) { ?>
                        <div class="col-dificuldade">
                            <div class="ranking-box">
                                <h2 style="font-size: 2rem; font-family: 'Texto';">Dificuldade <?php echo htmlspecialchars($nomes_dificuldade[$dificuldade_id]); ?></h2>
                                <div class="scroll-container">
                                    <table class="tabela">
                                        <thead class="nametable">
                                            <tr>
                                                <th style="font-size: 1.3rem;">Acertos</th>
                                                <th style="font-size: 1.3rem;">Tempo</th>
                                                <th style="font-size: 1.3rem;">Data da Realização</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($resultados_dificuldade as $resultado) { ?>
                                                <tr>
                                                    <td class="info" style="font-size: 1.2rem;"><?php echo htmlspecialchars($resultado['melhor_acertos']); ?>/15</td>
                                                    <td class="info">
                                                        <?php
                                                        $minutos = floor($resultado['tempo_segundos'] / 60);
                                                        $segundos = $resultado['tempo_segundos'] % 60;
                                                        echo sprintf('%d:%02d', $minutos, $segundos);
                                                        ?>
                                                    </td>
                                                    <td class="info" style="font-size: 1.2rem;"><?php echo htmlspecialchars($resultado['data_realizacao']); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>

                <!-- LINHA 3: Conteúdos Lidos - LARGURA TOTAL -->
                <div class="col-conteudos">
                    <div class="ranking-box">
                        <h2 style="font-size: 2rem; font-family: 'Texto';">Conteúdos Lidos</h2>
                        <div class="scroll-container">
                            <?php if (empty($conteudosLidos)) { ?>
                                <p style="text-align:center; color:#666; font-size: 1.2rem;">Este usuário ainda não leu nenhum conteúdo.</p>
                            <?php } else { ?>
                                <table class="tabela">
                                    <thead class="nametable">
                                        <tr>
                                            <th style="font-size: 1.3rem;">Título</th>
                                            <th style="font-size: 1.3rem;">Autor</th>
                                            <th style="font-size: 1.3rem;">Data de Publicação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($conteudosLidos as $conteudo) { ?>
                                            <tr>
                                                <td class="info" style="font-size: 1.2rem;"><?php echo htmlspecialchars($conteudo['titulo']); ?></td>
                                                <td class="info" style="font-size: 1.2rem;"><?php echo htmlspecialchars($conteudo['autor']); ?></td>
                                                <td class="info" style="font-size: 1.2rem;"><?php echo htmlspecialchars($conteudo['data_publicacao']); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            <?php } ?>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <footer>
            <div class="contatos">
                <h3>Contatos</h3>
                <p>Email: contato@martopia.com.br</p>
                <p>Telefone: +55 11 99999-9999</p>
                <p>Endereço: Rua do Oceano, 123, São Paulo, SP</p>
            </div>

            <div class="redes">
                <h3>Redes Sociais</h3>
                <div>
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-twitter"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <div class="mapa">
                <h3>Localização</h3>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3666.2168153595317!2d-46.766872!3d-23.235196!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94cede166027baab%3A0x566fc4df5821546c!2sEscola%20T%C3%A9cnica%20Estadual%20de%20Campo%20Limpo%20Paulista!5e0!3m2!1spt-BR!2sbr!4v1756695006929!5m2!1spt-BR!2sbr" allowfullscreen="" loading="lazy"></iframe>
            </div>

            <div class="copyright">
                &copy; 2025 Projeto Martopia. Todos os direitos reservados.
            </div>
        </footer>
    </div>

</body>

</html>