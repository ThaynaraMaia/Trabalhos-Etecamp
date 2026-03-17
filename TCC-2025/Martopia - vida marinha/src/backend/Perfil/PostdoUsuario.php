<!DOCTYPE html>
<html lang="pt-br">

<?php
session_start();
include_once '../classes/class_IRepositorioUsuarios.php';
include_once '../classes/class_IRepositorioInstamar.php';
$id = $_SESSION['id'] ;
$dados = $respositorioUsuario->buscarUsuario($id);
$post = $respositorioUsuario->listarTodasPostagens($id);
$usuario = $respositorioInstamar->PegardadosUsuario($id);
$foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Conteúdos - Projeto Martopia</title>

    <link rel="stylesheet" href="meus_cont.css">
    <link rel="stylesheet" href="../../frontend/public/css/base.css">
    <link rel="stylesheet" href="../../frontend/public/css/instamar.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

</head>

<body>

    <!-- NAVBAR  -->

        <style>
            .header {
                height: 120px;
                box-shadow: 0 40px 60px rgba(0, 0, 0, 0.2);
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
                box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
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
            /* 
            ===================
                final 3 PONTOS
            =====================
            */
        </style>


    <div class="page-content">
    
        
        <?php
        while ($linhas = $post->fetch_object()) {
            if ($linhas->imagem_post) {
        ?>
                <div class="post-card">
                    <div class="post-header">
                        <div class="user-avatar">
                            <img src="../../<?php echo htmlspecialchars($foto); ?>" alt="Imagem da sua postagem" id="img-perfil">
                        </div>

                        <div class="user-info">
                            <div class="user-name"> <?php echo $usuario['nome']; ?> </div>
                            <div class="post-date"><?php echo $linhas->data_postagem ?> </div>
                        </div>
                    </div>

                    <!-- IMAGEM DO POST-->
                    <img src="../../frontend/public/img_instamar/<?php echo $linhas->imagem_post ?>" alt="Imagem do Post" class="post-image" style="object-fit: contain;">

                    <!-- CURTIR E COMENTAR -->
                    <div class="post-actions">
                        <!-- Contador de curtidas dinâmico -->
                        <div class="likes-count" id="curtidas-<?php echo $linhas->id; ?>">
                            <?php echo $linhas->total_curtidas ?? 0; ?>  <i class="bi bi-heart"></i>
                        </div>

                        <!-- Botão de comentar com contador -->
                        <button class="action-btn">
                            <div class="likes-count" id="comentarios-<?php echo $linhas->id; ?>">
                                <?php echo $linhas->total_comentarios ?? 0; ?>  
                                <i class="bi bi-chat"></i>
                                <span>Comentar</span>
                            </div>
                        </button>
                    </div>

                    <!-- LEGENDA DO POST -->
                    <div class="post-caption">
                        <div class="caption-text collapsed" id="caption-text">
                            <?php echo $linhas->legenda ?>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="post-card 2" aria-label="Postagem de texto do usuário">
                    <div class="post-header">
                        <div class="user-avatar">
                            <img src="../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de perfil do Usuário" />
                        </div>
                        <div class="user-info">
                            <div class="user-name"> <?php echo $usuario['nome']; ?> </div>
                            <time class="post-date" datetime="2024-06-15"><?php echo $linhas->data_postagem ?></time>
                        </div>
                
                    </div>

                    <div class="post-text collapsed" id="post-text">
                        <?php echo $linhas->legenda ?>
                    </div>

                    <div class="post-actions">
                        <!-- Contador de curtidas dinâmico -->
                        <div class="likes-count" id="curtidas-<?php echo $linhas->id; ?>">
                            <?php echo $linhas->total_curtidas ?? 0; ?>  <i class="bi bi-heart"></i>
                        </div>
                        
                        <!-- Contador de comentários dinâmico -->
                        <div class="likes-count" id="comentarios-<?php echo $linhas->id; ?>">
                            <?php echo $linhas->total_comentarios ?? 0; ?>   <i class="bi bi-chat"></i>
                        </div>
                    </div>
                </div>
        <?php 
            }
        } 
        ?>
    </div>

</body>
</html>