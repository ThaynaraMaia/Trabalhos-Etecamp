<?php

@include 'config.php';
session_start();
if(!isset($_SESSION['admin_name'])){
    header('location:login_form.php');
}

?>


<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin page</title>
    <link rel="stylesheet" href="#">
</head>
<body>
    <div class="container">
        <div class="content">
            <h3>oi,<span>admin</span></h3>
            <h1>welcome <span><?php echo $_SESSION['admin_name'] ?></span></h1>
            <p>This is an admin page</p>
            <a href="../php/login_form.php" class="btn">Login</a>
            <a href="../php/registro_form.php" class="btn">Registro</a>
            <a href="../php/logout.php" class="btn">Logout</a>
        </div>
    </div>
</body>
</html> -->

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
  <link rel="stylesheet" href="../css/home_admin.css">

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

            <a href="#" class="logo">
              <img src="../img/logodesenho.png" alt="logo">
            </a>

            <button class="nav-close-btn" data-nav-close-btn aria-label="Close Menu">
              <ion-icon name="close-outline"></ion-icon>
            </button>

          </div>

          <div class="navbar-bottom">
            <ul class="navbar-list">


              <li>
               <a href="../php/admin_page.php" class="navbar-link" data-nav-link>Início</a>
              </li>

              <li>
                <a href="../php/perfil.admin.php" class="navbar-link" data-nav-link>Perfil</a>
              </li>

              <!-- <li>
                <a href="../html/sobrenois.html" class="navbar-link" data-nav-link>Sobre Nós</a>
              </li> -->

              <!-- <li>
                <a href="../html/suporte.html" class="navbar-link" data-nav-link>Suporte</a>
              </li>
              -->
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

            <h2 class="h1 hero-title">Seja Bem Vindo(a) Professor(a) <?php echo $_SESSION['admin_name'] ?>!</h2>

            <p class="hero-text">
            Este é o seu ponto central para gerenciar e monitorar todas as atividades do sistema. Aqui, você terá acesso a informações e ferramentas essenciais para manter o controle total, visualizar relatórios, administrar usuários e configurar parâmetros do sistema.
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
              <a href="../php/admin_alterar_usuario.php" class="features-card">

                <div class="card-icon">
                  <ion-icon name=""></ion-icon>
                </div>

                <h3 class="card-title">Gerenciar Usuário</h3>

                <div class="card-btn">
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </div>

              </a>
            </li>


            <li>
              <a href="../php/admin_materia.php" class="features-card">

                <div class="card-icon">
                  <ion-icon name=""></ion-icon>
                </div>

                <h3 class="card-title">Editar Materiais</h3>

                <div class="card-btn">
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </div>

              </a>
            </li>


            <li>
              <a href="../php/admin_topicos.php" class="features-card">

                <div class="card-icon">
                  <ion-icon name=""></ion-icon>
                </div>

                <h3 class="card-title">Editar Tópicos</h3>

                <div class="card-btn">
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </div>

              </a>
            </li>

            <li>
              <a href="../php/admin_conteudos.php" class="features-card">

                <div class="card-icon">
                  <ion-icon name=""></ion-icon>
                </div>

                <h3 class="card-title">Editar Conteúdos</h3>

                <div class="card-btn">
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </div>

              </a>
            </li>

            <li>
              <a href="../QUIZ/admin/index.php" class="features-card">

                <div class="card-icon">
                  <ion-icon name=""></ion-icon>
                </div>

                <h3 class="card-title">Editar Simulados</h3>

                <div class="card-btn">
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </div>

              </a>
            </li>

            <li>
              <a href="../php/adimin_provas_anteriores.php" class="features-card">

                <div class="card-icon">
                  <ion-icon name=""></ion-icon>
                </div>

                <h3 class="card-title">Editar provas Aneriores</h3>

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
              <p class="footer-link">início</p>
            </li>

            <li>
              <a href="../php/perfil.admin.php" class="footer-link">Perfil</a>
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
              <a href="../php/admin_alterar_usuario.php" class="footer-link">Gerenciar Usuário</a>
            </li>

            <li>
              <a href="../php/adimin_provas_anteriores.php" class="footer-link">Editar provas Anteriores</a>
            </li>

            <li>
              <a href="../QUIZ/admin/index.php" class="footer-link">Editar Simulado</a>
            </li>

            <li>
              <a href="../php/admin_materia.php" class="footer-link">Editar Materiais</a>
            </li>

            <li>
              <a href="../php/admin_topicos.php" class="footer-link">Editar Tópicos</a>
            </li>

            <li>
              <a href="../php/admin_conteudos.php" class="footer-link">Editar Conteúdo</a>
            </li>
            

          </ul>

          <ul class="footer-list">

            <li>
              <p class="footer-list-title">Usuario</p>
            </li>

            <li>
              <a href="../php/registro_form.php" class="footer-link">Trocar de Conta</a>
            </li>

            <li>
              <a href="../php/perfil.admin.php" class="footer-link">Minha Conta</a>
            </li>

          
          </ul>

        </div>

      </div>
    </div>

    <div class="footer-bottom">
      <div class="container">

        <p class="copyright">
          &copy; 2024 <a href="#"></a>
        </p>

      </div>
    </div>

  </footer>

  <script src="../javascript/home.js"></script>

  
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>