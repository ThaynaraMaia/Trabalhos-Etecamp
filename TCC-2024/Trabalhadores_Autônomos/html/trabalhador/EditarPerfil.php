<?php
session_start();
include_once('../../backend/Conexao.php');

$id_trabalhador = $_SESSION['id_trabalhador'];

$sql = "SELECT * FROM trabalhador WHERE id_trabalhador = '$id_trabalhador'";
$result = $conn->query($sql);

$resultado_pesquisar = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($resultado_pesquisar);

$sql_edit = "SELECT * FROM atualizacoes_pendentes WHERE id_trabalhador = ? AND aprovado = 0";
$stmt = $conn->prepare($sql_edit);
$stmt->bind_param("i", $id_trabalhador);
$stmt->execute();
$result_edit = $stmt->get_result();
$atualizacaoPendente = $result_edit->num_rows > 0;

?>
<style>
   
    .BarraNav{
    background-color: #FFF;
    padding:15px 35px 5px 15px;
    display: flex;
    justify-content: space-between ;
}
.BarraNav >img{
    width: 210px;
    margin-top: -10px;
}

.BarraNav a{
    text-decoration: none ;
    color: #EC5C14;
    font: 400 1.1rem "M PLUS 1p", sans-serif ;
}
.FotoPerfil{
    width: 50px;
    height: 50px;
    border: solid 3px var(--corPrimaria-2);
    border-radius: 50%;
    padding: 1px;

}
.menuLateral .icon i {
    font-size: 34px; /* Ajuste o valor de acordo com o tamanho desejado */

}
.menuLateral .icon i {
    font-size: 34px; /* Ajuste o valor de acordo com o tamanho desejado */
}
nav.menuLateral{
    width: 64px;
     height: 430px;
    background-color: #04074B;
    padding: 30px 0 40px 1%;
    box-shadow: 3px 0 0 #ED5C15;
    border-radius: 0 20px 20px 0;
    z-index: 100;

    position: fixed;
    top: 15%;
    left: 0;
    overflow: hidden; 
    transition: .3s;
}
nav.menuLateral.expandir{
    width: 300px;
}
.IconExpandir{
    width: 100%;
    padding-left: 7px;
}
.IconExpandir > i{
    color: #ED5C15;
    font-size: 1.8rem;
    cursor: pointer;
}

ul{
    height: 100%;
    list-style-type: none;
    padding-left: 3%;
    
}

ul li.itemMenu a:hover{
    background: #ED5C15;
    color: #FFF;
    border-radius: 9px 0px 0px 9px;
}

ul li.itemMenu a{
    color: #ED5C15;
    text-decoration: none;
    font-size: 1.2rem;
    padding: 10px 1%;
    display: flex;
    margin-bottom: 10px;
    line-height: 30px;
    transition: .4s;
}
ul li.ativo a{
    background-color: #ED5C15;
    color: #FFF;
    border-radius: 9px 0px 0px 9px;
}

ul li.itemMenu a .txtLink{
    margin-left: 70px;
    transition: .6s;
    opacity: 0;
}
nav.menuLateral.expandir .txtLink{
    margin-left: 40px;
    opacity: 1;
}
ul li.itemMenu a .icon > i{
    font-size: 30px;
    padding-left: 5px;
}
</style>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JundTask - Editar Pefil</title>
    <link rel="stylesheet" href="../../css/styleEditarPerfil.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <header>
        <nav class="BarraNav">
            <img src="../../img/LogoJundtaskCompleta.png" alt="Logo JundTask">
            <h1>Editar Perfil</h1>
            <div class="perfil">
            <img class="FotoPerfil" src="../../uploads/<?php echo !empty($row['foto_perfil']) ? $row['foto_perfil'] : '../img/FotoPerfilGeral.png' ?>" alt="">
            <!-- <a href="#">
                    <img class="FotoPerfil" src="../uploads/<?php echo !empty($row['foto_perfil']) ? $row['foto_perfil'] : '../img/FotoPerfilGeral.png' ?>" alt="">
                </a> -->
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
<?php if ($atualizacaoPendente): ?>
    <div class="MensagemAtualizacao">
        <strong>
            <p>
                <img src="../../img/loading.gif" alt="Carregando" style="width: 70px; vertical-align: middle;">
                Você já possui uma solicitação de atualização pendente, aguardando aprovação do administrador.
            </p>
        </strong>
    </div>
<?php endif; ?>


                <div class="container">
                    <div class="row me-0 mb-5 topoPerfil">
                        <div class="col-1 sucess imgPerfil" >
                            <img src="../../uploads/<?php echo !empty($row['foto_perfil']) ? $row['foto_perfil'] : '../img/images100x100.png' ?>" alt="Foto de perfil">
                    </div>
                    <div class="col txtPerfil d-flex flex-column justify-content-center">
                        <h3><?php echo $row['nome']; ?></h3>
                         <p><?php echo !empty($row['descricao']) ? $row['descricao'] : 'Sua descrição...' ?></p>
                    </div>
                    </div>
                    
                    <form method="POST" action="../../backend/atualizacoesPendentes.php" enctype="multipart/form-data">
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
                                <div class="box marginteste">
                                    <select name="id_categoria" id="id_categoria">
                                        <option value="">Selecione uma categoria</option>
                                    </select>
                                </div>
                                <div>
                                    <textarea name="descricao" id="descricao"><?php echo !empty($row['descricao']) ? $row['descricao'] : 'Fale sobre você...' ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="rol d-flex me-0 txtFotos">
                            <div class="col d-flex flex-column justify-content-center">
                                <label for="foto_perfil">Foto de perfil</label>
                                <input type="file" name="foto_perfil" id="foto_perfil">
                            </div>
                            
                            <div class="col d-flex flex-column justify-content-center">
                                <label for="foto_perfil">Foto do banner</label>
                                <input type="file" name="foto_banner" id="foto_banner">
                            </div>
                        </div>
                        <div class="rol d-flex mt-0 txtFotosT">
                            <div class="col d-flex flex-column justify-content-center txtMargin mt-5 ">
                                <label for="foto_trabalho1">Foto trabalho 1</label>
                                <input type="file" name="foto_trabalho1" id="foto_trabalho1">
                            </div>
                            <div class="col d-flex flex-column justify-content-center mt-5">
                                <label for="foto_trabalho2">Foto trabalho 2</label>
                                <input type="file" name="foto_trabalho2" id="foto_trabalho2">
                            </div>
                            <div class="col d-flex flex-column justify-content-center mt-5">
                                <label for="foto_trabalho3">Foto trabalho 3</label>
                                <input type="file" name="foto_trabalho3" id="foto_trabalho3">
                            </div>
                        </div>
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