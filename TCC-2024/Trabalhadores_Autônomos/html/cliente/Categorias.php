<?php
session_start();
include_once('../../backend/Conexao.php');

$id_cliente = $_SESSION['id_cliente'];

// Consulta para pegar as informações do cliente
$sql = "SELECT * FROM cliente WHERE id_cliente = '$id_cliente'";
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);

// Captura o valor da pesquisa pelo nome
$nome_pesquisa = isset($_POST['nome_pesquisa']) ? trim($_POST['nome_pesquisa']) : '';

// Consulta para pegar todas as categorias, com condição de busca se o nome for preenchido
$sql_categorias = "SELECT * FROM categorias WHERE nome_cat LIKE '%$nome_pesquisa%'";
$resultado_categorias = $conn->query($sql_categorias);

// Verifique se a consulta foi bem-sucedida
if (!$resultado_categorias) {
    echo "Erro na consulta: " . $conn->error;
}

$sql_trabalhadores = "
    SELECT t.*, a.cidade, cat.nome_cat AS nome_cat, COUNT(cu.id_trabalhador) AS total_curtidas 
    FROM trabalhador t
    LEFT JOIN area_atuação a ON t.id_area = a.id_area 
    LEFT JOIN categorias cat ON a.id_categoria = cat.id_categoria
    LEFT JOIN curtidas cu ON t.id_trabalhador = cu.id_trabalhador 
    GROUP BY t.id_trabalhador, a.cidade, cat.nome_cat
    HAVING total_curtidas > 0 
    ORDER BY total_curtidas DESC 
    LIMIT 3";


$result_trabalhadores = $conn->query($sql_trabalhadores);
?>
<style>
    nav.menuLateral {
        width: 65px;
        height: 420px;
    }
</style>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JundTask - Pesquisar</title>
    <link rel="stylesheet" href="../../css/styleCategoria.css">
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.min.css"> <!-- Adicionado Bootstrap CSS -->
    <link rel="shortcut icon" href="../../img/logo@2x.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">

</head>
<body>
    <header>
        <nav class="BarraNav">
            <img src="../../img/LogoJundtaskCompleta.png" alt="Logo JundTask">
            <div class="perfil">
                <a href="#">
                    <img class="FotoPerfilNav" src="../../uploads/<?php echo !empty($row['foto_perfil']) ? $row['foto_perfil'] : '../img/FotoPerfilGeral.png' ?>" alt="">
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


        <div class="containerbusca">
            <div class="sistemabusca">
                <div class="search-container">
                    <form action="" method="POST" class="search-form">
                        <div class="pesquisarTrabalhos">
                            <input type="text" name="nome_pesquisa" placeholder="O que você está buscando?..." value="<?php echo htmlspecialchars($nome_pesquisa); ?>">
                        </div>
                        <input class="search-button" type="submit" value="Buscar">
                    </form>
                </div>
            </div>
        </div>

        <div class="container">
            <?php if ($resultado_categorias->num_rows > 0): ?>
                <?php while ($categoria = $resultado_categorias->fetch_assoc()): ?>
                    <div class="card">
                        <a href="usuarios_por_categoria.php?id_categoria=<?= $categoria['id_categoria'] ?>">
                            <img src="../../uploads/categorias/<?= !empty($categoria['imagem']) ? $categoria['imagem'] : 'default.png' ?>" alt="<?= $categoria['nome_cat'] ?>">
                            <p><?= htmlspecialchars($categoria['nome_cat']) ?></p>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nenhuma categoria disponível.</p>
            <?php endif; ?>
        </div>
        <div class="containercarrosel">
    <h2 class="text-center">Trabalhadores com Mais Curtidas</h2> <!-- Usando Bootstrap para centralizar -->
    <div class="carousel">
        <?php if ($result_trabalhadores && $result_trabalhadores->num_rows > 0): ?>
            <?php while ($trabalhador = $result_trabalhadores->fetch_assoc()): ?>
                <a href="./Perfil.php?id_trabalhador=<?php echo $trabalhador['id_trabalhador']; ?>" class="cardtrabalhadores">
                    <div class="card-headertrabalhadores">
                        <img src="../../uploads/<?php echo $trabalhador['foto_perfil']; ?>" alt="">
                    </div>
                    <div class="card-bodytrabalhador">
                        <h2><?php echo htmlspecialchars($trabalhador['nome']); ?></h2>
                        <p><strong>Cidade:</strong> <?php echo htmlspecialchars($trabalhador['cidade']); ?></p>
                        <p><?php echo htmlspecialchars(substr($trabalhador['descricao'], 0, 70)) . '...'; ?></p> <!-- Exibe os primeiros 100 caracteres da descrição -->
                        <p><strong><?php echo htmlspecialchars($trabalhador['total_curtidas']); ?> Likes</strong></p>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Nenhum trabalhador disponível.</p>
        <?php endif; ?>
    </div>
</div>


    </main>

    <footer class="d-flex justify-content-center">
        <p style="margin-bottom: 0rem;">N</p>
        <p style="margin-bottom: 0rem;">Terms of Service</p>
        <p style="margin-bottom: 0rem;">Privacy Policy</p>
        <p style="margin-bottom: 0rem;">@2022yanliudesign</p>
    </footer>
    
    <script src="../../js/funcaoMenuLateral.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script> <!-- Adicionado Bootstrap JS -->
</body>
</html>
