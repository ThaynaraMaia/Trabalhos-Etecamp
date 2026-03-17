<!DOCTYPE html>
<html lang="pt-br">

<?php
session_start();
include_once '../../../../classes/class_IRepositorioUsuarios.php';
include_once '../../../../classes/class_IRepositorioMemoria.php';

$id = $_SESSION['id_usuario'];
// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);

// Inicializar variáveis
$melhor_tempo = null;
$melhor_tempo_formatado = '';

// Verificar se o repositório de memória está disponível e obter o melhor tempo
if (isset($respositorioMemoria)) {
    $melhor_tempo_result = $respositorioMemoria->obterMelhorTempoUsuario($id);

    // Verificar se o resultado é numérico (não array ou false)
    if (is_numeric($melhor_tempo_result)) {
        $melhor_tempo = (int)$melhor_tempo_result;

        // Formatar o melhor tempo para exibição
        $minutos = floor($melhor_tempo / 60);
        $segundos = $melhor_tempo % 60;
        $melhor_tempo_formatado = sprintf('%d:%02d', $minutos, $segundos);
    }
}

// Foto padrão se não tiver
// $foto = !empty($dados['foto']) ? $dados['foto'] : '../../../frontend/public/img/fotoperfil.png'; 
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo da Memória - Projeto Martopia</title>

    <link rel="stylesheet" href="./facilM.css">

    <style>
        @font-face {
            font-family: 'Titulo';
            src: url('../../../../../frontend/fontes/Título.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Texto';
            src: url('../../../../../frontend/fontes/Texto.otf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        .melhor-tempo {
            background: linear-gradient(135deg, #5d288e, #7f42d1);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            margin: 10px 0;
            text-align: center;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .sem-recorde {
            background: linear-gradient(135deg, #6c757d, #adb5bd);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            margin: 10px 0;
            text-align: center;
            font-style: italic;
        }

        .info-jogador {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .jogador {
            font-weight: bold;
            color: #045a94;
        }

        .timer-container {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .jogador,
        .timer {
            padding: 6px 14px;
            border-radius: 8px;
            background-color: #d9f0ff;
            box-shadow: 0 0 6px #80c1ff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .tras {
            background-image: url(./img/cartaMemTras.jpg);
        }

        .central {
            display: flex;
            justify-content: center;
            text-align: center;
            flex-direction: column;
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

</head>

<body>
    <main class="conteudo">

        <header class="cabecalho" style="background: #c6e1fe; border: 2px solid; border-color: #38a0dd; border-radius: 10px;">

            <span class="jogador" style="color: #045a94; font-family:'Texto'; font-size: 1.3rem;"><?php echo htmlspecialchars($_SESSION['nome']); ?></span>

            <div class="central">

                <div class="timer-container" style="color: #045a94; font-family:'Texto'; font-size: 1.3rem;">
                    <span style="font-family: 'Texto'; font-weight: bold;">Tempo: </span>
                    <span class="timer">00</span>
                </div>

                <br> <br>

                <div class="volt">
                    <a href="./niveisM.php" class="btn-voltar">Voltar</a>
                </div>

            </div>
        </header>

        <!-- Adicione o data attribute aqui -->
        <div class="grid" data-user-id="<?php echo $id; ?>"> </div>

    </main>

    <script>
        const nomeJogador = <?php echo json_encode($_SESSION['nome']); ?>;
        var nomeJogador = <?php echo json_encode($_SESSION['nome']); ?>;
        var userId = <?php echo json_encode($id); ?>; // pega direto do PHP

        sessionStorage.setItem('user_id', userId);

        var melhorTempo = <?php echo json_encode($melhor_tempo_formatado ?: null); ?>;
        if (melhorTempo !== null && melhorTempo !== "") {
            sessionStorage.setItem('melhor_tempo', melhorTempo);
        }
    </script>

    <script src="./jsMemoria/dificil.js"></script>

</body>

</html>