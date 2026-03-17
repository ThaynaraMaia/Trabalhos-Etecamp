<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poetsen+One&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="imagex/png" href="../img/mercury.simples.ico">
    <title>Mercury</title>
</head>
<body>
    <!-- Sidebar começa aqui!-->
    <nav id="sidebar" role="navigation">
        <div id="sidebar_content">
            <div id="user">
                <img src="../img/perfil.png" id="user_avatar" alt="Avatar">

                <p id="user_infos">
                    <span class="item-description perfil">
                    <?php
                        echo ($_SESSION['nome']);
                    ?>
                    </span>
                    <span class="item-description">
                    <?php
                        echo ($_SESSION['email']);
                    ?>
                    </span>
                </p>
            </div>

            <ul id="side_items">
                <li class="side-item active">
                    <a href="../html/home.php">
                    <i class="fa-solid fa-house"></i>
                        <span class="item-description">
                            Home
                        </span>
                    </a>
                </li>

                <li class="side-item">
                    <a href="../html/perfil.php">
                        <i class="fa-solid fa-user"></i>
                        <span class="item-description">
                            Perfil
                        </span>
                    </a>
                </li>

                <li class="side-item">
                    <a href="../html/meus_hobbies.php">
                    <i class="fa-solid fa-paintbrush"></i>
                        <span class="item-description">
                            Meus Hobbies
                        </span>
                    </a>
                </li>
            </ul>

            <button aria-label="Abrir menu" id="open_btn">
                <i id="open_btn_icon" class="fa-solid fa-chevron-right"></i>
            </button>
        </div>

        <div id="logout">
            <button aria-label="Cadastrar" id="logout_btn" onclick="window.location.href='../../backend/login/logout.php'">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="item-description">Logout</span>
            </button>
        </div>
    </nav>
    <!-- Sidebar termina aqui! -->

    <!-- Começa o conteúdo -->
    <div class="container
        <div role="main" class="section">
            <div class="borda">
                <div class="header">
                    <div class="row">
                    <div class="col-5">
                    </div>
                    <div class="col-4">
                        <img src="../img/logo.png" alt="" class="logo">
                    </div>
                    <div class="col-3">
                                    
                </div>
            </div>

                </div>
                    <div class="row">
                        <div class="col">
                            <div class="titulo" style="margin-left: 110px; font-size: 64px">
                            Bem-vindo.
                            </div>
                        </div>
                        <div class="col">
                            <img src="../img/download-removebg-preview.png
                            " alt= " class="inicio-foto" style="height: 300px; margin-top: -70px;">
                        </div>
                        <div class="col">
                            <div class="titulo" style="margin-right: 110px; font-size: 64px">
                                <p>Vamos lá! </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="sobre">
                    <H2>

                        <p>Vamos começar essa aventura juntos!</p>
                    </H2>
                </div>
                

                <!-- Imagem do meio -->
                    <div class="meio">
                        <div class="image-meio">
                            <img src="../img/fig1home.jpg" style="width: 300px; height: 300px" alt="">
                        </div>
                            <div class="text-meio">
                                <h1>O que são hobbies?</h1>
                                <p>Os hobbies são atividades que podem ser praticadas durante seu tempo livre para próprio lazer.</p>
                            </div>
                    </div>
                <!-- Imagem do meio termina aqui -->

                <!-- Cards começam aqui -->

                
                    
                    <div class="card-container">
                        <div class="paramodal">
                                    <div class="card">
                                        <img  src="../img/artes visuais.jfif" alt="">
                                        <div class="card-text">
                                            <h2>Artes visuais</h2>
                                            <button id="openModalBtn" class="btn-transparente">Ver mais...</button>
                                        </div>
                                    </div>
                                        <!-- Estrutura do Modal -->
                                    <div id="myModal" class="modal">
                                        <div class="modal-content">
                                            <span id="closeModalBtn" class="close">&times;</span>
                                            <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                                <ul>
                                                    <li>
                                                        <h5>Pintura</h5>
                                                        <p>Criar imagens através das cores, usando diferentes técnicas e materiais, como óleo, acrílico, aquarela, ou guache.</p>
                                                        <input type="hidden" name="nome" value="Pintura">
                                                        <input type="hidden" name="descricao" value="Criar imagens através das cores, usando diferentes técnicas e materiais, como óleo, acrílico, aquarela, ou guache.">
                                                        <input type="hidden" name="status" value="a fazer">
                                                    </li>
                                                    <button type="submit" class="salvar_sugestao ">+ adicionar sugestão</button>
                                                </ul>
                                            </form>
    
                                            <hr>
    
                                            <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                                <ul>
                                                    <li>
                                                        <h5>Desenho</h5>
                                                        <p>Criar imagens usando linhas e formas a lápis, carvão, ou grafite.</p>
                                                        <input type="hidden" name="nome" value="Desenho">
                                                        <input type="hidden" name="descricao" value="Criar imagens usando linhas e formas a lápis, carvão, ou grafite.">
                                                        <input type="hidden" name="status" value="a fazer">
                                                    </li>
                                                    <button type="submit" class="salvar_sugestao ">+ adicionar sugestão  </button>
                                                    
                                                </ul>
                                            </form>
                                                
                                            <hr>
    
                                            <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                                <ul>
                                                    <li>
                                                        <h5>Fotografia</h5>
                                                        <p>Capturar imagens e explorar diferentes estilos, como fotografia digital ou analógica.</p>
                                                        <input type="hidden" name="nome" value="Fotografia">
                                                        <input type="hidden" name="descricao" value="Capturar imagens e explorar diferentes estilos, como fotografia digital ou analógica.">
                                                        <input type="hidden" name="status" value="a fazer">
                                                    </li>
                                                    <button type="submit" class="salvar_sugestao ">+ adicionar sugestão</button>
                                                </ul>
                                            </form>
    
                                            <hr>
    
                                            <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                                <ul>
                                                    <li>
                                                        <h5> Colagem</h5>
                                                        <p>Montar imagens e texturas para criar composições visuais.</p>
                                                        <input type="hidden" name="nome" value=" Colagem">
                                                        <input type="hidden" name="descricao" value="Montar imagens e texturas para criar composições visuais.">
                                                        <input type="hidden" name="status" value="a fazer">
                                                    </li>
                                                    <button type="submit" class="salvar_sugestao ">+ adicionar sugestão</button>
                                                </ul>
                                            </form>
                                                
                                            <hr>
                                            <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                                <ul>
                                                    <li>
                                                        <h5> Mosaico</h5>
                                                        <p>Criar imagens e padrões usando peças de vidro, cerâmica, ou outros materiais.</p>
                                                        <input type="hidden" name="nome" value=" Colagem">
                                                        <input type="hidden" name="descricao" value="Criar imagens e padrões usando peças de vidro, cerâmica, ou outros materiais.">
                                                        <input type="hidden" name="status" value="a fazer">
                                                    </li>
                                                    <button type="submit" class="salvar_sugestao ">+ adicionar sugestão</button>
                                                </ul>
                                            </form>
    
                                            <hr>
    
                                            <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                                <ul>
                                                    <li>
                                                        <h5> Design de Moda</h5>
                                                        <p>Criar e esboçar roupas e acessórios.</p>
                                                        <input type="hidden" name="nome" value=" Design de Moda">
                                                        <input type="hidden" name="descricao" value="Criar e esboçar roupas e acessórios.">
                                                        <input type="hidden" name="status" value="a fazer">
                                                    </li>
                                                    <button type="submit" class="salvar_sugestao ">+ adicionar sugestão</button>
                                                </ul>
                                            </form>
    
                                            <hr>
    
                                            <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                                <ul>
                                                    <li>
                                                        <h5> Arte Conceitual</h5>
                                                        <p> Desenvolver ideias e visões para projetos futuros, muitas vezes em forma de esboços ou maquetes.</p>
                                                        <input type="hidden" name="nome" value="Arte Conceitual">
                                                        <input type="hidden" name="descricao" value=" Desenvolver ideias e visões para projetos futuros, muitas vezes em forma de esboços ou maquetes.">
                                                        <input type="hidden" name="status" value="a fazer">
                                                    </li>
                                                    <button type="submit" class="salvar_sugestao ">+ adicionar sugestão</button>
                                                </ul>
                                            </form>
                                                
                                            </ul>
                                        </div>
                                    </div>
                                    
                        </div>
                                
                        <div class="paramodal1">
                                        <div class="card">
                                            <img  src="../img/artesanatos.jfif" alt="">
                                            <div class="card-text">
                                                <h2>Artesanato</h2>
                                                <br>
                                                <br>
                                                
                                                <button id="openModalBtn1" class="btn-transparente">Ver mais...</button>
                                            </div>
                                        </div>
                                        <div id="myModal1" class="modal">
                                            <div class="modal-content">
                                                <span class="close" data-modal="myModal1">&times;</span>
                                                
                                                <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                                    <ul>
                                                        <li>
                                                            <h5>Crochê</h5>
                                                            <p>Criar peças usando agulhas e fios, como roupas, acessórios, e itens de decoração.</p>
                                                            <input type="hidden" name="nome" value="Crochê">
                                                            <input type="hidden" name="descricao" value="Criar peças usando agulhas e fios, como roupas, acessórios, e itens de decoração.">
                                                            <input type="hidden" name="status" value="a fazer">
                                                            <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                        </li>
                                                    </ul>
                                                </form>
    
                                                <hr>
    
                                                <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                                    <ul>
                                                        <li>
                                                            <h5>Tricô</h5>
                                                            <p>Trabalhar com agulhas e fios para fazer roupas, mantas, e outros itens têxteis.</p>
                                                            <input type="hidden" name="nome" value="Tricô">
                                                            <input type="hidden" name="descricao" value="Trabalhar com agulhas e fios para fazer roupas, mantas, e outros itens têxteis.">
                                                            <input type="hidden" name="status" value="a fazer">
                                                            <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                        </li>
                                                    </ul>
                                                </form>
    
                                                <hr>
    
                                                <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                                    <ul>
                                                        <li>
                                                            <h5>Costura</h5>
                                                            <p>Fazer roupas, acessórios, e itens para a casa utilizando máquinas de costura ou técnicas manuais.</p>
                                                            <input type="hidden" name="nome" value="Costura">
                                                            <input type="hidden" name="descricao" value="Fazer roupas, acessórios, e itens para a casa utilizando máquinas de costura ou técnicas manuais.">
                                                            <input type="hidden" name="status" value="a fazer">
                                                            <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                        </li>
                                                    </ul>
                                                </form>
    
                                                <hr>
    
                                                <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                                    <ul>
                                                        <li>
                                                            <h5>Bordado</h5>
                                                            <p>Decorar tecidos com pontos e fios para criar padrões e imagens.</p>
                                                            <input type="hidden" name="nome" value="Bordado">
                                                            <input type="hidden" name="descricao" value="Decorar tecidos com pontos e fios para criar padrões e imagens.">
                                                            <input type="hidden" name="status" value="a fazer">
                                                            <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                        </li>
                                                    </ul>
                                                </form>
    
                                                <hr>
    
                                                <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                                    <ul>
                                                        <li>
                                                            <h5>Escultura</h5>
                                                            <p>Trabalhar com materiais como argila, madeira, metal, ou pedra.</p>
                                                            <input type="hidden" name="nome" value="Escultura">
                                                            <input type="hidden" name="descricao" value="Trabalhar com materiais como argila, madeira, metal, ou pedra.">
                                                            <input type="hidden" name="status" value="a fazer">
                                                            <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                        </li>
                                                    </ul>
                                                </form>
    
                                                <hr>
    
                                                <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                                    <ul>
                                                        <li>
                                                            <h5>Marcenaria</h5>
                                                            <p>Trabalhar com madeira para criar móveis, decoração, e outros itens artesanais.</p>
                                                            <input type="hidden" name="nome" value="Marcenaria">
                                                            <input type="hidden" name="descricao" value="Trabalhar com madeira para criar móveis, decoração, e outros itens artesanais.">
                                                            <input type="hidden" name="status" value="a fazer">
                                                            <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                        </li>
                                                    </ul>
                                                </form>
    
                                                <hr>
    
                                                <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                                    <ul>
                                                        <li>
                                                            <h5>Bijuterias e Joias</h5>
                                                            <p>Criar peças de adorno utilizando materiais como contas, fios, e metais.</p>
                                                            <input type="hidden" name="nome" value="Bijuterias e Joias">
                                                            <input type="hidden" name="descricao" value="Criar peças de adorno utilizando materiais como contas, fios, e metais.">
                                                            <input type="hidden" name="status" value="a fazer">
                                                            <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                        </li>
                                                    </ul>
                                                </form>
    
                                                <hr>
    
                                                <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                                    <ul>
                                                        <li>
                                                            <h5>Patchwork</h5>
                                                            <p>Costurar pedaços de tecido para criar padrões e projetos, como cobertores e almofadas.</p>
                                                            <input type="hidden" name="nome" value="Patchwork">
                                                            <input type="hidden" name="descricao" value="Costurar pedaços de tecido para criar padrões e projetos, como cobertores e almofadas.">
                                                            <input type="hidden" name="status" value="a fazer">
                                                            <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                        </li>
                                                    </ul>
                                                </form>
                                        </div>
                                   </div>
                        </div>
    
                        <div class="paramodal2">
                                <div class="card">
                                    <img src="../img/musica.jfif" alt="">
                                    <div class="card-text">
                                        <h2>Música</h2>
                                        <br>
                                        <br>
                                        <button id="openModalBtn2" class="btn-transparente">Ver mais...</button>
                                    </div>
                                </div>
                                
                                <div id="myModal2" class="modal">
                                    <div class="modal-content">
                                        <span class="close" data-modal="myModal2">&times;</span>   
                                        <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                            <ul>
                                                <li>
                                                    <h5>Tocar um Instrumento</h5>
                                                    <p>Aprender e praticar instrumentos como piano, guitarra, violino, bateria, saxofone, etc.</p>
                                                    <input type="hidden" name="nome" value="Tocar um Instrumento">
                                                    <input type="hidden" name="descricao" value="Aprender e praticar instrumentos como piano, guitarra, violino, bateria, saxofone, etc.">
                                                    <input type="hidden" name="status" value="a fazer">
                                                    <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                </li>
                                            </ul>
                                        </form>
    
                                        <hr>
    
                                        <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                            <ul>
                                                <li>
                                                    <h5>Canto</h5>
                                                    <p>Praticar técnicas vocais, cantar em corais, ou explorar diferentes estilos e gêneros musicais.</p>
                                                    <input type="hidden" name="nome" value="Canto">
                                                    <input type="hidden" name="descricao" value="Praticar técnicas vocais, cantar em corais, ou explorar diferentes estilos e gêneros musicais.">
                                                    <input type="hidden" name="status" value="a fazer">
                                                    <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                </li>
                                            </ul>
                                        </form>
    
                                        <hr>
    
                                        <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                            <ul>
                                                <li>
                                                    <h5>Composição</h5>
                                                    <p>Escrever músicas, letras e criar arranjos musicais.</p>
                                                    <input type="hidden" name="nome" value="Composição">
                                                    <input type="hidden" name="descricao" value="Escrever músicas, letras e criar arranjos musicais.">
                                                    <input type="hidden" name="status" value="a fazer">
                                                    <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                </li>
                                            </ul>
                                        </form>
    
                                        <hr>
    
                                        <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                            <ul>
                                                <li>
                                                    <h5>Produção Musical</h5>
                                                    <p>Usar softwares de edição e produção para criar, gravar e mixar músicas.</p>
                                                    <input type="hidden" name="nome" value="Produção Musical">
                                                    <input type="hidden" name="descricao" value="Usar softwares de edição e produção para criar, gravar e mixar músicas.">
                                                    <input type="hidden" name="status" value="a fazer">
                                                    <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                </li>
                                            </ul>
                                        </form>
    
                                        <hr>
    
                                        <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                            <ul>
                                                <li>
                                                    <h5>Estudo da Teoria Musical</h5>
                                                    <p>Aprender sobre harmonia, ritmo, escalas, e outros fundamentos da música.</p>
                                                    <input type="hidden" name="nome" value="Estudo da Teoria Musical">
                                                    <input type="hidden" name="descricao" value="Aprender sobre harmonia, ritmo, escalas, e outros fundamentos da música.">
                                                    <input type="hidden" name="status" value="a fazer">
                                                    <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                </li>
                                            </ul>
                                        </form>
    
                                        <hr>
    
                                        <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                            <ul>
                                                <li>
                                                    <h5>Djing</h5>
                                                    <p>Mixar músicas e criar sets em eventos ou para prática pessoal.</p>
                                                    <input type="hidden" name="nome" value="Djing">
                                                    <input type="hidden" name="descricao" value="Mixar músicas e criar sets em eventos ou para prática pessoal.">
                                                    <input type="hidden" name="status" value="a fazer">
                                                    <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                </li>
                                            </ul>
                                        </form>
    
                                        <hr>
    
                                        <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                            <ul>
                                                <li>
                                                    <h5>Musicoterapia</h5>
                                                    <p>Usar a música como ferramenta terapêutica para melhorar o bem-estar e a saúde mental.</p>
                                                    <input type="hidden" name="nome" value="Musicoterapia">
                                                    <input type="hidden" name="descricao" value="Usar a música como ferramenta terapêutica para melhorar o bem-estar e a saúde mental.">
                                                    <input type="hidden" name="status" value="a fazer">
                                                    <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                </li>
                                            </ul>
                                        </form>
    
                                    </div>
                                </div>
                        </div>
    
                        <div class="paramodal3">
                                <div class="card">
                                    <img src="../img/esportes.jfif" alt="">
                                    <div class="card-text">
                                        <h2>Atividades Físicas</h2>
                                        <button id="openModalBtn3" class="btn-transparente">Ver mais...</button>
                                    </div>
                                </div>
                                
                                <div id="myModal3" class="modal">
                                    <div class="modal-content">
                                        <span class="close" data-modal="myModal3">&times;</span>
    
                                        <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                            <ul>
                                                <li>
                                                    <h5>Caminhada</h5>
                                                    <p>Uma atividade simples e acessível que pode ser feita em diversos ritmos e ambientes.</p>
                                                    <input type="hidden" name="nome" value="Caminhada">
                                                    <input type="hidden" name="descricao" value="Uma atividade simples e acessível que pode ser feita em diversos ritmos e ambientes.">
                                                    <input type="hidden" name="status" value="a fazer">
                                                    <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                </li>
                                            </ul>
                                        </form>
    
                                        <hr>
    
                                        <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                            <ul>
                                                <li>
                                                    <h5>Corrida</h5>
                                                    <p>Atividade cardiovascular que pode ser praticada ao ar livre ou em esteiras.</p>
                                                    <input type="hidden" name="nome" value="Corrida">
                                                    <input type="hidden" name="descricao" value="Atividade cardiovascular que pode ser praticada ao ar livre ou em esteiras.">
                                                    <input type="hidden" name="status" value="a fazer">
                                                    <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                </li>
                                            </ul>
                                        </form>
    
                                        <hr>
    
                                        <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                            <ul>
                                                <li>
                                                    <h5>Natação</h5>
                                                    <p>Exercício completo que trabalha diversos grupos musculares e melhora a resistência cardiovascular.</p>
                                                    <input type="hidden" name="nome" value="Natação">
                                                    <input type="hidden" name="descricao" value="Exercício completo que trabalha diversos grupos musculares e melhora a resistência cardiovascular.">
                                                    <input type="hidden" name="status" value="a fazer">
                                                    <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                </li>
                                            </ul>
                                        </form>
    
                                        <hr>
    
                                        <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                            <ul>
                                                <li>
                                                    <h5>Ciclismo</h5>
                                                    <p>Pedalar ao ar livre ou em bicicletas ergométricas, excelente para o condicionamento físico e fortalecimento das pernas.</p>
                                                    <input type="hidden" name="nome" value="Ciclismo">
                                                    <input type="hidden" name="descricao" value="Pedalar ao ar livre ou em bicicletas ergométricas, excelente para o condicionamento físico e fortalecimento das pernas.">
                                                    <input type="hidden" name="status" value="a fazer">
                                                    <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                </li>
                                            </ul>
                                        </form>
    
                                        <hr>
    
                                        <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                            <ul>
                                                <li>
                                                    <h5>Musculação</h5>
                                                    <p>Levantamento de pesos ou exercícios de resistência para fortalecer e tonificar músculos.</p>
                                                    <input type="hidden" name="nome" value="Musculação">
                                                    <input type="hidden" name="descricao" value="Levantamento de pesos ou exercícios de resistência para fortalecer e tonificar músculos.">
                                                    <input type="hidden" name="status" value="a fazer">
                                                    <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                </li>
                                            </ul>
                                        </form>
    
                                        <hr>
    
                                        <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                            <ul>
                                                <li>
                                                    <h5>Yoga</h5>
                                                    <p>Práticas que combinam posturas, respiração e meditação para melhorar a flexibilidade, força e equilíbrio.</p>
                                                    <input type="hidden" name="nome" value="Yoga">
                                                    <input type="hidden" name="descricao" value="Práticas que combinam posturas, respiração e meditação para melhorar a flexibilidade, força e equilíbrio.">
                                                    <input type="hidden" name="status" value="a fazer">
                                                    <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                </li>
                                            </ul>
                                        </form>
    
                                        <hr>
    
                                        <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                            <ul>
                                                <li>
                                                    <h5>Pilates</h5>
                                                    <p>Foco no fortalecimento do core, flexibilidade e alinhamento postural.</p>
                                                    <input type="hidden" name="nome" value="Pilates">
                                                    <input type="hidden" name="descricao" value="Foco no fortalecimento do core, flexibilidade e alinhamento postural.">
                                                    <input type="hidden" name="status" value="a fazer">
                                                    <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                </li>
                                            </ul>
                                        </form>
    
                                        <hr>
    
                                        <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                            <ul>
                                                <li>
                                                    <h5>Dança</h5>
                                                    <p>Atividade divertida que pode incluir estilos variados como ballet, salsa, hip-hop, ou dança de salão.</p>
                                                    <input type="hidden" name="nome" value="Dança">
                                                    <input type="hidden" name="descricao" value="Atividade divertida que pode incluir estilos variados como ballet, salsa, hip-hop, ou dança de salão.">
                                                    <input type="hidden" name="status" value="a fazer">
                                                    <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                </li>
                                            </ul>
                                        </form>
    
                                        <hr>
    
                                        <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                            <ul>
                                                <li>
                                                    <h5>Artes Marciais</h5>
                                                    <p>Práticas como karatê, jiu-jitsu, taekwondo, ou muay thai, que combinam defesa pessoal e condicionamento físico.</p>
                                                    <input type="hidden" name="nome" value="Artes Marciais">
                                                    <input type="hidden" name="descricao" value="Práticas como karatê, jiu-jitsu, taekwondo, ou muay thai, que combinam defesa pessoal e condicionamento físico.">
                                                    <input type="hidden" name="status" value="a fazer">
                                                    <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                </li>
                                            </ul>
                                        </form>
    
                                        <hr>
    
                                        <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                            <ul>
                                                <li>
                                                    <h5>Esportes Coletivos</h5>
                                                    <p>Participar de esportes como futebol, basquete, vôlei, ou rugby, promovendo trabalho em equipe e condicionamento físico.</p>
                                                    <input type="hidden" name="nome" value="Esportes Coletivos">
                                                    <input type="hidden" name="descricao" value="Participar de esportes como futebol, basquete, vôlei, ou rugby, promovendo trabalho em equipe e condicionamento físico.">
                                                    <input type="hidden" name="status" value="a fazer">
                                                    <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                </li>
                                            </ul>
                                        </form>
    
                                        <hr>
    
                                        <form action="../../backend/hobbys/sugestao_hobby.php" method="POST">
                                            <ul>
                                                <li>
                                                    <h5>Treinamento Funcional</h5>
                                                    <p>Exercícios que imitam movimentos do dia a dia para melhorar a força e a resistência geral.</p>
                                                    <input type="hidden" name="nome" value="Treinamento Funcional">
                                                    <input type="hidden" name="descricao" value="Exercícios que imitam movimentos do dia a dia para melhorar a força e a resistência geral.">
                                                    <input type="hidden" name="status" value="a fazer">
                                                    <button type="submit" class="salvar_sugestao">+ adicionar sugestão</button>
                                                </li>
                                            </ul>
                                        </form>
                                    </div>
                                </div>
                        </div>
                    </div>
                
        </div>
    

    <!-- Footer div section -->
            <div class="footer">
                <!-- <p> 2024 Meu Site. Todos os direitos reservados.</p> -->
            </div>
        </div>
    <script src="../../backend/scripts/script.js"></script>
    <script src="../../backend/scripts/scripthome.js"></script>
</body>
</html>