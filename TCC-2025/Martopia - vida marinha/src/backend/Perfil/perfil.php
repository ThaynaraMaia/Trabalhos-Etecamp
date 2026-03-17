<?php
session_start();
include_once '../classes/class_IRepositorioUsuarios.php';
include_once '../classes/class_IRepositorioInstamar.php';

// Proteção: só logado pode acessar
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/login.php");
    exit;
}

$id_usuario = $_SESSION['id'] = $_GET['id'];

$dados = $respositorioUsuario->buscarUsuario($id_usuario);
$post = $respositorioUsuario->listarTodasPostagens($id_usuario);
$usuario = $respositorioInstamar->PegardadosUsuario($id_usuario);
// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';


// Lista de avatares disponíveis (imagens na pasta /frontend/public/imagens/avatars/)
$avatars = [
    "baleia.jpg",
    "tartaruga.jpg",
    "tubaleia.jpg"
];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Escolher Avatar</title>
    <!-- <link rel="stylesheet" href="../../frontend/public/css/Perfil.css">  -->
    <link rel="stylesheet" href="../../frontend/public/css/perfilUser.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
     <link rel="stylesheet" href="./t.css">
</head>
<style>
    /* 
<!-- ==============
MODAL ALTERAR Dados
================ --> */

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
}

.modal-content {
    background: #fff;
    padding: 20px;
    margin: 10% auto;
    width: 400px;
    border-radius: 10px;
    text-align: left;
    position: relative;
}

.close {
    position: absolute;
    right: 15px; top: 10px;
    font-size: 22px;
    cursor: pointer;
}

.edit-icon {
    margin-left: 10px;
    cursor: pointer;
    color: #0077cc;
}


/* <!-- ==============
MODAL ALTERAR Dados FINAL
================ --> */
</style>
<body>

    <div class="container" style="max-height: 630px;">

        <div class="main">
            <h1>Perfil-Usuario</h1>
            <div class="dados">
                <img src="../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil" class="avatar">

                <h2>Informações</h2>

                <div class="dados">
                    <h3 class="nome">
                        <p  style="font-size: 1.2rem; font-family: 'Texto';">Nome: <?php echo $usuario['nome'] ?></p>
                    </h3>
                </div>

                <div class="dados">
                    <h3 class="nome"  style="font-size: 1.2rem;">
                        <p  style="font-size: 1.2rem; font-family: 'Texto';">Email: <?php echo $usuario['email'] ?></p>
                    </h3>
                </div>

            </div>

            <div class="botao">

                 <button onclick="history.back()" class="btn-login" style="font-size: 1.3rem;"> Voltar </button>
              
            </div>

        </div>


        <div class="troca-foto">

            <div class="troca">
                <h2>Post de <?php echo $usuario['nome'] ?></h2>
            </div>

            <iframe src="PostdoUsuario.php" width="100%" height="300" frameborder="0" title="Página 2" style="border: 2px solid #9FD5D1; border-radius: 20px;"></iframe>

        </div>


    </div>



</body>

</html>