<?php 
    session_start();
    include_once ('../../backend/Conexao.php');
    
    
    if (isset($_SESSION['id_trabalhador'])) {
        $idTrabalhador = $_SESSION['id_trabalhador']; // Pega o ID do trabalhador logado
        
        // Verificar se há erros na conexão
        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }
    
        // Preparar a consulta SQL
        $sql = "SELECT * FROM trabalhador WHERE id_trabalhador = ?";
        $stmt = $conn->prepare($sql); // Preparar a consulta
        if ($stmt === false) {
            die("Erro ao preparar a consulta: " . $conn->error);
        }
    
        // Vincular o parâmetro (i significa integer)
        $stmt->bind_param("i", $idTrabalhador); // "i" indica que o parâmetro é um inteiro
        $stmt->execute(); // Executar a consulta
    
        // Obter o resultado
        $resultado_pesquisar = $stmt->get_result();
    
        // Verificar se encontrou o trabalhador
        if ($resultado_pesquisar->num_rows > 0) {
            $row = $resultado_pesquisar->fetch_assoc();
        } else {
            echo "Trabalhador não encontrado.";
        }
    
        // Fechar o statement
        $stmt->close();
    } else {
        echo "Nenhum trabalhador está logado.";
    }
    
    // Fechar a conexão
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JundTask - Seu Perfil</title>
    <link rel="stylesheet" href="../../css/stylePerfil.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">

    <link rel="shortcut icon" href="../../img/logo@2x.png" type="image/x-icon">
</head>
<style>
   nav.menuLateral{
    width: 64px;
    height: 430px;
    }
    .menuLateral .icon i {
    font-size: 34px; /* Ajuste o valor de acordo com o tamanho desejado */
}
</style>
<body>
    <header>
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
        <div class="FotoFundo">
            <!-- foto background -->
            <img src="../../uploads/<?php echo !empty($row['foto_banner']) ? $row['foto_banner'] : '../img/TelaPredefinida.png' ?>" alt="">
            <div class="BlocoPerfilPrincipal">
                <div class="FotoPerfil"><img src="../../uploads/<?php echo !empty($row['foto_perfil']) ? $row['foto_perfil'] : '../../img/images100x100.png' ?>" alt=""></div>
                <div class="NomeTrabalhador"> <?php echo $row['nome']; ?></div>
                <div class="tel">
                    <p><?php echo $row['contato'];?></p>
                </div>
            </div>

            <div class="txt">
                <p><?php echo $row['descricao'];?></p>
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

    </main>     

    

    <footer class="d-flex justify-content-center ">
        <p>N</p>
        <p>Terms of Service</p>
        <p>Privacy Policy</p>
        <p>@2022yanliudesign</p>
    </footer>
    

    <script src="../../js/funcaoMenuLateral.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>