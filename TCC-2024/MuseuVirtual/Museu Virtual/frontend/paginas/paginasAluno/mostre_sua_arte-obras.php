<!DOCTYPE html>
<html lang="pt-br">
<?php
    session_start();

    if (!$_SESSION['tipo'] && !$_SESSION['logado']) {
        header('Location: ../mostre_sua_arte-login.php');
    }
    if ($_SESSION['tipo'] != 0 && $_SESSION['tipo']==1) {
        header('Location: ../paginasAdm/gerenciarUsuarios.php');
    }

    include_once '../../../backend/classes/class_repositorioUsuarios.php';
    $registroUsuario = $repositorioUsuario->buscarUsuario($_SESSION['id']);
    $listagemUsuario = $registroUsuario->fetch_object();

    include_once '../../../backend/classes/class_repositorioObras.php';

    $autor = $_SESSION['id'];
    $registros = $repositorioObra->listarObras($autor);

    if(isset($_SESSION['mensagemDesativado'])){
        $mensagemDesativado = $_SESSION['mensagemDesativado'];
    } else {
        $mensagemDesativado = "";
    }
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/mostre_sua_arte-obras.css">
    <link rel="stylesheet" href="../../bootstrap/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Mostre sua arte - Obras</title>
</head>

<body style="background-color: lightgoldenrodyellow;">
    <header>
        <div class="col-md-2">
            <div class="logo">
                <img src="../../img/Logo - ETECAMP CV.png">
            </div>
        </div>

        <div class="col-md-9">
            <div class="navbar">
                <a href="../inicio.php">INÍCIO</a>
                <a href="../exposições.php">EXPOSIÇÕES</a>
                <a href="">MOSTRE SUA ARTE</a>
                <a href="../sobre.php">SOBRE</a>
            </div>
        </div>

        <?php

        //Código que só será exibido se o usuário estiver logado (acesso à página de perfil).  

        if (isset($_SESSION['nome'])) {
            echo "<div class=\"col-md-1\">";
             echo "<div class=\"foto-perfil\">";
                echo "<a href=\"../perfil.php\"><img src=\"../../uploadsImg/{$listagemUsuario->foto}\" alt=\"Foto de perfil do usuário\" style=\"border: solid white; border-radius: 50%;\"></a>"; 
             echo "</div>";
            echo "</div>";
        }
        ?>

    </header>

    <container>

        <h1>Minhas obras</h1>

        <?php
        
            if($_SESSION['status'] == 1){
                echo "<a href=\"publicarObra.php\">";
                 echo "<img src=\"../../img/adicionar.png\" style=\"width: 36px; display:block; margin:auto\" alt=\"Adicionar nova obra\">";
                echo "</a>";
            }else{
                echo "<a href=\"../../../backend/usuarios/usuarioDesativado.php\">";
                 echo "<img src=\"../../img/adicionar.png\" style=\"width: 36px; display:block; margin:auto\" alt=\"Adicionar nova obra\">";
                echo "</a>";
                echo $mensagemDesativado;
            }
        ?>

        <div class="obras" style="display: flex; flex-wrap: wrap; padding: 5px;">
            
            <?php
                while ($listagem = $registros->fetch_object()) {
            ?>
            <div class="col" style="padding: 8px;">
        
                <div class="card" style="width: 22rem; margin: auto; padding: 5px; background-color: #501207; color: white;">

                <?php
                    if($listagem->categoria == 12){
                        echo "<video src=\"../../uploadsVideos/{$listagem->trabalho_artistico}\" class=\"card-img-top\" style=\"height: 250px; width: auto; \" alt=\"Trabalho artístico\"></video>";
                    }else{
                        echo "<img src=\"../../uploadsImg/{$listagem->trabalho_artistico}\" class=\"card-img-top\" style=\"height: 250px; width: auto; \" alt=\"Trabalho artístico\">";
                    }
                ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $listagem->titulo; ?></h5>

                        <?php

                            include_once '../../../backend/classes/class_repositorioCategorias.php';

                            //Código p/ exibir o nome da categoria de acordo com o id (chave estrangeira).
                            $idObra = $listagem->id;
                            $registroObra = $repositorioObra->buscarObra($idObra);
                            $listagemObra = $registroObra->fetch_object();
                            $idCategoria = $listagemObra->categoria;
                            $listaCategoria = $repositorioCategoria->buscarCategoria($idCategoria);
                            $nomeCategoria = $listaCategoria->fetch_object();

                        ?>

                        <p class="card-text"><?php echo "Descrição: {$listagem->descricao} <br> Categoria: {$nomeCategoria->nome}"; ?></p>
                        <a href="editarObras.php?id=<?php echo $listagem->id; ?>" class="btn btn-warning">Editar</a>
                        <a href="#" onclick="confirmarExclusao(<?php echo $listagem->id; ?>)" class="btn btn-danger">Excluir</a>
                        
                        <script>
                            function confirmarExclusao(id) {
                            const confirmacao = confirm("Você tem certeza que deseja excluir essa obra?");
                            if (confirmacao) {
                                window.location.href = `../../../backend/obras/excluirObras.php?id=${id}`;
                                }
                            }
                        </script>
                    </div>
                </div>
            </div>
                <?php
                    }
                ?>
        </div>

    </container>
</body>

</html>