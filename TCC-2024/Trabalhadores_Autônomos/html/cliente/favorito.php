<?php
session_start();
include_once('../../backend/Conexao.php');

if (!isset($_SESSION['id_cliente'])) {
    echo 'Usuário não está logado.';
    exit;
}

$id_cliente = $_SESSION['id_cliente'];
$sql_cli = "SELECT * FROM cliente WHERE id_cliente = '$id_cliente'";
$result_cli = $conn->query($sql_cli);

$resultado_cli = mysqli_query($conn, $sql_cli);
$row_cli = mysqli_fetch_assoc($resultado_cli);

// Consulta para obter os favoritos, incluindo a categoria
$sql = "SELECT t.*, c.nome_cat AS categoria 
        FROM favoritos f 
        JOIN trabalhador t ON f.id_trabalhador = t.id_trabalhador 
        JOIN categorias c ON t.id_categoria = c.id_categoria 
        WHERE f.id_cliente = '$id_cliente'";


$resultado_favoritos = mysqli_query($conn, $sql);

if (!$resultado_favoritos) {
    // Se a consulta falhar, exibe o erro e interrompe a execução
    die("Erro na consulta SQL: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JundTask - Trabalhadores Favoritos</title>
    <link rel="stylesheet" href="../../css/styleFavoritos.css">
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="../../img/logo@2x.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">

</head>
<body>
    <header>
        <nav class="BarraNav">
            <img src="../../img/LogoJundtaskCompleta.png" alt="Logo JundTask">
            <div class="perfil">
                <a href="#">
                <img class="FotoPerfil" src="../../uploads/<?php echo !empty($row_cli['foto_perfil']) ? $row_cli['foto_perfil'] : '../../img/FotoPerfilGeral.png' ?>" alt="">
                </a>
            </div>
        </nav>
    </header>

    <main class=""> 
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
        <li class="itemMenu ativo">
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

    
        <div class="resultado">
            <h1 class="mb-4">Trabalhadores Favoritos</h1>
            <div class="usuario">
                <?php
                if (mysqli_num_rows($resultado_favoritos) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado_favoritos)) {?> 
                        <div class="CampoEscolhaTrabalhador">
                            <a href="./Perfil.php?id_trabalhador=<?php echo $row['id_trabalhador']; ?>">
                                <div class="CardBox"> 
                                    <div class="imagem">
                                        <img src="../../uploads/<?php echo $row['foto_perfil']; ?>" alt="">
                                    </div>
                                    <div class="txtTrabalhador">
                                        <h3><?php echo htmlspecialchars($row['nome']); ?></h3>
                                        <p style="margin-bottom:0rem"><?php echo htmlspecialchars($row['categoria']); ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php }
                } else {
                    echo '<div class="tituloDEnaoEncontrado">';
                    echo '<p>Nenhum trabalhador favoritado</p>';
                    echo '</div>'; // Corrigido para fechar a div
                }    
                ?>
            </div>
        </div>
    </main>
   
    <script src="../../js/funcaoMenuLateral.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
