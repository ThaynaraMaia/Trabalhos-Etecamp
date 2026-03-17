<!DOCTYPE html>
<html lang="pt-br">

<?php
session_start();
include_once '../../../classes/class_IRepositorioUsuarios.php';
$id = $_SESSION['id_usuario'];
// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);
$post = $respositorioUsuario->listarTodasPostagens($id);
// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Conteúdos - Projeto Martopia</title>

    <link rel="stylesheet" href="meus_cont.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/base.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/instamar.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/footer.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

</head>

<body>

    <!-- NAVBAR  -->
    <header class="header">
        <style>
            @font-face {
                font-family: 'Titulo';
                src: url('../../../../frontend/fontes/Título.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
            }

            @font-face {
                font-family: 'Texto';
                src: url('../../../../frontend/fontes/Texto.otf') format('truetype');
                font-weight: normal;
                font-style: normal;
            }

            .header {
                height: 120px;
                box-shadow: 0 40px 60px rgba(0, 0, 0, 0.2);
                font-family: 'Texto';
            }

            body {
                background: #c6e1fe;
                background: linear-gradient(132deg, #9fcaec 0%, #81c0e9 50%, #045a94 100%);
            }

            /* 
            ===================
                3PONTOS
            =====================
            */
            .pontos {
                position: relative;
                display: inline-block;
            }

            .menu-btn {
                background: none;
                border: none;
                cursor: pointer;
            }

            .menu-dropdown {
                display: none;
                position: absolute;
                right: 0;
                top: 25px;
                background: white;
                border: 1px solid #ccc;
                border-radius: 8px;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
                min-width: 150px;
                z-index: 100;
            }

            .menu-dropdown a {
                display: block;
                padding: 10px;
                text-decoration: none;
                color: black;
                font-size: 14px;
            }

            .menu-dropdown a:hover {
                background: #f0f0f0;
            }

            h2#inicio {
                position: absolute;
                top: 33%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            #texto_in {
                position: absolute;
                top: 40%;
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

        <div class="logo-marca" style="margin-left: -3rem;">
            <a href="./home.php" class="logo"><img src="../../../../frontend/public/img/Logo.png" alt="Logo-Projeto Martopia"></a>
            <p style="margin-left: -3rem;">Projeto <br> Martopia</p>
        </div>

        <input type="checkbox" id="check">
        <label for="check" class="icone">
            <i class="bi bi-list" id="menu-icone"></i>
            <i class="bi bi-x" id="sair-icone"></i>
        </label>

        <nav class="navbar">
            <a href="../homeUsuario.php" style="--i:1;">Home</a>
            <a href="./instamar.php" style="--i:1;" class="active">InstaMar</a>
            <a href="../jogos/jogos.php" style="--i:2;">Jogos</a>
            <a href="../conteudos/conteudo.php" style="--i:3; ">Conteúdos Educativos</a>
            <a href="../../../trocar/trocarperfil.php"><img src="../../../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil" class="perfil"></a>
        </nav>
    </header>

    <div class="page-content">
        <div class="perfil_card">
            <div class="perfil-user">
                <img src="../../../../<?php echo htmlspecialchars($foto); ?>" alt="Imagem de Peril" id="img-perfil" style="margin-left: -2rem;">
                <div class="perfil-btn">
                    <h2 style="font-family: 'Texto';"> <?php echo $_SESSION['nome']; ?> </h2>
                    <br>
                    <a href="./instamar.php" class="botao" style="margin-left:42px; font-family: 'Texto'; font-size: 1.4rem;"> Voltar </a>
                </div>
            </div>
        </div>

        <?php
        while ($linhas = $post->fetch_object()) {
            if ($linhas->imagem_post) {
        ?>
                <div class="post-card">
                    <div class="post-header">
                        <div class="user-avatar" style="width: 60px; height: 60px;">
                            <img src="../../../../<?php echo htmlspecialchars($foto); ?>" alt="Imagem da sua postagem" id="img-perfil">
                        </div>

                        <div class="user-info">
                            <div class="user-name" style="font-size: 1.2rem;"> <?php echo $_SESSION['nome']; ?> </div>
                            <div class="post-date" style="font-size: 1rem;"><?php echo $linhas->data_postagem ?> </div>
                        </div>

                        <!-- Menu de três pontos para excluir postagem -->
                        <div class="pontos">
                            <button class="menu-btn" onclick="toggleMenu(this)">
                                <img src="../../../../frontend/public/img/pontos.png" width="30px" alt="">
                            </button>

                            <!-- MENU DROPDOWN -->
                            <div class="menu-dropdown">
                                <a href="excluirPost.php?excluir=<?php echo $linhas->id; ?>" onclick="return confirm('Tem certeza que deseja excluir este post?')" style="font-size: 1.1rem; font-family: 'Texto';">❌ Excluir</a>
                            </div>
                        </div>
                    </div>

                    <!-- IMAGEM DO POST-->
                    <img src="../../../../frontend/public/img_instamar/<?php echo $linhas->imagem_post ?>" alt="Imagem do Post" class="post-image" style="object-fit: contain;">

                    <!-- CURTIR E COMENTAR -->
                    <div class="post-actions">
                        <!-- Contador de curtidas dinâmico -->
                        <div class="likes-count" id="curtidas-<?php echo $linhas->id; ?>" style="color: red;">
                            <?php echo $linhas->total_curtidas ?? 0; ?> <i class="bi bi-heart"  style="font-size: 1.8rem; font-family: 'Texto';"></i>
                        </div>

                        <!-- Botão de comentar com contador -->
                        <button class="action-btn">
                            <div class="likes-count" id="comentarios-<?php echo $linhas->id; ?>" style="color:#045a94;">
                                <?php echo $linhas->total_comentarios ?? 0; ?>
                                <i class="bi bi-chat"  style="font-size: 1.8rem; font-family: 'Texto';"></i>
                                
                            </div>
                        </button>
                    </div>

                    <!-- LEGENDA DO POST -->
                    <div class="post-caption" style="font-size: 1.3rem;">
                        <div class="caption-text collapsed" id="caption-text" style="font-size: 1.1rem; font-family: 'Texto';">
                            <?php echo $linhas->legenda ?>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="post-card 2" aria-label="Postagem de texto do usuário">
                    <div class="post-header">
                        <div class="user-avatar" style="width: 60px; height: 60px;">
                            <img src="../../../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de perfil do Usuário" />
                        </div>
                        <div class="user-info">
                            <div class="user-name" style="font-size: 1.2rem;"> <?php echo $_SESSION['nome']; ?> </div>
                            <time class="post-date" datetime="2024-06-15" style="font-size: 1rem;"><?php echo $linhas->data_postagem ?></time>
                        </div>

                        <!-- Menu de três pontos para excluir postagem -->
                        <div class="pontos">
                            <button class="menu-btn" onclick="toggleMenu(this)">
                                <img src="../../../../frontend/public/img/pontos.png" width="30px" alt="">
                            </button>

                            <!-- MENU DROPDOWN -->
                            <div class="menu-dropdown">
                                <a href="excluirPost.php?excluir=<?php echo $linhas->id; ?>" onclick="return confirm('Tem certeza que deseja excluir este post?')" style="font-size: 1.1rem; font-family: 'Texto';">❌ Excluir</a>
                            </div>
                        </div>
                    </div>

                    <div class="post-text collapsed" id="post-text" style="font-size: 1.2rem; font-family: 'Texto';">
                        <?php echo $linhas->legenda ?>
                    </div>

                    <div class="post-actions">
                        <!-- Contador de curtidas dinâmico -->
                        <div class="likes-count" id="curtidas-<?php echo $linhas->id; ?>">
                            <?php echo $linhas->total_curtidas ?? 0; ?> <i class="bi bi-heart"></i>
                        </div>

                        <!-- Contador de comentários dinâmico -->
                        <div class="likes-count" id="comentarios-<?php echo $linhas->id; ?>">
                            <?php echo $linhas->total_comentarios ?? 0; ?> <i class="bi bi-chat"></i>
                        </div>
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>

    <!-- FOOTER  -->
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

    <script src="./instamarJS/pontos.js"></script>
</body>

</html>