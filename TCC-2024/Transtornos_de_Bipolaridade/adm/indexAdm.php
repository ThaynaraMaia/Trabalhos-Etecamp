<!DOCTYPE html>
<html lang="pt-br">
<?php

session_start();
// $usuario = $_SESSION['nome'];

include_once '../conn/classes/class_IRepositorioUsuario.php';

$registro = $respositorioUsuario->listarTodosUsuarios();

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Equilibrio Administrador</title>
</head>

<body>

    <header>
        <nav id="navbar">
            <img src="../img/logoamarela (1).png" alt="logo" style="width: 85px"> 
            <ul id="nav-list">
                <li class="nav-item active">
                    <a href="#">Área do Administrador</a>
                </li>
                <li class="nav-item">
                    <a href="../adm/bipolaridadeAdm.php">Bipolaridade ADM</a>
                </li>
                <li class="nav-item">
                    <a href="../adm/autoajudaAdm.php">Autoajuda ADM</a>
                </li>
            </ul>

            <div class="icones-usuario">
                <button class="phone-button">
                    <a href="../usuario/logout.php">
                        <img src="../img/sair.png" style="width: 30px;">
                    </a>
                </button>
            </div> 
        </nav>
    </header>
            
    <h1 class="mt-3 text-center">
        Administrador
    </h1>

    <div class="row overflow-y-auto" style="height: 350px;">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Email</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>

                <tbody>

                <?php while ($listagem = $registro->fetch_object()) { ?>

                    <tr>
                        <td><?php echo $listagem->nome; ?></td>
                        <td><?php echo $listagem->email; ?></td>
                        <td><?php if ($listagem->tipo == 1) { ?>

                        <a class="text-success" href="atualizaTipo.php?id=<?php echo $listagem->id; ?>&tipo=0">Administrador</a>
                            <?php } else { ?>
                                <a class="text-primary" href="atualizaTipo.php?id=<?php echo $listagem->id; ?>&tipo=1">Comum</a>
                            <?php } ?>
                            </td>
                            <td>

                            <?php if ($listagem->status == 1) { ?>
                                <a class="text-success" href="atualizaStatus.php?id=<?php echo $listagem->id; ?>&status=0">Ativado</a>
                                <?php
                                    } else {
                                    ?>
                                        <a class="text-danger" href="atualizaStatus.php?id=<?php echo $listagem->id; ?>&status=1">Desativado</a>
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="excluirUsuario.php?id=<?php echo $listagem->id ?>" 
                                    onclick="if(!confirm('Tem certeza de que deseja excluir este usuário?')) return false;">x</a>
                                </td>

                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <footer>
            <img src="../img/wave (1).svg" alt="">

            <div id="footer-item">
                <img src="../img/logoazul (1).png" alt="logo" style="width: 70px">
                <span id="copyright">
                    &copy 2024 Equilibrio
                </span>

                <a href="tel:+555" id="phone-button">
                    <button class="phone-button">
                        <i class="fi fi-sr-phone-call"></i>
                        (180)
                    </button>
                        Central de Atendimento a Mulher
                </a>

                <a href="tel:+555" id="phone-button">
                    <button class="phone-button">
                        <i class="fi fi-sr-phone-call"></i>
                        (192)
                    </button>
                        SAMU
                </a>

                <a href="tel:+555" id="phone-button">
                    <button class="phone-button">
                        <i class="fi fi-sr-phone-call"></i>
                        (193)
                    </button>
                        Corpo de Bombeiros
                </a>
            </div>
        </footer>
    </div>
    
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>