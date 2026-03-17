<?php
session_start(); // Se ainda não tiver iniciado

require_once '../../backend/classes/class_conexao.php';

if (!isset($_SESSION['user'])) {
    // Redireciona para login se não estiver logado
    header("Location: ../../backend/login/login_form.php");
    exit;
}

$id_usuario = $_SESSION['user']['id'];

// Busca todos os animais cadastrados pelo usuário
$sql = "SELECT * FROM tblanimaisestimacao WHERE id_usuario = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$animais = [];
while ($row = $result->fetch_assoc()) {
    $animais[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="stylesheet" href="../../css/ongs.css">

    <link rel="shortcut icon" href="../../img/favicon.ico" type="image/x-icon">

    <title>Pawceiros</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "AlteHassGrotesk";
        }

        @font-face {
            font-family: "AlteHassGrotesk";
            src: url(../../assets/AlteHaasGroteskRegular.ttf);
            font-weight: 300;
            font-style: normal;
        }

        @font-face {
            font-family: "AlteHassGrotesk";
            src: url(../../assets/AlteHaasGroteskBold.ttf);
            font-weight: 400;
            font-style: normal;
        }

        body {
            color: #333;
            background-color: #FFF;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 70px;
        }

        .header h1 {
            color: #4E6422;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
            font-size: 50px;
        }

        .header p {
            color: #7a4100;
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .guides-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
            margin-top: 60px;
        }

        .guide-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 1px 15px #c0ca7bff;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .guide-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #a8b16b, #95b654, #4e6422, #95b654, #a8b16b);
            background-size: 200% 100%;
            animation: shimmer 3s linear infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        .guide-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .guide-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
        }

        .guide-icon i {
            color: #4E6422;
        }

        .guide-card h3 {
            font-size: 1.4rem;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .guide-card p {
            color: #7f8c8d;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .guide-link {
            display: inline-block;
            background: #7a4100;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 25px;
            border: 2px solid white;
            font-weight: 630;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .guide-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .guide-link:hover::before {
            left: 100%;
        }

        .guide-link:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btnTipo {
            text-align: center;
            align-items: center;
            gap: 70px;
            justify-content: center;
            display: flex;
            margin: 20px;
        }

        .btndog {
            height: 260px;
            width: 260px;
            border: solid 1px #C06500;
            border-radius: 15px;
        }

        .btndog:hover {
            transform: scale(1.1);
            transition: 0.5s;
        }

        .btncat {
            filter: drop-shadow(2px 4px 8px #C0650060);
            height: 260px;
            width: 260px;
            border: solid 1px #C06500;
            border-radius: 15px;
        }

        .btncat:hover {
            transform: scale(1.1);
            transition: 0.5s;
        }

        .cardMeu {
            width: 250px;
            height: 250px;
        }

        .rodape {
            background-color: white;
            margin-top: 150px;
            margin-bottom: 35px;
        }

        .linha {
            border: 1px solid #4E6422;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .header h1 {
                font-size: 2rem;
            }

            .guides-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .guide-card {
                padding: 20px;
            }
        }

        .alertaa {
            background-color: #efebce;
            width: 70%;
            height: 150px;
            margin-top: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 50px;
            text-align: center;
            padding: 30px;
        }

        .cad {
            display: inline-block;
            padding: 5px;
            background-color: #A8B16B;
            color: rgb(34, 44, 15);
            text-align: center;
            width: 270px;
            height: 40px;
            text-decoration: none;
            cursor: pointer;
            margin-top: 20px;
            border: #4E6422 1px solid;
            border-radius: 20px
        }

        .teste {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <header>
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
        </header>

        <nav>
            <div class="row">
                <div class="nav2">
                    <ul class="nav justify-content-center">
                        <li class="nav-item">
                            <div class="col-sm">
                                <a href="../../frontend/pgInicial.php" class="nav-link" id="linksnav">Pagina Inicial
                                </a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="col-sm">
                                <a href="../../frontend/parceiros/ongs.php" class="nav-link" id="linksnav">Parceiros</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="col-sm">
                                <a href="../../frontend/deficiencias/guias.php" class="nav-link" id="linksnav">Guias</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="col-sm">
                                <a href="../../frontend/adocao/adocao2.php" class="nav-link" id="linksnav">Adoção</a>
                            </div>
                        </li>

                        <li class="nav-item">
                            <div class="col-sm">
                                <a href="../../frontend/locais.php" class="nav-link" id="linksnav">Locais</a>
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
        </nav>

        <main>
            <div class="container">
                <div class="header">
                    <h1>Guia de Deficiências</h1>
                    <p>Em caso de emergência, sempre procure atendimento veterinário</p>
                </div>

                <div class="links">
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-8">
                            <div class="btnTipo">
                                <div>
                                    <a href="../deficiencias/guiasc.php" class="cardMeu">
                                        <img src="../../img/cc.png" class="btndog" alt="...">
                                    </a>
                                </div>
                                <div>
                                    <a href="../deficiencias/guiasg.php" class="cardMeu">
                                        <img src="../../img/gc.png" class="btncat" alt="...">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-2"></div>
                    </div>
                </div>

                <div class="guides-grid">
                    <?php
                    $exibeGuia = false;

                    foreach ($animais as $animal) {
                        $tipo_animal = trim(strtolower($animal['especie_animale']));
                        $condicoes = strtolower($animal['condicao_saudee']);

                        if ($tipo_animal === 'gato') {
                            if (strpos($condicoes, 'deficiencia visual') !== false) : ?>
                                <div class="guide-card">
                                    <span class="guide-icon"><i class="fa-solid fa-eye-slash"></i> <i class="fa-solid fa-cat"></i></span>
                                    <h3>Deficiências Visuais</h3>
                                    <p>Cegueira, catarata, glaucoma e outras condições que afetam a visão do seu pet. Aprenda adaptações e cuidados especiais.</p>
                                    <a href="defvisg.php" class="guide-link">Acessar Guia</a>
                                </div>
                            <?php $exibeGuia = true;
                            endif;

                            if (strpos($condicoes, 'deficiencia auditiva') !== false) : ?>
                                <div class="guide-card">
                                    <span class="guide-icon"><i class="fa-solid fa-ear-deaf"></i> <i class="fa-solid fa-cat"></i></span>
                                    <h3>Deficiências Auditivas</h3>
                                    <p>Surdez parcial ou total, infecções do ouvido e como se comunicar com pets com problemas auditivos.</p>
                                    <a href="defaudg.php" class="guide-link">Acessar Guia</a>
                                </div>
                            <?php $exibeGuia = true;
                            endif;

                            if (strpos($condicoes, 'deficiencia motora') !== false) : ?>
                                <div class="guide-card">
                                    <span class="guide-icon"><i class="fa-solid fa-wheelchair"></i> <i class="fa-solid fa-cat"></i></span>
                                    <h3>Problemas de Mobilidade</h3>
                                    <p>Artrite, displasia, paralisia e outras condições que limitam o movimento. Equipamentos e exercícios adaptados.</p>
                                    <a href="defmotg.php" class="guide-link">Acessar Guia</a>
                                </div>
                            <?php $exibeGuia = true;
                            endif;

                            if (strpos($condicoes, 'idoso') !== false || $animal['idade_animale'] >= 7) : ?>
                                <div class="guide-card">
                                    <span class="guide-icon"><i class="fa-solid fa-person-cane"></i> <i class="fa-solid fa-cat"></i></span>
                                    <h3>Idosos</h3>
                                    <p>Processo biológico natural, caracterizado por alterações nos sistemas fisiológicos. É influenciado por fatores genéticos, ambientais e comportamentais.</p>
                                    <a href="idosog.php" class="guide-link">Acessar Guia</a>
                                </div>
                        <?php $exibeGuia = true;
                            endif;
                        }
                    }
                    if (!$exibeGuia) : ?>
                        <div class="teste">
                            <div class="alertaa" role="alert">
                                Nenhum guia disponível para o tipo de animal ou condições cadastradas no seu pet.
                                Se não tiver nenhum animal cadastrado ou se o tipo de animal não for compatível, cadastre agora!
                                <a href="../../backend/usuarios/comum/cadAnimalE.php" class="cad">Cadastrar um animal</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="footer">
                    <p><strong>Importante:</strong> Este guia é informativo e não substitui a consulta veterinária.
                        Sempre consulte um profissional para diagnósticos e tratamentos específicos.</p>
                    <p style="margin-top: 10px; font-style: italic;">Desenvolvido como TCC - Guia Online para Cuidados
                        de Pets Idosos e Deficientes</p>
                </div>

        </main>


    </div>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="../frontend/PgPrincipal.js"></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 3,
            spaceBetween: 30,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });
    </script>
</body>

</html>