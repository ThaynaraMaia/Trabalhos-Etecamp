<?php
$mensagem = "";
session_start();

if (!$_SESSION['tipo']  && !$_SESSION['logado']) {
    header('Location:../../../../frontend/home.php');
}

if ($_SESSION) {
    $mensagem = $_SESSION['mensagem'];
} else {
    $mensagem = "";
}

include_once '../../../classes/class_IRepositorioUsuarios.php';

$id = $_SESSION['id_usuario'];
// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);
// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';

$listar = $respositorioUsuario->MostrarTodasPostagem();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstaMar - Projeto Martopia</title>

    <link rel="stylesheet" href="../../../../frontend/public/css/instamar.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/baseGeral.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/footer.css">
    <link rel="stylesheet" href="modal-coment.css">

    <script defer src="./instamarJS/coment.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

</head>

<body>

    <style>
        body {
            background: #c6e1fe;
            background: linear-gradient(132deg, #9fcaec 0%, #81c0e9 50%, #045a94 100%);
        }

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

        .bem-vindo {
            font-size: 3rem;
            letter-spacing: 3px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, .3);
            font-family: 'Titulo';
        }

        .text-bv {
            font-size: 1.3rem;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, .3);
            font-family: 'Texto';
        }

        .header {
            box-shadow: 0 40px 60px rgba(0, 0, 0, 0.2);
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

        .submit-btn {
            background: #e18451;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px #efb68dff;
        }

        /* 
===================
    final 3 PONTOS
=====================
*/
    </style>

    <script>
        // Define variável global acessível pelo JavaScript
        var userId = <?php echo isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0; ?>;
    </script>

    <svg id="onda" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 318">
        <path fill="#045a94" fill-opacity="1" d="M0,192L40,197.3C80,203,160,213,240,186.7C320,160,400,96,480,101.3C560,107,640,181,720,224C800,267,880,277,960,245.3C1040,213,1120,139,1200,106.7C1280,75,1360,85,1400,90.7L1440,96L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z"></path>
    </svg>


    <header class="header">

        <div class="logo-marca" style="margin-left: -3rem;">
            <a href="./home.php" class="logo"><img src="../../../../frontend/public/img/Logo.png" alt="Logo-Projeto Martopia"></a>
            <p style="margin-left: -3rem;">Projeto <br> Martopia</p>
        </div>


        <input type="checkbox" id="check">
        <label for="check" class="icone">
            <i class="bi bi-list" id="menu-icone"></i>
            <i class="bi bi-x" id="sair-icone"></i>
        </label>


        <nav>
            <a href="../../../trocar/trocarperfil.php"><img src="../../../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil" class="perfil" style="--i:4;"></a>
        </nav>
    </header>


    <div id="top"></div>



    <div class="page-content">

        <h2 id="inicio"> Bem -Vindo ao Instamar</h2>
        <p id="texto_in" style="padding-top: 2%; width: 1200px;">Um espaço de interação, conhecimento e criativiadade para o seu dia a dia com o oceano!</p>

        <div class="perfil_card">

            <div class="perfil-user">

                <img src="../../../../<?php echo htmlspecialchars($foto); ?>" alt="Imagem de Peril" id="img-perfil" style="margin-left: -2rem;">

                <div class="perfil-btn">
                    <h2 style="font-family: 'Texto'; font-weight: bold; margin-left: -2rem;"> <?php echo $_SESSION['nome']; ?> </h2>
                    <br>
                    <a href="./meus_cont.php" class="botao" style="background: #e18451; font-size: 1.4rem;"> Minhas Postagens </a>
                </div>

            </div>


        </div>

        <?php
        while ($linhas = $listar->fetch_object()) {
            if ($linhas->imagem_post) {
        ?>
                <div class="post-card">

                    <div class="post-header">

                        <div class="user-avatar" style="width: 60px; height: 60px;">
                            <img src="../../../../<?php echo $linhas->foto_usuario ? $linhas->foto_usuario : 'frontend/public/img/fotoperfil.png'; ?>" alt="Imagem de Peril" id="img-perfil">
                        </div>

                        <div class="user-info">
                            <div class="user-name" style="font-size: 1.2rem;"> <?php echo $linhas->autor; ?> </div>
                            <div class="post-date" style="font-size: 1rem;"> <?php echo $linhas->data_postagem; ?> </div>
                        </div>

                        <div class="pontos">
                            <button class="menu-btn" onclick="toggleMenu(this)">
                                <img src="../../../../frontend/public/img/pontos.png" width="30px" alt="">
                            </button>

                            <!-- MENU DROPDOWN -->
                            <div class="menu-dropdown">
                                <a href="../../../Perfil/perfil.php?id=<?php echo $linhas->id_usuario_post; ?>" style="font-size: 1.1rem; font-family: 'Texto';">👤 Ver perfil</a>
                                <a href="excluirPost.php?excluir=<?php echo $linhas->id; ?>" onclick="return confirm('Tem certeza que deseja excluir este post?')" style="font-size: 1.1rem; font-family: 'Texto';">❌ Excluir</a>
                            </div>
                        </div>


                    </div>

                    <!-- IMAGEM DO POST-->

                    <img src="../../../../frontend/public/img_instamar/<?php echo $linhas->imagem_post; ?>" alt="Imagem do Post" class="post-image" style="object-fit: contain;">

                    <!-- NUMERO DE CURTIDAS  -->
                    <div class="post-stats">
                        <!-- <div class="likes-count"> 0 curtidas</div> -->
                        <div class="likes-count" id="curtidas-<?php echo $linhas->id; ?>" style="font-size: 1.1rem;">
                            <?php echo $linhas->total_curtidas ?? 0; ?> curtidas
                        </div>
                    </div>

                    <!-- CURTIR E COMENTAR -->
                    <div class="post-actions">
                        <!-- 
                <button class="action-btn btn-curtir ">
                    <i class="bi bi-heart"></i>
                    <span>Curtir</span> -->
                        <!-- </button> -->
                        <button class="action-btn btn-curtir <?php echo ($linhas->usuario_curtiu == 1) ? 'ativo' : ''; ?>"
                            data-id="<?php echo $linhas->id; ?>" style="color: red;">
                            <i class="bi bi-heart<?php echo ($linhas->usuario_curtiu == 1) ? '-fill' : ''; ?>"></i>
                            <span style="font-size: 1.1rem; font-family: 'Texto';"><?php echo ($linhas->usuario_curtiu == 1) ? 'Descurtir' : 'Curtir'; ?></span>
                        </button>

                        <button onclick="abrirModal(<?php echo $linhas->id; ?>)" class="action-btn" style="color:#045a94;">
                            <i class="bi bi-chat"></i>
                            <span style="font-size: 1.1rem; font-family: 'Texto';">Comentar</span>
                        </button>
                    </div>

                    <!-- LEGENDA DO POST -->

                    <div class="post-caption" style="font-size: 1.3rem;">

                        <div class="caption-text collapsed" id="caption-text" style="font-size: 1.1rem; font-family: 'Texto';">
                            <?php echo $linhas->legenda; ?>
                        </div>


                    </div>
                </div>
            <?php
            } else {
            ?>
                <div class="post-card 2" aria-label="Postagem de texto do usuário">

                    <div class="post-header">
                        <div class="user-avatar" style="width: 60px; height: 60px;">
                            <img src="../../../../<?php echo $linhas->foto_usuario ? $linhas->foto_usuario : 'frontend/public/img/fotoperfil.png'; ?>" alt="Foto de perfil do Usuário" />
                        </div>
                        <div class="user-info">
                            <div class="user-name" style="font-size: 1.2rem;"> <?php echo $linhas->autor; ?> </div>
                            <time class="post-date" datetime="2024-06-15" style="font-size: 1rem;"><?php echo $linhas->data_postagem; ?></time>
                        </div>

                        <div class="pontos">
                            <button class="menu-btn" onclick="toggleMenu(this)">
                                <img src="../../../../frontend/public/img/pontos.png" width="30px" alt="">
                            </button>

                            <!-- MENU DROPDOWN -->
                            <div class="menu-dropdown">
                                <a href="../../../Perfil/perfil.php?id=<?php echo $linhas->id_usuario_post; ?>" style="font-size: 1.1rem; font-family: 'Texto';">👤 Ver perfil</a>
                                <a href="excluirPost.php?excluir=<?php echo $linhas->id; ?>" onclick="return confirm('Tem certeza que deseja excluir este post?')" style="font-size: 1.1rem; font-family: 'Texto';">❌ Excluir</a>
                            </div>
                        </div>
                    </div>

                    <div class="post-text collapsed" id="post-text">
                        <p style="font-size: 1.2rem; font-family: 'Texto';"><?php echo $linhas->legenda; ?></p>
                    </div>

                    <div class="post-actions">

                        <button class="action-btn btn-curtir <?php echo ($linhas->usuario_curtiu == 1) ? 'ativo' : ''; ?>"
                            data-id="<?php echo $linhas->id; ?>" style="color: red;">
                            <i class="bi bi-heart<?php echo ($linhas->usuario_curtiu == 1) ? '-fill' : ''; ?>"></i>
                            <span style="font-size: 1.1rem; font-family: 'Texto';"><?php echo ($linhas->usuario_curtiu == 1) ? 'Descurtir' : 'Curtir'; ?></span>
                        </button>

                        <button onclick="abrirModal(<?php echo $linhas->id; ?>)" class="action-btn" style="color:#045a94;">
                            <i class="bi bi-chat"></i>
                            <span style="font-size: 1.1rem; font-family: 'Texto';">Comentar</span>
                        </button>


                        <!-- MODAL DE COMENTÁRIOS -->
                        <div class="modal" id="comentarios-modal">
                            <div class="modal-content">

                                <button class="close-btn" onclick="closeModal('comentarios-modal')" style="font-size: 1.9rem;">
                                    <i class="bi bi-x"></i>
                                </button>

                                <div class="modal-header">
                                    <i class="bi bi-chat" style="color: #38a0dd;"></i>
                                    <h2 style="color: #045a94;">Comentários</h2>
                                </div>

                                <div class="modal-body" style=" flex-direction: column; height: 100%; max-height:400px">

                                    <!-- Área de comentários existentes -->
                                    <div style="max-height: 300px; overflow-y: auto; padding: 10px; font-size: 1.1rem" class="comentarios-list" id="lista-comentarios">
                                    </div>


                                    <br> <br>


                                    <!-- Formulário para novo comentário -->
                                    <div class="novo-comentario" style="border-top: 1px solid #ddd; padding: 10px; background: #fff; margin-top:6rem;">
                                        <div class="comentario-input-container" style="display: flex; align-items: center; gap: 10px;">
                                            <div class="user-avatar" style=" width: 60px; height: 60px;">
                                                <img src="../../../../<?php echo htmlspecialchars($foto); ?>" style="display: inline;" alt="Seu perfil" class="comentario-avatar" />
                                            </div>

                                            <div class="comentario-input-wrapper" style="flex: 1; display: flex; align-items: center; gap: 8px;">

                                                <input type="hidden" id="post-id-comentario" value="" style="width: 80%; padding: 8px; border-radius: 8px; border: 1px solid #ccc; display:inline; font-size: 1.2rem;">

                                                <input type="text" id="texto-comentario" placeholder="Escreva seu comentário..." style="flex: 1; padding: 8px; border-radius: 8px; border: 1px solid #ccc; font-size: 1.2rem">

                                                <button type="button" class="comentario-enviar" id="btn-enviar-comentario" style="padding: 14px 16px; border-radius: 8px; background-color: #e18451; color: white; border: none; cursor: pointer;">

                                                    <i class="bi bi-send" style="font-size: 1.3rem;"></i>

                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="likes-count" id="likes-count">0 curtidas</div> -->
                    <div class="likes-count" id="curtidas-<?php echo $linhas->id; ?>">
                        <?php echo $linhas->total_curtidas ?? 0; ?> curtidas
                    </div>

                </div>
        <?php
            }
        }
        ?>

    </div>



    <!-- BOTÃO PARA POSTAR  -->

    <div class="bots">

        <button class="bot-btn" id="bot-btn" style="width: 80px; height: 80px; border: 3px solid #e18451;">
            <i class="bi bi-plus" style="font-size: 3.8rem;"></i>
        </button>

        <div class="bot-options" id="bot-options">

            <button class="bot-option" id="camera-btn" style="width: 70px; height: 70px;">
                <i class="bi bi-camera" style="font-size:2rem;"></i>
            </button>

            <button class="bot-option" id="text-btn" style="width: 70px; height: 70px;">
                <i class="bi bi-card-text" style="font-size:2rem;"></i>
            </button>

        </div>

        <a class="bot seta" href="#top" aria-label="Voltar ao topo" style="width: 80px; height: 80px;">
            <span><i class="bi bi-arrow-up" style="font-size:2.8rem;"></i></span>
        </a>

    </div>

    <!-- MODAL PARA POSTAR FOTOS -->

    <div class="modal" id="camera-modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal('camera-modal')">
                <i class="bi bi-x" style="font-size: 2rem;"></i>
            </button>

            <div class="modal-header">
                <i class="bi bi-camera" style="color: #38a0dd;"></i>
                <br> <br>
                <h2 style="color: #045a94; font-family:'Texto'; ">Poste suas Fotos</h2>
            </div>

            <form action="novoPost.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="file-upload" id="file-upload-area">
                        <i class="bi bi-cloud-upload"></i>
                        <p>Clique ou arraste arquivos para enviar</p>
                        <p class="text-sm text-gray-500 mt-2">Formatos suportados: JPG, PNG</p>
                        <input type="file" name="foto" id="file-input" accept="image/*" multiple onchange="handleFileSelect(event)">
                    </div>
                    <div class="preview-container" id="preview-container">
                        <p class="text-sm font-semibold mb-2" style="font-family: 'Texto'; font-size: 1.3rem;">Pré-visualização:</p>
                        <div id="image-previews"></div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="photo-description" style="font-family: 'Texto'; font-size: 1.3rem;">Legenda</label>
                        <textarea id="photo-description" name="legenda" placeholder="Adicione uma leganda para sua foto..."></textarea>
                    </div>

                    <style>
                        .submit-btn {
                            background-color: #e18451;
                            font-family: 'Texto';
                            font-size: 1.3rem;
                        }
                    </style>

                    <br>

                </div>

                <button class="submit-btn" type="submit" onclick="submitPhotos()">
                    Publicar Foto
                </button>

                <script>
                    const foto = document.getElementById("file-input");
                    const botao = document.getElementById("publicar");

                    botao.addEventListener("click", function() {
                        if (foto.files.length > 0) {
                            alert("A sua foto foi publicada com sucesso!");
                        } else {
                            alert("Escolha uma foto para a publicação ser concluída.");
                        }
                    });
                </script>

            </form>
        </div>
    </div>



    <!-- MODAL PARA POSTAR TEXTOS -->

    <div class="modal" id="text-modal">

        <div class="modal-content">
            <button class="close-btn" onclick="closeModal('text-modal')">
                <i class="bi bi-x" style="font-size: 2rem;"></i>
            </button>

            <div class="modal-header">
                <i class="bi bi-card-text" style="color: #38a0dd;"></i>
                <h2 style="color: #045a94; font-family:'Texto'; ">Postagem de Textos</h2>
            </div>

            <form action="novoText.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="text-content" style="font-family: 'Texto'; font-size: 1.3rem;">Texto</label>
                    <textarea id="text-content" name="legenda" placeholder="Escreva seu texto aqui..." rows="6"></textarea>
                </div>

                <button class="submit-btn" onclick="submitText()">
                    Publicar Texto
                </button>

                <script>
                    const texto = document.getElementById("text-content");
                    const botaoT = document.getElementById("publicarT");

                    botaoT.addEventListener("click", function() {
                        if (texto.value.trim() === "") {
                            alert("Escreva um texto para a publicação ser concluída.");
                        } else {
                            alert("O seu texto foi publicado com sucesso!");
                        }
                    });
                </script>

            </form>

        </div>
    </div>


    <!-- MODAL PARA COMENTÁRIOS -->
    <div class="modal" id="comentarios-modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal('comentarios-modal')">
                <i class="bi bi-x"></i>
            </button>

            <div class="modal-header">
                <i class="bi bi-chat"></i>
                <h2>Comentários</h2>
            </div>

            <div class="modal-body">
                <!-- Área de comentários existentes -->
                <div style="max-height: 300px; overflow-y: auto;" class="comentarios-list" id="lista-comentarios">
                </div>

                <!-- Formulário para novo comentário -->
                <div class="novo-comentario">
                    <div class="comentario-input-container">
                        <div class="user-avatar">
                            <img src="../../../../<?php echo htmlspecialchars($foto); ?>" style="display: inline;" alt="Seu perfil" class="comentario-avatar" />
                        </div>

                        <div class="comentario-input-wrapper" style="margin-top: 15px; display:inline; bottom:0;">
                            <!-- Campo hidden para o ID da postagem -->
                            <input type="hidden" id="post-id-comentario" value="" style="width: 80%; padding: 8px; border-radius: 8px; border: 1px solid #ccc; display:inline;">

                            <input type="text" id="texto-comentario" placeholder="Escreva seu comentário..." style="width: 80%; padding: 8px; border-radius: 8px; border: 1px solid #ccc; display:inline;">
                            <button type="button" class="comentario-enviar" id="btn-enviar-comentario" style="padding: 8px 12px; border-radius: 8px; background-color: #ff9c57; color: white; border: none; cursor: pointer; margin-left: 8px; display:inline;">
                                <i class="bi bi-send"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <footer style="background: #045a94;">
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
                <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
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



    <!-- JAVASCRIPT  -->
    <script src="./instamarJS/bot.js"></script>
    <script src="./instamarJS/modal.js"></script>
    <script src="./instamarJS/acao.js"></script>
    <script src="./instamarJS/pontos.js"></script>

</body>

</html>