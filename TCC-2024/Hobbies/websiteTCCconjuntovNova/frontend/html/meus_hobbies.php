<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: meus_hobbies.php');
    exit;
}

$id_usuarios = $_SESSION['id_usuario'];

// Incluir o repositório de hobbies
include_once '../../backend/classes/class_iRepositorioHobby.php';

// Instanciar o repositório de hobbies
$repositorioHobby = new RepositorioHobbyMYSQL();

// Listar hobbies por status
$hobbiesAFazer = $repositorioHobby->listarHobbiesPorUsuarioEStatus($id_usuarios, 'a fazer');
$hobbiesEmAndamento = $repositorioHobby->listarHobbiesPorUsuarioEStatus($id_usuarios, 'em andamento');
$hobbiesExecutados = $repositorioHobby->listarHobbiesPorUsuarioEStatus($id_usuarios, 'executados');

// Obter sentimentos
$sentimentos = $repositorioHobby->contarSentimentos($id_usuarios);

$totalFeliz = 0;
$totalMeh = 0;
$totalTriste = 0;

while ($row = $sentimentos->fetch_object()) {
    if ($row->sentimento === 'feliz') {
        $totalFeliz = $row->total;
    } elseif ($row->sentimento === 'meh') {
        $totalMeh = $row->total;
    } elseif ($row->sentimento === 'triste') {
        $totalTriste = $row->total;
    }
}

// Calcular o total de sentimentos
$totalSentimentos = $totalFeliz + $totalMeh + $totalTriste;

// Calcular as porcentagens de cada sentimento
if ($totalSentimentos > 0) {
    $porcentagemFeliz = ($totalFeliz / $totalSentimentos) * 100;
    $porcentagemMeh = ($totalMeh / $totalSentimentos) * 100;
    $porcentagemTriste = ($totalTriste / $totalSentimentos) * 100;
} else {
    $porcentagemFeliz = $porcentagemMeh = $porcentagemTriste = 0;
}

// Exibir mensagem motivacional com base no sentimento predominante
if ($totalFeliz > $totalMeh && $totalFeliz > $totalTriste) {
    $mensagemMotivacional = "Reparamos que seu humor vem melhorando conforme os hobbies!!! Continue explorando e testando novos passatempos.";
} elseif ($totalMeh > $totalFeliz && $totalMeh > $totalTriste) {
    $mensagemMotivacional = "Não vamos desistir de melhorar a nossa saúde. Teste novos hobbies, você vai achar algo que goste!!";
} elseif ($totalTriste > $totalFeliz && $totalTriste > $totalMeh) {
    $mensagemMotivacional = "Reparamos que você não tem progredido com os hobbies. Não desista, vamos tentar outra coisa.";
} else {
    $mensagemMotivacional = "Continue explorando seus hobbies para ver como eles te afetam!";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/meus_hobbies.css">
    <link rel="stylesheet" href="../css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poetsen+One&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="imagex/png" href="../img/mercury.simples.ico">
    <title>Mercury</title>
</head>
<body>
  <nav id="sidebar">
    <div id="sidebar_content">
        <div id="user">
            <img src="../img/perfil.png" id="user_avatar" alt="Avatar">

            <p id="user_infos">
                <span class="item-description perfil">
                <?php
                    echo ($_SESSION['nome']);
                ?>
                </span>
                <span class="item-description">
                <?php
                    echo ($_SESSION['email']);
                ?>
                </span>
            </p>
        </div>

        <ul id="side_items">
            <li class="side-item">
                <a href="../html/home.php">
                  <i class="fa-solid fa-house"></i>
                    <span class="item-description">
                        Home
                    </span>
                </a>
            </li>

            <li class="side-item">
                <a href="../html/perfil.php">
                    <i class="fa-solid fa-user"></i>
                    <span class="item-description">
                        Perfil
                    </span>
                </a>
            </li>

            <li class="side-item active">
                <a href="../html/meus_hobbies.php">
                  <i class="fa-solid fa-paintbrush"></i>
                    <span class="item-description">
                        Meus Hobbies
                    </span>
                </a>
            </li>
        </ul>

        <button aria-label="Abrir menu" id="open_btn">
            <i id="open_btn_icon" class="fa-solid fa-chevron-right"></i>
        </button>
    </div>

        <div id="logout">
            <button aria-label="Cadastrar" id="logout_btn" onclick="window.location.href='../../backend/login/logout.php'">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="item-description">Logout</span>
            </button>
        </div>
    </div>
        </nav>

            <div class="container">
                <div class="header">

                    <div class="row">
                        <div class="col-5">
                            
                        </div>
                        <div class="col-4">
                            <img src="../img/logo.png" alt="" class="logo">
                        </div>
                        <div class="col-3">
                    
                        </div>
                    </div>
                </div>

                <!-- começo do conteudo -->
                <div class="section">

                    <!-- inicio do mapeamento de humor -->
                    <div class="motiva">
                        <div class="card-motivacional">
                            <div class="mensagem-conteudo">
                                <h3>Seu Feedback Emocional</h3>
                                <p id="mensagem-motivacional">
                                    <?php echo $mensagemMotivacional; ?>
                                </p>

                                <div class="porcento-mood">
                                    <div class="feliz">
                                        <img src="../img/feliz.png" alt="feliz">
                                        <p id="percent-feliz"><?php echo round($porcentagemFeliz, 2); ?>%</p> <!-- Exibe a porcentagem de 'feliz' -->
                                    </div>
                                    <div class="meh">
                                        <img src="../img/meh.png" alt="meh">
                                        <p id="percent-meh"><?php echo round($porcentagemMeh, 2); ?>%</p> <!-- Exibe a porcentagem de 'meh' -->
                                    </div>
                                    <div class="triste">
                                        <img src="../img/triste.png" alt="triste">
                                        <p id="percent-triste"><?php echo round($porcentagemTriste, 2); ?>%</p> <!-- Exibe a porcentagem de 'triste' -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="top-section">
                        <div class="top-content">
                            <h1>Meus Hobbies</h1>
                            <div class="divider"></div>

                            <div class="row">
                                <div class="col-7">
                                    <p>adicione seus hobbies conforme a sua agenda !!</p>

                                    <div class="adicionar-hobby">
                                        <div class="buttons">
                                            <button class="button" id="adicionarHobby">adicionar hobby</button> 
                                        </div>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="calendar-icon">
                                        <img src="../img/crie-um-novo.png" alt="Calendar">
                                    </div>
                                </div>  
                            </div>

                            <!-- Modal do formulário -->
                                <div class="formularioContainer" id="formularioContainer">
                                    <div class="formularioContent">
                                        <form action="../../backend/hobbys/novo_hobby.php" method="post">
                                            <label for="nome">Nome do Hobby:</label>
                                            <input type="text" class="nome" name="nome" required>

                                            <label for="status">Status:</label>
                                            <select class="status" name="status">
                                                <option value="executados">Executados</option>
                                                <option value="em andamento">Em Andamento</option>
                                                <option value="a fazer">A Fazer</option>
                                            </select>
                                            <label for="descricao">Descrição:</label>
                                            <textarea class="descricao" name="descricao" rows="4" cols="27"></textarea>
                                            <button type="submit">Salvar Hobby</button>
                                        </form>

                                    </div>
                                </div>


                        </div>
                    </div>

                    <!-- inicio das tasks -->
                    <div class="tasks">
                        <div class="task green">
                            <div class="task-content">
                                <img src="../img/tarefa.png" alt="" class="icon-tarefa">
                                <span class="spam-task">hobbies executados</span>
                                <button class="plus-button">+</button>
                            </div>
                            <div class="task-details">
                                <?php while ($hobby = $hobbiesExecutados->fetch_object()): ?>
                                    <div class="task-card">
                                        <div class="task-info">
                                            <h3><?= htmlspecialchars($hobby->nome); ?></h3>
                                            <p><?= htmlspecialchars($hobby->descricao); ?></p>
                                        </div>
                                        <div class="task-buttons">

                                            <button class="edit-btn" onclick="openEditModal(<?= $hobby->id ?>)">Editar</button>

                                            <button class="delete-btn" 
                                                onclick="if (confirm('Tem certeza que deseja deletar este registro?')) {window.location.href='../../backend/hobbys/excluir_hobby.php?id=<?= htmlspecialchars($hobby->id); ?>';}">
                                                Excluir
                                            </button>

                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                        

                        <div class="task yellow">
                            <div class="task-content">
                                <img src="../img/tarefa.png" alt="" class="icon-tarefa">
                                <span class="spam-task">hobbies em andamento</span>
                                <button class="plus-button">+</button>
                            </div>
                            <div class="task-details">

                            
                            <?php while ($hobby = $hobbiesEmAndamento->fetch_object()): ?>
                                <div class="task-card">
                                        <div class="task-info">
                                            <h3><?= htmlspecialchars($hobby->nome); ?></h3>
                                            <p><?= htmlspecialchars($hobby->descricao); ?></p>
                                        </div>
                                        <div class="task-buttons">
                                        
                                            <button class="edit-btn" onclick="openEditModal(<?= $hobby->id ?>)">Editar</button>

                                            <button class="delete-btn" 
                                                onclick="if (confirm('Tem certeza que deseja deletar este registro?')) {window.location.href='../../backend/hobbys/excluir_hobby.php?id=<?= htmlspecialchars($hobby->id); ?>';}">
                                                Excluir
                                            </button>
                                        </div>
                                </div>
                            <?php endwhile; ?>

                            </div>
                        </div>
                        
                        <div class="task red">
                            <div class="task-content">
                                <img src="../img/tarefa.png" alt="" class="icon-tarefa">
                                <span class="spam-task">hobbies para fazer</span>
                                <button class="plus-button">+</button>
                            </div>
                            <div class="task-details">

                            
                            <?php while ($hobby = $hobbiesAFazer->fetch_object()): ?>
                                <div class="task-card">
                                        <div class="task-info">
                                            <h3><?= htmlspecialchars($hobby->nome); ?></h3>
                                            <p><?= htmlspecialchars($hobby->descricao); ?></p>
                                        </div>
                                        <div class="task-buttons">

                                            <button class="edit-btn" onclick="openEditModal(<?= $hobby->id ?>)">Editar</button>

                                            <button class="delete-btn" 
                                                onclick="if (confirm('Tem certeza que deseja deletar este registro?')) {window.location.href='../../backend/hobbys/excluir_hobby.php?id=<?= htmlspecialchars($hobby->id); ?>';}">
                                                Excluir
                                            </button>

                                        </div>
                                </div>
                            <?php endwhile; ?>

                            </div>
                        </div>
                    </div>
                    


                </div>

                    <!-- fim das tasks -->

                    <!-- Modal de Edição -->
            
                    <div id="editModal" class="modal" style="display: none;">
                        <div class="modal-content">
                            <h2>Editar Status do Hobby</h2>
                            <form method="POST" action="../../backend/hobbys/alterar_hobby.php">
                                <input type="hidden" name="id" id="id"> <!-- Campo para o ID -->

                                <label for="status">Novo Status:</label>
                                <select name="status" id="status" onchange="toggleMoodTrack()">
                                    <option value="á fazer">Á fazer</option>
                                    <option value="em andamento">Em andamento</option>
                                    <option value="executados">Executado</option>
                                </select>
                            
                                <!-- Campo de feedback emocional (inicialmente escondido) -->
                                

                                    <div id="moodTrackContainer" style="display: none;">
                                        <label for="sentimento">Como você se sente ao execultar este hobby ?</label>
                                        <div class="mood-container">
                                            <div class="mood feliz" onclick="setMood('feliz')">
                                                <img src="../img/feliz.png" alt="feliz" >
                                                
                                            </div>
                                            <div class="mood meh" onclick="setMood('meh')">
                                                <img src="../img/meh.png" alt="meh">
                                                
                                            </div>
                                            <div class="mood triste" onclick="setMood('triste')">
                                                <img src="../img/triste.png" alt="triste">
                                                
                                            </div>
                                            <input type="hidden" name="sentimento" id="sentimento">
                                        </div>                              
                                    </div>
                                        
                                        <button type="submit">Salvar</button>
                            </form>
                            </div>
                        </div>  

                    


                </div>

            </div>
                <div class="footer">
                    <!-- <p> 2024 Meu Site. Todos os direitos reservados.</p> -->
                </div>
            </div>
        
        <script src="../../backend/scripts/addHobby.js"></script>
        <script src="../../backend/scripts/script.js"></script>
</body>
</html>