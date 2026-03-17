<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

  <link rel="stylesheet" href="../css/stylepgi.css">

  <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">

  <title>Página Inicial</title>
  <style>
    #cima {
      border-radius: 10px;
      width: 450px;
      margin: 30px;
      margin-left: 115px;
    }

    #itemCima {
      background-color: #F1DEC3;
      padding: 30px;
    }

    #linkcima {
      text-decoration: none;
      color: #4E6422;
      font-size: 20px;
    }

    #linkcima:hover {
      color: #F1DEC3;
      transition: 0.3s;
    }

    .carrosel {
      margin: 30px;
      margin-left: 155px;
      /* background-color: #4E6422; */
      height: 365px;
      margin-right: 100px;
    }

    .imgCar {
      height: 365px;
      /* width: 100%;
      height: 100%; */
    }

    .partedecima {
      margin-top: 40px;
    }

    .meio {
      background-color: #F1DEC3;
      margin-top: 50px;
      padding-top: 20px;
      padding-bottom: 20px;
    }

    .rodape {
      background-color: white;
      margin-top: 150px;
      margin-bottom: 35px;
    }

    .teste {
      border: 1px solid #4E6422;
    }

    .paragrafo {
      margin: 15px 30px 10px 5px;
      /* top right bottom left */
      text-align: justify;
      font-size: 20px;
      color: #313131;
    }

    .paragraCon {
      font-size: 20px;
      text-align: justify;
      margin: 30px 100px 50px;
      /* top right bottom left */
      color: #313131;
    }

    .img01 {
      width: 1150px;
      height: 500px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-wrap: wrap;
      margin-bottom: 70px;
    }

    .img02 {
      width: 590px;
      height: 540px;
      margin-bottom: 50px;
      margin-right: 65px;
    }
  </style>
</head>


<body>
  <div class="container-fluid">
    <div class="row" id="rownav" style="background-color: #A8B16B;">
      <nav class="navbar position-relative" style="height: 135px; position: relative;">
        <div class="container position-relative" style="position: relative;">

          <!-- Botão à direita -->
          <div class="ms-auto">
            <a href="../imgUsuarios/user_padrao.png"></a>
            <?php
            session_start();

            if (isset($_SESSION['user'])) {
              // Caminho padrão da foto do usuário
              $foto_padrao = '/imgUsuarios/user_padrao.png'; // Usando o caminho correto para a imagem padrão
            
              // Usa a foto do usuário ou a foto padrão se estiver vazia
              $foto_user = !empty($_SESSION['user']['foto_usuario']) ? $_SESSION['user']['foto_usuario'] : $foto_padrao;

              // Verifica se a imagem do usuário é a imagem padrão ou personalizada
              if ($foto_user == $foto_padrao) {
                // Caminho da imagem padrão com "../"
                $caminho_imagem = '../' . $foto_user;
              } else {
                // Caminho da imagem personalizada (sem "../")
                $caminho_imagem = $foto_user;
              }

              // Exibe o link para o perfil com a foto do usuário
              echo '<a href="../backend/usuarios/comum/perfilusuario.php">';
              echo '<img src="' . htmlspecialchars($caminho_imagem) . '?t=' . time() . '" alt="Perfil" style="width:50px; height:50px; border-radius:50%; margin-right:20px;">';
              echo '</a>';

              // Botão de logout
              echo '<a class="btnLogout" href="../backend/usuarios/comum/logout.php" style="border: #4E6422 1px solid; background-color: #737b3f; width: 80px; height: 30px; border-radius: 50px; color: #FFF5EA; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">Sair</a>';
            } else {
              // Caso o usuário não esteja logado, exibe o botão de login
              echo '<a class="btnLogin" href="../backend/login/login_form.php"><button class="btnLogin" style="border: #4E6422 1px solid; background-color: #737b3f; width: 130px; height: 30px; border-radius: 50px; color: #FFF5EA;">Login</button></a>';
            }
            ?>

            <!-- Logo centralizada -->
            <div style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
              <img src="../img/logonav2.png" alt="Logo" id="imgnav"
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
              <a href="../frontend/pgInicial.php" class="nav-link" id="linksnav">Pagina Inicial </a>
            </div>
          </li>
          <li class="nav-item">
            <div class="col-sm">
              <a href="../frontend/parceiros/parceiros.php" class="nav-link" id="linksnav">Parceiros</a>
            </div>
          </li>
          <li class="nav-item">
            <div class="col-sm">
              <a href="../frontend/deficiencias/guiasc.php" class="nav-link" id="linksnav">Guias</a>
            </div>
          </li>
          <li class="nav-item">
            <div class="col-sm">
              <a href="../frontend/adocao/adocao2.php" class="nav-link" id="linksnav">Adoção</a>
            </div>
          </li>
          <li class="nav-item">
            <div class="col-sm">
              <a href="../frontend/locais.php" class="nav-link" id="linksnav">Locais</a>
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

    <div class="partedecima">
      <div class="categorias">
        <div class="row">
          <div class="col-4">
            <ul class="list-group list-group-flush" id="cima">
              <li class="list-group-item" id="itemCima" style="background-color: #A8B16B;">
                <a href="../frontend/deficiencias/guiasc.php" id="linkcima">Deficiência motora</a>
              </li>
              <li class="list-group-item" id="itemCima" style="background-color: #A8B16B;">
                <a href="../frontend/deficiencias/guiasc.php" id="linkcima">Deficiência visual</a>
              </li>
              <li class="list-group-item" id="itemCima" style="background-color: #A8B16B;">
                <a href="../frontend/deficiencias/guiasc.php" id="linkcima">Deficiência auditiva</a>
              </li>
              <li class="list-group-item" id="itemCima" style="background-color: #A8B16B;">
                <a href="../frontend/deficiencias/guiasc.php" id="linkcima">Idosos</a>
              </li>
            </ul>
          </div>

          <div class="col-8">
            <div class="carrosel">
              <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img src="../img/carrossel1.png" class="imgCar d-block w-100" alt="...">
                  </div>
                  <div class="carousel-item">
                    <img src="../img/carrossel3.png" class="imgCar d-block w-100" alt="...">
                  </div>
                  <div class="carousel-item">
                    <img src="../img/carrossel2.png" class="imgCar d-block w-100" alt="...">
                  </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                  data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                  data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="conteudo">
      <div class="textoCont">
        <h1 class="tituloCont">Nosso Trabalho</h1>
        <p class="paragraCon">O Pawsitive surgiu em 2025 como um projeto elaborado para fins didáticos no Trabalho de
          Conclusão de Curso (TCC) de três estudantes do Ensino Médio integrado ao Curso Técnico de Informática para
          Internet em uma das ETEC’s da região de Jundiaí (SP), o propósito era contribuir com a elaboração de um guia
          on-line para auxiliar tutores de cães e gatos idosos e deficientes no processo de cuidado desses animaizinhos.
          Mayra, Melissa e Talita foram motivadas a criar essa iniciativa ao compreender as dificuldades e escassez que
          esses tutores enfrentavam ao buscar por informações, materiais, profissionais e assistências que contribuiriam
          com o objetivo de proporcionar bem-estar e qualidade de vida para determinados pets.
          Assim como os seres humanos, os animais domésticos — principalmente cães e gatos — podem nascer com
          deficiências ou adquiri-las ao decorrer da vida, com o envelhecimento sendo uma das principais causas. Esses
          animais passam a necessitar de maiores cuidados e assistências adequadas para que, assim como qualquer outro,
          eles possam ter o conforto cotidiano como garantia.
          Infelizmente, pela ausência da informação de qualidade e da consciência sobre o cuidado ideal, muitos deles
          são abandonados, enfrentam preconceito e dificuldade para serem adotados ou até mesmo recebem eutanásia de
          forma equivocada e desnecessária.</p>
      </div>
      <div class="imgCont">
        <img class="img01" src="../imgAnimais/ceguinho.jpg" alt="">
      </div>
      <div class="texto2cont">
        <div class="row">
          <div class="col">
            <p class="paragrafo">Por isso, o Pawsitive compreende a necessidade de conscientizar o público sobre pets
              idosos e deficientes e entende que ao acolher o tutor e disponibilizar informações e orientações
              qualificadas, contribui diretamente com a inclusão desses pets na sociedade. Aqui, você pode encontrar
              inúmeros passo-a-passo de como cuidar de animais com condições específicas, adaptações recomendadas,
              alimentação e nutrição, contato e indicações de clínicas, ONG’s, veterinários, comércios e outros
              parceiros que podem contribuir com o dia a dia do pet.
              O Pawsitive também oferece àqueles que buscam a companhia de um bichinho especial e estão cientes das
              assistências exigidas, a ferramenta de encontrar e adotar esses pets com uma de nossas ONG’s parceiras!
              Descubra mais ao explorar nosso site e entre em contato conosco para mais informações.</p>
          </div>
          <div class="col">
            <div class="imgCont2">
              <img class="img02" src="../imgAnimais/feliz2.jpg" alt="">
            </div>

          </div>
        </div>
      </div>

    </div>
    <div class="row">
      <div class="meio">
        <div class="col"></div>
        <div class="col">
          <h1 class="paw">Pawceiros</h1>
        </div>
        <div class="col"></div>

        <div class="ongs">
          <div class="cards">
            <?php
            require_once '../backend/classes/class_Ong.php';
            require_once '../backend/classes/class_IRepositorioOng.php';

            $repositorio = new RepositorioOngMYSQL();
            $ongs = $repositorio->listarTodasOngs();
            $ongs = array_slice($ongs, 0, 3);
            ?>

            <div class="ongs">
              <div class="cards">

                <?php if (!empty($ongs)): ?>
                  <?php foreach ($ongs as $ong): ?>
                    <div class="cardMeu">
                      <div class="imgC">

                        <img src="../<?php echo htmlspecialchars($ong->getFotoOng()); ?>"
                          alt="<?php echo htmlspecialchars($ong->getNomeOng()); ?>" class="imgcd">
                      </div>
                      <h1 class="titulo"><?php echo htmlspecialchars($ong->getNomeOng()); ?></h1>
                      <div class="content">
                        <p class="descricao2">
                          <?php echo nl2br(htmlspecialchars($ong->getHistoriaOng())); ?>
                        </p>
                        <a href="parceiros/detalhe_parceiros.php?id=<?php echo $ong->getIdOng(); ?>" class="linkC">Saiba
                          mais</a>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p style="text-align:center;">Nenhuma ONG cadastrada ainda.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        <!-- <div class="row">
        <div class="col"></div>
        <div class="col">
          <button class="botao">Saiba mais</button>
        </div>
        <div class="col"></div>
      </div> -->
      </div>
    </div>


    <footer class="rodape">
      <div class="row ">
        <hr class="teste">
        <div class="col logo">
          <img src="../img/logofooter2.png" alt="" style="width: 150px; height: 150px; margin: 20px;
          margin-left: 80px">
          <!-- <p style="font-size: 15px; color: #4E6422;">Todos os direitos <br> reservados</p> -->
        </div>
        <div class="col colabore">
          <h4 style="margin-top: 35px; color: #4E6422;">Colabore</h4>
          <p style="font-size: 17px; color: #4E6422; margin-top: 15px;">Doe qualquer valor!</p>
          <p style="font-size: 17px; color: #4E6422;">Cobertores, ração e itens são <br> sempre bem-vindos para as
            ONG's!
          </p>
        </div>
        <div class="col redes">
          <h4 style="margin-top: 35px; color: #4E6422;">Siga-nos</h4>
          <a href="/"><img src="../img/instagram.png" alt="" style="width: 30px; height: 30px; margin-right: 15px;"></a>
          <a href="/"><img src="../img/facebook.png" alt="" style="width: 30px; height: 30px; margin-right: 15px;"></a>
          <a href="/"><img src="../img/tktk.png" alt="" style="width: 30px; height: 30px;"></a>
        </div>
        <div class="col parceiros">
          <h4 style="margin-top: 35px; color: #4E6422;">Pawceiros</h4>
          <p style="font-size: 17px; color: #4E6422; margin-top: 15px;">ONG's</p>
          <p style="font-size: 17px; color: #4E6422;">Veterinários</p>
        </div>
      </div>
    </footer>
  </div>



  <script src="../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/d486d2cd81.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="../frontend/PgPrincipal.js"></script>
  <script>
    var swiper = new Swiper(".mySwiper", {
      slidesPerView: 3,
      spaceBetween: 30,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
    });
  </script>
</body>

</html>