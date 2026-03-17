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
    <title>Deficiência Auditiva - Gato</title>
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
    <a href="https://www.youtube.com/"><img src="../../img/dagv.png" alt="" style="width: 500px; height: 300px; display: flex; flex-direction: column; align-items: center; margin-top: 50px;"></a>
    </div>

        <!-- links -->
        <div class="links">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="btnTipo">
                        <div>
                            <a href="../deficiencias/defaudc.php" class="card">
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
                    <p style="text-align: justify;">A deficiência auditiva em gatos pode ser hereditária (comum em gatos brancos de olhos azuis) ou adquirida por infecções, idade avançada, traumas ou exposição a medicamentos ototóxicos. Gatos surdos frequentemente passam despercebidos pelos tutores devido à sua natureza independente e habilidade de compensação através da visão aguçada e sensibilidade às vibrações. Com adaptações apropriadas, felinos com deficiência auditiva mantêm sua qualidade de vida e comportamentos naturais.
                    </p>
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
                ['id' => 1, 'sugestao' => '01. Comunicação visual: Gatos com deficiência auditiva aprendem facilmente a se comunicar por gestos. É possível ensinar sinais simples, como mostrar a tigela para indicar comida ou acenar com as mãos para chamar. A consistência nos gestos ajuda o gato a se adaptar.', 'img_front' => '../img/flipcards/dag1.png', 'img_back' => '../img/flipcards/dag2.png'],

                ['id' => 2, 'sugestao' => '02. Estímulos vibratórios: Palmas, batidas leves no chão ou vibrações próximas podem chamar a atenção do gato sem assustá-lo. Ele aprende a associar o movimento à presença do tutor, o que fortalece o vínculo e melhora a convivência.', 'img_front' => '../img/flipcards/dag3.png', 'img_back' => '../img/flipcards/dag4.png'],

                ['id' => 3, 'sugestao' => '03. Dessensibilização ao toque: Acostumar o gato ao toque é fundamental. Toques leves e frequentes nas costas ou no ombro, acompanhados de carinho e recompensas, ensinam que o contato físico é uma forma positiva de comunicação.', 'img_front' => '../img/flipcards/dag5.png', 'img_back' => '../img/flipcards/dag6.png'],
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
                    ['id' => 4, 'sugestao' => '04. Antioxidantes: Vitaminas antioxidantes ajudam a proteger o sistema nervoso e retardar processos degenerativos relacionados à idade. Elas devem ser incluídas na dieta conforme orientação do veterinário.', 'img_front' => '../img/flipcards/dag7.png', 'img_back' => '../img/flipcards/dag8.png'],

                    ['id' => 5, 'sugestao' => '05. Sinais visuais na alimentação: Fazer um gesto ou movimento de rotina antes de servir a refeição ajuda o gato a entender o momento da comida. Isso facilita a comunicação e evita ansiedade.', 'img_front' => '../img/flipcards/dag9.png', 'img_back' => '../img/flipcards/dag10.png'],

                    ['id' => 6, 'sugestao' => '06. Nutrição neurológica: Suplementos com ômega-3 e vitaminas do complexo B fortalecem o sistema nervoso e contribuem para o equilíbrio mental e físico do gato, promovendo qualidade de vida.', 'img_front' => '../img/flipcards/dag11.png', 'img_back' => '../img/flipcards/dag12.png'],
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
                        ['id' => 7, 'sugestao' => '07. Ambiente interno seguro: Como gatos surdos não escutam sons de alerta, é ideal mantê-los dentro de casa ou em áreas totalmente protegidas, como varandas com telas. Isso evita fugas e acidentes com outros animais ou veículos.', 'img_front' => '../img/flipcards/dag13.png', 'img_back' => '../img/flipcards/dag14.png'],

                        ['id' => 8, 'sugestao' => '08. Eliminação de sustos: Evite se aproximar por trás ou fazer movimentos bruscos sem aviso visual. Gatos com deficiência auditiva podem se assustar facilmente se surpreendidos, o que pode gerar estresse e reações defensivas.', 'img_front' => '../img/flipcards/dag15.png', 'img_back' => '../img/flipcards/dag16.png'],

                        ['id' => 9, 'sugestao' => '09. Rotina visual: Usar sinalizações luminosas, como pequenas luzes ou lanternas, ajuda o gato a reconhecer momentos específicos, como a hora da alimentação ou do descanso. A previsibilidade traz segurança e tranquilidade.', 'img_front' => '../img/flipcards/dag17.png', 'img_back' => '../img/flipcards/dag18.png'],
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