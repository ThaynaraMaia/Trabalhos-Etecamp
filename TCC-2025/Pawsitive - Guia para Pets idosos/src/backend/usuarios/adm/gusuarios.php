<?php
include_once '../../classes/class_IRepositorioUsuarios.php';
$registros = $respositorioUsuario->listarTodosUsuarios();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../adm/gusuarios.css">
    <link rel="shortcut icon" href="../../../img/favicon.ico" type="image/x-icon">
    <title>Usuarios</title>
</head>


<body>

    <div class="container-fluid">
        <div class="row" id="rownav" style="background-color: #A8B16B;">
            <nav class="navbar position-relative" style="height: 135px; position: relative;">
                <div class="container position-relative" style="position: relative;">

                    <!-- Botão à direita -->
                    <div class="ms-auto">
                        <a href="../imgUsuarios/user_padrao.png"></a>
                        <?php
                        session_start();

                        if (isset($_SESSION['user'])) {
                            // Caminho padrão da foto do usuário
                            $foto_padrao = '/imgUsuarios/user_padrao.png'; // Usando o caminho correto para a imagem padrão
                        
                            // Usa a foto do usuário ou a foto padrão se estiver vazia
                            $foto_user = !empty($_SESSION['user']['foto_usuario']) ? $_SESSION['user']['foto_usuario'] : $foto_padrao;

                            // Verifica se a imagem do usuário é a imagem padrão ou personalizada
                            if ($foto_user == $foto_padrao) {
                                // Caminho da imagem padrão com "../"
                                $caminho_imagem = '../../../' . $foto_user;
                            } else {
                                // Caminho da imagem personalizada (sem "../")
                                $caminho_imagem = $foto_user;
                            }

                            // Exibe o link para o perfil com a foto do usuário
                            echo '<a href="../../../backend/usuarios/comum/perfilusuario.php">';
                            echo '<img src="' . htmlspecialchars($caminho_imagem) . '?t=' . time() . '" alt="Perfil" style="width:50px; height:50px; border-radius:50%; margin-right:20px;">';
                            echo '</a>';

                            // Botão de logout
                            echo '<a class="btnLogout" href="../../../backend/usuarios/comum/logout.php" style="border: #4E6422 1px solid; background-color: #737b3f; width: 80px; height: 30px; border-radius: 50px; color: #FFF5EA; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">Sair</a>';
                        } else {
                            // Caso o usuário não esteja logado, exibe o botão de login
                            echo '<a class="btnLogin" href="../../../backend/login/login_form.php"><button class="btnLogin" style="border: #4E6422 1px solid; background-color: #737b3f; width: 130px; height: 30px; border-radius: 50px; color: #FFF5EA;">Login</button></a>';
                        }
                        ?>

                        <!-- Logo centralizada -->
                        <div style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
                            <img src="../../../img/logonav2.png" alt="Logo" id="imgnav"
                                style="width: 320px; max-height: 140px; object-fit: contain; display: block; margin: 0 auto;">
                        </div>
                    </div>
            </nav>
        </div>
        <div class="row">
            <div class="nav2">
                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../../../frontend/pgInicial.php" class="nav-link" id="linksnav">Pagina Inicial </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../adm/pgAdm.php" class="nav-link" id="linksnav">Administração</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../adm/gusuarios.php" class="nav-link" id="linksnav">Usuarios</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../adm/ganimais.php" class="nav-link" id="linksnav">Animais para Adoção</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../adm/gong.php" class="nav-link" id="linksnav">Parceiros</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../adm/glocais.php" class="nav-link" id="linksnav">Locais</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>


        <table style="padding: 8px; align-items: center; justify-content: center; display: flex; margin-top: 90px">
            <tr>
                <td
                    style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;">
                    Id</td>
                <td
                    style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;">
                    Nome</td>
                <td
                    style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;">
                    Email</td>
                <td
                    style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;">
                    Status</td>
                <td
                    style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;">
                    Tipo</td>
                <td
                    style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;">
                    Ações</td>
            </tr>

            <?php
            while ($usuarios = $registros->fetch_object()) {
                ?>

                <tr>
                    <td
                        style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;">
                        <?php echo $usuarios->id; ?></td>
                    <td
                        style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;">
                        <?php echo $usuarios->nome_usuario; ?></td>
                    <td
                        style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;">
                        <?php echo $usuarios->email_usuario; ?></td>
                    <td
                        style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;">
                        <?php echo $usuarios->status_usuario; ?></td>
                    <td
                        style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;">
                        <form method="post" action="atualizaTipo.php">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($usuarios->id); ?>">
                            <select name="tipo" onchange="this.form.submit()">
                                <option value="administrador" <?php if ($usuarios->tipo_usuario == 'administrador')
                                    echo 'selected'; ?>>Administrador</option>
                                <option value="tutor/adotante" <?php if ($usuarios->tipo_usuario == 'tutor/adotante')
                                    echo 'selected'; ?>>Tutor/Adotante</option>
                            </select>
                        </form>
                    </td>
                    <td
                        style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;">
                        <form action="processa_remocao.php" method="post"
                            onsubmit="return confirm('Deseja realmente excluir este usuário?');">
                            <input type="hidden" name="idUsuario" value="<?= htmlspecialchars($usuarios->id) ?>">
                            <button type="submit" name="removerUsuario" class="btn"
                                style="padding: 5px; background-color: #C06500; color: white; border-radius: 15px">Excluir</button>
                        </form>
                    </td>
                </tr>

            <?php } ?>
        </table>


    </div>

    <script src="../../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.min.js"></script>
</body>

</html>