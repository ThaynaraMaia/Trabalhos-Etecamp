<?php
session_start();

// if (!isset($_SESSION['user']) || ($_SESSION['user']['tipo_usuario'] ?? '') !== 'administrador') {
//     echo '<p style="color: #7a4100; font-weight: bold">Você não tem autorização para acessar esta página.</p>';
//     exit();
// }

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<p style="color: red;">ID da ONG inválido.</p>';
    exit();
}

$id = intval($_GET['id']);

$conn = new mysqli("localhost", "root", "", "pawsitive");
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// Dados da ONG
$sql = "SELECT * FROM tblong WHERE id_ong = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo '<p style="color: red;">ONG não encontrada.</p>';
    exit();
}

$ong = $res->fetch_assoc();
$stmt->close();

// Telefones
$sqlTel = "SELECT telefone, tipo_telefone FROM tblong_telefones WHERE id_ong = ?";
$stmtTel = $conn->prepare($sqlTel);
$stmtTel->bind_param("i", $id);
$stmtTel->execute();
$resTel = $stmtTel->get_result();

$telefones = [];
while ($row = $resTel->fetch_assoc()) {
    $telefones[] = $row;
}
$stmtTel->close();

// Endereços
$sqlEnd = "SELECT rua, numero, complemento, cidade, estado, cep FROM tblong_enderecos WHERE id_ong = ?";
$stmtEnd = $conn->prepare($sqlEnd);
$stmtEnd->bind_param("i", $id);
$stmtEnd->execute();
$resEnd = $stmtEnd->get_result();

$enderecos = [];
while ($row = $resEnd->fetch_assoc()) {
    $enderecos[] = $row;
}
$stmtEnd->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($ong['nome_ong']) ?></title>

    <!-- Estilos e bibliotecas -->
    <link rel="stylesheet" href="../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../../css/ong1.css">
    <link rel="shortcut icon" href="../../img/favicon.ico" type="image/x-icon">

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="../pgONG.js" defer></script>

    <style>
        .imgPTS {
            width: 450px;
            margin-top: 40px;
            margin-left: 90px;
            border-radius: 10px;
        }

        .mirasol {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .conteudo {
            width: 550px;
            margin-top: 90px;
            margin-left: 85px;
        }

        .titulo03 {
            font-size: 45px;
            margin-bottom: 15px;
            color: #4E6422;
        }

        .ong1 {
            margin-top: 40px;
        }

        .conteudo {
            width: 530px;
            margin-top: 80px;
            margin-left: 105px;
            margin-right: 70px;
        }

        .cont {
            width: 500px;
            background-color: #4E6422;
            display: grid;
            place-items: center;
            width: 100%;
            height: 500px;
            margin-top: 130px;
        }

        .fundo {
            background-color: #fffef5;
            width: 1330px;
            height: 420px;
        }

        .map {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .rodape {
            background-color: white;
            margin-top: 150px;
            margin-bottom: 35px;
        }

        .linha {
            border: 1px solid #4E6422;
        }

        .tetulo {
            font-size: 40px;
            margin-bottom: 30px;
            color: #4E6422;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <!-- NAVBAR -->
        <div class="row" id="rownav" style="background-color: #A8B16B;">
            <nav class="navbar position-relative" style="height: 135px; position: relative;">
                <div class="container position-relative" style="position: relative;">

                    <!-- Botão à direita -->
                    <div class="ms-auto">
                        <a href="/imgUsuarios/user_padrao.png"></a>
                        <?php

                        if (isset($_SESSION['user'])) {
                            // Caminho padrão da foto do usuário
                            $foto_padrao = '/imgUsuarios/user_padrao.png'; // Usando o caminho correto para a imagem padrão

                            // Usa a foto do usuário ou a foto padrão se estiver vazia
                            $foto_user = !empty($_SESSION['user']['foto_usuario']) ? $_SESSION['user']['foto_usuario'] : $foto_padrao;

                            // Verifica se a imagem do usuário é a imagem padrão ou personalizada
                            if ($foto_user == $foto_padrao) {
                                // Caminho da imagem padrão com "../"
                                $caminho_imagem = '../../' . $foto_user;
                            } else {
                                // Caminho da imagem personalizada (sem "../")
                                $caminho_imagem = $foto_user;
                            }

                            // Exibe o link para o perfil com a foto do usuário
                            echo '<a href="../../backend/usuarios/comum/perfilusuario.php">';
                            echo '<img src="' . htmlspecialchars($caminho_imagem) . '?t=' . time() . '" alt="Perfil" style="width:50px; height:50px; border-radius:50%; margin-right:20px;">';
                            echo '</a>';

                            // Botão de logout
                            echo '<a class="btnLogout" href="../../backend/usuarios/comum/logout.php" style="border: #4E6422 1px solid; background-color: #737b3f; width: 80px; height: 30px; border-radius: 50px; color: #FFF5EA; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">Sair</a>';
                        } else {
                            // Caso o usuário não esteja logado, exibe o botão de login
                            echo '<a class="btnLogin" href="../../backend/login/login_form.php"><button class="btnLogin" style="border: #4E6422 1px solid; background-color: #737b3f; width: 130px; height: 30px; border-radius: 50px; color: #FFF5EA;">Login</button></a>';
                        }
                        ?>

                        <!-- Logo centralizada -->
                        <div style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
                            <img src="../../img/logonav2.png" alt="Logo" id="imgnav"
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
                            <a href="../pgInicial.php" class="nav-link" id="linksnav">Pagina Inicial </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../parceiros/parceiros.php" class="nav-link" id="linksnav">Parceiros</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../deficiencias/guiasc.php" class="nav-link" id="linksnav">Guias</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../adocao/adocao2.php" class="nav-link" id="linksnav">Adoção</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="col-sm">
                            <a href="../locais.php" class="nav-link" id="linksnav">Locais</a>
                        </div>
                    </li>

                    <?php
                    if (isset($_SESSION['user']) && ($_SESSION['user']['tipo_usuario'] ?? '') === 'administrador') {
                        echo '<li class="nav-item">';
                        echo '<div class="col-sm">';
                        echo '<a href="../../backend/usuarios/adm/pgAdm.php" class="nav-link" id="linksnav">Administração</a>';
                        echo '</div>';
                        echo '</li>';
                    }
                    ?>

                </ul>
            </div>
        </div>

        <!-- CONTEÚDO PRINCIPAL -->
        <main>
            <h1 class="efeitoT" style="text-align: center; font-size: 55px; margin-top: 40px; color: #4E6422;">
                <?= htmlspecialchars($ong['nome_ong']) ?>
            </h1>
            <h2 class="efeitoT" style="text-align: center; font-size: 35px; margin-top: -10px;">
                Fundada em <?= htmlspecialchars($ong['fundacao_ong']) ?>
            </h2>

            <div class="ong1">
                <!-- <h3 class="titulo03 efeitoH">História da ONG</h3> -->
                <div class="mirasol">
                    <div class="row">
                        <div class="col-5 d-flex align-items-center">
                            <?php if (!empty($ong['foto_ong'])): ?>
                                <img src="../../<?= htmlspecialchars($ong['foto_ong']) ?>" alt="Foto da ONG" class="imgPTS efeitoT">
                            <?php else: ?>
                                <img src="../../img/placeholder.png" alt="Sem foto" class="imgPTS efeitoT">
                            <?php endif; ?>
                        </div>
                        <div class="col-7 d-flex align-items-center">
                            <div class="conteudo">
                                <h2 class="tetulo">Descrição da ONG</h2>
                                <p class="prgf efeitoH" style="font-size: 25px;">
                                    <?= nl2br(htmlspecialchars($ong['historia_ong'])) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cont">
                    <div class="fundo">
                        <div class="row" style="margin-top: 50px;">
                            <div class="col map efeitoH">
                                <?php if (!empty($enderecos)): ?>
                                    <iframe
                                        src="https://www.google.com/maps?q=<?= urlencode($enderecos[0]['rua'] . ', ' . $enderecos[0]['numero'] . ', ' . $enderecos[0]['cidade'] . ', ' .
                                                                                $enderecos[0]['estado']) ?>&output=embed"
                                        width="380" height="300" style="border:0;" allowfullscreen="" loading="lazy">
                                    </iframe>
                                <?php else: ?>
                                    <p>Endereço não disponível.</p>
                                <?php endif; ?>
                            </div>
                            <div class="col" style="margin-right: 110px;">
                                <h2 class="efeitoT" style="font-size: 40px; margin-bottom: 30px; color: #4E6422;">Localização</h2>
                                <?php foreach ($enderecos as $end): ?>
                                    <p class="efeitoH" style="font-size: 20px;">
                                        <?= htmlspecialchars($end['rua']) ?>, <?= htmlspecialchars($end['numero']) ?>
                                        <?= $end['complemento'] ? ', ' . htmlspecialchars($end['complemento']) : '' ?> -
                                        <?= htmlspecialchars($end['cidade']) ?>/<?= htmlspecialchars($end['estado']) ?> <br>
                                        CEP: <?= htmlspecialchars($end['cep']) ?>
                                    </p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="botaocnt efeitoH" style="margin-top: 80px; margin-bottom: 80px; text-align: center">
                    <button class="botao" data-bs-toggle="modal" data-bs-target="#exampleModal">Entrar em contato</button>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #4E6422; color: #fff;">
                                <h5 class="modal-title" id="exampleModalLabel">Olá Adotante!</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Fechar"></button>
                            </div>
                            <div class="modal-body">
                                <p>Entre em contato com a ONG para mais informações!</p>
                                <ul>
                                    <?php foreach ($telefones as $tel): ?>
                                        <li><strong><?= htmlspecialchars($tel['tipo_telefone']) ?>:</strong> <?= htmlspecialchars($tel['telefone']) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn" data-bs-dismiss="modal"
                                    style="background-color: #4E6422; color: #fff;">Fechar</button>
                                <button type="button" class="btn Meu">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="rodape">
            <div class="row ">
                <hr class="linha">
                <div class="col logo">
                    <img src="../../img/logofooter2.png" alt="" style="width: 150px; height: 150px; margin: 20px;
          margin-left: 80px">
                    <!-- <p style="font-size: 15px; color: #4E6422;">Todos os direitos <br> reservados</p> -->
                </div>
                <div class="col colabore">
                    <h4 style="margin-top: 35px; color: #4E6422;">Colabore</h4>
                    <p style="font-size: 17px; color: #4E6422; margin-top: 15px;">Doe qualquer valor!</p>
                    <p style="font-size: 17px; color: #4E6422;">Cobertores, ração e itens são <br> sempre bem-vindos para as ONG's!
                    </p>
                </div>
                <div class="col redes">
                    <h4 style="margin-top: 35px; color: #4E6422;">Siga-nos</h4>
                    <a href="/"><img src="../../img/instagram.png" alt="" style="width: 30px; height: 30px; margin-right: 15px;"></a>
                    <a href="/"><img src="../../img/facebook.png" alt="" style="width: 30px; height: 30px; margin-right: 15px;"></a>
                    <a href="/"><img src="../../img/tktk.png" alt="" style="width: 30px; height: 30px;"></a>
                </div>
                <div class="col parceiros">
                    <h4 style="margin-top: 35px; color: #4E6422;">Pawceiros</h4>
                    <p style="font-size: 17px; color: #4E6422; margin-top: 15px;">ONG's</p>
                    <p style="font-size: 17px; color: #4E6422;">Veterinários</p>
                </div>
            </div>
        </footer>

        <script src="../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.min.js"></script>
        <script src="https://kit.fontawesome.com/d486d2cd81.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>

</html>