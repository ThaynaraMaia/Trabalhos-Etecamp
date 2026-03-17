<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/defg.css">
    <link rel="shortcut icon" href="../../img/favicon.ico" type="image/x-icon">
    <title>Deficiência Motora - Gato</title>
    <style>
        #areaSugestoes {
            /* background-color: #efebce; */
            /* padding: 50px; */
            color: #412200ff;
            /* border-radius: 50px; */
        }

        #areaSugestoes p {
            margin-bottom: 20px;
            /* Espaço entre os textos */
            line-height: 1.4;
            /* Melhor legibilidade */
            background-color: #efebce;
            border-radius: 50px;
            padding: 50px;
        }

        .flip-card-img {
            border: solid 2px #C06500;
        }

        .funcionamento h3 {
            margin-top: 20px;
            /* ajusta para descer o h3 */
        }

        .rodape {
            background-color: white;
            margin-top: 150px;
            margin-bottom: 35px;
        }

        .linha {
            border: 1px solid #4E6422;
        }

        .teste {
            width: 1020px;
        }

        .teste2 {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #botaoMostrarSugestoes {
            background-color: #A8B16B;
            width: 200px;
            height: 40px;
            color: rgb(49, 63, 22);
            border: 1px #4E6422 solid;
            border-radius: 20px;
            margin-top: 50px;
            margin-left: 30px;
            text-align: center;
        }

        #areaSugestoes {
            white-space: pre-wrap;
            /* background: #efebce;  */
            /* padding: 10px; 
            min-height: 50px; */
            margin-bottom: 50px;
            margin-left: 30px;
            margin-top: 20px;
        }

        .sugs {
            color: #4E6422;
            text-align: center;
            margin-top: 60px;
            font-size: 35px;
        }

        /* Estiliza o checkbox original */
        .checkboxSugestao {
            width: 24px;
            height: 24px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            border: 2px solid #c06500;
            border-radius: 4px;
            background-color: #fff;
            position: relative;
            cursor: pointer;
            transition: background-color 0.3s, border-color 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        /* Estilo quando o checkbox está marcado */
        .checkboxSugestao:checked {
            background-color: #ee9c2e;
            border-color: #ee9c2e;
        }

        /* Checkmark no centro do quadrado */
        .checkboxSugestao:checked::after {
            content: '\2713';
            /* ✔ */
            font-size: 22px;
            color: #c06500;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Efeito de hover */
        .checkboxSugestao:hover {
            border-color: #ee9c2e;
        }

        /* Wrapper do checkbox e texto */
        .realizado {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 18px;
            color: #7a4100;
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

        <!-- conteudo -->

        <!-- como funciona -->

        <div class="sla" style="display: flex; justify-content: center; align-items: center">
            <div class="funcionamento" style="background-color: #efebce; width: 70%; height: 150px; margin-top: 60px;
        display: flex; flex-direction: column; align-items: center; border-radius: 50px; padding: 10px">
                <h3 style="color:#4E6422">Olá,
                    <?php
                    if (isset($_SESSION['nome_usuario']) && !empty($_SESSION['nome_usuario'])) {
                        echo $_SESSION['nome_usuario'];
                    } else {
                        echo "visitante";
                    }
                    ?>
                </h3>
                <P style="text-align: center;">Faça o processo de seleção de check-list para os cuidados que já foram realizados no seu cotidiano.
                    Posteriormente, clique em sugestões para receber dicas personalizadas!</P>
            </div>

        </div>

        <div class="sla" style="display: flex; justify-content: center; align-items: center">
        <div class="funcionamento" style="background-color: #efebce; width: 70%; height: 70px; margin-top: 60px;
        display: flex; flex-direction: column; align-items: center; border-radius: 50px; padding: 10px">
            <h5 style="color: #4E6422; margin-top: 10px">Assista a um vídeo curto para melhor visualização e compreensão!</h5>
        </div>
    </div>

    <div class="div" style="display: flex; justify-content: center; align-items: center">
    <a href="https://www.youtube.com/"><img src="../../img/dmgv.png" alt="" style="width: 500px; height: 300px; display: flex; flex-direction: column; align-items: center; margin-top: 50px;"></a>
    </div>

        <!-- links -->
        <div class="links">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="btnTipo">
                        <div>
                            <a href="../deficiencias/defmotc.php" class="card">
                                <img src="../../img/cc.png" class="btndog" alt="...">
                            </a>
                        </div>
                        <div>
                            <a href="#" class="card">
                                <img src="../../img/gc.png" class="btncat" alt="...">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-2"></div>
            </div>
        </div>

        <!-- introdução -->
        <div class="introducao">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <p style="text-align: justify;">A deficiência motora em gatos também é caracterizada por limitações na capacidade de locomoção, podendo ser resultado de malformações congênitas, traumas, doenças neurológicas, lesões ortopédicas ou comprometimentos da medula espinhal.
                    </p>
                    <p style="text-align: justify;">Os felinos possuem uma elevada capacidade de adaptação, frequentemente desenvolvendo estratégias compensatórias para se locomover e realizar suas atividades diárias. Com acompanhamento veterinário adequado, adaptações no ambiente e cuidados direcionados, gatos com deficiência motora conseguem preservar sua independência e demonstram grande resiliência, levando uma vida ativa e de qualidade.</p>
                </div>
                <div class="col-2"></div>
            </div>
        </div>

        <!-- cuidados basicos -->
        <div class="cuidadosBasicos" style="margin-top: 80px;">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">

                    <h2 style="margin-bottom: 30px;">Cuidados Básicos</h2>

                </div>
                <div class="col-2"></div>
            </div>

            <?php
            // Definir a variável $dados como array antes de usar
            $dados = [
                ['id' => 1, 'sugestao' => '01. Prevenção de automutilação: Alguns gatos com paralisia podem lamber ou morder demais os próprios membros, causando feridas. É essencial observar o comportamento e manter as unhas aparadas para evitar machucados. O uso de coleiras ou bandagens pode ajudar quando necessário. Acesse nossa guia locais para ter acesso a lojas como Petz e Cobasi, onde você pode encontrar cortadores de unha para gatos e coleiras diversas para auxiliar nesse cuidado', 'img_front' => '../img/flipcards/dmg1.png', 'img_back' => '../img/flipcards/dmg2.png'],

                ['id' => 2, 'sugestao' => '02. Exercícios passivos: Movimentar suavemente as patinhas ajuda a manter a flexibilidade e a circulação, evitando rigidez muscular. Esses exercícios simples podem ser feitos em casa com orientação veterinária e fortalecem o corpo do gato.', 'img_front' => '../img/flipcards/dmg3.png', 'img_back' => '../img/flipcards/dmg4.png'],

                ['id' => 3, 'sugestao' => '03. Higiene assistida: Gatinhos com deficiência motora podem ter dificuldade para se limpar sozinhos. Nesses casos, o tutor deve atentar-se regularmente e ajudar na higiene, especialmente em áreas de difícil acesso, para evitar infecções e desconforto.', 'img_front' => '../img/flipcards/dmg5.png', 'img_back' => '../img/flipcards/dmg6.png'],
            ];
            ?>

            <div class="teste2">
                <div class="row gx-0 teste">
                    <?php $contador = 1; ?>
                    <?php foreach ($dados as $item) : ?>
                        <div class="col-4 d-flex flex-column align-items-center" style="margin-top: 20px;">
                            <div class="flip-card" style="margin-top: 20px;">
                                <div class="flip-card-inner">
                                    <div class="flip-card-front">
                                        <img src="../../img/<?= htmlspecialchars($item['img_front']) ?>" class="flip-card-img" alt="">
                                    </div>
                                    <div class="flip-card-back">
                                        <img src="../../img/<?= htmlspecialchars($item['img_back']) ?>" class="flip-card-img" alt="">
                                    </div>
                                </div>
                            </div>
                            <label class="realizado">
                                <input type="checkbox" class="checkboxSugestao" data-sugestao="<?= htmlspecialchars($item['sugestao']) ?>">
                                <?= str_pad($contador, 2, '0', STR_PAD_LEFT) ?>. Já realizado
                            </label>
                        </div>
                        <?php $contador++; ?>
                    <?php endforeach; ?>
                </div>
            </div>



            <!-- nutrição -->
            <div class="nutricao" style="margin-top: 80px;">
                <div class="row">
                    <div class="col-2"></div>
                    <div class="col-8">

                        <h2 style="margin-bottom: 30px;">Alimentação e Nutrição</h2>

                    </div>
                    <div class="col-2"></div>
                </div>

                <!-- flip-cards -->
                <?php
                // Definir a variável $dados como array antes de usar
                $dados = [
                    ['id' => 4, 'sugestao' => '04. Proteína de alta qualidade: A proteína é essencial para manter a massa muscular, especialmente em gatos com mobilidade reduzida. A alimentação deve ser equilibrada e de boa qualidade para apoiar o sistema muscular e a recuperação. Acesse nossa guia locais para ter acesso a lojas como Petz e Cobasi, onde rações e alimentos assim podem ser adquiridos', 'img_front' => '../img/flipcards/dmg7.png', 'img_back' => '../img/flipcards/dmg8.png'],

                    ['id' => 5, 'sugestao' => '05. Hidratação: Muitos gatos bebem pouca água, o que pode ser agravado pela limitação de movimento. Estimular o consumo com fontes de água ou oferecendo ração úmida ajuda a manter a hidratação adequada.', 'img_front' => '../img/flipcards/dmg9.png', 'img_back' => '../img/flipcards/dmg10.png'],

                    ['id' => 6, 'sugestao' => '06. Ração específica para mobilidade: Algumas rações são enriquecidas com nutrientes que fortalecem articulações e músculos. Elas contribuem para o conforto e o bem-estar, especialmente em gatos com deficiência motora, e podem ser compradas em casas de rações locais ou em lojas maiores, como Cobasi e Petz.', 'img_front' => '../img/flipcards/dmg11.png', 'img_back' => '../img/flipcards/dmg12.png'],
                ];
                ?>

                <div class="teste2">
                    <div class="row gx-0 teste">
                        <?php $contador = 4; ?>
                        <?php foreach ($dados as $item) : ?>
                            <div class="col-4 d-flex flex-column align-items-center" style="margin-top: 20px;">
                                <div class="flip-card" style="margin-top: 20px;">
                                    <div class="flip-card-inner">
                                        <div class="flip-card-front">
                                            <img src="../../img/<?= htmlspecialchars($item['img_front']) ?>" class="flip-card-img" alt="">
                                        </div>
                                        <div class="flip-card-back">
                                            <img src="../../img/<?= htmlspecialchars($item['img_back']) ?>" class="flip-card-img" alt="">
                                        </div>
                                    </div>
                                </div>
                                <label class="realizado">
                                    <input type="checkbox" class="checkboxSugestao" data-sugestao="<?= htmlspecialchars($item['sugestao']) ?>">
                                    <?= str_pad($contador, 2, '0', STR_PAD_LEFT) ?>. Já realizado
                                </label>
                            </div>
                            <?php $contador++; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- adaptações -->
                <div class="adaptacoes" style="margin-top: 80px;">
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-8">

                            <h2 style="margin-bottom: 30px;">Adaptações do Ambiente</h2>

                        </div>
                        <div class="col-2"></div>
                    </div>

                    <!-- flip-cards -->
                    <?php
                    // Definir a variável $dados como array antes de usar
                    $dados = [
                        ['id' => 7, 'sugestao' => '07. Ambiente seguro: Para garantir a segurança, é importante instalar telas em janelas e sacadas e usar tapetes antiderrapantes, assim como também procurar por materiais com textura, que ajudam o gato a se locomover melhor e evitam escorregões. Você pode encontrar telas próprias para sacadas e janelas em sites como Mercado Livre ou em lojas físicas como a Leroy Merlin. Já os tapetes antiderrapantes podem ser encontrados em lojas como Petz e Cobasi.', 'img_front' => '../img/flipcards/dmg13.png', 'img_back' => '../img/flipcards/dmg14.png'],

                        ['id' => 8, 'sugestao' => '08. Caixas com bordas baixas e fácil acesso facilitam o uso e previnem acidentes. Elas devem ser colocadas em locais estratégicos e de fácil alcance, especialmente se o gato tiver dificuldade para se mover. Encontre-as para compra em casas de ração locais, na Petz ou na Cobasi.', 'img_front' => '../img/flipcards/dmg15.png', 'img_back' => '../img/flipcards/dmg16.png'],

                        ['id' => 9, 'sugestao' => '09. Comedouros posicionados: A altura dos potes deve ser ajustada para que o gatinho se alimente com conforto, sem forçar o pescoço ou a coluna. Você pode colocar os potes em locais adaptados ou em suportes de comedouros, que são uma ótima alternativa para facilitar a rotina. Os suportes podem ser encontrados nos sites ou lojas físicas da Cobasi e Petz.', 'img_front' => '../img/flipcards/dmg17.png', 'img_back' => '../img/flipcards/dmg18.png'],
                    ];
                    ?>

                    <div class="teste2">
                        <div class="row gx-0 teste">
                            <?php $contador = 7; ?>
                            <?php foreach ($dados as $item) : ?>
                                <div class="col-4 d-flex flex-column align-items-center" style="margin-top: 20px;">
                                    <div class="flip-card" style="margin-top: 20px;">
                                        <div class="flip-card-inner">
                                            <div class="flip-card-front">
                                                <img src="../../img/<?= htmlspecialchars($item['img_front']) ?>" class="flip-card-img" alt="">
                                            </div>
                                            <div class="flip-card-back">
                                                <img src="../../img/<?= htmlspecialchars($item['img_back']) ?>" class="flip-card-img" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <label class="realizado">
                                        <input type="checkbox" class="checkboxSugestao" data-sugestao="<?= htmlspecialchars($item['sugestao']) ?>">
                                        <?= str_pad($contador, 2, '0', STR_PAD_LEFT) ?>. Já realizado
                                    </label>
                                </div>
                                <?php $contador++; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>


                    <div>
                        <h3 class="sugs">Sugestões:</h3>

                        <button id="botaoMostrarSugestoes" style="display:none;">Mostrar Sugestões</button>

                        <div id="areaSugestoes">
                        </div>
                    </div>

                    <script>
                        const checkboxes = document.querySelectorAll('.checkboxSugestao');
                        const botaoMostrarSugestoes = document.getElementById('botaoMostrarSugestoes');
                        const areaSugestoes = document.getElementById('areaSugestoes');

                        function verificaCheckboxes() {
                            const checkboxes = document.querySelectorAll('.checkboxSugestao');
                            const botaoMostrarSugestoes = document.getElementById('botaoMostrarSugestoes');
                            const areaSugestoes = document.getElementById('areaSugestoes');

                            let algumNaoMarcado = false;

                            checkboxes.forEach(chk => {
                                if (!chk.checked) {
                                    algumNaoMarcado = true;
                                }
                            });

                            if (algumNaoMarcado) {
                                botaoMostrarSugestoes.style.display = 'inline-block';
                                areaSugestoes.textContent = ''; // limpa as sugestões
                            } else {
                                botaoMostrarSugestoes.style.display = 'none';
                                areaSugestoes.textContent = ''; // limpa as sugestões quando todos selecionados
                            }
                        }

                        document.getElementById('botaoMostrarSugestoes').addEventListener('click', () => {
                            const checkboxes = document.querySelectorAll('.checkboxSugestao');
                            const areaSugestoes = document.getElementById('areaSugestoes');

                            areaSugestoes.innerHTML = ''; // Limpa

                            checkboxes.forEach(chk => {
                                if (!chk.checked) {
                                    const p = document.createElement('p');
                                    p.textContent = chk.dataset.sugestao;
                                    areaSugestoes.appendChild(p);
                                }
                            });
                        });

                        // Adicionar evento para verificar a condição a cada mudança do checkbox
                        document.querySelectorAll('.checkboxSugestao').forEach(chk => {
                            chk.addEventListener('change', verificaCheckboxes);
                        });

                        // Executar no carregamento inicial
                        verificaCheckboxes();

                        // Adicionar evento em cada checkbox
                        checkboxes.forEach(chk => {
                            chk.addEventListener('change', verificaCheckboxes);
                        });

                        // Clique no botão para mostrar todas sugestões

                        botaoMostrarSugestoes.addEventListener('click', () => {
                            const checkboxes = document.querySelectorAll('.checkboxSugestao');
                            const areaSugestoes = document.getElementById('areaSugestoes');
                            areaSugestoes.innerHTML = ''; // Limpa conteúdo anterior

                            checkboxes.forEach(chk => {
                                if (!chk.checked) {
                                    const p = document.createElement('p');
                                    p.textContent = chk.dataset.sugestao;
                                    areaSugestoes.appendChild(p);
                                }
                            });

                            // Cria o botão final
                            const btnFinal = document.createElement('button');
                            btnFinal.textContent = 'Acessar Locais';
                            btnFinal.style.backgroundColor = '#A8B16B';
                            btnFinal.style.color = 'areaSugestoes.appendChild(btnFinal)';
                            btnFinal.style.border = '1px solid #4E6422';
                            btnFinal.style.borderRadius = '30px';
                            btnFinal.style.marginTop = '20px';
                            btnFinal.style.padding = '15px 30px';
                            btnFinal.style.fontSize = '18px';
                            btnFinal.onclick = function() {
                                window.location.href = '../locais.php';
                            };

                            areaSugestoes.appendChild(btnFinal);

                        });
                    </script>

                </div>
            </div>
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

    </div>






    <script src="../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.min.js"></script>
</body>

</html>