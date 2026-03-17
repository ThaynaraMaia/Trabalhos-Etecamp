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

include_once '../../../backend/classes/class_repositorioObras.php';
include_once '../../../backend/classes/class_repositorioCategorias.php';

$registros = $repositorioObra->listarMaisRecente();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/gerenciar.css">
    <link rel="stylesheet" href="../../bootstrap/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Obras</title>
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
                <a href="./gerenciarUsuarios.php">USUÁRIOS</a>
                <a href="">OBRAS</a>
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

        <h1>Gerenciar obras</h1>


        <table class="table table-hover" style = "width: 80%; border: solid #501207 8px;">
            <thead style="background-color: #501207;">
                <tr>
                    <th scope="col" style="color: #ffff">ID da obra</th>
                    <th scope="col" style="color: #ffff">Trabalho artístico</th>
                    <th scope="col" style="color: #ffff">Título</th>
                    <th scope="col" style="color: #ffff">Descrição</th>
                    <th scope="col" style="color: #ffff">Categoria</th>
                    <th scope="col" style="color: #ffff">ID do autor</th>
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
                        <td><?php
                            if($listagem->categoria == 8){
                                echo "<img src=\"../../uploadsImg/{$listagem->trabalho_artistico}\" style=\"width: 150px;\" alt=\"Trabalho artístico\">";
                                echo "<p style=\"width: 150px\">{$listagem->texto}</p>";
                            }
                            else if($listagem->categoria == 12){
                                echo "<video src=\"../../uploadsVideos/{$listagem->trabalho_artistico}\" alt=\"Trabalho artístico\" style=\"width: 150px\"></video>";
                            }else{
                                echo "<img src=\"../../uploadsImg/{$listagem->trabalho_artistico}\" style=\"width: 150px\" alt=\"Trabalho artístico\">";
                            }
                        ?></td>
                        <td><?php echo $listagem->titulo; ?></td>
                        <td><?php echo $listagem->descricao; ?></td>
                        <?php
                        //Código p/ exibir o nome da categoria de acordo com o id da categoria (chave estrangeira).
                            $idObra = $listagem->id;
                            $registroObra = $repositorioObra->buscarObra($idObra);
                            $listagemObra = $registroObra->fetch_object();
                            $idCategoria = $listagemObra->categoria;
                            $listaCategoria = $repositorioCategoria->buscarCategoria($idCategoria);
                            $nomeCategoria = $listaCategoria->fetch_object();
                        ?>
                        <td><?php echo $nomeCategoria->nome; ?></td>
                        <td><?php echo $listagem->autor; ?></td>
                        <td>
                            <?php
                            if ($listagem->status == 1) {
                            ?>
                                <!-- <a class="text-success" href="../../../backend/obras/alterarStatus.php?id=<?php echo $listagem->id; ?>&status=0">Ativado</a> -->
                                <a href="../../../backend/obras/alterarStatus.php?id=<?php echo $listagem->id; ?>&status=0" style="background-color: #198754; padding: 8px; border-radius: 4px; color: #ffffff; text-decoration: none;">Ativada</a>
                            <?php
                            } else {
                            ?>
                                <!-- <a class="text-danger" href="../../../backend/obras/alterarStatus.php?id=<?php echo $listagem->id; ?>&status=1">Desativado</a> -->
                                <a href="../../../backend/obras/alterarStatus.php?id=<?php echo $listagem->id; ?>&status=1" style="background-color: #ffc107; padding: 8px; border-radius: 4px; color: #ffffff; text-decoration: none;">Desativada</a>
                            <?php
                            }
                            ?>
                        </td>
                        <td><a href="#" onclick="confirmarExcluirObra(<?php echo $listagem->id; ?>)" style="background-color: #dc3545; padding: 8px; border-radius: 4px; color: #ffffff; text-decoration: none;"><img src="../../img/icon-lixo.png" alt="icone do instagram" style="width: 22px;"></a></td>
                        
                        <script>
                            function confirmarExcluirObra(id) {
                            const confirmacao = confirm("Você tem certeza que deseja excluir essa obra?");
                            if (confirmacao) {
                                    window.location.href = `../../../backend/obras/admExcluirObras.php?id=${id}`;
                                }
                            }
                        </script>
                        
                    </tr>

                <?php
                }
                ?>
            </tbody>
        </table>

    </container>
</body>

</html>