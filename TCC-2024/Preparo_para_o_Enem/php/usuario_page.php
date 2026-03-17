<?php

@include 'config.php';
session_start();
if(!isset($_SESSION['user_nome'])){
    header('location:login_form.php');
}

?>





<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ENEM PlatD</title>

  <!-- 
    - favicon
  -->
  <!-- <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml"> -->

  <!-- 
    - custom css link
  -->
  <link rel="stylesheet" href="../css/home.css">

  <!-- 
    - google font link
  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="shortcut icon" href="../img/logoxampp.png" type="image/x-icon">
  <link
    href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&family=Poppins:wght@400;500;600;700&display=swap"
    rel="stylesheet">
    
</head>

<body>

  <!-- 
    - #HEADER
  -->

  <header class="header" data-header>

    <div class="header-bottom">
      <div class="container">
       

        <nav class="navbar" data-navbar>

          <div class="navbar-top">

           

           

          </div>

          <div class="navbar-bottom">
          <!-- <div href="#" class="logo"> 
              <p>EnemPlatd</p> -->
              <!-- <img src="../img/logo escritalogin.png" alt="logo"> -->
            <!-- </div> -->
            
          
            <ul class="navbar-list">


              <li>
               <a href="../php/usuario_page.php" class="navbar-link" data-nav-link>Início</a>
              </li>

              <li>
                <a href="../php/perfil.php" class="navbar-link" data-nav-link>Perfil</a>
              </li>

              <li>
                <a href="../html/sobrenois.html" class="navbar-link" data-nav-link>Sobre Nós</a>
              </li>

              <li>
                <a href="../html/suporte.html" class="navbar-link" data-nav-link>Suporte</a>
              </li>
             
            </ul>
          </div>

        </nav>

        <div class="header-bottom-actions">

  
          </button>

          <a href="../php/registro_form.php"><button class="btn cta-btn">
            <ion-icon name="person-outline"></ion-icon>
              <span>Trocar de Conta </span>
              <ion-icon name="arrow-forward-outline"></ion-icon>
            </button></a>

          

         

        </div>

      </div>
    </div>

  </header>





  <main>
    <article>

      <!-- 
        - #HERO
      -->

      <section class="hero" id="home">
        <div class="container">

          <div class="hero-content">

            <!-- <p class="hero-subtitle">
              <ion-icon name="home"></ion-icon>

              <span>Real Estate Agency</span>
            </p> -->

            <h2 class="h1 hero-title">Seja Bem Vindo(a)!<span><?php echo $_SESSION['user_nome'] ?></span></h2>

            <p class="hero-text">
             Estamos aqui para te ajudar a mandar bem no Enem. Aproveite nossos recursos e alcance seus objetivos!
            </p>

           

          </div>

          <figure class="hero-banner">
            <img src="../img/logodesenho.png" alt="Modern house model" class="w-100">
          </figure>

        </div>
      </section>

      



      <!-- 
        - #FEATURES
      -->

      <section class="features">
        <div class="container">


          <h2 class="h2 section-title"></h2>

          <ul class="features-list">

            <li>
              <a href="../php/usuario_provas_anteriores.php" class="features-card">

                <div class="card-icon">
                  <ion-icon name=""></ion-icon>
                </div>

                <h3 class="card-title">ENEM <p>Provas Anteriores</p></h3>

                <div class="card-btn">
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </div>

              </a>
            </li>

            <li>
              <a href="../html/passeios.html" class="features-card">

                <div class="card-icon">
                  <ion-icon name=""></ion-icon>
                </div>

                <h3 class="card-title">Passeios Educativos</h3>

                <div class="card-btn">
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </div>

              </a>
            </li>

            <li>
              <a href="../planner/php/planner.php" class="features-card">

                <div class="card-icon">
                  <ion-icon name=""></ion-icon>
                </div>

                <h3 class="card-title">Planner</h3>

                <div class="card-btn">
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </div>

              </a>
            </li>

            <li>
              <a href="../php/materias.php" class="features-card">

                <div class="card-icon">
                  <ion-icon name=""></ion-icon>
                </div>

                <h3 class="card-title">Materiais </h3>

                <div class="card-btn">
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </div>

              </a>
            </li>

            <li>
              <a href="../QUIZ/php/quizzes_list.php" class="features-card">

                <div class="card-icon">
                  <ion-icon name=""></ion-icon>
                </div>

                <h3 class="card-title">Simulados</h3>

                <div class="card-btn">
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </div>

              </a>
            </li>






          </ul>

        </div>
        
        
      </section>

     






      <!-- 
        - #CTA
      -->

      <section class="cta">
        <div class="container">

          <div class="cta-card">
            <div class="card-content">

            </div>

            
          </div>

        </div>
      </section>

    </article>
  </main>





  <!-- 
    - #FOOTER
  -->

  <footer class="footer">

    <div class="footer-top">
      <div class="container">

        <div class="footer-brand">

          <a href="#" class="logo">
            <img src="../img/Design sem nome (1).png" alt="logo">
          </a>

         

          <ul class="contact-list">

            <li>
              <a href="#" class="contact-link">
                <ion-icon name="location-outline"></ion-icon>

                <address>Etec de Campo Limpo Paulista </address>
              </a>
            </li>

            <li>
              <a href="https://wa.me/5511978955191?text=EnemPlatD" class="contact-link">
                <ion-icon name="call-outline"></ion-icon>

                <span>+55 11 97895-5191</span>
                
              </a>
            </li>

            <li>
              <a href="mailto:contact@homeverse.com" class="contact-link">
                <ion-icon name="mail-outline"></ion-icon>

                <span>EnemPlatd@gmail.com</span>
              </a>
            </li>

          </ul>

          

        </div>

        <div class="footer-link-box">

          <ul class="footer-list">

            <li>
              <p class="footer-list-title">Início</p>
            </li>

            <li>
              <p class="footer-link">Início</p>
            </li>

            <li>
              <a href="../php/perfil.php" class="footer-link">Perfil</a>
            </li>

            <li>
              <a href="../html/sobrenois.html" class="footer-link">Sobre Nós</a>
            </li>

            <li>
              <a href="../html/suporte.html" class="footer-link">Suporte</a>
            </li>

           
          </ul>

          <ul class="footer-list">

            <li>
              <p class="footer-list-title">Serviços</p>
            </li>

            <li>
              <a href="../php/usuario_provas_anteriores.php" class="footer-link">Provas Anteriores</a>
            </li>

            <li>
              <a href="../html/passeios.html" class="footer-link">Passeios Educativos</a>
            </li>

            <li>
              <a href="../planner/php/planner.php" class="footer-link">Planner </a>
            </li>

            <li>
              <a href="../php/materias.php" class="footer-link">Materiais</a>
            </li>

            <li>
              <a href="../QUIZ/php/quizzes_list.php" class="footer-link">Simulados </a>
            </li>

            

          </ul>

          <ul class="footer-list">

            <li>
              <p class="footer-list-title">Usuário</p>
            </li>

            <li>
              <a href="../html/a_inicio.html" class="footer-link">Cadastro</a>
            </li>

            <li>
              <a href="../php/perfil.php" class="footer-link">Minha Conta</a>
            </li>

          
          </ul>

        </div>

      </div>
    </div>

    <div class="footer-bottom">
      <div class="container">

        <p class="copyright">
          &copy; EnemPlatD <a href="#"></a>
        </p>

      </div>
    </div>

  </footer>

  <script src="../javascript/home.js"></script>

  
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>