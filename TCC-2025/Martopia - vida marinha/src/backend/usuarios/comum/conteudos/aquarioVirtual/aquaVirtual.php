<!DOCTYPE html>
<html lang="pt-br">

<?php
session_start();
include_once '../../../../classes/class_IRepositorioUsuarios.php';
$id = $_SESSION['id_usuario'];
// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);
// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aquário Virtual - Projeto Martopia</title>

    <link rel="stylesheet" href="./aquaVirtual.css">
    <link rel="stylesheet" href="../../../../../frontend/public/css/baseGeral.css">
    <link rel="stylesheet" href="../../../../../frontend/public/css/logo.css">
    <link rel="stylesheet" href="../../../../../frontend/public/css/footer.css">

    <!-- <link rel="stylesheet" href="../../../../../frontend/public/css/base.css">  -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <script defer src="../../../../../frontend/js/bolhas.js"></script>
    <script defer src="./aqua.js"></script>
</head>

<body>
    <style>
        .header {
            box-shadow: 0 40px 60px rgba(0, 0, 0, 0.2);
        }

        body {
            background: #045A94;
            background: radial-gradient(circle, rgba(4, 90, 148, 1) 0%, rgba(129, 192, 233, 1) 50%, #9fcaec);
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
        }

        /* Estilos da animação de scroll integrados */
        .gallery {
            position: relative;
            width: 100%;
            /* A altura total será definida dinamicamente pelo JS com base nas wrappers */
        }

        .image-wrapper {
            height: 850px;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Opcional: para separar visualmente */
            position: relative;
            overflow: hidden;
            /* Garante que imagens offscreen não sejam visíveis inicialmente */
        }

        .image {
            width: 700px;
            height: 750px;
            object-fit: cover;
            opacity: 0;
            /* Inicialmente invisível para "sem aparecer" */
            transition: opacity 0.3s ease;
            /* Fade in suave quando entra */
        }

        .image.visible {
            opacity: 1;
            /* Torna visível quando começa a animação */
        }

        /* Para telas menores, ajuste se necessário */
        @media (max-width: 600px) {
            .image {
                width: 200px;
                height: 200px;
            }

            .image-wrapper {
                height: 300px;
            }
        }

        /* CSS do fundo com bolhas (integrado) */
        .ocean-bottom {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: linear-gradient(to bottom, #c6e1fe, #81c0e9, #38a0dd);
            overflow: hidden;
            z-index: -1;
        }

        .bubble {
            position: absolute;
            bottom: -100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            animation: bolhas linear infinite;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        @keyframes bolhas {
            from {
                transform: translateY(0);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            to {
                transform: translateY(-110vh);
                opacity: 0;
            }
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            position: relative;
            text-align: center;
        }

        .modal-content img {
            margin-bottom: 15px;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .modal-content h2 {
            margin-top: 0;
        }

        .modal-content p {
            font-size: 16px;
            line-height: 1.5;
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #ff4757;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            font-size: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            transform: rotate(90deg);
            background: #ff6b81;
        }


        .learn-btn {
            padding: 10px 20px;
            font-family: 'Texto';
            background-color: #81c0e9;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.3rem;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .learn-btn:hover {
            background-color: #38a0dd;
        }

        .bots {
            position: fixed;
            right: clamp(14px, 3vw, 36px);
            bottom: clamp(16px, 4vw, 40px);
            display: flex;
            flex-direction: column;
            gap: 18px;
            z-index: 10;
        }

        .bot {
            width: 82px;
            height: 82px;
            border-radius: 50%;
            background: linear-gradient(145deg, #e18451, #f5e1ce);
            display: grid;
            place-items: center;
            color: #fff;
            text-decoration: none;
            font-size: 46px;
            font-weight: 700;
            box-shadow: 0 10px 20px #00000025;
            transition: transform .15s ease, filter .15s ease;
        }

        .bot.seta {
            width: 72px;
            height: 72px;
            position: relative;
            border: 3px solid #e18451;
            color: #fff;
        }

        .bot:hover {
            transform: translateY(-2px) scale(1.02);
            filter: brightness(1.05);
        }


        .bot.seta::before {
            content: "";
            position: absolute;
            inset: 5px;
            border-radius: 50%;
        }

        .bot.seta span {
            position: relative;
            font-size: 36px;
            line-height: 1;
            color: #fff;
        }

        .navbar a {
            font-size: 1.3rem;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, .3);
        }

        h2#inicio {
            position: absolute;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        #texto_in {
            position: absolute;
            top: 55%;
            left: 50%;
            padding-top: 2%;
        }

        .navbar a {
            font-size: 1.5rem;
        }

        .perfil {
            width: 80px;
            height: 80px;
            margin-left: -3rem;
            border: 1.5px solid #e18451;
            /* color: #81c0e9; */
        }

        .header {
            left: 0;
            width: 100%;
            padding: 1.6rem 1rem;
        }

        nav a.active {
            color: #c6e1fe;
            font-weight: bold;
            text-shadow: 0px 3px 6px #045a94;
        }
    </style>

    <!-- NAVBAR  -->
    <header class="header">
        <div class="logo-marca" style="margin-left: -3rem;">
            <a href="./home.php" class="logo"><img src="../../../../../frontend/public/img/Logo.png" alt="Logo-Projeto Martopia"></a>
            <p style="margin-left: -3rem;">Projeto <br> Martopia</p>
        </div>

        <input type="checkbox" id="check">
        <label for="check" class="icone">
            <i class="bi bi-list" id="menu-icone"></i>
            <i class="bi bi-x" id="sair-icone"></i>
        </label>

        <nav class="navbar">
            <a href="../../homeUsuario.php" style="--i:1;">Home</a>
            <a href="../../instamar/instamar.php" style="--i:1;">InstaMar</a>
            <a href="../../jogos/jogos.php" style="--i:2;">Jogos</a>
            <a href="../../conteudos/conteudo.php" style="--i:3;" class="active">Conteúdos Educativos</a>
            <a href="../../../../trocar/trocarperfil.php"><img src="../../../../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil" class="perfil" style="--i:4;"></a>
        </nav>
    </header>

    <div id="top"></div>


    <!-- CONTEÚDO  -->
    <svg id="onda" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 318">
        <path fill="#045a94" fill-opacity="1" d="M0,192L40,197.3C80,203,160,213,240,186.7C320,160,400,96,480,101.3C560,107,640,181,720,224C800,267,880,277,960,245.3C1040,213,1120,139,1200,106.7C1280,75,1360,85,1400,90.7L1440,96L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z"></path>
    </svg>


    <h1 id="inicio">Bem-Vindo ao Aquário Virtual Martopia! </h1>
    <p id="texto_in" style="padding-top: 2%;">Se prepare para um passeio virtual pela vida marinha de São Paulo. <br> Clique nos animais e conheça sobre cada um.</p>

    <section class="ocean-bottom" id="ocean-bottom"></section>


    <div class="page-content">

        <!-- Galeria de imagens com animação de scroll integrada aqui -->
        <div id="gallery" class="gallery">

        </div>

        <div class="bots">
            <a class="bot seta" href="#top" aria-label="Voltar ao topo" style="width: 80px; height: 80px;">
                <span><i class="bi bi-arrow-up" style="font-size:2.8rem;"></i></span>
            </a>
        </div>

    </div>


    <!-- FOOTER   -->
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
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3666.2168153595317!2d-46.766872!3d-23.235196!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94cede166027baab%3A0x566fc4df5821546c!2sEscola%20T%C3%A9cnica%20Estadual%20de%20Campo%20Limpo%20Paulista!5e0!3m2!1spt-BR!2sbr!4v1756695006929!5m2!1spt-BR!2sbr                allowfullscreen="" loading=" lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Mapa do local">
            </iframe>
        </div>

        <div class="copyright">
            &copy; 2025 Projeto Martopia. Todos os direitos reservados.
        </div>
    </footer>





    <!-- JS da animação de scroll integrado -->
    <script>
        const gallery = document.getElementById('gallery');
        const wrapperHeight = 850;
        const moveDistance = 500;

        // 🐠 Aqui você adiciona suas imagens e descrições:
        const animais = [{
                imgGaleria: "./img_Aqua/img01Aqua.png",
                imgModal: "./img_Aqua/cart01Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img02Aqua.png",
                imgModal: "./img_Aqua/cart02Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img03Aqua.png",
                imgModal: "./img_Aqua/cart03Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img04Aqua.png",
                imgModal: "./img_Aqua/cart04Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img05Aqua.png",
                imgModal: "./img_Aqua/cart05Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img06Aqua.png",
                imgModal: "./img_Aqua/cart06Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img07Aqua.png",
                imgModal: "./img_Aqua/cart07Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img08Aqua.png",
                imgModal: "./img_Aqua/cart08Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img09Aqua.png",
                imgModal: "./img_Aqua/cart09Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img010Aqua.png",
                imgModal: "./img_Aqua/cart010Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img012Aqua.png",
                imgModal: "./img_Aqua/cart012Aqua.jpeg"
            },
            {
                imgGaleria: "./img_Aqua/img013Aqua.png",
                imgModal: "./img_Aqua/cart013Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img014Aqua.png",
                imgModal: "./img_Aqua/cart014Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img015Aqua.png",
                imgModal: "./img_Aqua/cart015Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img016Aqua.png",
                imgModal: "./img_Aqua/cart016Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img017Aqua.png",
                imgModal: "./img_Aqua/cart017Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img018Aqua.png",
                imgModal: "./img_Aqua/cart018Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img019Aqua.png",
                imgModal: "./img_Aqua/cart019Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img020Aqua.png",
                imgModal: "./img_Aqua/cart020Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img021Aqua.png",
                imgModal: "./img_Aqua/cart021Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img022Aqua.png",
                imgModal: "./img_Aqua/cart022Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img023Aqua.png",
                imgModal: "./img_Aqua/cart023Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img024Aqua.png",
                imgModal: "./img_Aqua/cart024Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img025Aqua.png",
                imgModal: "./img_Aqua/cart025Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img026Aqua.png",
                imgModal: "./img_Aqua/cart026Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img027Aqua.png",
                imgModal: "./img_Aqua/cart027Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img028Aqua.png",
                imgModal: "./img_Aqua/cart028Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img029Aqua.png",
                imgModal: "./img_Aqua/cart029Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img030Aqua.png",
                imgModal: "./img_Aqua/cart030Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img031Aqua.png",
                imgModal: "./img_Aqua/cart031Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img032Aqua.png",
                imgModal: "./img_Aqua/cart032Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img033Aqua.png",
                imgModal: "./img_Aqua/cart033Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img034Aqua.png",
                imgModal: "./img_Aqua/cart034Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img035Aqua.png",
                imgModal: "./img_Aqua/cart035Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img036Aqua.png",
                imgModal: "./img_Aqua/cart036Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img037Aqua.png",
                imgModal: "./img_Aqua/cart037Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img038Aqua.png",
                imgModal: "./img_Aqua/cart038Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img039Aqua.png",
                imgModal: "./img_Aqua/cart039Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img040Aqua.png",
                imgModal: "./img_Aqua/cart040Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img041Aqua.png",
                imgModal: "./img_Aqua/cart041Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img042Aqua.png",
                imgModal: "./img_Aqua/cart042Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img043Aqua.png",
                imgModal: "./img_Aqua/cart043Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img044Aqua.png",
                imgModal: "./img_Aqua/cart044Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img045Aqua.png",
                imgModal: "./img_Aqua/cart045Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img046Aqua.png",
                imgModal: "./img_Aqua/cart046Aqua.png"
            },
            {
                imgGaleria: "./img_Aqua/img047Aqua.png",
                imgModal: "./img_Aqua/cart047Aqua.png"
            },
        ];

        // Funções de posicionamento
        function getFinalPosition(index) {
            const offsets = [-60, -40, -20, 0, 20, 40, 60, 40, 20, 0];
            return `${offsets[index % offsets.length]}px`;
        }

        function getInitialDirection(index) {
            return index % 2 === 0 ? 'right' : 'left';
        }



        animais.forEach((animal, i) => {
            const wrapper = document.createElement('div');
            wrapper.classList.add('image-wrapper');

            // Atributos para animação
            wrapper.dataset.index = i;
            wrapper.dataset.startScroll = (i * wrapperHeight).toString();
            wrapper.dataset.endScroll = ((i * wrapperHeight) + moveDistance).toString();
            wrapper.dataset.finalPosition = getFinalPosition(i);
            wrapper.dataset.direction = getInitialDirection(i);

            const img = document.createElement('img');
            img.classList.add('image');
            img.src = animal.imgGaleria;
            img.alt = animal.nome || 'Animal';
            img.style.cursor = "pointer";




            // Criação do modal
            const modal = document.createElement('div');
            modal.classList.add('modal');

            modal.innerHTML = `
    <div class="modal-content">
        <button class="close-btn"><i class="bi bi-x"></i></button>
        <h2>${animal.nome || 'Animal'}</h2>
        <img src="${animal.imgModal || animal.imgGaleria}" alt="${animal.nome || 'Animal'}" style="width:100%; border-radius:8px; margin-bottom: 15px;">
        <p>${animal.descricao || 'Informações sobre este animal.'}</p>
        <button class="learn-btn">Eu aprendi com Martopia</button>
    </div>
`;


            document.body.appendChild(modal);

            img.addEventListener('click', () => {
                modal.style.display = 'block';
            });
            modal.querySelector('.close-btn').addEventListener('click', () => {
                modal.style.display = 'none';
            });

            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.style.display = 'none';
            });

            modal.querySelector('.learn-btn').addEventListener('click', () => {
                alert(`Parabéns! Você aprendeu sobre ${animal.nome || 'este animal'}!`);
                modal.style.display = 'none';
                modal.style.fontFamily = 'Texto'
            });

            const caption = document.createElement('p');
            caption.style.position = "absolute";
            caption.style.bottom = "15px";
            caption.style.padding = "6px 12px";
            caption.textContent = animal.nome || '';

            wrapper.appendChild(img);
            wrapper.appendChild(caption);
            gallery.appendChild(wrapper);
        });




        // Função para animação no scroll
        function updateAnimations() {
            const scrollY = window.pageYOffset || document.documentElement.scrollTop;
            const wrappers = document.querySelectorAll('.image-wrapper');

            wrappers.forEach(wrapper => {
                const index = parseInt(wrapper.dataset.index);
                const startScroll = parseFloat(wrapper.dataset.startScroll);
                const endScroll = parseFloat(wrapper.dataset.endScroll);
                const finalPosition = wrapper.dataset.finalPosition;
                const direction = wrapper.dataset.direction;
                const imgGaleria = wrapper.querySelector('.image');

                let translateX = direction === 'right' ? '50vw' : '-50vw'; // Inicial: offscreen à direita ou esquerda
                let isVisible = false;

                if (scrollY < startScroll) {
                    // Antes do trigger: offscreen na direção inicial e invisível
                    translateX = direction === 'right' ? '50vw' : '-50vw';
                    isVisible = false;
                } else if (scrollY > endScroll) {
                    // Depois do trigger: parado na posição final variada (próxima ao centro, mas diferente)
                    translateX = finalPosition;
                    isVisible = true;
                } else {
                    // Durante o movimento: interpola da posição inicial para a posição final
                    const progress = (scrollY - startScroll) / (endScroll - startScroll);
                    const approxViewportWidth = window.innerWidth;
                    const initialX = direction === 'right' ? (50 / 100) * approxViewportWidth : -(50 / 100) * approxViewportWidth;
                    const finalX = parseFloat(finalPosition);
                    translateX = `${initialX + (finalX - initialX) * progress}px`;
                    isVisible = true;
                }

                imgGaleria.style.transform = `translateX(${translateX})`;
                if (isVisible) {
                    imgGaleria.classList.add('visible');
                } else {
                    imgGaleria.classList.remove('visible');
                }
            });
        }

        // Listener de scroll (throttled para melhor performance)
        let ticking = false;

        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateAnimations);
                ticking = true;
            }
        }
        window.addEventListener('scroll', () => {
            requestTick();
            ticking = false;
        });

        // Chamar inicialmente para definir posições
        updateAnimations();

        // Opcional: Smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';
    </script>




    <!-- JS do fundo com bolhas (integrado inline, mas você pode mover para bolhas.js se preferir) -->
    <script>
        function createBubble(containerId) {
            const container = document.getElementById(containerId);
            const maxBubbles = 30;

            if (container.children.length >= maxBubbles) {
                return;
            }

            const bubble = document.createElement("div");
            bubble.classList.add("bubble");
            const size = Math.random() * 30 + 10 + "px";
            bubble.style.width = size;
            bubble.style.height = size;
            bubble.style.left = Math.random() * window.innerWidth + "px";
            bubble.style.bottom = "-" + size;
            bubble.style.animationDuration = Math.random() * 10 + 5 + "s";

            container.appendChild(bubble);

            setTimeout(() => {
                bubble.remove();
            }, 15000);
        }

        setInterval(() => {
            createBubble('ocean-bottom');
        }, 300);
    </script>



</body>

</html>