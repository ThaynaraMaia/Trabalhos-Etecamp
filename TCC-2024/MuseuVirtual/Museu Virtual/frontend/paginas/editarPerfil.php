<!DOCTYPE html>
<html lang="pt-br">
<?php
    session_start();

    if (!$_SESSION['tipo'] && !$_SESSION['logado']) {
        header('Location: ../mostre_sua_arte-login.php');
    }
   
    include_once '../../backend/classes/class_repositorioUsuarios.php';

    $id = $_GET['id'];

    $registroUsuario = $repositorioUsuario->buscarUsuario($id); //Pega as informações referentes à obra, através do id dela.
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/perfil.css">
    <link rel="stylesheet" href="../bootstrap/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Perfil</title>
</head>
<body style="background-color: lightgoldenrodyellow;">
    <header>
        <div class="col-md-2">
            <div class="logo">
             <img src="../img/Logo - ETECAMP CV.png">
            </div>
        </div>

        <div class="col-md-9">
         <div class="navbar">
            <a href="inicio.php">INÍCIO</a>
            <a href="exposições.php">EXPOSIÇÕES</a>
            <?php
            if($_SESSION['tipo']== 1){
              echo "<a href=\"paginasAdm/gerenciarUsuarios.php\">USUÁRIOS</a>";
            }
            else if($_SESSION['tipo']== 0){
              echo "<a href=\"paginasAluno/mostre_sua_arte-obras.php\">MOSTRE SUA ARTE</a>";
            }
            else{
              echo "<a href=\"mostre_sua_arte-login.php\">MOSTRE SUA ARTE</a>";
            }
            ?>
            <?php
            //Se o usuário esiver logado, aparecerá a página que contém as obras dele em "Mostre sua arte", senão, aparecerá a página de login.
                if(isset($_SESSION['nome'])){
                    if($_SESSION['tipo']== 1){
                        echo "<a href=\"paginasAdm/gerenciarObras.php\">OBRAS</a>";
                    }
                }
            ?>
            <?php
                if(isset($_SESSION['nome'])){
                    if($_SESSION['tipo']== 1){
                        echo "<a href=\"paginasAdm/gerenciarCategorias.php\">CATEGORIAS</a>";
                    }
                }
            ?>
            <?php
                if(!isset($_SESSION['logado']) || isset($_SESSION['nome']) && $_SESSION['tipo'] != 1){
                    echo "<a href=\"sobre.php\">SOBRE</a>";
                }
            ?>
         </div>  
        </div>

     </header>

     <container>

        <div class="icone-voltar" style="text-align: left; padding: 6px; margin-left: 6px; margin-top: 6px;">
            <a href="perfil.php"><img src="../img/icone-voltar.png" alt="voltar" style="width: 60px;"></a>
        </div>

        <form action="../../backend/usuarios/editarUsuario.php" method="post" enctype="multipart/form-data">

        <?php
            $listagemUsuario = $registroUsuario->fetch_object();
        ?>

            <h1>Editar perfil</h1>

            <input type="hidden" name="id" value="<?php echo $listagemUsuario->id ?>">
            <div class="perfil" style="padding: 8px;">
                <?php echo "<img src=\"../uploadsImg/{$listagemUsuario->foto}\" alt=\"Foto de perfil\" style=\"border: solid white; border-radius: 50%;\">"; ?>
            </div>

            <div class="dados">

          <div class="col-md-12">
           
            <label for="InputDescricao" class="form-label">Nome: </label>
            <input type="text" name="nome" class="form-control" id="InputNome" aria-describedby="nomeHelp" value = "<?php echo $listagemUsuario->nome ?>">
            <br>
            <label for="InputDescricao" class="form-label">Foto de perfil: </label>
            <input type="file" name="foto" class="form-control" id="InputFile" aria-describedby="fileHelp">
            <br>
            <p style="text-align: center; padding: 4px;">
            <!-- <button class="btn btn-warning"><a href="perfil.php" style="color: #ffffff; text-decoration: none;">Voltar</a></button> -->
                <button type="submit" class="btn btn-success"><a href="perfil.php"></a>Editar</button>
            </p>
         </div>
            
        </form>
      
     </container>
</body>
</html>