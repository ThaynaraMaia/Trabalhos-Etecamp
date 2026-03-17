<!DOCTYPE html>
<html lang="pt-br">
<?php

session_start();

if (!$_SESSION['tipo']  && !$_SESSION['logado']) {
    header('Location:../../../../frontend/home.php');
}

$usuario = $_SESSION['nome'];
include_once '../../../classes/class_IRepositorioUsuarios.php';
include_once '../../../classes/class_IRepositorioInstamar.php';
$id = $_SESSION['id_usuario'];

// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);
// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';

$listarComent = $respositorioInstamar->listarDenunciasComentarios();
$contDenuncias = $respositorioInstamar->contarTodasDenunciasComent();


?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./denun.css">
    <!-- <link rel="stylesheet" href="../homeAdmin.css"> -->
    <link rel="stylesheet" href="../../../../frontend/public/css/baseGeral.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/instamar.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/logo.css">
    <link rel="stylesheet" href="../../../../frontend/public/css/footer.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <title>Denúncias - Administrador</title>
</head>

<body>

    <style>
        .person {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10rem;
        }

        body {
            background: #045A94;
            background: radial-gradient(circle, rgba(4, 90, 148, 1) 0%, rgba(129, 192, 233, 1) 50%, #9fcaec);
        }

        footer {
            background: #045A94;
        }

        .tabela tbody tr:hover {
            background-color: #dcf1fa;
        }

        footer {
            background: #045A94;
        }


        header {
            box-shadow: 0 40px 60px rgba(0, 0, 0, 0.2);
        }

        .btn-excluir.disabled {
            pointer-events: none;
            /* não clica */
            opacity: 0.5;
            /* esmaecido */
            cursor: not-allowed;
        }

        .btn-notificar.disabled {
            pointer-events: none;
            /* não clica */
            opacity: 0.5;
            /* esmaecido */
            cursor: not-allowed;
        }

        .card {
            height: 100%;
            max-height: 2000px;
        }

        .card form button[type="submit"] {
            width: 100%;
            max-width: 100px;
            padding: 14px 0;
            background-color: #81c0e9;
            border: none;
            border-radius: 20px;
            color: white;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-family: 'Texto';
        }

        .card form button[type="submit"]:hover {
            background-color: #38a0dd;
        }

        /* Inputs */
        .card form input {
            width: 100%;
            max-width: 200px;
            padding: 12px 25px;
            margin-top: 30px;
            margin-bottom: 20px;
            border: 1.8px solid #81c0e9;
            border-radius: 20px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .card form input:focus {
            border-color: #38a0dd;
            outline: none;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.3);
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

    <header class="header">

        <div class="logo-marca" style="margin-left: -3rem;">
            <a href="./homeAdm.php" class="logo"><img src="../../../../frontend/public/img/Logo.png" alt="Logo-Projeto Martopia"></a>
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

    <div class="iconeCentral">

        <div> <img src="../img/comentario.png" alt="postDenunciaIcon" width="100px" height="100px"> </div>

        <div class="centraliza">

            <h2 style="text-shadow: 2px 2px 4px rgba(0, 0, 0, .3); color: #fff;">Denúncias - Comentários</h2>

            <br><br>

            <div>
                <button onclick="history.back()" class="btn-voltar"> Voltar </button>
            </div>

        </div>

    </div>


    <div class="page-content" style="margin-top: 10rem;">

        <!-- <p id="texto_in"> Clique na sua forma preferida de estudo. </p>  -->

        <div class="card" id="totalDenunciasCard" aria-live="polite" aria-atomic="true" role="region" aria-label="Total de denúncias feitas" style="height: 100%; max-height: 400px;">
            Total de denúncias feitas: <?php echo $contDenuncias; ?>
        </div>

        <div class="card">
            <form method="GET" action="denunciaComent.php" style="margin-bottom: 20px; text-align:center;">
                <label for="pesquisa_id" style=" font-weight: bold;">Pesquisar por ID do comentário denunciado:</label>
                <input type="number" name="pesquisa_id" id="pesquisa_id" placeholder="Digite o ID" required style="font-size: 1.2rem;">
                <button type="submit">Buscar</button>
            </form>
            <?php
            if (isset($_GET['pesquisa_id'])) {
                $id_pesquisa = intval($_GET['pesquisa_id']);
                $resultado = $respositorioInstamar->listarDenunciaComentario($id_pesquisa);

                if ($resultado && $resultado->num_rows > 0) {
                    while ($linhas = $resultado->fetch_object()) {
            ?>
                        <div class="post-card">
                            <div class="post-header">
                                <div class="user-info">
                                    <div class="user-name" style="font-size: 1.6rem;"><?php echo $linhas->autor_comentario; ?></div>
                                    <time class="post-date" style="font-size: 1rem;"><?php echo $linhas->data_denuncia; ?></time>
                                </div>
                            </div>

                            <div class="post-text collapsed" id="post-text">
                                <p style="font-size: 1.2rem;"> <strong style="color:#045A94;">Comentário:</strong> <?php echo $linhas->comentario; ?></p>
                            </div>
                        </div>
            <?php
                    }
                } else {
                    echo "<p style='color:red; text-align:center; font-size:1.2rem;'>Nenhuma postagem encontrada com esse ID.</p>";
                }
            }
            ?>

        </div>



        <div class="usuarios" role="region" aria-label="Lista de denúncias">

            <div class="tabela-scroll">

                <table class="tabela" aria-describedby="totalDenunciasCard">
                    <thead class="nametable">
                        <tr style="font-size: 1.3rem;">

                            <th scope="col" title="Nome do usuário que fez a denúncia" style="padding: 0 70px;;">Usuário - <br> Denunciou</th>
                            <th scope="col" title="ID do usuário">ID - Denunciou </th>
                            <!-- <th scope="col" title="Email do usuário">Email</th> -->
                            <th scope="col" title="Texto da denúncia">ID - Denunciado</th>
                            <th scope="col" title="Excluir postagem denunciada" style="padding-left: 100px;">Gerenciar</th>
                        </tr>
                    </thead>

                    <tbody id="denunciasTableBody">
                        <?php
                        while ($linhas = $listarComent->fetch_object()) {
                        ?>
                            <tr style="font-size: 1.2rem;">
                                <th class="info" scope="row" data-label="#"> <?php echo $linhas->usuario; ?> </th>
                                <td class="info" data-label="Nome Usuário" style="padding-left: 70px;"> <?php echo $linhas->id_usuario; ?> </td>
                                <td class="info" data-label="Tipo" style="padding-left: 70px;"><?php echo $linhas->id_comentario; ?> </td>
                                <td data-label="Excluir">
                                    <div class="btns">


                                        <a href="excluirComent.php?excluir=<?php echo $linhas->id_comentario; ?>"
                                            class="btn-excluir disabled"
                                            style="font-size: 1.2rem; border-radius: 20px; color: #fff;"
                                            data-id1="<?php echo $linhas->id_comentario; ?>"
                                            title="Excluir postagem">
                                            <i class="bi bi-x-circle-fill"></i> Excluir
                                        </a>


                                        <button class="btn-notificar" data-id1="<?php echo $linhas->id_comentario; ?>" title="Notificar usuário" style="font-size: 1.2rem; border-radius: 20px; background:#38a0dd; color: #fff;">
                                            <i class="bi bi-bell-fill"></i> Notificar
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>

                    </tbody>

                </table>

            </div>

            <?php
            // LÓGICA CORRETA: VERIFICA, EXIBE E DEPOIS LIMPA
            if (isset($_SESSION['notificacao']) && !empty($_SESSION['notificacao'])):
            ?>

                <div class="alerta-notificacao">
                    <?php
                    echo htmlspecialchars($_SESSION['notificacao']);
                    // Limpa a variável logo após exibi-la
                    unset($_SESSION['notificacao']);
                    ?>
                </div>

            <?php
            endif;
            ?>


        </div>

    </div>


    <footer style="margin-top:10rem;">
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


    <script src="denunComet.js"></script>

</body>

</html>