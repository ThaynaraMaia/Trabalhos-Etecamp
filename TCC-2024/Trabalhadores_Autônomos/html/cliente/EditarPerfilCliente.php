<?php
session_start();
include_once('../../backend/Conexao.php');

$id_cliente = $_SESSION['id_cliente'];

$sql = "SELECT * FROM cliente WHERE id_cliente = '$id_cliente'";
$result = $conn->query($sql);

$resultado_pesquisar = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($resultado_pesquisar);


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
    <title>JundTask - Editar Pefil</title>
    <link rel="stylesheet" href="../../css/styleEditarPerfil.css">
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="shortcut icon" href="../../img/logo@2x.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">

</head>
<body>
    <header>
        <nav class="BarraNav">
            <img src="../../img/LogoJundtaskCompleta.png" alt="Logo JundTask">
            <h1>Editar Perfil</h1>
            <div class="perfil">
            <img class="FotoPerfil" src="../../uploads/<?php echo !empty($row['foto_perfil']) ? $row['foto_perfil'] : '../../img/FotoPerfilGeral.png' ?>" alt="">
            
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
        <li class="itemMenu ativo">
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
                    <div class="row me-0 mb-5 topoPerfil">
                        <div class="col-1 sucess imgPerfil" >
                            <img src="../../uploads/<?php echo !empty($row['foto_perfil']) ? $row['foto_perfil'] : '../../img/images100x100.png' ?>" alt="Foto de perfil">
                    </div>
                    <div class="col txtPerfil d-flex flex-column justify-content-center">
                        <h3><?php echo $row['nome']; ?></h3>
                    </div>
                    </div>
                    
                    <form method="POST" action="./AtualizaDadosCliente.php" enctype="multipart/form-data">
                        <div class="row me-0 ">
                            <div class="col coluna1">
                                <div class="EstiloInputs">
                                    <input type="text" name="nome" id="nome" value="<?php echo $row['nome']; ?>">
                                </div>
                                <div class="EstiloInputs">
                                    <input type="email" name="email" id="" value="<?php echo $row['email']; ?>">
                                </div>
                                <div class="EstiloInputs">
                                    <input type="password" name="senha" id="Senha" placeholder="Nova senha">
                                </div>
                                <div class="EstiloInputs">
                                    <input type="text" name="contato" id="contato" value="<?php echo !empty($row['contato']) ? $row['contato'] :  ''  ?>" placeholder="Atualize seu telefone">
                                </div>
                                <div class="EstiloInputs mb-5">
                                    <input type="date" name="data_nasc" id="data_nasc" value="<?php echo !empty($row['data_nasc']) ? $row['data_nasc'] : '' ?>">
                                </div>
                            </div>
                            <div class="col">
                                <div class="box">
                                        <select name="id_area" id="id_area">
                                            <option value="">Altere a cidade</option>
                                        </select>
                                </div>
                                <label for="foto_perfil" class="marginteste">Foto de perfil</label>
                                <input type="file" name="foto_perfil" id="foto_perfil">
                                
                            </div>
                        </div>
                        <!-- <div class="rol d-flex me-0">
                            <label for="foto_perfil">Foto de perfil</label>
                            <input type="file" name="foto_perfil" id="foto_perfil">
                        </div> -->
                        <div class="row">
                            <div class="col txtMargin botaoSalvar d-flex justify-content-end mt-5">
                                <input type="submit" value="Salvar">
                            </div>
                        </div>
                        </div>
                    </form>
                </div>

    </main>

    

    <footer class="d-flex justify-content-center ">
        <p>N</p>
        <p>Terms of Service</p>
        <p>Privacy Policy</p>
        <p>@2022yanliudesign</p>
    </footer>
    
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const areaSelect = document.getElementById('id_area');
        const categoriaSelect = document.getElementById('id_categoria');

        // Carregar áreas
        fetch('../getcidades.php')
            .then(response => response.json())
            .then(areas => {
                console.log(areas); 
                areaSelect.innerHTML = '<option value="">Altere a Cidade</option>'; 
                areas.forEach(area => {
                    const option = document.createElement('option'); 
                    option.value = area.id_area; 
                    option.textContent = area.cidade; 
                    areaSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Erro ao carregar áreas:', error));

        // Carregar categorias
        fetch('../getcategoriacadastro.php')
            .then(response => response.json())
            .then(categorias => {
                console.log(categorias); 
                categoriaSelect.innerHTML = '<option value="">Selecione uma categoria</option>'; 
                categorias.forEach(categoria => {
                    const option = document.createElement('option');
                    option.value = categoria.id_categoria; 
                    option.textContent = categoria.nome; 
                    categoriaSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Erro ao carregar categorias:', error));
      });
    </script>
    <script src="../../js/funcaoMenuLateral.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>