<!DOCTYPE html>
<html lang="pt-br">
<?php

session_start();

if (!$_SESSION['tipo']  && !$_SESSION['logado']) {
    header('Location:../../../../../frontend/home.php');
}

$usuario = $_SESSION['nome'];
include_once '../../../../classes/class_IRepositorioUsuarios.php';
$id = $_SESSION['id_usuario'];


// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);
// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';

$registroUsuario = $respositorioUsuario->listarTodosUsuarios();

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../../homeAdmin.css">
    <link rel="stylesheet" href="../../../../../frontend/public/css/baseGeral.css">
    <link rel="stylesheet" href="../../../../../frontend/public/css/logo.css">
    <link rel="stylesheet" href="../../../../../frontend/public/css/footer.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">


    <title>Jogos - Administrador</title>
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

        .person {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10rem;
        }

        .card {
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

        .cards-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
        }

        .card i {
            color: #000;
        }

        #span {
            display: block;
            width: 100%;
            max-width: 1200px;
            margin: 2rem auto 0 auto;
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
            <a href="../homeAdm.php" class="logo"><img src="../../../../../frontend/public/img/Logo.png" alt="Logo-Projeto Martopia"></a>
            <p style="margin-left: -3rem;">Projeto <br> Martopia</p>
        </div>


        <input type="checkbox" id="check">
        <label for="check" class="icone">
            <i class="bi bi-list" id="menu-icone"></i>
            <i class="bi bi-x" id="sair-icone"></i>
        </label>


        <nav>
            <a href="../../../trocar/trocarperfil.php"><img src="../../../../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil" class="perfil" style="--i:4;"></a>
        </nav>

    </header>

    <div class="iconeCentral">

         <div> <i class="bi bi-question-circle" style="color: #000; font-size: 7rem;"></i> </div>

        <div class="centraliza">

            <h2 style="text-shadow: 2px 2px 4px rgba(0, 0, 0, .3); color: #fff;">Gerenciamento - Quiz</h2>

            <br><br>

            <div>
                <button onclick="history.back()" class="btn-voltar"> Voltar </button>
            </div>

        </div>

    </div>


    <div class="page-content" style="margin-top: 5rem;">

        <!-- <h1 id="inicio" style="width: 100%; max-width: 1200px; top: 20%;"> Área do Administrador </h1> -->

        <!-- <p id="texto_in"> Clique na sua forma preferida de estudo. </p>  -->


        <div class="cards-container">


            <a href="./quiz_form.php">

                <div class="card">
                    <img src="../../img/adicionar.png" alt="adicionarPerguntasIcon" width="80px" height="80px"> <br>
                    <h3>Adicionar Perguntas</h3>
                </div>

            </a>

            <a href="./minhasPerguntas.php">

                <div class="card">
                    <img src="../../img/pergunta.png" alt="minhasPerguntasIcon" width="90px" height="90px"> <br>
                    <h3>Minhas Perguntas</h3>
                </div>

            </a>

        </div>

    </div>


    <footer style="margin-top: 5rem;">
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