<!DOCTYPE html>
<html lang="pt-br">
<?php
    session_start();

    include_once '../../backend/classes/class_repositorioObras.php';
    include_once '../../backend/classes/class_repositorioUsuarios.php';
    include_once '../../backend/classes/class_repositorioCategorias.php';

    $id = $_GET['id'];
    $registros = $repositorioObra->buscarObra($id);
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/informacoesObra.css">
    <link rel="stylesheet" href="../bootstrap/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Museu Virtual - Exposições</title>
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
            //Se o usuário esiver logado, aparecerá a página que contém as obras dele em "Mostre sua arte", senão, aparecerá a página de login.
            if(isset($_SESSION['nome'])){
                if($_SESSION['tipo']== 1){
                    echo "<a href=\"paginasAdm/gerenciarUsuarios.php\">USUÁRIOS</a>";
                  }
                  else if($_SESSION['tipo']== 0){
                    echo "<a href=\"paginasAluno/mostre_sua_arte-obras.php\">MOSTRE SUA ARTE</a>";
                  }
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

        <?php

            //Código que só será exibido se o usuário estiver logado (acesso à página de perfil).

            if(isset($_SESSION['nome'])){
                include_once '../../backend/classes/class_repositorioUsuarios.php';
                $registroUsuario = $repositorioUsuario->buscarUsuario($_SESSION['id']);
                $listagemUsuario = $registroUsuario->fetch_object();
                echo "<div class=\"col-md-1\">";
                    echo "<div class=\"foto-perfil\">";
                     echo "<a href=\"perfil.php\"><img src=\"../uploadsImg/{$listagemUsuario->foto}\" alt=\"Foto de perfil do usuário\" style=\"border: solid white; border-radius: 50%;\"></a>"; 
                    echo "</div>";
                echo "</div>";
             }
        ?>

    </header>

    <container>

        <div class="icone-voltar" style="text-align: left; padding: 6px; margin-left: 6px; margin-top: 6px;">
        <a href="exposições.php"><img src="../img/icone-voltar.png" alt="voltar" style="width: 60px;"></a>
       </div>

        <!-- <h1>Informações sobre a obra</h1> -->

        <div class="informacoes">
            
            <?php

                //Código p/ exibir o nome do autor de acordo com o id do usuário (chave estrangeira).
                $listagem = $registros->fetch_object();
                $idAutor = $listagem->autor;
                $listaAutor = $repositorioUsuario->buscarUsuario($idAutor);
                $autores = $listaAutor->fetch_object();

                
                //Código p/ exibir o nome da categoria de acordo com o id da categoria (chave estrangeira).
                $idObra = $listagem->id;
                $registroObra = $repositorioObra->buscarObra($idObra);
                $listagemObra = $registroObra->fetch_object();
                $idCategoria = $listagemObra->categoria;
                $listaCategoria = $repositorioCategoria->buscarCategoria($idCategoria);
                $nomeCategoria = $listaCategoria->fetch_object();
                
                //Exibir a formatação do poema
                $conteudo = $listagem->texto;
                $conteudo = nl2br($conteudo);

                //Se a obra for um texto
                if(isset($listagem->texto) && !empty($listagem->texto)){
                    echo "<div class=\"texto\" style=\"text-align: center; width: 50%; margin-left: 60px;\">";
                        echo "<p>{$listagem->titulo}</p><p>{$conteudo}</p><br>";
                    echo "</div>";
                    echo "<p style=\"font-size: 18px; text-align: center; margin-right: 60px;\">Título da obra: {$listagem->titulo} <br> Categoria: {$nomeCategoria->nome} <br> Descrição: {$listagem->descricao} <br> Autor: {$autores->nome} <br> Data: {$listagem->data}</p>";
                    // echo "<br>";
                    // echo "<img src=\"../uploadsImg/{$listagem->trabalho_artistico}\" style=\"width: 250px;\" alt=\"Trabalho artístico\">";
                   
                }
                else if($listagem->categoria == 12){
                    echo "<video src=\"../uploadsVideos/{$listagem->trabalho_artistico}\" alt=\"Trabalho artístico\" style=\" width: 50%; border: 10px solid #ffff;\" controls></video>";
                    echo "<p>Título da obra: {$listagem->titulo} <br> Categoria: {$nomeCategoria->nome} <br> Descrição: {$listagem->descricao} <br> Autor: {$autores->nome} <br> Data: {$listagem->data}</p>";
                }
                else
                {
                    echo "<img src=\"../uploadsImg/{$listagem->trabalho_artistico}\" alt=\"Trabalho artístico\">";
                    echo "<p>Título da obra: {$listagem->titulo} <br> Categoria: {$nomeCategoria->nome} <br> Descrição: {$listagem->descricao} <br> Autor: {$autores->nome} <br> Data: {$listagem->data}</p>";
                }
                
                // $registros = $repositorioObra->nomeAutor();
                // echo $registros;
            ?>

        </div>
            
    </container>
</body>
</html>