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

    $id = $_GET['id'];

    $registros = $repositorioObra->buscarObra($id); //Pega as informações referentes à obra, através do id dela.
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/publicarObra.css">
    <link rel="stylesheet" href="../../bootstrap/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Editar obras</title>
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

            if(isset($_SESSION['nome'])){
                echo "<div class=\"col-md-1\">";
                 echo "<div class=\"foto-perfil\">";
                 echo "<a href=\"../perfil.php\"><img src=\"../../uploadsImg/{$listagemUsuario->foto}\" alt=\"Foto de perfil do usuário\" style=\"border: solid white; border-radius: 50%;\"></a>";   
                 echo "</div>";
                echo "</div>";
            }
        ?>
    </header>

    <container>
        
        <form action="../../../backend/obras/updateObra.php" method="post" enctype="multipart/form-data">

        <?php
            $listagem = $registros->fetch_object();
        ?>

            <h1>Editar publicação</h1>

            <input type="hidden" name="id" value="<?php echo $listagem->id ?>">
            <div class="mb-3">
                <label for="InputTitulo" class="form-label">Título: </label>
                <input type="text" name="titulo" class="form-control" id="InputTitulo" aria-describedby="tituloHelp" value = "<?php echo $listagem->titulo ?>" required>
            </div>

            <div class="mb-3">
                <label for="InputDescricao" class="form-label">Descrição: </label>
                <input type="text" name="descricao" class="form-control" id="InputDescricao" aria-describedby="descricaoHelp" value = "<?php echo $listagem->descricao ?>">
            </div>

            <?php
                if(isset($listagem->texto) && !empty($listagem->texto)){
            ?>
                <div class="mb-3" id="categoriaTextoConteudo" style="margin: auto;">
                    <label for="InputDescricao" class="form-label">Poema/Poesia: </label>
                    <br>
                    <div class="obraTexto" style="text-align: center;">
                        <textarea name="textoObra" cols="50" rows="10" class="area" ><?php echo $listagem->texto ?></textarea>
                        <!-- <input type="text" name="textoObra" class="form-control" id="InputDescricao" aria-describedby="descricaoHelp" placeholder="Digite aqui..."> -->
                    </div>
                    <br>
                    <label for="InputFile" class="form-label">Capa do Trabalho Artístico: </label>
                    <div class="obra" style="text-align: center;">
                        <?php 
                            echo "<img src=\"../../uploadsImg/{$listagem->trabalho_artistico}\" class=\"card-img-top\" style=\"max-width: 40%; max-height: 40%; width: auto; height: auto;\" alt=\"Trabalho artístico\">";
                        ?>
                    </div>
                    <br>
                    <input type="file" name="trabalhoArtistico" class="form-control" id="InputFile" aria-describedby="fileHelp">
                </div>
                
            <?php
                }
                else
                {
            ?>
            <div class="mb-3">
                <label for="InputFile" class="form-label">Trabalho Artístico: </label>
                <div class="obra" style="text-align: center;">
                    <?php 
                        if($listagem->categoria == 12){
                            echo "<video src=\"../../uploadsVideos/{$listagem->trabalho_artistico}\" alt=\"Trabalho artístico\" style=\" width: 50%;\" controls></video>";
                        }else{
                            echo "<img src=\"../../uploadsImg/{$listagem->trabalho_artistico}\" class=\"card-img-top\" style=\"max-width: 60%; max-height: 60%; width: auto; height: auto;\" alt=\"Trabalho artístico\">";
                        }
                    ?>
                </div>
                <br>
                <input type="file" name="trabalhoArtistico" class="form-control" id="InputFile" aria-describedby="fileHelp">
            </div>
            <?php
                }
            ?>

            <button class="btn btn-warning"><a href="mostre_sua_arte-obras.php" style="color: #ffffff; text-decoration: none;">Voltar</a></button>
            <button type="submit" class="btn btn-success"><a href="publicarObra.php"></a>Confirmar</button>
            
        </form>
        
    </container>
</body>
</html>