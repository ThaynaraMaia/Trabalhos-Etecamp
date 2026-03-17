<?php
session_start(); // Inicia a sessão
include_once('../../backend/Conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['id_cliente'])) {
    echo 'Usuário não está logado.';
    exit;
}

// ID do cliente logado
$id_cliente = $_SESSION['id_cliente'];

$sql_cli = "SELECT * FROM cliente WHERE id_cliente = '$id_cliente'";
$result_cli = $conn->query($sql_cli);
$resultado_cli = mysqli_query($conn, $sql_cli);
$row_cli = mysqli_fetch_assoc($resultado_cli);

// ID do trabalhador a ser visualizado
$id_trabalhador = isset($_GET['id_trabalhador']) ? $_GET['id_trabalhador'] : null;

// Verifica se o ID do trabalhador foi passado
if ($id_trabalhador === null) {
    echo 'ID do trabalhador não fornecido.';
    exit;
}

// Inicializa as variáveis isFavorito e hasLiked
$isFavorito = false;
$hasLiked = false;

// Consulta para obter os dados do trabalhador
$sql = "SELECT * FROM trabalhador WHERE id_trabalhador = '$id_trabalhador'";
$resultado_pesquisar = mysqli_query($conn, $sql);

// Verifica se o trabalhador foi encontrado
if ($row = mysqli_fetch_assoc($resultado_pesquisar)) {
    // Consulta para verificar se o trabalhador é favorito
    $sqlFavorito = "SELECT * FROM favoritos WHERE id_trabalhador = '$id_trabalhador' AND id_cliente = '$id_cliente'";
    $resultFavorito = mysqli_query($conn, $sqlFavorito);

    if (mysqli_num_rows($resultFavorito) > 0) {
        $isFavorito = true; // Define como true se o trabalhador for favorito
    }

    // Consulta para contar as curtidas
    $sqlCurtidas = "SELECT COUNT(*) as total_curtidas FROM curtidas WHERE id_trabalhador = '$id_trabalhador'";
    $resultCurtidas = mysqli_query($conn, $sqlCurtidas);
    $rowCurtidas = mysqli_fetch_assoc($resultCurtidas);
    $totalCurtidas = $rowCurtidas['total_curtidas'];

    // Verifica se o trabalhador foi curtido pelo cliente
    $sqlLike = "SELECT * FROM curtidas WHERE id_trabalhador = '$id_trabalhador' AND id_cliente = '$id_cliente'";
    $resultLike = mysqli_query($conn, $sqlLike);
    
    if (mysqli_num_rows($resultLike) > 0) {
        $hasLiked = true; // Define como true se o trabalhador foi curtido
    }
} else {
    echo 'Trabalhador não encontrado.';
    exit;
}
// Inserir mensagem inicial se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensagem = $conn->real_escape_string($_POST['mensagem']);

    $sql = "INSERT INTO mensagens (id_cliente, id_trabalhador, mensagem, remetente) 
            VALUES ('$id_cliente', '$id_trabalhador', '$mensagem', 'cliente')";
    
    if ($conn->query($sql) === TRUE) {
        // Redirecionar para a tela de troca de mensagens
        header("Location: troca_mensagens.php?id_trabalhador=$id_trabalhador");
        exit();
    } else {
        echo "Erro: " . $conn->error;
    }
}
?>
<style>
    nav.menuLateral{
    width: 65px;
    height: 420px;
    }
</style>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JundTask - Perfil</title>
    <link rel="stylesheet" href="../../css/stylePerfilCliente.css">
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="../../img/logo@2x.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">

</head>
<body>
    <header>
        <nav class="BarraNav">
            <img src="../../img/LogoJundtaskCompleta.png" alt="Logo JundTask">
            <h1>Perfil</h1>
            <div class="perfil">
                <img class="FotoPerfilNav" src="../../uploads/<?php echo !empty($row_cli['foto_perfil']) ? $row_cli['foto_perfil'] : '../../img/FotoPerfilGeral.png' ?>" alt="">
            </div>
        </nav>
    </header>

    <main>
    <nav class="menuLateral">
    <div class="IconExpandir">
        <!-- <ion-icon name="menu-outline" id="btn-exp"></ion-icon> -->
        <i class="bi bi-list" id="btn-exp"></i>
    </div>

    <ul style="padding-left: 0rem;">
        <li class="itemMenu ">
            <a href="homeClienteLogado.php">
                <span class="icon">
                    <!-- <ion-icon name="home-outline"></ion-icon> -->
                    <i class="bi bi-house-door"></i>
                </span>
                <span class="txtLink">Início</span>
            </a>
        </li>
        <li class="itemMenu ">
            <a href="EditarPerfilCliente.php">
                <span class="icon">
                    <!-- <ion-icon name="settings-outline"></ion-icon> -->
                    <i class="bi bi-gear"></i>
                </span>
                <span class="txtLink">Configurações</span>
            </a>
        </li>
        <li class="itemMenu ativo">
            <a href="Categorias.php">
                <span class="icon">
                    <!-- <ion-icon name="search-outline"></ion-icon> -->
                    <i class="bi bi-search"></i>
                </span>
                <span class="txtLink">Pesquisar</span>
            </a>
        </li>
        <li class="itemMenu">
            <a href="favorito.php">
                <span class="icon">
                    <!-- <ion-icon name="heart-outline"></ion-icon> -->
                    <i class="bi bi-heart"></i>
                </span>
                <span class="txtLink">Favoritos</span>
            </a>
        </li>
        <li class="itemMenu">
            <a href="historico_conversas_cliente.php"> <!-- Novo item de menu para histórico de mensagens -->
                <span class="icon">
                    <!-- <ion-icon name="chatbubbles-outline"></ion-icon> -->
                    <i class="bi bi-chat"></i>
                </span>
                <span class="txtLink">Mensagens</span>
            </a>
        </li>
        <li class="itemMenu">
            <a href="LogoutCliente.php">
                <span class="icon">
                    <!-- <ion-icon name="exit-outline"></ion-icon> -->
                    <i class="bi bi-box-arrow-right"></i>
                </span>
                <span class="txtLink">Sair</span>
            </a>
        </li>
    </ul>
</nav>

        <div class="FotoFundo">
            <img src="../../uploads/<?php echo !empty($row['foto_banner']) ? $row['foto_banner'] : '../img/TelaPredefinida.png' ?>" alt="Banner">
            <div class="BlocoPerfilPrincipal">
                <div class="FotoPerfil">
                    <img src="../../uploads/<?php echo !empty($row['foto_perfil']) ? $row['foto_perfil'] : '../img/images100x100.png' ?>" alt="Perfil">
                </div>
                <div class="NomeTrabalhador">
                    <p><?php echo htmlspecialchars($row['nome']); ?></p>
                </div>

                <div class="tel">
                    <p>Tel: <?php echo htmlspecialchars($row['contato']); ?></p>
                </div>
                <div class="email">
                    <p><?php echo htmlspecialchars($row['email']); ?></p>
                </div>
                <div class="curtir">
                    <button id="likeBtn" class="curtir-button">
                        <i class="bi bi-heart<?php echo $hasLiked ? '-fill' : ''; ?>"></i> 
                        <?php echo '<div class = "butao">';
                            echo $hasLiked ? 'Descurtir' : 'Curtir'; 
                            echo '</div>';
                        ?>
                    </button>
                    <span id="likeCount"><?php echo $totalCurtidas; ?> Likes</span>
                </div>
                <div class="favorito">
                    <button id="favoritarBtn" data-id="<?php echo $id_trabalhador; ?>">
                        <?php echo $isFavorito ? 'Remover dos Favoritos' : 'Adicionar aos Favoritos'; ?>
                    </button>
                </div>
            </div>
            <div class="txt">
                <p><?php echo htmlspecialchars($row['descricao']); ?></p>
            </div>
        </div>

        <div class="trabalhos">
                <div class="carrousel">
                <div class="col">
                        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active ">
                                <img src="../../uploads/<?php echo !empty($row['foto_trabalho1']) ? $row['foto_trabalho1'] : '../img/TelaPredefinidaTrabalhos1.png' ?>" class="d-block w-100 img-fluid" alt="">
                                </div>
                                <div class="carousel-item">
                                    <img src="../../uploads/<?php echo !empty($row['foto_trabalho2']) ? $row['foto_trabalho2'] : '../img/TelaPredefinidaTrabalhos2.png' ?>" class="d-block w-100 img-fluid" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="../../uploads/<?php echo !empty($row['foto_trabalho3']) ? $row['foto_trabalho3'] : '../img/TelaPredefinidaTrabalhos3.png' ?>" class="d-block w-100 img-fluid" alt="...">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
        </div>

         <!-- Formulário para enviar nova mensagem -->
         <div class="EnviarMensagem">
    <h2>Enviar mensagem</h2>
    <form action="./troca_mensagens_cliente.php" method="GET">
        <input type="hidden" name="id_trabalhador" value="<?php echo $id_trabalhador; ?>">
        <textarea name="mensagem" rows="4" placeholder="Escreva sua mensagem..." required></textarea>
        <button type="submit">Enviar</button>
    </form>
</div>

        <!-- <h1>Comentários e Avaliações</h1>
        <div id="reviews">
            Comentários e avaliações serão carregados aqui -->
        <!-- </div>

        <form id="comentario" method="POST" action="post_comentario.php">
            <textarea name="comentario" id="comentario" placeholder="Escreva seu comentário" required></textarea>
            <input type="hidden" name="id_trabalhador_sessao" value=" 
            
            <?php echo $id_trabalhador; ?>

            <input type="submit" form="comentario" value="Enviar comentário"/><br>
        </form> --> 
    </main>

    <footer class="d-flex justify-content-center">
        <p>Terms of Service</p>
        <p>Privacy Policy</p>
        <p>@2022yanliudesign</p>
    </footer>

    <script src="../../js/funcaoMenuLateral.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const likeBtn = document.getElementById('likeBtn');
        const likeCount = document.getElementById('likeCount');
        let hasLiked = <?php echo json_encode($hasLiked); ?>; // Verifica se já curtiu
        let count = <?php echo $totalCurtidas; ?>; // Contagem inicial

        likeBtn.addEventListener('click', function() {
            const trabalhadorId = <?php echo $id_trabalhador; ?>;

            if (!hasLiked) {
                // Curtir
                count++;
                likeCount.textContent = count + " Likes";
                likeBtn.innerHTML = '<i class="bi bi-heart-fill"></i> Descurtir';
                hasLiked = true;

                // Enviar a informação de curtir para o servidor
                fetch('curtir.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: trabalhadorId, action: 'curtir' })
                });
            } else {
                // Descurtir
                count--;
                likeCount.textContent = count + " Likes";
                likeBtn.innerHTML = '<i class="bi bi-heart"></i> Curtir';
                hasLiked = false;

                // Enviar a informação de descurtir para o servidor
                fetch('curtir.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: trabalhadorId, action: 'descurtir' })
                });
            }
        });

        document.getElementById('favoritarBtn').addEventListener('click', function() {
    const idTrabalhador = this.getAttribute('data-id');
    const action = this.textContent.includes('Adicionar') ? 'adicionar' : 'remover';

    fetch('favoritos_action.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id_trabalhador: idTrabalhador, action: action })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualiza a interface
            this.textContent = action === 'adicionar' ? 'Remover dos Favoritos' : 'Adicionar aos Favoritos';
        } else {
            alert(data.message); // Mostra mensagem de erro
        }
    })
    .catch(error => console.error('Erro ao processar favoritos:', error));
});


    </script>
</body>
</html>
