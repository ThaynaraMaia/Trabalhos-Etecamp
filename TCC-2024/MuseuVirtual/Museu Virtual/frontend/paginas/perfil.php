<!DOCTYPE html>
<html lang="pt-br">

<?php

  session_start();

  if($_SESSION['id'] && $_SESSION['nome'] && $_SESSION['email'] && $_SESSION['logado'])
  {
    $id = $_SESSION['id'];
    $nome = $_SESSION['nome'];
    $email = $_SESSION['email'];
    $foto = $_SESSION['foto'];
    $logado = $_SESSION['logado'];

    include_once '../../backend/classes/class_repositorioUsuarios.php';
    $registroUsuario = $repositorioUsuario->buscarUsuario($id);

  }
  else
  {
    $logado = false;
    $nome = "";
  }

  
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

       <div class="icone-sair" style="text-align: right; padding: 6px; margin-right: 6px;">
        <a href="../../backend/usuarios/logoutUsuarios.php"><img src="../img/icone-sair-removebg-preview.png" alt="sair" style="width: 60px;"></a>
       </div>

        <h1>Meu perfil</h1>

        <?php
          $listagemUsuario = $registroUsuario->fetch_object();
        ?>

        <div class="perfil" style="padding: 8px;">
          <?php echo "<img src=\"../uploadsImg/{$listagemUsuario->foto}\" alt=\"Foto de perfil\" style=\"border: solid white; border-radius: 50%;\">"; ?>
         <!-- <img src="../img/icon-perfil.png" alt="Foto de perfil"> -->
        </div>

        <!-- Editar foto de perfil -->
        <!-- <form action="../../backend/usuarios/editarPerfil.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php //echo $id ?>">
            <div class="mb-3" style="width: 20%;">
                <input type="file" name="foto" class="form-control" id="InputFile" aria-describedby="fileHelp" required>
                <button type="submit" class="btn btn-success"><a href="perfil.php"></a>Editar</button>
            </div>
        </form> -->

        <div class="dados">

          <div class="col-md-11">
            <p style="font-weight: bold;">Nome: 
              <?php
                if($logado){
                  echo $listagemUsuario->nome;
                }else{
                  $nome = "Nenhum usuário logado.";
                  // echo $nome;
                }
              ?>
            </p>
           <p style="font-weight: bold;">Email: 
            <?php
                if($logado){
                  echo $listagemUsuario->email;
                }else{
                  $email = "Nenhum usuário logado.";
                  // echo $email;
                }
              ?>
           </p>
      
         </div>
          <!--<div class="col-md-1">
            <img src="../img/icon - editar - com cor.png" alt="icone de editar">
            <img src="../img/icon - editar - com cor.png" alt="icone de editar">
            <img src="../img/icon - editar - com cor.png" alt="icone de editar">
          </div> -->
          
        </div>

        <p style="text-align: center; padding: 8px;">
          <a href="editarPerfil.php?id=<?php echo $id; ?>" class="btn btn-warning">Editar perfil</a>
          <a href="#" onclick="confirmarExclusaoUsuario(<?php echo $listagemUsuario->id; ?>)" class="btn btn-danger">Excluir conta</a>
        </p>
      
        <script>
            function confirmarExclusaoUsuario(id) {
            const confirmacao = confirm("Você tem certeza que deseja excluir sua conta?");
            if (confirmacao) {
                window.location.href = `../../backend/usuarios/excluirUsuario.php?id=${id}`;
              }
            }
        </script>
     </container>
</body>
</html>