<?php
include_once '../../classes/class_IRepositorioOng.php';

$repositorio = new RepositorioOngMYSQL();
$ongs = $repositorio->listarTodasOngs();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../adm/gusuarios.css">
    <link rel="shortcut icon" href="../../../img/favicon.ico" type="image/x-icon">
    <title>ONG's</title>
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

        <a href="cadOng.php" style="display: inline-block; padding: 5px; background-color: #A8B16B; color:rgb(34, 44, 15); 
        text-align: center; width: 270px; height: 40px; text-decoration: none; cursor: pointer; margin-top: 60px; border:#4E6422 1px solid; 
        border-radius: 20px">Cadastrar Parceiro</a>

        <div class="table-responsive" style="margin-top: 90px; padding: 8px;">
            <table class="table" style="align-items: center; justify-content: center;">
                <thead>
                    <tr>
                        <th style="padding: 15px 20px; border: #4E6422 solid 1.5px;">Id</th>
                        <th style="padding: 15px 20px; border: #4E6422 solid 1.5px;">Nome</th>
                        <th style="padding: 15px 20px; border: #4E6422 solid 1.5px;">Fundação</th>
                        <th style="padding: 15px 20px; border: #4E6422 solid 1.5px;">História</th>
                        <th style="padding: 15px 20px; border: #4E6422 solid 1.5px;">Telefone</th>
                        <th style="padding: 15px 20px; border: #4E6422 solid 1.5px;">Endereço</th>
                        <th style="padding: 15px 20px; border: #4E6422 solid 1.5px;">Editar</th>
                        <th style="padding: 15px 20px; border: #4E6422 solid 1.5px;">Excluir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ongs as $ong): ?>
                        <tr>
                            <td style="padding:15px 20px; border: #4E6422 solid 1.5px;"><?= htmlspecialchars($ong->getIdOng()) ?></td>
                            <td style="padding:15px 20px; border: #4E6422 solid 1.5px;"><?= htmlspecialchars($ong->getNomeOng()) ?></td>
                            <td style="padding:15px 20px; border: #4E6422 solid 1.5px;"><?= htmlspecialchars($ong->getFundacaoOng()) ?></td>
                            <td style="padding:15px 20px; border: #4E6422 solid 1.5px;"><?= nl2br(htmlspecialchars($ong->getHistoriaOng())) ?></td>
                            <td style="padding:15px 20px; border: #4E6422 solid 1.5px;">
                                <ul style="list-style-type: none; padding-left: 0;">
                                    <?php foreach ($ong->getTelefones() as $tel): ?>
                                        <li>
                                            <?php
                                            if (is_array($tel)) {
                                                echo htmlspecialchars($tel['telefone'] ?? '') . " (" . htmlspecialchars($tel['tipo'] ?? '') . ")";
                                            } else {
                                                echo htmlspecialchars($tel);
                                            }
                                            ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                            <td style="padding:15px 20px; border: #4E6422 solid 1.5px;">
                                <ul style="list-style-type: none; padding-left: 0;">
                                    <?php foreach ($ong->getEnderecos() as $end): ?>
                                        <li>
                                            <?= htmlspecialchars($end['rua'] ?? 'N/A') ?>, <?= htmlspecialchars($end['numero'] ?? 'N/A') ?>
                                            <?= !empty($end['complemento']) ? '- ' . htmlspecialchars($end['complemento']) : '' ?>,
                                            <?= htmlspecialchars($end['cidade'] ?? 'N/A') ?> - <?= htmlspecialchars($end['estado'] ?? 'N/A') ?>, CEP: <?= htmlspecialchars($end['cep'] ?? 'N/A') ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>

                            <td style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;">
                                <!-- Botão Editar -->
                                <a href="editarOng.php?id=<?php echo htmlspecialchars($ong->getIdOng()); ?>"
                                    class="btn"
                                    style="padding: 5px; background-color: #4E6422; color: white; border-radius: 15px; margin-right:5px;">
                                    Editar
                                </a>
                            </td>

                            <td style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;">

                                <form action="processa_remocao_parceiros.php" method="post" onsubmit="return confirm('Deseja realmente excluir este parceiro?');" style="display:inline;">
                                    <input type="hidden" name="idOng" value="<?php echo htmlspecialchars($ong->getIdOng()); ?>">
                                    <button type="submit" class="btn" style="padding: 5px; background-color: #C06500; color: white; border-radius: 15px">Excluir</button>
                                </form>
                                <!-- Pode adicionar outras ações aqui, como editar -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>