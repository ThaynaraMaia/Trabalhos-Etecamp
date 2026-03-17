<?php
session_start();
include_once '../conn/classes/class_IRepositorioUsuario.php';
$id_usuario= $_SESSION['id'];

$encontrou=$respositorioUsuario->buscarUsuario($id_usuario);
$usuario=$encontrou->fetch_object();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.flaticon.com/br/">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/usuario.css">
    <link rel="shortcut icon" href="../img/logoazul (1).png" type="image/x-icon">
    <title>Equilibrio - Perfil</title>

</head>
<?php
    if(isset($_SESSION['logado'])){
    ?>
    
<body>


    <div class="container">
        <div class="perfil-img">
            <img src="../img/mente.png" alt="" style="width: 500px;">
        </div>
  
            <div class="perfil">
            <nav id="navbarUsuario">
                <img src="../img/logoamarela (1).png" alt="logo" style="width: 110px"> 
            </nav>

                <form action="../usuario/perfil_editado.php" method="POST">
                <div class="perfil-header">
                    <div class="title">
                        <h1>Página do perfil</h1>
                    </div>
                    <div class="btn-voltar">
                        <a href="home.php">Voltar</a>
                    </div>
                    <div class="btn-voltar">
                    <a href="../usuario/logout.php">Sair</a>
                    </div>
                </div>
            
                <div class="input-group">
                    <div class="input-box">
                    <label for="name">Nome</label>
                    <input value="<?php echo $usuario->nome; ?>" id="name" type="text" name="nome" required>  
                </div>

        
                <div class="input-box">
                    <label for="name">E-mail</label>
                    <input value="<?php echo $usuario->email; ?>" id="e-mail" type="e-mail" name="email" required>
                </div>


                <div class="input-box">
                    <label for="password">Senha</label>
                    <input value="<?php echo $usuario->senha; ?>" id="password" type="" name="senha" required>
                </div>

            </div>

          
            <div class="btn-editar">
                <input value="salvar" name="editar" type="submit">
            </div>     
            
            </form>
          
      </div>
    </div>
    <?php
    }
    ?>

    <link rel="stylesheet" href="../bootstrap/js/bootstrap.min.js">

</body>
