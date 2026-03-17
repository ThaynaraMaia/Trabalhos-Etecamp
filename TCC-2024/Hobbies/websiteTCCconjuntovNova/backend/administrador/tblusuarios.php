<?php
 include_once "../classes/class_Conexao.php";
 include_once "../classes/class_iRepositorioUsuario.php";

 session_start();
 
 $registroUsuario = $respositorioUsuario->listarTodosUsuarios();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../frontend/css/configuracoes.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poetsen+One&display=swap" rel="stylesheet">
    <title>Mercury</title>
</head>
<body>
<!-- Sidebar começa aqui!-->
<nav id="sidebar" role="navigation">
    <div id="sidebar_content">
        <div id="user">

            <p id="user_infos">
            <span class="item-description perfil">
                <?php
                    echo ($_SESSION['nome']);
                ?>
                </span>
                <span class="item-description">
                <?php
                    echo ($_SESSION['email']);
                ?>
                </span>
            </p>
        </div>

        <ul id="side_items">
            <li class="side-item">
                <a href="../../backend/administrador/home_admin.php">
                  <i class="fa-solid fa-house"></i>
                    <span class="item-description">
                        Home
                    </span>
                </a>
            </li>

            <li class="side-item active">
                <a href="../../backend/administrador/tblusuarios.php">
                    <i class="fa-solid fa-user"></i>
                    <span class="item-description">
                        Usuários
                    </span>
                </a>
            </li>

            <li class="side-item">
                <a href="../../frontend/html/home.php">
                  <i class="fa-solid fa-paintbrush"></i>
                    <span class="item-description">
                        Ir ao site
                    </span>
                </a>
            </li>
        </ul>

        <button aria-label="Abrir menu" id="open_btn">
            <i id="open_btn_icon" class="fa-solid fa-chevron-right"></i>
        </button>
    </div>

    <div id="logout">
        <button aria-label="Cadastrar" id="logout_btn">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span class="item-description">
            <a href="../../backend/login/logout.php">Logout </a>
            </span>
        </button>
    </div>
</nav>
<!-- Sidebar termina aqui -->

<div class="container">
<!-- Cabeçalho com logo começa aqui -->
    <div class="header">
        <div class="row">
            <div class="col-5"> </div>
            <div class="col-4">
                <img src="../../frontend/img/logo.png" alt="" class="logo">
            </div>
            <div class="col-3"> </div>
        </div>
    </div>
<!-- Cabeçalho com logo termina aqui -->

<!-- Tabela de usuários começa aqui -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Alterar status</th>
                <th>Alterar tipo</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($linhas = $registroUsuario->fetch_object()) { ?>
                <tr>
                    <td><?php echo $linhas->nome; ?></td>
                    <td><?php echo $linhas->email; ?></td>
                    <td><?php echo $linhas->tipo; ?></td>
                    <td>
                    <?php 
                        if ($linhas->status == 1) {
                            ?>
                        <a class="text-success" href="alterar_status.php?id=<?php echo $linhas->id; ?>&status=0">Ativado</a>
                        <?php
                            } else {
                        ?>
                            <a class="text-danger" href="alterar_status.php?id=<?php echo $linhas->id; ?>&status=1">Desativado</a>
                        <?php
                            }
                        ?>
                    </td>
                    <td>
                        <?php if ($linhas->tipo == 1) { ?>
                            <a href="alterar_tipo.php?id=<?php echo $linhas->id; ?>&tipo=0">
                                <img src="../../frontend/img/alterar.png" class="icon-alterar" alt="Alterar">
                            </a>
                        <?php } else { ?>
                            <a href="alterar_tipo.php?id=<?php echo $linhas->id; ?>&tipo=1">
                                <img src="../../frontend/img/alterar.png" class="icon-alterar" alt="Alterar">
                            </a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Tabela de usuários termina aqui -->

<script src="../../backend/scripts/script.js"></script>
</body>
</html>