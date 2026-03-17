<!DOCTYPE html>
<html lang="pt-br">
<?php
    session_start();

    if (!$_SESSION['tipo'] && !$_SESSION['logado']) {
        header('Location: ../mostre_sua_arte-login.php');
    }
    if ($_SESSION['tipo'] != 1 && $_SESSION['tipo']==0) {
        header('Location: ../mostre_sua_arte-obras.php');
    }

    include_once '../../../backend/classes/class_repositorioCategorias.php';

    $id = $_GET['id'];

    $registroCategoria = $repositorioCategoria->buscarCategoria($id); //Pega as informações referentes à categoria, através do id dela.
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
                <a href="./gerenciarUsuarios.php">USUÁRIOS</a>
                <a href="./gerenciarObras.php">OBRAS</a>
                <a href="./gerenciarCategorias.php">CATEGORIAS</a>
            </div>
        </div>

        <?php
            //Código que só será exibido se o usuário estiver logado (acesso à página de perfil).

            if(isset($_SESSION['nome'])){
                echo "<div class=\"col-md-1\">";
                 echo "<div class=\"foto-perfil\">";
                 echo "<a href=\"../perfil.php\"><img src=\"../../uploadsImg/{$_SESSION['foto']}\" alt=\"Foto de perfil do usuário\" style=\"border: solid white; border-radius: 50%;\"></a>";   
                 echo "</div>";
                echo "</div>";
            }
        ?>
    </header>

    <container>
        
        <form action="../../../backend/categorias/admAlterarCategoria.php" method="post" enctype="multipart/form-data">

        <?php
            $listagem = $registroCategoria->fetch_object();
        ?>

            <h1>Editar Categoria</h1>

            <input type="hidden" name="id" value="<?php echo $listagem->id ?>">
            <div class="mb-3">
                <label for="InputTitulo" class="form-label">Nome: </label>
                <input type="text" name="nome" class="form-control" id="InputNome" aria-describedby="nomeHelp" value = "<?php echo $listagem->nome ?>" required>
            </div>

            <button class="btn btn-warning"><a href="./gerenciarCategorias.php" style="color: #ffffff; text-decoration: none;">Voltar</a></button>
            <button type="submit" class="btn btn-success"><a href="inserirCategoria.php"></a>Confirmar</button>
            
        </form>
        
    </container>
</body>
</html>