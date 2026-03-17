<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../adm/gusuarios.css">
    <link rel="shortcut icon" href="../../../img/favicon.ico" type="image/x-icon">
    <title>Página Administrativa</title>

    <style>
        .tras {
            width: 180px;
            height: 180px;
            margin-bottom: 40px;
            margin-top: 40px;
            border-radius: 20px;
            border: 1px solid #A8B16B;
        }

        .imag {
            width: 178px;
            height: 140px;
            border-radius: 20px;
        }

        .corpo {
            margin-top: 15px;
            font-size: 18px;
            color: #4e6422;
        }

        .texto {
            margin-top: -10px;
            text-align: center;
        }

        .func {
            margin-top: 70px;
            margin-bottom: 100px;
        }

        .meu {
            margin-top: 30px;
        }

        .minha {
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.4s;
        }

        .cardMeu {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .minha:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border-radius: 30px;
        }

        .titulo {
            margin-top: 70px;
            text-align: center;
            font-size: 40px;
            color: #c06500;
            margin-bottom: 30px;
        }

        .cardImg {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            /* justify-content: center;
            align-items: center; */
            /* border: 2px #4e6422 solid;
            border-radius: 30px; */
        }

        .imgCard {
            text-align: center;
            width: 150px;
            height: 170px;
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

        <section class="sla"
            style="display: flex; justify-content: center; align-items: center; margin-top: 80px; margin-bottom: 25px;">
            <div class="fundo"
                style="background-color: #c06500; width: 80%; height: 95vh; border-radius: 30px; display: flex; justify-content: center; align-items: center;">
                <div class="fundo2"
                    style="background-color: white; width: 90%; height: 80vh; border-radius: 30px; display: flex; flex-direction: row;">
                    <div class="esquerda"
                        style="width: 50%; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; z-index: 1;">
                        <h1 style="color: #4e6422;">Bem-vindo </h1>
                        <h2 style="color: #4e6422;">ao Painel Administrativo!</h2>
                        <p style="text-align: center;  margin-top: 15px; margin-right: 50px; margin-left: 50px;">Você está logado como um administrador,
                            portanto, existem alguns recursos especificos que você pode acessar.</p>
                        <p>Verifique abaixo quais funções você pode exercer!</p>
                    </div>
                    <div class="direita"
                        style="width: 50%; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; z-index: 1;">
                        <img src="../../../img/pgAdm.png" alt="" style="width: 85%; height: 100%; border-radius: 30px;">
                    </div>
                </div>
            </div>
        </section>

        <div class="func">
            <h2 class="titulo">Funcionalidades</h2>
            <div class="cardMeu">
                <div class="row meu">
                    <div class="col minha">
                        <a href="../adm/gusuarios.php" style="text-decoration: none; color: inherit;">
                            <div class="cardImg" style="width: 180px; height: 200px;">
                                <img src="../../../img/userI.png" class="imgCard imag" alt="...">
                                <div class="card-body corpo">
                                    <p class="card-text texto">Usuários </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col minha">
                        <a href="../adm/ganimais.php" style="text-decoration: none; color: inherit;">
                            <div class="cardImg " style="width: 180px; height: 200px">
                                <img src="../../../img/animaisI.png" class="imgCard imag" alt="...">
                                <div class="card-body corpo">
                                    <p class="card-text texto">Animais</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col minha">
                        <a href="../adm/gong.php" style="text-decoration: none; color: inherit;">
                            <div class="cardImg " style="width: 180px; height: 200px">
                                <img src="../../../img/parceirosI.png" class="imgCard imag" alt="...">
                                <div class="card-body corpo">
                                    <p class="card-text texto">Parceiros</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col minha">
                        <a href="../adm/glocais.php" style="text-decoration: none; color: inherit;">
                            <div class="cardImg " style="width: 180px; height: 200px">
                                <img src="../../../img/localI.png" class="imgCard imag" alt="...">
                                <div class="card-body corpo">
                                    <p class="card-text texto">Locais</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

</body>

</html>