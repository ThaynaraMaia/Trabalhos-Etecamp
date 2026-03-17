<?php
session_start();
include_once('../../backend/Conexao.php');
$id_cliente = $_SESSION['id_cliente'];

// Pegar o id_categoria da URL (via GET)
$id_categoria = $_GET['id_categoria'];

if (!is_numeric($id_categoria)) {
    die("ID da categoria inválido.");
}



// Pegar a cidade selecionada via GET, se existir
$id_area = isset($_GET['id_area']) ? $_GET['id_area'] : '';

// Capturar o valor da pesquisa pelo nome
$nome_pesquisa = isset($_POST['nome_pesquisa']) ? trim($_POST['nome_pesquisa']) : '';

// Consulta para obter todas as cidades da área de atuação
$query_cidades = "SELECT DISTINCT id_area, cidade FROM area_atuação";
$result_cidades = mysqli_query($conn, $query_cidades);

if (!$result_cidades) {
    die("Erro ao obter as cidades: " . mysqli_error($conn));
}

// Construir a consulta SQL para filtrar por cidade, categoria e nome (se houver)
$query = "
    SELECT t.*, a.cidade, COUNT(c.id_trabalhador) AS total_curtidas 
    FROM trabalhador t 
    LEFT JOIN curtidas c ON t.id_trabalhador = c.id_trabalhador 
    INNER JOIN area_atuação a ON t.id_area = a.id_area
    WHERE t.id_categoria = $id_categoria
";

// Se uma cidade for selecionada, adicionar condição na consulta
if (!empty($id_area)) {
    $query .= " AND t.id_area = $id_area";
}

// Se um nome foi pesquisado, adicionar a condição de busca pelo nome
if (!empty($nome_pesquisa)) {
    $nome_pesquisa = mysqli_real_escape_string($conn, $nome_pesquisa);
    $query .= " AND t.nome LIKE '%$nome_pesquisa%'";
}

$query .= " GROUP BY t.id_trabalhador ORDER BY total_curtidas DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Erro na consulta: " . mysqli_error($conn));
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
    <title>JundTask - Home Cliente</title>
    <link rel="stylesheet" href="../../css/stylebusca.css">
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
                    <img class="FotoPerfil" src="../../uploads/<?php echo !empty($row['foto_perfil']) ? $row['foto_perfil'] : '../../img/FotoPerfilGeral.png' ?>" alt="">
                </a>
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

        <div class="sistemabusca">
            <div class="search-container">
                <form action="" method="POST" class="search-form">
                    <div class="pesquisarTrabalhos">
                        <input type="text" name="nome_pesquisa" placeholder="O que você está buscando?..." value="<?php echo htmlspecialchars($nome_pesquisa); ?>">
                    </div>
                    <input class="search-button" type="submit" value="Pesquisar">
                </form>

                
            </div>
            <div class="city-buttons-container">
                    <?php while ($cidade = mysqli_fetch_assoc($result_cidades)) { ?>
                        <form action="usuarios_por_categoria.php" method="GET" style="display: inline;">
                            <input type="hidden" name="id_categoria" value="<?php echo $id_categoria; ?>">
                            <input type="hidden" name="id_area" value="<?php echo $cidade['id_area']; ?>">
                            <button type="submit" class="city-btn">
                                <?php echo htmlspecialchars($cidade['cidade']); ?>
                            </button>
                        </form>
                    <?php } ?>
                </div>

                <div class="listatrabalhadores">
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) { ?>
                            <div class="CampoEscolhaTrabalhador">
                                <a href="./Perfil.php?id_trabalhador=<?php echo $row['id_trabalhador']; ?>">
                                    <div class="CardBox">
                                        <div class="imagem">
                                            <img src="../../uploads/<?php echo $row['foto_perfil']; ?>" alt="">
                                        </div>
                                        <div class="txtTrabalhador">
                                            <h3><?php echo htmlspecialchars($row['nome']); ?></h3>
                                            <p><?php echo htmlspecialchars($row['cidade']); ?></p> <!-- Exibindo a cidade -->
                                            <p><?php echo htmlspecialchars($row['total_curtidas']); ?> Likes</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php }
                    } else {
                        echo '<div class="tituloDEnaoEncontrado"><p>Nenhum trabalhador encontrado.</p></div>';
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

<?php 
$conn->close(); 
?>
