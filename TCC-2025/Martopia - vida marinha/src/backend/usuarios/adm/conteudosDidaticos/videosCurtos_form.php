<?php
include_once '../../../classes/class_IRepositorioUsuarios.php';

$mensagem = "";
session_start();
if ($_SESSION) {
  $mensagem = $_SESSION['mensagem'];
} else {
  $mensagem = "";
}

$usuario = $_SESSION['nome'];
$id = $_SESSION['id_usuario'];

// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);
// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';

$registroUsuario = $respositorioUsuario->listarTodosUsuarios();


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Publicar Conteúdos Educativos - Projeto Martopia</title>

  <!-- Estilos -->
  <link rel="stylesheet" href="../../../../frontend/public/css/baseGeral.css">
  <link rel="stylesheet" href="../../../../frontend/public/css/logo.css">
  <link rel="stylesheet" href="./videos.css">
  <link rel="stylesheet" href="../../../../frontend/public/css/footer.css">
  <!-- <link rel="stylesheet" href="../../../../frontend/public/css/instamar.css"> -->

  <!-- <link rel="stylesheet" href="../Quiz/quizForm.css">  -->

  <!-- Ícones Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

  <script defer src="./modalVideos.js"></script>

</head>

<body>


  <style>
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


    .page-content {
      flex: 1 0 auto;
      /* max-width: 1200px; */
      /* width: 100%; */
      margin: 5% auto 2rem auto;
      display: flex;
      flex-direction: column;
      gap: 2rem;
      justify-content: center;
      align-items: center;
    }

    .form {
      background-color: #ffffff;
      border: 2px solid #38a0dd;
      height: 100%;
      max-height: 1600px;
      border-radius: 20px;
      padding: 50px 30px;
      max-width: 1200px;
      width: 100%;
      margin: 20px auto;
      color: #045A94;
      box-shadow: 0 0 15px 3px #81c0e9;
    }

    .form:hover {
      box-shadow: 0 0 25px 5px #38a0dd;
    }

    .form h2 {
      font-family: 'Titulo';
      font-size: 2rem;
      letter-spacing: 2px;
      text-align: center;
      margin-bottom: 20px;
    }

    .form label {
      font-family: 'Texto';
      font-size: 1.3rem;
      display: block;
      margin-top: 25px;
      font-weight: bold;
      justify-content: baseline;
      padding-bottom: 1em
    }

    .form select,
    .form textarea,
    .form input[type="text"] {
      width: 100%;
      padding: 8px 10px;
      margin-top: 5px;
      border: 1.5px solid #007BFF;
      border-radius: 5px;
      font-size: 14px;
      color: #003366;
      box-sizing: border-box;
      transition: border-color 0.3s ease;
      font-family: 'Texto';
    }

    .form select:focus,
    .form textarea:focus,
    .form input[type="text"]:focus {
      border-color: #38a0dd;
      outline: none;
      box-shadow: 0 0 5px #81c0e9;
      font-family: 'Texto';
    }

    .form button {
      margin-top: 25px;
      width: 100%;
      max-width: 300px;
      text-align: center;
      background-color: #81c0e9;
      color: white;
      border: none;
      padding: 12px;
      font-size: 16px;
      font-weight: bold;
      border-radius: 20px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .form button:hover {
      background-color: #045A94;
    }

    .btn {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }

    footer {
      background-color: #045A94;
    }

    #image-previews img {
      display: block;
      margin-top: 10px;
    }

    .person {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 10rem;
    }

    header {
      box-shadow: 0 40px 60px rgba(0, 0, 0, 0.2);
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



  <!-- NAVBAR -->
  <header class="header">

    <input type="checkbox" id="check">
    <label for="check" class="icone">
      <i class="bi bi-list" id="menu-icone"></i>
      <i class="bi bi-x" id="sair-icone"></i>
    </label>

    <div class="logo-marca" style="margin-left: -3rem;">
      <a href="./home.php" class="logo"><img src=".../../../../../../frontend/public/img/Logo.png" alt="Logo-Projeto Martopia"></a>
      <p style="margin-left: -3rem;">Projeto <br> Martopia</p>
    </div>

    <nav>

      <a href="../../../trocar/trocarperfil.php"><img src="../../../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil" class="perfil" style="--i:4;"></a>
    </nav>
  </header>


  <div class="iconeCentral">

    <div> <i class="bi bi-play-btn" style="color: #000; font-size: 7rem;"></i> </div>

    <div class="centraliza">

      <h2 style="text-shadow: 2px 2px 4px rgba(0, 0, 0, .3); color: #fff;">Adicionar Vídeos Curtos</h2>

      <br><br>

      <div>
        <button onclick="history.back()" class="btn-voltar"> Voltar </button>
      </div>

    </div>

  </div>


  <div class="page-content">


    <form class="form" action="salvar_videos.php" method="POST" enctype="multipart/form-data">

      <h2>Adicionar Vídeos Curtos</h2>
      <label>Autor: <?php echo $_SESSION['nome']; ?> </label>


      <label for="tipo">Tipo:</label>
      <input type="text" name="tipo" value="Videos" readonly style="font-size: 1.2rem;">

      <label for="categoria">Categoria:</label>
      <select name="categoria" id="categoria" required style="font-size: 1.2rem;">
        <option value="" style="font-size: 1.2rem;">Selecione uma categoria... </option>
        <option value="Tartaruga" style="font-size: 1.2rem;">Tartaruga</option>
        <option value="Baleia" style="font-size: 1.2rem;">Baleia</option>
        <option value="Tubarão" style="font-size: 1.2rem;">Tubarão </option>
        <option value="Arraia" style="font-size: 1.2rem;">Arraia</option>
        <option value="Golfinho" style="font-size: 1.2rem;">Golfinho</option>
        <option value="Água-Viva" style="font-size: 1.2rem;">Água-Viva</option>
        <option value="Polvo" style="font-size: 1.2rem;">Polvo</option>
        <option value="Moreia" style="font-size: 1.2rem;">Moreia</option>
      </select>

      <label for="link" style="display: flex; align-items: center; gap: 0.5rem;">
        Link: (Aprenda a adicionar o link do vídeo no ícone ao lado)
        <!-- Ícone de interrogação -->
        <div class="dados">
          <i class="bi bi-question-circle" onclick="abrirModal()" style="color: #045a94; font-size: 1.8rem;"></i>
        </div>
      </label>
      <input class="input" type="text" name="link" id="link" style="font-size: 1.2rem; font-family: 'Texto';">


      <div class="btn"><button type="submit" style="font-size: 1.2rem; font-family: 'Texto';">Salvar</button></div>

    </form>

  </div>

  <div id="modal-editar" class="modal">

    <div class="modal-content" style="max-height: 900px;">

      <button class="close close-btn" id="closeModalBtn" onclick="fecharModal()">
        <i class="bi bi-x" style="font-size: 2rem;"></i>
      </button>

      <!-- <span class="close" onclick="fecharModal()">&times;</span>  -->

      <h2 style="font-size: 2rem;">Não sabe como adicionar o link dos vídeos? <br> Siga o passo a passo abaixo:</h2>

      <div class="ajuda">

        <ol>
          <li style="font-size: 1.2rem;">No <a href="https://www.youtube.com/">Youtube</a> selecione o vídeo que você deseja adicionar na
            página.</li>

          <li style="font-size: 1.2rem;">No vídeo clique em compartilhar e depois em incorporar</li>
          <div style="display: flex;">
            <img src="../img/passo1.png" alt="passo1" width="320px" height="auto" id="img1" class="img">
            <img src="../img/passo2.png" alt="passo1" width="300px" height="auto" id="img2" class="img">
          </div>

          <li style="font-size: 1.2rem;">Copie somente o link dentro do código e cole no campo</li>
          <img src="../img/passo3.png" alt="passo1" width="450px" height="auto" id="img3" class="img">

        </ol>

        <!-- <div class="img">
                    <img src="./backend/usuarios/adm/img/passo1.png" alt="passo1" width="200px" height="auto">
                    <img src="./backend/usuarios/adm/img/passo2.png" alt="passo1" width="200px" height="auto">
                    <img src="./backend/usuarios/adm/img/passo3.png" alt="passo1" width="200px" height="auto">
                </div> -->

      </div>

      </form>
    </div>
  </div>



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
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3666.2168153595317!2d-46.766872!3d-23.235196!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94cede166027baab%3A0x566fc4df5821546c!2sEscola%20T%C3%A9cnica%20Estadual%20de%20Campo%20Limpo%20Paulista!5e0!3m2!1spt-BR!2sbr!4v1756695006929!5m2!1spt-BR!2sbr        allowfullscreen="" loading=" lazy"
        referrerpolicy="no-referrer-when-downgrade"
        title="Mapa do local">
      </iframe>
    </div>

    <div class="copyright">
      &copy; 2025 Projeto Martopia. Todos os direitos reservados.
    </div>
  </footer>

</body>

</html>