<!DOCTYPE html>
<html lang="pt-br">
<?php

session_start();

if (!$_SESSION['tipo']  && !$_SESSION['logado']) {
    header('Location:../../../../frontend/home.php');
}

$usuario = $_SESSION['nome'];
include_once '../../../classes/class_IRepositorioUsuarios.php';
include_once '../../../classes/class_IRepositorioConteudos.php';
$id = $_SESSION['id_usuario'];


// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);
// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';

$conteudos = $respositorioConteudo->listarConteudosPorAutor($id);


?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../homeAdm.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/baseGeral.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/logo.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/footer.css">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Biblioteca Scroll -->
    <script src="https://unpkg.com/scrollreveal"></script>

    <title>Usuários do Sistema - Projeto Martopia</title>
</head>

<body>
    <div class="container-fluid">

        <header class="header">

            <input type="checkbox" id="check">
            <label for="check" class="icone">
                <i class="bi bi-list" id="menu-icone"></i>
                <i class="bi bi-x" id="sair-icone"></i>
            </label>

            <div class="logo-marca" style="margin-left: -3rem;">
                <a href="./homeAdm.php" class="logo"><img src="../../../../frontend/public/img/Logo.png" alt="Logo-Projeto Martopia"></a>
                <p style="margin-left: -3rem;">Projeto <br> Martopia</p>
            </div>

            <nav>
                <a href="../../trocar/trocarperfil.php"><img src="../../../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil" class="perfil" style="--i:4;"></a>
            </nav>
        </header>
        <style>
            @font-face {
                font-family: 'Logo';
                src: url('../../fontes/Logo.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
            }

            @font-face {
                font-family: 'Titulo';
                src: url('../../fontes/Título.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
            }

            @font-face {
                font-family: 'Texto';
                src: url('../../fontes/Texto.otf') format('truetype');
                font-weight: normal;
                font-style: normal;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                height: 100vh;
                background: #045A94;
                background: radial-gradient(circle, rgba(4, 90, 148, 1) 0%, rgba(129, 192, 233, 1) 50%, #9fcaec);
            }

            /* Modal */
            .modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000;
                animation: fadeIn 0.3s ease;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            .modal-content {
                background: #fff;
                border-radius: 20px;
                padding: 40px;
                width: 600px;
                max-width: 95%;
                max-height: 90vh;
                overflow-y: auto;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                animation: slideUp 0.3s ease;
            }

            @keyframes slideUp {
                from {
                    transform: translateY(50px);
                    opacity: 0;
                }

                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            /* Título do Modal */
            .modal-content h2 {
                font-family: 'Titulo', serif;
                color: #045A94;
                font-size: 28px;
                font-weight: 600;
                margin-bottom: 30px;
                text-align: center;
            }

            /* Autor Info */
            .autor-info {
                color: #045A94;
                font-size: 14px;
                margin-bottom: 25px;
                font-weight: 500;
            }

            /* Labels */
            label {
                display: block;
                font-family: 'Texto', sans-serif;
                color: #045A94;
                font-weight: 600;
                margin-bottom: 8px;
                font-size: 14px;
            }

            /* Inputs e Textareas */
            input[type="text"],
            input[type="url"],
            textarea,
            select {
                width: 100%;
                padding: 12px 15px;
                border: 2px solid #d1e8f5;
                border-radius: 8px;
                font-size: 14px;
                margin-bottom: 20px;
                transition: all 0.3s ease;
                background: #fff;
                color: #333;
            }

            input[type="text"]:focus,
            input[type="url"]:focus,
            textarea:focus,
            select:focus {
                outline: none;
                border-color: #045A94;
                box-shadow: 0 0 0 3px rgba(4, 90, 148, 0.1);
            }

            textarea {
                resize: vertical;
                min-height: 100px;
                font-family: inherit;
            }

            select {
                cursor: pointer;
                appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23045A94' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 15px center;
                padding-right: 40px;
            }

            /* File Upload Area */
            .file-upload {
                position: relative;
                border: 2px dashed #cbd5e0;
                border-radius: 12px;
                padding: 40px 20px;
                text-align: center;
                cursor: pointer;
                transition: all 0.3s ease;
                margin-bottom: 20px;
            }

            .file-upload:hover {
                border-color: #38a0dd;
                background: rgba(102, 126, 234, 0.05);
            }

            .file-upload i {
                font-size: 48px;
                color: #a0aec0;
                margin-bottom: 15px;
            }

            .file-upload p {
                color: #045A94;
                font-size: 14px;
                margin: 5px 0;
            }

            .file-upload .subtitle {
                color: #7a9bb5;
                font-size: 12px;
            }

            .file-upload input {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0;
                cursor: pointer;
            }

            /* Preview Container */
            .preview-container {
                margin-top: 20px;
                display: none;
                text-align: center;
            }

            .preview-image {
                max-width: 100%;
                max-height: 200px;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                margin-top: 10px;
            }

            /* Botões */
            .botoes {
                display: flex;
                gap: 15px;
                margin-top: 30px;
            }

            .botoes button {
                flex: 1;
                padding: 14px 20px;
                border: none;
                border-radius: 10px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .botoes button[type="submit"] {
                background: linear-gradient(135deg, #045A94, #2b7ab8);
                color: #fff;
            }

            .botoes button [type="submit"]:hover {
                background: linear-gradient(135deg, #034677, #045A94);
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(4, 90, 148, 0.3);
            }


            .botoes button[type="button"]:hover {
                background: #d1e8f5;
                transform: translateY(-2px);
            }

            /* Scrollbar personalizada */
            .modal-content::-webkit-scrollbar {
                width: 8px;
            }

            .modal-content::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }

            .modal-content::-webkit-scrollbar-thumb {
                background: #81c0e9;
                border-radius: 10px;
            }

            .modal-content::-webkit-scrollbar-thumb:hover {
                background: #045A94;
            }

            /* Responsividade */
            @media (max-width: 768px) {
                .modal-content {
                    padding: 25px;
                    width: 100%;
                }

                .modal-content h2 {
                    font-size: 24px;
                }

                .botoes {
                    flex-direction: column;
                }
            }

            .modal {
                font-family: 'Texto';
            }

            .perfil {
                width: 80px;
                height: 80px;
                margin-left: -3rem;
                border: 1.5px solid #e18451;
                /* color: #81c0e9; */
            }

            .iconeCentral {
                display: flex;
                align-items: center;
                /* centraliza verticalmente o ícone e o texto */
                justify-content: center;
                /* centraliza horizontalmente na tela */
                background: transparent;
                border-radius: 20px;
                width: 100%;
                max-width: 1000px;
                font-weight: bold;
                filter: blur(.2px);
                box-shadow: 0 0 15px 3px #81c0e9;
                height: auto;
                padding: 2rem;
                margin: 8rem auto;
                text-align: center;
                font-family: 'Texto';
                gap: 3rem;
                margin-top: 10rem;
            }

            .centraliza {
                display: flex;
                flex-direction: column;
                /* h2 e botão ficam um embaixo do outro */
                align-items: center;
                text-align: center;
            }

            .btn-voltar {
                transition: 0.3s;
                padding: 0.8rem 1.4rem;
                background: linear-gradient(135deg, #c6e1f6, #9fcaec);
                color: #045a94;
                font-weight: bold;
                text-transform: uppercase;
                font-size: 1.1rem;
                font-family: 'Texto', serif;
                border-radius: 25px;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
                text-decoration: none;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
            }

            .btn-voltar:hover {
                background: linear-gradient(135deg, #81C0E9, #38a0dd);
                transform: translateY(-2px);
                box-shadow: 0 6px 14px rgba(0, 0, 0, 0.35);
            }

        </style>

        <div class="iconeCentral">

            <div> <img src="../img/administrador.png" alt="adminIcon" style="color: #000; "></i> </div>

            <div class="centraliza">

                <h2 style="text-shadow: 2px 2px 4px rgba(0, 0, 0, .3); color: #fff;">Meus Conteúdos Educativos</h2>

                <br><br>

                <div>
                    <button onclick="history.back()" class="btn-voltar"> Voltar </button>
                </div>

            </div>

        </div>


        <main>



            <div class="usuarios">

                <div class="col">

                    <table class="tabela" style="font-family: 'Texto';">

                        <thead class="nametable" style="width: 100%;">
                            <tr style="font-size: 1.3rem;">
                                <th scope="col">Tipo - Conteúdo</th>
                                <th scope="col">Imagem</th>
                                <th scope="col">Título</th>
                                <th scope="col">Link</th>
                                <th scope="col">Editar</th>
                                <th scope="col">Excluir</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            if (!empty($conteudos)) {
                                // mapeamento das pastas por origem
                                $pastas = [
                                    'conteudos'       => "img_conteudos/",
                                    'conscientizacao' => "img_conscientizacao/",
                                    'videos'          => "img_videos/"
                                ];

                                foreach ($conteudos as $c) {
                                    $pastaBase = "../../../../frontend/public/";
                                    $pasta = $pastas[$c['origem']] ?? "img/";

                                    // monta caminho final
                                    $caminhoFinal = !empty($c['caminho_img'])
                                        ? $pastaBase . $pasta . $c['caminho_img']
                                        : $pastaBase . "img/educa.png";

                                    // sanitização
                                    $tipo      = htmlspecialchars($c['tipo']);
                                    $titulo    = htmlspecialchars($c['titulo']);
                                    $link      = htmlspecialchars($c['link']);
                                    $id        = (int)$c['id'];
                                    $tabela    = rawurlencode($c['origem']);
                                    $categoria = htmlspecialchars($c['categoria']);
                                    $texto = isset($c['texto']) ? htmlspecialchars($c['texto']) : '';
                            ?>
                                    <tr>
                                        <th class="info" scope="row" data-label="nome-conteudo" style="font-size: 1.2rem;"><?php echo $tipo; ?></th>
                                        <td style="text-align:center;" style="font-size: 1.2rem;">
                                            <img src="<?php echo $caminhoFinal; ?>"
                                                width="40"
                                                alt="img"
                                                onerror="this.onerror=null;this.src='../../../../frontend/public/img/educa.png'">
                                        </td>
                                        <td class="info" data-label="titulo-conteudo" style="font-size: 1.2rem;"><?php echo $titulo; ?></td>
                                        <td style="font-size: 1.2rem;"><a href="<?php echo $link; ?>" target="_blank">Ver</a></td>
                                        <td style="font-size: 1.2rem;">
                                            <a href="#"
                                                class="btn-editar"
                                                data-id="<?php echo $id; ?>"
                                                data-tabela="<?php echo $tabela; ?>"
                                                data-titulo="<?php echo $titulo; ?>"
                                                data-link="<?php echo $link; ?>"
                                                data-tipo="<?php echo $tipo; ?>"
                                                data-categoria="<?php echo $categoria; ?>"
                                                data-texto="<?php echo isset($c['texto']) ? htmlspecialchars($c['texto']) : ''; ?>">
                                                ✏️
                                            </a>
                                        </td>
                                        <td style="font-size: 1.2rem;">
                                            <a href="excluir.php?id=<?php echo $id; ?>&tabela=<?php echo $tabela; ?>" style="color:red;">✖</a>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr style="font-size: 1.2rem;">
                                    <td colspan="6" style="text-align:center; color:#000">Nenhum conteúdo encontrado</td>
                                </tr>;
                            <?php
                            }
                            ?>

                        </tbody>

                    </table>
                </div>
            </div>


            <!-- Modal de edição -->
            <div id="modal-editar" class="modal" style="display: none; color:#000">
                <div class="modal-content">
                    <h2 class="info" style="font-size: 2rem; font-family: 'Titulo'; color:#045A94;">Editar Conteúdo</h2>
                    <form id="form-editar" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="edit-id" style="font-size: 1.2rem; color:#000; font-family: 'Texto';">
                        <input type="hidden" name="tabela" id="edit-tabela" style="font-size: 1.2rem; color:#000; font-family: 'Texto';">

                        <label class="info" style="font-size: 1.2rem; color:#045A94; font-family: 'Texto';">Título:</label>
                        <input type="text" name="titulo" id="edit-titulo" required style="font-size: 1.2rem; color:#000; font-family: 'Texto';">

                        <label class="info" style="font-size: 1.2rem; color:#045A94; font-family: 'Texto';">Tipo:</label>
                        <input type="text" name="tipo" id="edit-tipo" style="font-size: 1.2rem; color:#000; font-family: 'Texto';">

                        <label class="info" style="font-size: 1.2rem; color:#045A94; font-family: 'Texto';">Categoria:</label>
                        <input type="text" name="categoria" id="edit-categoria" style="font-size: 1.2rem; color:#000; font-family: 'Texto';">

                        <div id="link" >
                            <label class="info" style="font-size: 1.2rem; color:#045A94; font-family: 'Texto';">Link:</label>
                            <input type="text" name="link" id="edit-link" style="font-size: 1.2rem; color:#000; font-family: 'Texto';">
                        </div>

                        <div id="campo-extra-texto">
                            <label class="info" style="font-size: 1.2rem; color:#045A94; font-family: 'Texto';">Texto:</label>
                            <textarea name="texto" id="edit-texto" rows="5" style="width: 100%;" style="font-size: 1.2rem; color:#000; font-family: 'Texto';"></textarea>
                        </div>

                        <div id="campo-imagem">
                            <div class="file-upload" id="file-upload-area">
                                <i class="bi bi-cloud-upload"></i>
                                <p>Clique ou arraste arquivos para enviar</p>
                                <p class="text-sm text-gray-500 mt-2">Formatos suportados: JPG, PNG</p>
                                <input type="file" name="foto" id="file-input" accept="image/*" multiple onchange="handleFileSelect(event)">
                            </div>

                            <div class="preview-container" id="preview-container">
                                <p class="text-sm font-semibold mb-2" style="font-size: 1.2rem; color:#000; font-family: 'Texto';">Pré-visualização:</p>
                                <div id="image-previews"></div>
                            </div>

                        </div>

                        <div class="botoes">
                            <button type="submit"  style="width: 100%; padding: 12px; margin-top: 20px; background-color: #045A94; color: white; border: none; border-radius: 20px; cursor: pointer; font-size: 1.2rem; color:#fff; font-family: 'Texto';" >Salvar</button>

                            <button type="button" class="fechar" id="fechar-modal" style="width: 100%; padding: 12px; margin-top: 20px; background-color: #045A94; color: white; border: none; border-radius: 20px; cursor: pointer; font-size: 1.2rem; color:#fff; font-family: 'Texto';">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>

    </div>

    </main>


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


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./editar.js"></script>

</body>

</html>