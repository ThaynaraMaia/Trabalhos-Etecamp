<!DOCTYPE html>
<html lang="pt-br">
<?php

session_start();

if (!$_SESSION['tipo']  && !$_SESSION['logado']) {
    header('Location:../../../frontend/home.php');
}

$usuario = $_SESSION['nome'];
include_once '../../classes/class_IRepositorioUsuarios.php';
$id = $_SESSION['id_usuario'];


// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);
// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : '../../../frontend/public/img/fotoperfil.png';

$registroUsuario = $respositorioUsuario->listarTodosUsuarios();

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./homeAdmin.css">
    <link rel="stylesheet" href="../../../frontend/public/css/baseGeral.css">
    <link rel="stylesheet" href="../../../frontend/public/css/logo.css">
    <link rel="stylesheet" href="../../../frontend/public/css/footer.css">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Biblioteca Scroll -->
    <script src="https://unpkg.com/scrollreveal"></script>

    <title>Sistema Tarefas - Administrador</title>
</head>

<body>

    <style>
        body {
            background: #045A94;
            background: radial-gradient(circle, rgba(4, 90, 148, 1) 0%, rgba(129, 192, 233, 1) 50%, #9fcaec);
        }

        footer {
            background: #045A94;
        }


        header {
            box-shadow: 0 40px 60px rgba(0, 0, 0, 0.2);
        }

        .navbar a {
            font-size: 1.3rem;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, .3);
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

        .card {
            /* Remover margin para evitar corte */
            margin: 0;
            background-color: #f0f8ff;
            border-radius: 20px;
            box-shadow: 0 0 15px 3px #81c0e9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: default;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 180px;
            padding: 10px;
            cursor: pointer;
        }

        #span {
            display: block;
            width: 90%;
            max-width: 835px;
            margin: 2rem auto 0 auto;
        }
    </style>

    <svg id="onda" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 318">
        <path fill="#045a94" fill-opacity="1" d="M0,192L40,197.3C80,203,160,213,240,186.7C320,160,400,96,480,101.3C560,107,640,181,720,224C800,267,880,277,960,245.3C1040,213,1120,139,1200,106.7C1280,75,1360,85,1400,90.7L1440,96L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z"></path>
    </svg>

    <header class="header">

        <div class="logo-marca" style="margin-left: -3rem;">
            <a href="./homeAdm.php" class="logo"><img src="../../../frontend/public/img/Logo.png" alt="Logo-Projeto Martopia"></a>
            <p style="margin-left: -3rem;">Projeto <br> Martopia</p>
        </div>


        <input type="checkbox" id="check">
        <label for="check" class="icone">
            <i class="bi bi-list" id="menu-icone"></i>
            <i class="bi bi-x" id="sair-icone"></i>
        </label>


        <nav>
            <a href="../../trocar/trocarperfil.php"><img src="../../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil" class="perfil" style="--i:4;"></a>
        </nav>

    </header>

    <div class="page-content">

        <h1 id="inicio" style="width: 100%; max-width: 1200px; top: 19%;"> Área do Administrador </h1>

        <!-- <p id="texto_in"> Clique na sua forma preferida de estudo. </p>  -->


        <div class="cards-container">


            <a href="./usuarios.php">

                <div class="card">
                    <i class="bi bi-person-fill-gear"></i>
                    <h3>Usuários</h3>
                </div>

            </a>

            <a href="./instaMar/insta.php">

                <div class="card">
                    <i class="bi bi-instagram"></i>
                    <h3>InstaMar</h3>
                </div>

            </a>

            <a href="./conteudosDidaticos/conteudos.php">

                <div class="card">
                    <i class="bi bi-file-earmark-richtext"></i>
                    <h3>Conteúdos Educativos</h3>
                </div>

            </a>

            <a href="./jogos/jogosAdm.php">

                <div class="card">
                    <i class="bi bi-controller"></i>
                    <h3>Jogos</h3>
                </div>

            </a>

        </div>

        <a href="./denuncias/denunciasGeral.php" id="span" style="text-decoration:none;">

            <div class="card">
                <i class="bi bi-exclamation-triangle"></i>
                <h3>Denúncias</h3>
            </div>

        </a>

    </div>

    <br> <br> <br>


    <footer>
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

    <!-- 
    <script src="../../../frontend/public/style/bootstrap/js/bootstrap.bundle.min.js"></script> -->

</body>

</html>