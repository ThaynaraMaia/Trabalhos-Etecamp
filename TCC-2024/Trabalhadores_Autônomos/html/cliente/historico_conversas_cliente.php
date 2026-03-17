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

// Consulta para obter todos os trabalhadores que trocaram mensagens com o cliente, incluindo a categoria
$sql_historico = "SELECT DISTINCT t.id_trabalhador, t.nome, t.foto_perfil, c.nome_cat AS categoria 
                  FROM mensagens m 
                  JOIN trabalhador t ON t.id_trabalhador = m.id_trabalhador 
                  JOIN categorias c ON t.id_categoria = c.id_categoria 
                  WHERE m.id_cliente = '$id_cliente' 
                  ORDER BY t.nome ASC";

$resultado_historico = $conn->query($sql_historico);

// Verifica se a consulta foi bem-sucedida
if ($resultado_historico === false) {
    echo "Erro na consulta: " . $conn->error;
    exit();
}
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
    <title>Histórico de Conversas</title>
    <link rel="stylesheet" href="../../css/stylehistorico1.css"> <!-- Ajuste o caminho conforme necessário -->
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">

</head>

<link rel="shortcut icon" href="../../img/logo@2x.png" type="image/x-icon">
</head>
<body>
    <header>
        <nav class="BarraNav">
            <img src="../../img/LogoJundtaskCompleta.png" alt="Logo JundTask">
            <div class="perfil">
                <a href="#">
                
                </a>
            </div>
        </nav>
    </header>

         
       
    <nav class="menuLateral">
    <div class="IconExpandir">
        <!-- <ion-icon name="menu-outline" id="btn-exp"></ion-icon> -->
        <i class="bi bi-list" id="btn-exp"></i>
    </div>

    <ul style="padding-left: 0rem;">
        <li class="itemMenu ativo">
            <a href="homeClienteLogado.php">
                <span class="icon">
                    <!-- <ion-icon name="home-outline"></ion-icon> -->
                    <i class="bi bi-house-door"></i>
                </span>
                <span class="txtLink">Início</span>
            </a>
        </li>
        <li class="itemMenu">
            <a href="EditarPerfilCliente.php">
                <span class="icon">
                    <!-- <ion-icon name="settings-outline"></ion-icon> -->
                    <i class="bi bi-gear"></i>
                </span>
                <span class="txtLink">Configurações</span>
            </a>
        </li>
        <li class="itemMenu">
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

<div class="container">
    <h2>Histórico de Conversas</h2>
    
    <div class="historico">
        <?php if (mysqli_num_rows($resultado_historico) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($resultado_historico)): ?> 
                <div class="CampoEscolhaTrabalhador">
                    <a href="troca_mensagens_cliente.php?id_trabalhador=<?php echo $row['id_trabalhador']; ?>">
                        <div class="CardBox"> 
                            <div class="imagem">
                                <img src="../../uploads/<?php echo $row['foto_perfil']; ?>" alt="">
                            </div>
                            <div class="txtTrabalhador">
                                <h3><?php echo htmlspecialchars($row['nome']); ?></h3>
                                <p style="margin-bottom:0rem"><?php echo htmlspecialchars($row['categoria']); ?></p> <!-- Categoria do trabalhador -->
                            </div>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="tituloDEnaoEncontrado">
                <p>Nenhuma conversa encontrada.</p>
            </div>
        <?php endif; ?>
    </div>

  
</div>

<script src="../../js/funcaoMenuLateral.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
