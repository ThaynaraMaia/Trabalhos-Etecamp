<?php
include_once '../../classes/class_IRepositorioAnimaisAdocao.php';

$repositorio = new RepositorioAnimaisAdocaoMYSQL();
$registros = $repositorio->listarTodosAnimaisAd();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../adm/gusuarios.css">
    <link rel="shortcut icon" href="../../../img/favicon.ico" type="image/x-icon">
    <title>Animais para Adoção</title>

    <style>
        .tabela {
            width: 200px;
        }

        .linha {
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 15px;
            padding-bottom: 15px;
            border: #4E6422 solid 1.5px;
        }

        .tabela {
            width: 1200px;
            display: grid;
            place-items: center;
        }
    </style>
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

        <!-- Botão/link para abrir a página de cadastro -->
        <a href="cadAnimaisAdocao.php" style="display: inline-block; padding: 5px; background-color: #A8B16B; color:rgb(34, 44, 15); 
        text-align: center; width: 270px; height: 40px; text-decoration: none; cursor: pointer; margin-top: 60px; border:#4E6422 1px solid; 
        border-radius: 20px">Cadastrar Animal</a>

        <div style="padding: 8px; align-items: center; justify-content: center; display: flex; margin-top: 50px; margin-bottom: 20px;">
            <table>

                <tr>
                    <th class="linha">Foto</th>
                    <th class="linha">Id</th>
                    <th class="linha">Nome</th>
                    <th class="linha">Características</th>
                    <th class="linha">Cidade</th>
                    <th class="linha">Descrição</th>
                    <th class="linha">Gênero</th>
                    <th class="linha">Espécie</th>
                    <th class="linha">Idade</th>
                    <th class="linha">Condição Saúde</th>
                    <th class="linha">Status</th>
                    <th class="linha">Editar</th>
                    <th class="linha">Excluir</th>
                </tr>

                <tbody>
                    <?php
                    while ($animal = $registros->fetch_object()) {
                    ?>
                        <tr>
                            <td style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;"><img src="<?php echo htmlspecialchars($animal->foto_animal); ?>" alt="Foto Animal" style="max-width: 80px; max-height: 80px;"></td>
                            <td style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;"><?php echo htmlspecialchars($animal->id_animal); ?></td>
                            <td style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;"><?php echo htmlspecialchars($animal->nome_animal); ?></td>
                            <td style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;"><?php echo htmlspecialchars($animal->caracteristicas_animal); ?></td>
                            <td style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;"><?php echo htmlspecialchars($animal->cidade_animal); ?></td>
                            <td style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;"><?php echo htmlspecialchars($animal->descricao_animal); ?></td>
                            <td style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;"><?php echo htmlspecialchars($animal->genero_animal); ?></td>
                            <td style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;"><?php echo htmlspecialchars($animal->especie_animal); ?></td>
                            <td style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;"><?php echo htmlspecialchars($animal->idade_animal); ?></td>
                            <td style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;"><?php echo htmlspecialchars($animal->condicao_saude); ?></td>
                            <td style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;">
                                <form method="post" action="atualizaStatus.php">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($animal->id_animal); ?>">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="para/adocao" <?php if ($animal->status_animal == 'para/adocao') echo 'selected'; ?>>Para Adoção</option>
                                        <option value="adotado" <?php if ($animal->status_animal == 'adotado') echo 'selected'; ?>>Adotado</option>
                                        <option value="tratamento" <?php if ($animal->status_animal == 'tratamento') echo 'selected'; ?>>Tratamento</option>
                                    </select>
                                </form>
                            </td>

                            <td style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;">
                                <a href="editarAnimal.php?id=<?php echo htmlspecialchars($animal->id_animal); ?>"
                                    class="btn"
                                    style="padding: 5px; background-color: #4E6422; color: white; border-radius: 15px; text-decoration: none;">
                                    Editar
                                </a>
                            </td>

                            <td style="padding-left: 20px; padding-right: 20px; padding-top: 15px; padding-bottom: 15px; border: #4E6422 solid 1.5px;">
                                <form action="processa_remocao_animal.php" method="post" onsubmit="return confirm('Deseja realmente excluir este animal?');" style="display:inline;">
                                    <input type="hidden" name="idAnimal" value="<?php echo htmlspecialchars($animal->id_animal); ?>">
                                    <button type="submit" class="btn" style="padding: 5px; background-color: #C06500; color: white; border-radius: 15px">Excluir</button>
                                </form>
                                <!-- Pode adicionar outras ações aqui, como editar -->
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>


</body>

</html>