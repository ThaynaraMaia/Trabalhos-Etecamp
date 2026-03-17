<?php

include_once '../../conn/classes/class_IRepositorioFuncionario.php';

session_start();

$pesquisar = $_POST['pesquisar'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pesquisar = $_POST['pesquisar'] ?? '';
    $resultado_pesquisa = $respositorioFuncionario->pesquisarFuncio($pesquisar);
    $linhas = $resultado_pesquisa->num_rows;
} else {
    $resultado_pesquisa = null;
    $linhas = 0;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../css/styles.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/scrollreveal"></script>
    <link rel="shortcut icon" href="../../img/logo2.png" />
    <title>Pesquisar Nome do Funcionario</title>
</head>

<body>
    <header>
        <nav id="navbar" class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container">
                <div class="logo">
                    <img src="../../img/logo.png" width="150" height="100">
                </div>
                <ul id="nav_list">
                    <li class="nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
                            <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z" />
                        </svg>
                        <a aria-current="page" href="../home_adm.php">Home</a> </a>
                    </li>
                    <li class="nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-text" viewBox="0 0 16 16">
                            <path d="M5 10.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5m0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5" />
                            <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                            <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
                        </svg>
                        <a class="nav-item active" href="../cardapio_adm.php">Cardápio</a>
                    </li>
                    <li class="nav-item active">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
                        </svg>
                        <a class="nav-item active" href="#">Usuários</a>
                        <ul class="submenu">
                            <li><a href="../gerenciar_cliente.php">Cliente</a></li>
                            <li><a href="../gerenciar_funcionario.php">Funcionário</a></li>
                            <li><a href="../gerenciar_adm.php">Administrador</a></li>
                        </ul>
                    </li>
                    <li class="nav-item ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-basket" viewBox="0 0 16 16">
                            <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9zM1 7v1h14V7zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5" />
                        </svg>
                        <a class="nav-item active" href="../gerenciar_pedidos.php">Pedidos</a>
                    </li>
                    <li class="nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                        </svg>
                        <a class="nav-item active" href="../perfil_adm.php">Perfil</a>
                    </li>
                </ul>
                <div>
                    <form action="../../usuario/logout.php">
                        <input value="Sair" name="Sair" type="submit" class="btn-default">
                    </form>
                </div>
    </header>

    <?php
    if (mysqli_num_rows($resultado_pesquisa) > 0) {
    ?>
        <div class="adicionar">
            <div class="row">
                <div class="col-md-4">
                    <h3>Funcionários:</h3>
                </div>
                <div class="col-md-4">
                    <form method="POST" action="pesquisar_nome.php">
                        <div class="search">
                            <label for="searchInput">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                </svg>
                            </label>
                            <input type="text" id="searchInput" name="pesquisar" placeholder="Pesquisar pelo nome">
                        </div>
                    </form>
                </div>
                <div class="col-md-4 d-flex align-items-center">
                    <a href="../adicionar_funcionario.php">
                        <button class="botao-saldo">+ Adicionar Funcionário</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="tabela">
            <table>
                <thead>
                    <tr>
                        <th>CPF:</th>
                        <th>NOME:</th>
                        <th>EMAIL:</th>
                        <th>TIPO:</th>
                        <th>STATUS:</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($rows_pesquisa = mysqli_fetch_array($resultado_pesquisa)) { ?>
                        <tr>
                            <td><?php echo $rows_pesquisa['cpf']; ?></td>
                            <td><?php echo $rows_pesquisa['nome_completo']; ?></td>
                            <td><?php echo $rows_pesquisa['email']; ?></td>
                            <td><?php
                                if ($rows_pesquisa['tipo'] == 1) {
                                ?>
                                    <a class="text-success" href="atualiza_tipo.php?id=<?php echo $rows_pesquisa['id']; ?>&tipo=0">Comum</a>
                                <?php
                                } else {
                                ?>
                                    <a class="text-primary" href="atualiza_tipo.php?id=<?php echo $rows_pesquisa['id']; ?>&tipo=2">Funcionário</a>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($rows_pesquisa['status'] == 1) {
                                ?>
                                    <a class="text-success" href="alterar_status.php?id=<?php echo $rows_pesquisa['id']; ?>&status=0">Ativado</a>
                                <?php
                                } else {
                                ?>
                                    <a class="text-danger" href="alterar_status.php?id=<?php echo $rows_pesquisa['id']; ?>&status=1">Desativado</a>
                                <?php
                                }
                                ?>
                            </td>
                            <td><button class="remove "> <a href="excluir_usuario.php?id_usuario=<?php echo $rows_pesquisa['id']; ?>"> <i class="bx bx-x "></i></a></button></td>
                        </tr>

                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php
    } else {
    ?>
        <div class="page-title">Funcionário não encontrado.</div>
    <?php
    }
    ?>
    
    <link href="../bootstrap/js/bootstrap.min.js">
    </link>
</body>

</html>