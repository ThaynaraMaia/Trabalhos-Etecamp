<?php
session_start();
include_once ('../../backend/Conexao.php');
// $id_cliente = $_SESSION['id_cliente'];

if (isset($_SESSION['id_cliente'])) {
    $id_cliente = $_SESSION['id_cliente']; // Pega o ID do trabalhador logado
    
    // Verificar se há erros na conexão
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Preparar a consulta SQL
    $sql = "SELECT * FROM cliente WHERE id_cliente = ?";
    $stmt = $conn->prepare($sql); // Preparar a consulta
    if ($stmt === false) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    // Vincular o parâmetro (i significa integer)
    $stmt->bind_param("i", $id_cliente); // "i" indica que o parâmetro é um inteiro
    $stmt->execute(); // Executar a consulta

    // Obter o resultado
    $resultado_pesquisar = $stmt->get_result();

    // Verificar se encontrou o trabalhador
    if ($resultado_pesquisar->num_rows > 0) {
        $row = $resultado_pesquisar->fetch_assoc();
    } else {
        echo "Cliente não encontrado.";
    }

    // Fechar o statement
    $stmt->close();
} else {
    echo "Nenhum cliente está logado.";
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
    <link rel="stylesheet" href="../../css/styleHomeLogado.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">

    <link rel="shortcut icon" href="../../img/logo@2x.png" type="image/x-icon">
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

    <main class=""> 
        
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


        <div class="row me-0 inicioPage d-flex justify-content-center BGblob">
            <div class="col d-flex justify-content-center flex-column ">
                <h1>Seja Bem-vindo(a)! <br> <?php echo $row['nome']; ?></h1>
                <p>Espero que este seja o lugar onde você encontre os melhores profissionais da sua região.</p>
            </div>
            <div class="col me-0 pe-0 imgfundo">
                <img src="../../img/boasvindasCliente.png" alt="" >
            </div>
       </div>
    
                <div class="row me-0 d-flex justify-content-center fundocarrossel">
                    <h2>Olhe algumas de nossas avaliações!</h2>
                    <div class="col">
                        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="../../img/avaliacao1.png" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="../../img/avaliacao2.png" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="../../img/avaliacao3.png" class="d-block w-100" alt="...">
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

            <form action="../../backend/reclamacao_cliente.php" method="POST">
                <div class="row me-0 mt-5 pb-5 d-flex ContateNos">
                    <div class="col flex-column elementosContateNos align-content-center">
                        <div class="TituloContateNos mb-3">
                            <h2>Contate-nos</h2>
                        </div>
                        <input type="hidden" name="id_cliente" value="<?php echo $row['id_cliente']; ?>">
                        <input type="hidden" name="nome" value="<?php echo $row['nome']; ?>">
                        <input type="hidden" name="email" value="<?php echo $row['email']; ?>">
                        <div>
                            <label for="ContateNos">Mensagem*</label>
                        </div>
                        <div>
                            <textarea name="reclamacao" id="" placeholder="Mande sua mensagem..."></textarea>
                        </div>
                        <div class="mt-3 botaoMensagem">
                            <input type="submit" value="Enviar mensagem">
                        </div>
                        <!-- <?php echo $_SESSION['mensagem'];?> -->
                    </div>
                    <div class="col ImgHomeContate">
                        <img src="../../img/ElementoHomeLogado.png" alt="#">
                    </div>
                </div>
            </form>


    </main>

    <footer class="d-flex justify-content-center " >
        <p style="margin-bottom: 0rem;">N</p>
        <p style="margin-bottom: 0rem;">Terms of Service</p>
        <p style="margin-bottom: 0rem;">Privacy Policy</p>
        <p style="margin-bottom: 0rem;">@2022yanliudesign</p>
    </footer>
    

    <script src="../../js/funcaoMenuLateral.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>