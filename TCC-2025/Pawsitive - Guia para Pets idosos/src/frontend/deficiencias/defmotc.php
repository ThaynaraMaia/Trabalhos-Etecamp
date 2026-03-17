<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/def.css">
    <link rel="shortcut icon" href="../../img/favicon.ico" type="image/x-icon">
    <title>Deficiência Motora - Cachorro</title>
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
    <a href="https://www.youtube.com/"><img src="../../img/dmcv.png" alt="" style="width: 500px; height: 300px; display: flex; flex-direction: column; align-items: center; margin-top: 50px;"></a>
    </div>

        <!-- links -->
        <div class="links">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="btnTipo">
                        <div>
                            <a href="#" class="card">
                                <img src="../../img/cc.png" class="btndog" alt="...">
                            </a>
                        </div>
                        <div>
                            <a href="../deficiencias/defmotg.php" class="card">
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
                    <p style="text-align: justify;">A deficiência motora em cães é caracterizada por limitações na capacidade de locomoção, que podem comprometer os membros posteriores, anteriores ou ambos. Essas alterações podem ser congênitas – presentes desde o nascimento – ou adquiridas ao longo da vida, geralmente em decorrência de traumas (como atropelamentos e quedas de grandes alturas), doenças neurológicas (como hérnias de disco), enfermidades infecciosas (como a doença do carrapato), intoxicações, sequelas de cinomose ou lesões ortopédicas, incluindo fraturas e luxações.
                    </p>
                    <p style="text-align: justify;">É importante ressaltar que a presença de deficiência motora não significa, necessariamente, redução da qualidade de vida. Com acompanhamento veterinário adequado, adaptações no ambiente e cuidados específicos, esses cães podem manter uma rotina saudável, ativa e feliz.</p>
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
                ['id' => 1, 'sugestao' => '01. Fisioterapia regular: A fisioterapia ajuda a manter a força muscular, melhora a mobilidade das articulações e alivia dores. Mesmo em casos de lesões irreversíveis, os exercícios reduzem o desconforto e evitam problemas de compensação em outras partes do corpo. O ideal é que o tratamento seja acompanhado por um profissional especializado. Acesse nossa guia locais para ter acesso a veterinários especializados como OrtoNeuro Veterinária e FisioCare Pet.', 'img_front' => '../img/flipcards/cdm1.png', 'img_back' => '../img/flipcards/cdm2.png'],

                ['id' => 2, 'sugestao' => '02. Proteção contra arrasto: Os cãezinhos com deficiência motora, principalmente com paralisia em algum membro, podem arrastar as patinhas, causando feridas, inflamações e irritações na pele. Para evitar machucados, é importante usar sacos de arrasto, botinhas ou meias grossas presas com fita ou bandagens, para assim proteger as patas e manter o conforto do animal durante o deslocamento. Você pode adquirir os sacos de arrasto, meias e botinhas para cachorros em lojas físicas na região de Jundiaí. Acesse nossa guia locais para ter acesso a lojas como Petz, Cobasi e Carrefour.', 'img_front' => '../img/flipcards/cdm3.png', 'img_back' => '../img/flipcards/cdm4.png'],

                ['id' => 3, 'sugestao' => '03. Controle de peso: Manter o peso ideal é essencial para que o cão se mova com mais facilidade e sem sobrecarregar as articulações, visto que o excesso de peso pode agravar a limitação motora e dificultar o cuidado diário. Para evitar esse tipo de situação, você pode manter uma alimentação equilibrada para o cãozinho através do acompanhamento de uma nutricionista especializada, porções em quantidade razoável e implementação de suplementos próprios para controle do peso, além de implementar exercícios leves na rotina do bichinho, podendo optar por fisioterapia, passeios e estímulos físicos. ', 'img_front' => '../img/flipcards/cdm5.png', 'img_back' => '../img/flipcards/cdm6.png'],
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
                    ['id' => 4, 'sugestao' => '04. Hidratação adequada: Durante a fisioterapia ou em dias mais quentes, a hidratação deve ser reforçada. A falta de água pode causar desidratação e aumentar o risco de infecções urinárias. Deixe sempre água limpa e fresca disponível em vários ambientes da casa.', 'img_front' => '../img/flipcards/cdm7.png', 'img_back' => '../img/flipcards/cdm8.png'],

                    ['id' => 5, 'sugestao' => '05. Controle calórico: O controle da quantidade de calorias ajuda a evitar a obesidade, que dificulta a locomoção e prejudica a recuperação. É importante seguir a recomendação do veterinário quanto à ração e quantidade diária.', 'img_front' => '../img/flipcards/cdm9.png', 'img_back' => '../img/flipcards/cdm10.png'],

                    ['id' => 6, 'sugestao' => '06. Suplementação orientada: Suplementos como o ômega-3 auxiliam na redução de inflamações e na recuperação muscular. No entanto, o uso deve ser sempre orientado por um veterinário, conforme as necessidades de cada animal. Os suplementos e vitaminas podem ser comprados nas lojas físicas ou nos sites da Cobasi e Petz.', 'img_front' => '../img/flipcards/cdm11.png', 'img_back' => '../img/flipcards/cdm12.png'],
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
                        ['id' => 7, 'sugestao' => '07. Ambiente seguro: Um espaço seguro para convívio do cachorrinho faz toda a diferença, ele deve ter uma área cercada e livre de riscos para se locomover com autonomia. Para ajudar na movimentação e evitar escorregões e quedas, tapetes antiderrapantes e pisos nem muito lisos nem muito ásperos ajudam na movimentação, contribuindo com a segurança e bem-estar do pet. Acesse nossa guia locais para ter acesso a lojas físicas como Americana e Petz ou busque em sites como Magazine Luiza, Amazon e Mercado Livre.', 'img_front' => '../img/flipcards/cdm13.png', 'img_back' => '../img/flipcards/cdm14.png'],

                        ['id' => 8, 'sugestao' => '08. Eliminação de obstáculos: Móveis pontiagudos, escadas e sacadas representam perigo para cães com limitações motoras. Retirar ou proteger esses elementos, além de instalar rampas para acessar sofás ou camas, torna o ambiente mais seguro e reduz o risco de acidentes. Rampas podem ser adquiridas no site da Rampets, uma marca online especializada na fabricação e comércio de rampas adaptadas para animais domésticos com deficiências motoras.', 'img_front' => '../img/flipcards/cdm15.png', 'img_back' => '../img/flipcards/cdm16.png'],

                        ['id' => 9, 'sugestao' => '09. Comedouros adequados: A altura dos potes deve ser ajustada para que o cão se alimente com conforto, sem forçar o pescoço ou a coluna. Você pode colocar os potes em locais adaptados ou em suportes de comedouros, que são uma ótima alternativa para facilitar a rotina. Os suportes podem ser encontrados nos sites ou lojas físicas da Cobasi e Petz.', 'img_front' => '../img/flipcards/cdm17.png', 'img_back' => '../img/flipcards/cdm18.png'],
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