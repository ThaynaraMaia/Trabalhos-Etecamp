<?php
session_start(); // Inicia a sessão
include_once('../../backend/Conexao.php');

// Verifica se o trabalhador está logado
if (!isset($_SESSION['id_trabalhador'])) {
    echo 'Trabalhador não está logado.';
    exit;
}

// ID do trabalhador logado
$id_trabalhador = $_SESSION['id_trabalhador'];

// ID do cliente a ser visualizado
$id_cliente = isset($_GET['id_cliente']) ? $_GET['id_cliente'] : null;

// Verifica se o ID do cliente foi passado
if ($id_cliente === null) {
    echo 'ID do cliente não fornecido.';
    exit;
}

// Inserir nova mensagem se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensagem = $conn->real_escape_string($_POST['mensagem']);

    $sql = "INSERT INTO mensagens (id_cliente, id_trabalhador, mensagem, remetente, data_envio) 
            VALUES ('$id_cliente', '$id_trabalhador', '$mensagem', 'trabalhador', NOW())";
    
    if ($conn->query($sql) === TRUE) {
        // Redirecionar para a tela de troca de mensagens
        header("Location: troca_mensagens_trabalhador.php?id_cliente=$id_cliente");
        exit();
    } else {
        echo "Erro: " . $conn->error;
    }
}

// Consulta para obter mensagens trocadas entre trabalhador e cliente
$sql_mensagens = "SELECT * FROM mensagens 
                  WHERE (id_cliente = '$id_cliente' AND id_trabalhador = '$id_trabalhador') 
                     OR (id_cliente = '$id_trabalhador' AND id_trabalhador = '$id_cliente') 
                  ORDER BY data_envio ASC"; // Ordena pela data de envio

$resultado_mensagens = $conn->query($sql_mensagens);

// Verifica se a consulta foi bem-sucedida
if ($resultado_mensagens === false) {
    echo "Erro na consulta: " . $conn->error;
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Troca de Mensagens</title>
    <link rel="stylesheet" href="../../css/stylemensagem.css"> <!-- Ajuste o caminho conforme necessário -->
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">

    <script>
        function scrollToBottom() {
            var mensagensContainer = document.getElementById('mensagens');
            mensagensContainer.scrollTop = mensagensContainer.scrollHeight;
        }

        window.onload = scrollToBottom; // Chama a função ao carregar a página
    </script>
</head>
<body>
<nav class="menuLateral">
    <div class="IconExpandir">
        <!-- <ion-icon name="menu-outline" id="btn-exp"></ion-icon> -->
        <i class="bi bi-list" id="btn-exp"></i>
    </div>

    <ul style="padding-left: 0rem;">
        <li class="itemMenu ativo">
            <a href="homeLogado.php">
                <span class="icon">
                    <!-- <ion-icon name="home-outline"></ion-icon> -->
                    <i class="bi bi-house-door"></i>
                </span>
                <span class="txtLink">Início</span>
            </a>
        </li>

        <li class="itemMenu">
            <a href="SeuPerfil.php">
        <span class="icon">
            <i class="bi bi-person"></i> <!-- Ícone de perfil -->
        </span>
        <span class="txtLink">Meu Perfil</span>
         </a>
</li>
        <li class="itemMenu">
            <a href="EditarPerfil.php">
                <span class="icon">
                    <!-- <ion-icon name="settings-outline"></ion-icon> -->
                    <i class="bi bi-gear"></i>
                </span>
                <span class="txtLink">Configurações</span>
            </a>
        </li>
        <li class="itemMenu">
            <a href="historico_conversas.php"> <!-- Novo item de menu para histórico de mensagens -->
                <span class="icon">
                    <!-- <ion-icon name="chatbubbles-outline"></ion-icon> -->
                    <i class="bi bi-chat"></i>
                </span>
                <span class="txtLink">Mensagens</span>
            </a>
        </li>
        <li class="itemMenu">
            <a href="Logout.php">
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
    
    <div class="mensagens" id="mensagens"> <!-- Adicionei o ID aqui -->
        <?php if ($resultado_mensagens->num_rows > 0): ?>
            <?php while ($mensagem = $resultado_mensagens->fetch_assoc()): ?>
                <div class="mensagem <?= $mensagem['remetente'] === 'trabalhador' ? 'mensagem-trabalhador' : 'mensagem-cliente' ?>">
                    <p><strong><?= $mensagem['remetente'] === 'trabalhador' ? 'Você' : 'Cliente' ?>:</strong></p>
                    <p><?= htmlspecialchars($mensagem['mensagem']) ?></p>
                    <p class="data"><?= date('d/m/Y H:i', strtotime($mensagem['data_envio'])) ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Nenhuma mensagem encontrada.</p>
        <?php endif; ?>
    </div>
    
    <!-- Formulário para enviar nova mensagem -->
    <div class="EnviarMensagem">
        <h3>Enviar nova mensagem</h3>
        <form action="" method="POST">
            <textarea name="mensagem" rows="4" placeholder="Escreva sua mensagem..." required></textarea>
            <button type="submit">Enviar</button>
        </form>
    </div>
</div>

<style>
    /* Estilos básicos para a página */
    nav.menuLateral{
    width: 64px;
    height: 430px;
    }
 

</style>

<script src="../../js/funcaoMenuLateral.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>


