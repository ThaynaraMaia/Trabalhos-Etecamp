<!DOCTYPE html>
<html lang="pt-br">
<?php
    session_start();
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/sobre.css">
    <link rel="stylesheet" href="../bootstrap/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Museu Virtual - Sobre</title>
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
              if(isset($_SESSION['nome'])){
                if($_SESSION['tipo']== 1){
                    echo "<a href=\"paginasAdm/gerenciarUsuarios.php\">USUÁRIOS</a>";
                  }
                  else if($_SESSION['tipo']== 0){
                    echo "<a href=\"paginasAluno/mostre_sua_arte-obras.php\">MOSTRE SUA ARTE</a>";;
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
            <a href="">SOBRE</a>
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
        <h1>SOBRE</h1>
        <p>A educação artística é essencial para o desenvolvimento integral dos estudantes, estimulando a criatividade, a expressão individual e a compreensão cultural. No entanto, muitas escolas enfrentam desafios para oferecer recursos e espaços adequados à exposição e valorização dos trabalhos artísticos dos alunos, limitando suas oportunidades de desenvolver habilidades criativas e de expressão.</p>
        <p>Em muitas regiões, especialmente em áreas remotas ou economicamente desfavorecidas, o acesso à cultura e à arte é limitado devido a restrições logísticas e financeiras. Um museu virtual oferece a oportunidade de democratizar o acesso à cultura e à arte, permitindo que alunos de diferentes locais e realidades compartilhem suas produções artísticas em um ambiente online inclusivo e acessível.</p>
        <p>Um museu virtual dedicado à exposição de trabalhos artísticos de alunos promove o estímulo à criatividade e à expressão individual, proporcionando um espaço seguro e moderado para que os estudantes compartilhem suas produções artísticas, interajam com as obras de seus colegas e recebam feedback construtivo, incentivando o desenvolvimento de suas habilidades artísticas.</p>
    </container>

    <footer>
        <!-- <p>Redes Sociais</p>
        <a href=""><img src="../img/icon-instagram-colorido.png" alt="icone do instagram"></a> -->
        <p>© 2024 Todos os direitos reservados</p>
    </footer>
</body>
</html>