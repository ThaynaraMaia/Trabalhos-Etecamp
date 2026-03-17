<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="../../css/pandora.css">
  <title>Página Inicial</title>
</head>

<body>
  <div class="container-fluid">
    <div class="row" id="rownav" style="background-color: #A8B16B;">
      <nav class="navbar position-relative" style="height: 135px; position: relative;">
        <div class="container position-relative" style="position: relative;">

          <!-- Botão à direita -->
          <div class="ms-auto">

            <?php
            session_start();

            if (isset($_SESSION['user'])) {
              $foto_padrao = 'img/user_padrao.png';
              $foto_user = $_SESSION['user']['foto_usuario'] ?? $foto_padrao;

              // Ajuste o caminho do arquivo para raiz pública (depende de onde está o script)
              // Se o navbar está em pasta diferente, ajusta para acessar pasta correta
              $path_prefix = ''; // Exemplo: '', '../', '../../' conforme estrutura

              $foto_usar = (empty($foto_user) || $foto_user === $foto_padrao) ? $foto_padrao : $foto_user;
              $foto_url = $path_prefix . $foto_usar . '?t=' . time();

              echo '<a href="../../backend/usuarios/comum/perfilusuario.php">';
              echo '<img src="' . htmlspecialchars($foto_url) . '" alt="Perfil" style="width:50px; height:50px; border-radius:50%; margin-right: 20px;">';
              echo '</a>';
              echo '<a class="btnLogout" href="../backend/usuarios/comum/logout.php" style="border: #4E6422 1px solid; background-color: #737b3f; width: 80px; height: 30px; border-radius: 50px; color: #FFF5EA; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">Sair</a>';
            } else {
              echo '<a class="btnLogin" href="../backend/login/login_form.php"><button class="btnLogin" style="border: #4E6422 1px solid; background-color: #737b3f; width: 130px; height: 30px; border-radius: 50px; color: #FFF5EA;">Login</button></a>';
            }
            ?>

            <!-- Logo centralizada -->
            <div style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
              <img src="../../img/logonav2.png" alt="Logo" id="imgnav"
                style="width: 320px; max-height: 140px; object-fit: contain; display: block; margin: 0 auto;">
            </div>

          </div>
      </nav>
    </div>
    <div class="row">
      <div class="nav2">
        <ul class="nav justify-content-center">
          <li class="nav-item">
            <div class="col-sm">
              <a href="../frontend/pgInicial.html" class="nav-link" id="linksnav">Pagina Inicial </a>
            </div>
          </li>
          <li class="nav-item">
            <div class="col-sm">
              <a href="../frontend/parceiros/ongs.html" class="nav-link" id="linksnav">Parceiros</a>
            </div>
          </li>
          <li class="nav-item">
            <div class="col-sm">
              <a href="" class="nav-link" id="linksnav">Guias</a>
            </div>
          </li>
          <li class="nav-item">
            <div class="col-sm">
              <a href="../frontend/adoçao/adocao.html" class="nav-link" id="linksnav">Adoção</a>
            </div>
          </li>

          <?php
          if (isset($_SESSION['user']) && ($_SESSION['user']['tipo_usuario'] ?? '') === 'administrador') {
            echo '<li class="nav-item">';
            echo '<div class="col-sm">';
            echo '<a href="../backend/usuarios/adm/pgAdm.php" class="nav-link" id="linksnav">Administração</a>';
            echo '</div>';
            echo '</li>';
          }
          ?>

        </ul>
      </div>
    </div>


    <div class="pandora">
      <div class="row">
        <div class="col-5">
          <img src="../../img/gatinho.jpg" alt="" class="imgPTS">
        </div>
        <div class="col-7">
          <div class="conteudo">
            <h1 class="titulo01">
              Chico
              <i class="fa-regular fa-heart icon-favorite" data-animal-id="123" style="cursor:pointer;"></i>
            </h1>
            <h6 class="titulo02" style="color: #53662d;">Deficiente auditiva | 14 anos | Porte medio</h6>
            <p class="parag01">rua: das tulipas n°45, bairro vista alegre, Jundiaí-SP</p>
            <h3 class="titulo03">Historia</h3>
            <p class="parag02">Este é o Chico, um gatinho especial que perdeu a visão dos dois olhinhos, mas nunca a vontade de viver. Mesmo cego, ele se adapta com facilidade, explora o ambiente com curiosidade e distribui carinho com confiança. É um verdadeiro exemplo de superação e ternura. Tudo o que ele precisa é de um lar tranquilo, seguro e cheio de amor — exatamente o que ele tem de sobra pra oferecer.</p>
            <h3 class="titulo04">caracteristicas</h3>
            <div class="caracter">
              <div class="carac">
                cega
              </div>
              <div class="carac">
                carinhosa
              </div>
              <div class="carac">
                idosa
              </div>
              <div class="carac">
                gato
              </div>
              <div class="carac">
                docil
              </div>
              <div class="carac">
                docil
              </div>
            </div>
            <div class="caracter">
              <div class="carac">
                cega
              </div>
              <div class="carac">
                carinhosa
              </div>
              <div class="carac">
                idosa
              </div>
              <div class="carac">
                gato
              </div>
              <div class="carac">
                gato
              </div>
            </div>
            <div class="botaocnt">
              <button class="botao" data-bs-toggle="modal" data-bs-target="#exampleModal">Quero adotar</button>

              <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title titulomodal" id="exampleModalLabel">Ola Adotante!</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="background-color: #ffebcf;">
                      <p class="prgf">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minus ducimus eaque
                        corrupti commodi. Eum similique iste autem quidem, quis iusto eos dolor, blanditiis vitae
                        obcaecati, quibusdam odit minima. Quos, ipsum.</p>
                      <br>
                      <p>Contato para a adoção: <a href="https://wa.me/5511930475829"
                          class="whats"><strong></strong>(11)93047-5829</a></p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                      <button type="button" class="btn btn-outline-info">Save changes</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="cards">
      <h2 class="titulocatg">Deficiencias que podem te interessar</h2>
      <div class="cardsPET">
        <div class="row">
          <div class="col d-flex justify-content-center">
            <div class="card" style="width: 18rem;">
              <img src="../../img/gato-idoso.jpg" class="card-img-top imgcard" alt="...">
              <div class="card-body">
                <h5 class="card-title titulo">Idosos</h5>
                <p class="card-text">Nesta página, você encontrará informações detalhadas sobre os cuidados especiais necessários para animais idosos que podem ser de seu interesse.</p>
                <button class="botao2"><a href="#" class="link">Ver pagina</a></button>
              </div>
            </div>
          </div>
          <div class="col d-flex justify-content-center">
            <div class="card" style="width: 18rem;">
              <img src="../../img/auditiva.jpg" class="card-img-top imgcard" alt="...">
              <div class="card-body">
                <h5 class="card-title titulo">Deficiência visual</h5>
                <p class="card-text">Nesta página, você encontrará informações detalhadas sobre os cuidados necessários para animais com deficiência visual que podem te interessar.</p>
                <button class="botao2"><a href="#" class="link">Go somewhere</a></button>
              </div>
            </div>
          </div>
          <div class="col d-flex justify-content-center">
            <div class="card" style="width: 18rem;">
              <img src="../../img/gatos.jpg" class="card-img-top imgcard" alt="...">
              <div class="card-body">
                <h5 class="card-title titulo">Deficiência auditiva</h5>
                <p class="card-text">Nesta página, você encontrará informações detalhadas sobre os cuidados necessários para animais com deficiência auditiva que podem te interessar.</p>
                <button class="botao2"><a href="#" class="link">Go somewhere</a></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer>
      <div class="row">
        <div class="col logo">
          <img src="../../img/logofooter.png" alt="" style="width: 200px; height: 200px; margin-top: 20px;">
          <!-- <p style="font-size: 15px; color: #4E6422;">Todos os direitos <br> reservados</p> -->
        </div>
        <div class="col colabore">
          <h4 style="margin-top: 35px; color: #4E6422;">Colabore</h4>
          <p style="font-size: 17px; color: #4E6422; margin-top: 15px;">Doe qualquer valor!</p>
          <p style="font-size: 17px; color: #4E6422;">Cobertores, ração e itens são sempre bem-vindos para as ONG's!
          </p>
        </div>
        <div class="col redes">
          <h4 style="margin-top: 35px; color: #4E6422;">Siga-nos</h4>
          <a href="/"><img src="../../img/instagram.png" alt=""
              style="width: 30px; height: 30px; margin-right: 15px;"></a>
          <a href="/"><img src="../../img/facebook.png" alt=""
              style="width: 30px; height: 30px; margin-right: 15px;"></a>
          <a href="/"><img src="../../img/tktk.png" alt="" style="width: 30px; height: 30px;"></a>
        </div>
        <div class="col parceiros">
          <h4 style="margin-top: 35px; color: #4E6422;">Pawceiros</h4>
          <p style="font-size: 17px; color: #4E6422; margin-top: 15px;">ONG's</p>
          <p style="font-size: 17px; color: #4E6422;">Veterinários</p>
        </div>
      </div>
    </footer>



    <script src="../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/d486d2cd81.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
      // Aguarda o DOM estar carregado
      document.addEventListener('DOMContentLoaded', function() {
        // Seleciona todos os ícones favoritos
        document.querySelectorAll('.icon-favorite').forEach(function(icon) {
          icon.addEventListener('click', function(event) {
            event.preventDefault(); // Evita o reload da página

            this.classList.toggle('fa-regular');
            this.classList.toggle('fa-solid');
            this.classList.toggle('favorite');

            const animalId = this.getAttribute('data-animal-id');

            fetch('favoritar2.php', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id_animal=' + encodeURIComponent(animalId)
              })
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  console.log('Animal favoritado!');
                  // Você pode atualizar a lista de favoritos aqui sem recarregar
                } else {
                  console.error('Erro ao salvar favorito: ', data.error || data.message);
                }
              })
              .catch(error => {
                console.error('Erro na requisição: ', error);
              });
          });
        });
      });
    </script>
</body>

</html>