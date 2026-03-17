<!DOCTYPE html>
<html lang="pt-br">
<?php
session_start();

if (!$_SESSION['tipo'] && !$_SESSION['logado']) {
    header('Location: ../mostre_sua_arte-login.php');
}
if ($_SESSION['tipo'] != 1) {
    header('Location: ../paginasAluno/mostre_sua_arte-obras.php');
}

include_once '../../../backend/classes/class_repositorioUsuarios.php';

$registros = $repositorioUsuario->listarTodosUsuarios();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/gerenciar.css">
    <link rel="stylesheet" href="../../bootstrap/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Gerenciar Usuários</title>
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
                <a href="">USUÁRIOS</a>
                <a href="./gerenciarObras.php">OBRAS</a>
                <a href="./gerenciarCategorias.php">CATEGORIAS</a>
            </div>
        </div>

        <?php

        //Código que só será exibido se o usuário estiver logado (acesso à página de perfil).  

        if (isset($_SESSION['nome'])) {
            echo "<div class=\"col-md-1\">";
             echo "<div class=\"foto-perfil\">";
                echo "<a href=\"../perfil.php\"><img src=\"../../uploadsImg/{$_SESSION['foto']}\" alt=\"Foto de perfil do usuário\" style=\"border: solid white; border-radius: 50%;\"></a>"; 
             echo "</div>";
            echo "</div>";
        }
        ?>

    </header>

    <container>

        <h1>Gerenciar usuários</h1>


        <table class="table table-hover" style = "width: 80%; border: solid #501207 8px;">
            <thead style="background-color: #501207; color: #ffff">
                <tr>
                    <th scope="col" style="color: #ffff">ID do usuário</th>
                    <th scope="col" style="color: #ffff">Nome </th>
                    <th scope="col" style="color: #ffff">Email</th>
                    <th scope="col" style="color: #ffff">Tipo de usuário</th>
                    <th scope="col" style="color: #ffff">Status</th>
                    <th scope="col" style="color: #ffff">Excluir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($listagem = $registros->fetch_object()) {
                ?>
                    <tr>
                        <th scope="row"><?php echo $listagem->id; ?></th>
                        <td><?php echo $listagem->nome; ?></td>
                        <td><?php echo $listagem->email; ?></td>
                        <td>
                            <?php

                            if ($listagem->tipo == 1) {
                            ?>
                                <a href="../../../backend/usuarios/alterarTipo.php?id=<?php echo $listagem->id; ?>&tipo=0" style="background-color: #198754; padding: 8px; border-radius: 4px; color: #ffffff; text-decoration: none;">Administrador</a>
                            <?php
                            } else {
                            ?>
                                <a href="../../../backend/usuarios/alterarTipo.php?id=<?php echo $listagem->id; ?>&tipo=1" style="background-color: #ffc107; padding: 8px; border-radius: 4px; color: #ffffff; text-decoration: none;">Comum</a> 
                            <?php
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($listagem->status == 1) {
                            ?>
                                <a href="../../../backend/usuarios/alterarStatus.php?id=<?php echo $listagem->id; ?>&status=0" style="background-color: #198754; padding: 8px; border-radius: 4px; color: #ffffff; text-decoration: none;">Ativado</a>
                                <!-- <button type="submit" class="btn btn-success"><a href="../../../backend/usuarios/alterarStatus.php?id=<?php echo $listagem->id; ?>&status=0" style="color: #ffffff; text-decoration: none;">Ativado</a></button>    -->
                            <?php
                            } else {
                            ?>
                                <a href="../../../backend/usuarios/alterarStatus.php?id=<?php echo $listagem->id; ?>&status=1" style="background-color: #ffc107; padding: 8px; border-radius: 4px; color: #ffffff; text-decoration: none;">Desativado</a>
                            <?php
                            }
                            ?>
                        </td>
                        <td><a href="#" onclick="confirmarExcluirUsuario(<?php echo $listagem->id; ?>)" style="background-color: #dc3545; padding: 8px; border-radius: 4px; color: #ffffff; text-decoration: none;"><img src="../../img/icon-lixo.png" alt="icone do instagram" style="width: 22px;"></a></td>
                    </tr>
      
                    <script>
                        function confirmarExcluirUsuario(id) {
                        const confirmacao = confirm("Você tem certeza que deseja excluir esse usuário?");
                        if (confirmacao) {
                                window.location.href = `../../../backend/usuarios/admExcluirUsuarios.php?id=${id}`;
                            }
                        }
                    </script>

                <?php
                }
                ?>
            </tbody>
        </table>

    </container>
</body>

</html>