<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Inicial - Projeto Martopia</title>

    <!-- Estilos -->
    <link rel="stylesheet" href="./public/css/homeInicio.css">
    <link rel="stylesheet" href="./public/css/footer.css">

    <!-- Ícones Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Biblioteca Scroll -->
    <script src="https://unpkg.com/scrollreveal"></script>

</head>

<body>

    <div class="container">

        <!-- NAVBAR -->
        <header class="header">


            <div class="logo-marca" style="margin-left: -3rem;">
                <a href="./home.php" class="logo"><img src="./public/img/Logo.png" alt="Logo-Projeto Martopia"></a>
                <p style="margin-left: -3rem;">Projeto <br> Martopia</p>
            </div>


            <input type="checkbox" id="check">
            <label for="check" class="icone">
                <i class="bi bi-list" id="menu-icone"></i>
                <i class="bi bi-x" id="sair-icone"></i>
            </label>

            <nav class="navbar">
                <!-- <a href="#" style="--i:0;">Home</a>
                <a href="#" style="--i:1;">InstaMar</a>
                <a href="inicial.html" style="--i:2;">Jogos</a>
                <a href="#" style="--i:3;">Conteúdos Educativos</a> -->

                <a href="../backend/login/login.php" class="btn-login" style="font-size: 1.3rem;">Login</a>
            </nav>
        </header>


        <!-- PARALLAX -->
        <section class="parallax">
            <img src="./public/img/Fundo.png" alt="Fundo" id="img01">
            <img src="./public/img/Onda01.png" alt="Onda 1" id="img02">
            <img src="./public/img/Onda02.png" alt="Onda 2" id="img03">
            <img src="./public/img/Coral01.png" alt="Coral esquerdo" id="img04">
            <h2 id="text" style="font-family: 'Logo'; letter-spacing: 5px; font-size: 6rem;">Projeto Martopia</h2>
            <img src="./public/img/Coral02.png" alt="Coral direito" id="img05">
        </section>

        <!-- CONTEÚDO -->
        <main>
            <section class="sec">
                <!-- <div class="titulo">
                    <h1 style="font-family: 'Titulo'; letter-spacing: 3px;">Sobre o Projeto Martopia</h1>
                </div> -->

                <div class="cardsP" style="font-family: 'Texto'; letter-spacing: 2px;">
                    <div class="cards">
                        <div class="card efeito-cards1">
                            <h2 style="font-family: Titulo; font-size: 2rem;">Quem Somos</h2>
                            <br>
                            <p style=" font-size: 1.3rem;">O Projeto Martopia nasceu através de um grupo de alunos da Etecamp com o desejo de levar conhecimento a todos sobre a biodiversidade marinha do litoral de São Paulo</p>
                        </div>
                        <div class="card efeito-cards2">
                            <h2 style="font-family: Titulo;  font-size: 2rem;">Nosso Objetivo</h2>
                            <br>
                            <p style=" font-size: 1.3rem;">Temos o objetivo de educar e conscientizar sobre a vida marinha, além de realizar uma interação entre você e oceano.</p>
                        </div>
                        <!-- <div class="card efeito-cards3">
                            <h2>Salvar a Vida</h2>
                            <p></p>
                        </div> -->
                    </div>
                </div>
                <div class="texto-imagens">
                    <div class="texto efeito-texto">
                        <h2 style="font-family: 'Titulo'; letter-spacing: 3px; font-size: 3rem;">Biólogas do Projeto Martopia</h2>
                        <br> <br> <br> <br>
                        <p style="font-family: 'Texto'; letter-spacing: 2px;  font-size: 1.3rem;">
                            Todos os envolvidos no Projeto Martopia acreditam no poder da educação para transformar a forma como as novas gerações enxergam o meio ambiente.
                        </p>
                        <p style="font-family: 'Texto'; letter-spacing: 2px;  font-size: 1.3rem;">
                            Por isso, contamos com uma equipe de biólogos responsáveis por garantir que todo o conteúdo compartilhado seja confiável e atualizado, promovendo uma aprendizagem de qualidade.
                        </p>
                        <p style="font-family: 'Texto'; letter-spacing: 2px;  font-size: 1.3rem;">
                            Eles não apenas administram o sistema, mas também compartilham conteúdos educativos sobre a vida marinha, conteúdos de conscientização e interagem com os usuários respondendo suas dúvidas.
                        </p>
                    </div>
                    <div class="imagens efeito-imagem ">
                        <img src="./public/img/biom.png" alt="Bio 1">
                        <br>
                        <img src="./public/img/bio.png" alt="Bio 2">
                        <br>
                        <img src="./public/img/biologa3.png" alt="Bio 1" style="object-fit: cover;">
                    </div>

                </div>

                <div class="page-content">

                    <style>
                        header {
                            box-shadow: 0 40px 60px rgba(0, 0, 0, 0.2);
                        }

                        main {
                            background: #81C0E9;
                            background: radial-gradient(circle, #045a94dc 0%, rgba(129, 192, 233, 1) 50%, rgb(236, 251, 255) 100%);
                        }

                        .card-funcionalidades {
                            background: transparent;
                            filter: blur(.2px);
                            box-shadow: 0 0 15px 3px #81c0e9;
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            transition: transform 0.3s ease, box-shadow 0.3s ease;
                            height: 400px;
                            padding: 10px;
                            cursor: pointer;
                            text-align: center;
                            width: 250px;
                            border: 2px solid #c6e1fe;
                            border-radius: 8px;
                            margin: 20px;
                        }

                        .card-funcionalidades i {
                            font-size: 4rem;
                            margin-bottom: 1rem;
                            color: #c6e1fe;
                        }

                        .card-funcionalidades h2 {
                            color: #c6e1fe;
                            text-shadow: 2px 2px 4px rgba(0, 0, 0, .3);
                        }

                        .cards-container {
                            display: block;
                            justify-content: center;
                            gap: 20px;
                            padding: 20px 50px;
                            background: transparent;
                            border-radius: 20px;
                            box-shadow: 0 0 15px 3px #045a94;
                            width: 1420px;
                            max-width: 1700px;
                            transition: transform 0.3s ease, box-shadow 0.3s ease;
                        }

                        .cards-efeito {
                            visibility: hidden;
                        }

                        .cards-container h1 {
                            text-align: center;
                            color: #c6e1fe;
                            font-family: 'Titulo';
                            font-size: 2.5rem;
                            text-shadow: 2px 2px 4px rgba(0, 0, 0, .3);
                        }

                        .card-funcionalidades .saiba-mais {
                            color: #045a94;
                            font-family: 'Texto';
                        }

                        #span {
                            display: block;
                            width: 90%;
                            max-width: 835px;
                            margin: 2rem auto 0 auto;
                        }

                        .cards-container {
                            width: 100%;
                            max-width: 1420px;
                            margin: auto;
                            padding: 2rem 1rem;
                        }

                        /* GRID DESTES CARDS */
                        .dispos {
                            display: grid;
                            grid-template-columns: repeat(4, 1fr);
                            gap: 2rem;
                        }

                        .card {
                            cursor: pointer;
                        }

                        .card p {
                            font-size: 1.1em;
                        }

                        .cards {
                            display: grid;
                            grid-template-columns: repeat(2, 1fr);
                        }

                        /* Ajustando em telas menores */
                        @media (max-width: 1200px) {
                            .dispos {
                                grid-template-columns: repeat(2, 1fr);
                            }
                        }

                        @media (max-width: 700px) {
                            .dispos {
                                grid-template-columns: 1fr;
                            }

                            .card-funcionalidades {
                                width: 100%;
                                max-width: 350px;
                                margin: 0 auto;
                                height: auto;
                                padding: 2rem 1rem;
                            }
                        }

                        /* GRID DOS CARDS PRINCIPAIS */
                        .cards {
                            display: grid;
                            grid-template-columns: repeat(2, 1fr);
                            gap: 2rem;
                        }

                        /* Quando a tela diminuir — vira 1 coluna */
                        @media (max-width: 900px) {
                            .cards {
                                grid-template-columns: 1fr;
                                width: 100%;
                                padding: 0 1rem;
                            }

                            .card {
                                width: 100%;
                                margin: 0 auto;
                                padding: 1rem;
                            }

                            .card h2 {
                                font-size: 1.8rem;
                            }

                            .card p {
                                font-size: 1.2rem;
                            }
                        }

                        @media (max-width: 480px) {
                            .card {
                                padding: 1rem;
                                border-radius: 10px;
                            }

                            .card h2 {
                                font-size: 1.5rem;
                            }

                            .card p {
                                font-size: 1.05rem;
                            }
                        }
                    </style>


                    <div class="cards-container cards-efeito">

                        <h1 id="span" style=" font-size: 2.8rem;">Educação Interativa</h1>


                        <div class="dispos">


                            <div class="card-funcionalidades">

                                <i class="bi bi-instagram"></i>

                                <h2 style="font-family: 'Texto'; letter-spacing: 2px;">InstaMar - O Seu Dia a Dia Com o Oceano</h2>
                                <a href="../backend/login/login.php" class="saiba-mais" style=" font-size: 1.3rem;">SAIBA MAIS ↗</a>

                            </div>

                            <div class="card-funcionalidades">

                                <i class="bi bi-controller"></i>

                                <h2 style="font-family: 'Texto'; letter-spacing: 2px;">Jogos Temáticos de Vida Marinha</h2>
                                <a href="../backend/login/login.php" class="saiba-mais" style=" font-size: 1.3rem;">SAIBA MAIS ↗</a>
                            </div>

                            <div class="card-funcionalidades">

                                <i class="bi bi-file-earmark-richtext"></i>

                                <h2 style="font-family: 'Texto'; letter-spacing: 2px;">Conteúdos Educativos</h2>
                                <a href="../backend/login/login.php" class="saiba-mais" style=" font-size: 1.3rem;">SAIBA MAIS ↗</a>

                            </div>

                            <div class="card-funcionalidades">

                                <i class="bi bi-award"></i>

                                <h2 style="font-family: 'Texto'; letter-spacing: 2px;">Ranking de Usuários</h2>
                                <a href="../backend/login/login.php" class="saiba-mais" style=" font-size: 1.3rem;">SAIBA MAIS ↗</a>

                            </div>

                        </div>

                    </div>

                </div>
            </section>
        </main>
    </div>








    <footer style="background: #045a94;text-shadow: 2px 2px 4px rgba(0, 0, 0, .3);">

        <div class="contatos">
            <h3>Contatos</h3>
            <p>Email: contato@martopia.com.br</p>
            <p>Telefone: +55 11 99999-9999</p>
            <p>Endereço: Rua do Oceano, 123, São Paulo, SP</p>
        </div>

        <div class="redes">
            <h3>Redes Sociais</h3>
            <div>
                <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
            </div>
        </div>

        <div class="mapa">
            <h3>Localização</h3>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3666.2168153595317!2d-46.766872!3d-23.235196!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94cede166027baab%3A0x566fc4df5821546c!2sEscola%20T%C3%A9cnica%20Estadual%20de%20Campo%20Limpo%20Paulista!5e0!3m2!1spt-BR!2sbr!4v1756695006929!5m2!1spt-BR!2sbr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" aria-label="Mapa interativo"></iframe>
        </div>

        <div class="copyright">
            <p> &copy; 2025 Projeto Martopia. Todos os direitos reservados.</p>
        </div>
    </footer>


    <!-- SCRIPTS -->
    <script src="./js/parallax.js"></script>
    <script src="./js/scroll.js"></script>
    <!-- <script src="./js/slide.js" defer></script> -->

    <script>
        window.efeitoScroll = ScrollReveal({
            reset: false
        }) // reset false para rodar só uma vez

        //CARROSSEL

        efeitoScroll.reveal('.cards-efeito', {
            duration: 2000,
            distance: '90px'
        })
    </script>

</body>

</html>