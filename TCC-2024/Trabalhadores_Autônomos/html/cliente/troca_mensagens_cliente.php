<?php
session_start(); // Inicia a sessão
include_once('../../backend/Conexao.php');

// Verifica se o cliente está logado
if (!isset($_SESSION['id_cliente'])) {
    echo 'Cliente não está logado.';
    exit;
}

// ID do cliente logado
$id_cliente = $_SESSION['id_cliente'];
$sql = "SELECT * FROM cliente WHERE id_cliente = '$id_cliente'";
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);
// ID do trabalhador a ser visualizado
$id_trabalhador = isset($_GET['id_trabalhador']) ? $_GET['id_trabalhador'] : null;

// Verifica se o ID do trabalhador foi passado
if ($id_trabalhador === null) {
    echo 'ID do trabalhador não fornecido.';
    exit;
}

// Inserir nova mensagem se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensagem = $conn->real_escape_string($_POST['mensagem']);

    $sql = "INSERT INTO mensagens (id_cliente, id_trabalhador, mensagem, remetente) 
            VALUES ('$id_cliente', '$id_trabalhador', '$mensagem', 'cliente')";
    
    if ($conn->query($sql) === TRUE) {
        // Redirecionar para a tela de troca de mensagens
        header("Location: troca_mensagens_cliente.php?id_trabalhador=$id_trabalhador");
        exit();
    } else {
        echo "Erro: " . $conn->error;
    }
}

// Consulta para obter mensagens trocadas entre cliente e trabalhador
$sql_mensagens = "SELECT * FROM mensagens 
                  WHERE (id_cliente = '$id_cliente' AND id_trabalhador = '$id_trabalhador') 
                     OR (id_cliente = '$id_trabalhador' AND id_trabalhador = '$id_cliente') 
                  ORDER BY id_mensagem ASC";
$resultado_mensagens = $conn->query($sql_mensagens);
?>

<style>
    nav.menuLateral{
    width: 65px;
    height: 420px;
    }
</style>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JundTask - Troca de Mensagens</title>
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.min.css"> <!-- Adicionado Bootstrap CSS -->
    <link rel="stylesheet" href="../../css/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../css/stylemensagem.css"> <!-- Referência ao CSS separado -->
    <link rel="shortcut icon" href="../../img/logo@2x.png" type="image/x-icon">
</head>
<body>
    <header>
        <nav class="BarraNav">
            <img src="../../img/LogoJundtaskCompleta.png" alt="Logo JundTask">
            <div class="perfil">
            </div>
        </nav>
    </header>

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
        <li class="itemMenu ">
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
        <li class="itemMenu ativo">
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

    <div class="container">
    <h2>Troca de Mensagens</h2>
    
    <div class="mensagens" id="mensagens">
        <?php if ($resultado_mensagens->num_rows > 0): ?>
            <?php while ($mensagem = $resultado_mensagens->fetch_assoc()): ?>
                <div class="mensagem <?= $mensagem['remetente'] === 'cliente' ? 'mensagem-cliente' : 'mensagem-trabalhador' ?>">
                    <p><strong><?= $mensagem['remetente'] === 'cliente' ? 'Você' : 'Trabalhador' ?>:</strong></p>
                    <p><?= htmlspecialchars($mensagem['mensagem']) ?></p>
                    <p class="data"><?= date('d/m/Y H:i', strtotime($mensagem['data_envio'])) ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Nenhuma mensagem encontrada.</p>
        <?php endif; ?>
    </div>

    <!-- Formulário para enviar nova mensagem -->
    <form action="" method="POST">
        <textarea name="mensagem" required placeholder="Escreva sua mensagem..."></textarea>
        <button type="submit">Enviar</button>
    </form>

  


    <footer class="d-flex justify-content-center">
        <p style="margin-bottom: 0rem;">N</p>
        <p style="margin-bottom: 0rem;">Terms of Service</p>
        <p style="margin-bottom: 0rem;">Privacy Policy</p>
        <p style="margin-bottom: 0rem;">@2024nerisdesign</p>
    </footer>
    <script>
   document.addEventListener("DOMContentLoaded", function() {
    const mensagensDiv = document.getElementById('mensagens');
    mensagensDiv.scrollTop = mensagensDiv.scrollHeight; // Rolagem inicial

    const form = document.querySelector('form');
    form.addEventListener('submit', function() {
        setTimeout(() => {
            mensagensDiv.scrollTop = mensagensDiv.scrollHeight; // Rolagem após o envio
        }, 100);
    });
});

</script>
    <script src="../../js/funcaoMenuLateral.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script> <!-- Adicionado Bootstrap JS -->
    

</body>
</html>
