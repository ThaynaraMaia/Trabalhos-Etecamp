<!DOCTYPE html>
<html lang="pt-br">
<?php
    session_start();

    if (!$_SESSION['tipo'] && !$_SESSION['logado']) {
        header('Location: ../mostre_sua_arte-login.php');
    }
    if ($_SESSION['tipo'] != 0 && $_SESSION['tipo']==1) {
        header('Location: ../paginasAdm/gerenciarObras.php');
    }

    include_once '../../../backend/classes/class_repositorioUsuarios.php';
    $registroUsuario = $repositorioUsuario->buscarUsuario($_SESSION['id']);
    $listagemUsuario = $registroUsuario->fetch_object();

    include_once '../../../backend/classes/class_repositorioObras.php';

    $autor = $_SESSION['id'];
    $registros = $repositorioObra->listarObras($autor);

    if(isset($_SESSION['mensagem'])){
        $mensagem = $_SESSION['mensagem'];
    } else {
        $mensagem = "";
    }

?>

</script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/publicarObra.css">
    <link rel="stylesheet" href="../../bootstrap/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Publicar obra</title>
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
        
        <form action="../../../backend/obras/publicacaoObra.php" method="post" enctype="multipart/form-data">

            <h1>Publicar obra</h1>

            <div class="mb-3">
                <label for="InputTitulo" class="form-label">Título: </label>
                <input type="text" name="titulo" class="form-control" id="InputTitulo" aria-describedby="tituloHelp" placeholder="Digite o título de sua obra" required>
            </div>

            <div class="categoria">
                <label for="categoria">Categoria: </label>
                <select name="categoria" id="categoria" onchange="mostrarOpcoes()" required>
                    <option value="">Selecione</option>
                    <?php
                        include_once '../../../backend/classes/class_repositorioCategorias.php';

                        //Mostra as categorias de acordo com o banco de dados.
                        $listaCategorias = $repositorioCategoria->listarCategorias();
                        while($listagem = $listaCategorias->fetch_object()) {
                    ?>
                    <option value="<?php echo $listagem->id ?>"><?php echo $listagem->nome ?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>

            <!-- <div class="mb-3">
                <label for="InputDescricao" class="form-label">Descrição: </label>
                <input type="text" name="descricao" class="form-control" id="InputDescricao" aria-describedby="descricaoHelp" placeholder="Digite uma descrição">
            </div>

            <div class="mb-3">
                <label for="InputFile" class="form-label">Trabalho Artístico: </label>
                <input type="file" name="trabalhoArtistico" class="form-control" id="InputFile" aria-describedby="fileHelp" required>
                <span><?php echo $mensagem;?></span>
            </div> -->

            <div>

                <div class="mb-3">
                    <label for="InputDescricao" class="form-label">Descrição: </label>
                    <input type="text" name="descricao" class="form-control" id="InputDescricao" aria-describedby="descricaoHelp" placeholder="Digite uma descrição" required>
                </div>

                <div class="mb-3" id="categoriaTextoConteudo" style="display: none; margin: auto;">
                    <label for="InputDescricao" class="form-label">Poema/Poesia: </label>
                    <br>
                    <textarea name="textoObra" cols="50" rows="10" placeholder="Digite aqui..." class="area"></textarea>
                    <!-- <input type="text" name="textoObra" class="form-control" id="InputDescricao" aria-describedby="descricaoHelp" placeholder="Digite aqui..."> -->
                </div>

                <div class="mb-3">
                    <label for="InputFile" class="form-label" id="categoriasComunsObra">Trabalho Artístico: </label>
                    <label for="InputFile" class="form-label" id="categoriaTextoCapa" style="display: none; margin: auto;">Insira uma capa para seu poema/poesia: </label>
                    <input type="file" name="trabalhoArtistico" class="form-control" id="InputFile" aria-describedby="fileHelp" required>
                    
                    <br>
                    <span>
                        <?php 
                            if(isset($_SESSION['mensagemInsiraUmTexto'])){
                                $mensagemTexto = $_SESSION['mensagemInsiraUmTexto'];
                                echo $mensagemTexto;
                            }
                            echo $mensagem;
                        ?>
                    </span>
                    <br>
                </div>
            
            </div>

            <script>
                function mostrarOpcoes() {
                    var selectCategoria = document.getElementById('categoria');
                    var categoriasComunsObra = document.getElementById('categoriasComunsObra');
                    var categoriaTextoConteudo = document.getElementById('categoriaTextoConteudo');
                    var categoriaTextoCapa = document.getElementById('categoriaTextoCapa');

                    if (selectCategoria.value === '8') {
                        categoriaTextoConteudo.style.display = 'block'; // Mostra o container de categorias
                        categoriaTextoCapa.style.display = 'block';
                        categoriasComunsObra.style.display = 'none';
                    } else {
                        categoriaTextoConteudo.style.display = 'none'; // Esconde o container de categorias
                        categoriaTextoCapa.style.display = 'none';
                        categoriasComunsObra.style.display = 'block';
                    }
                    
                }
            </script>


            <button class="btn btn-warning"><a href="mostre_sua_arte-obras.php" style="color: #ffffff; text-decoration: none;">Voltar</a></button>
            <button type="submit" class="btn btn-success" onclick="exibirMensagem()"><a href="publicarObra.php"></a>Publicar obra</button>
            
            <script>
                function exibirMensagem() {
                    alert("Sua obra será verificada por um administrador, aguarde até que fique disponível em 'Exposições'!");
                }
            </script>
        </form>
        
    </container>
</body>
</html>