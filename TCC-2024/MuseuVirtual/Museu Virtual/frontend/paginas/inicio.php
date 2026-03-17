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
    <link rel="stylesheet" href="../css/inicio.css">
    <link rel="stylesheet" href="../bootstrap/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Museu Virtual - Início</title>
</head>
<body style="background-color: lightgoldenrodyellow;">

    <header>
        <div class="col-md-2">
            <div class="logo">
             <img src="../img/Logo - ETECAMP CV.png" alt="Logo do Museu Virtual">
            </div>
        </div>

        <div class="col-md-9">
         <div class="navbar">
            <a href="">INÍCIO</a>
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

     
     <main>
        <container>
            <div class="col-md-6">
             <div class="texto-sobre">
                <h1>Museu Virtual</h1>
                <p>Bem-vindo ao Museu Virtual da ETECAMP! Aqui, você pode explorar um conteúdo incrível que mostra a história e a cultura da nossa escola. Prepare-se para descobrir exposições interativas e aprender sobre as diferentes áreas do conhecimento. Esperamos que você aproveite essa experiência e se inspire muito!</p>
             </div>
            </div>
    
            <div class="col-md-6">
             <div class="imagem-inicio">
                <img src="../img/ImagemTelaInicial.png" alt="Imagem de um garoto escrevendo em cima de uma folha de papel gigante">
             </div>
            </div>
        </container>
     </main>

    <footer></footer>
    
</body>
</html>