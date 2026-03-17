<?php
include_once '../classes/conn.php';

session_start();

// Recupera o ID do tópico da URL
$id = $_GET['id'];

// Recupera o tópico
$sql = 'SELECT * FROM topico WHERE id = '.$id;
$res = $conn->query($sql);
// if ($res && $res->num_rows > 0) {
$linha = $res->fetch_assoc();
// } else {
//     die("Tópico não encontrado.");
// }
if($linha['restrito']==1 && $_SESSION['tipo']==0){
    header('Location: main.php');   
}
// Verifica se o usuário está logado
$logged = isset($_SESSION['userid']);

// Envio de comentário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comentario'])) {
    $comentario = $conn->real_escape_string($_POST['comentario']);
    $data = date('Y-m-d'); // Data atual
    $postId = $id; // ID do tópico

    if ($logged) {
        $userid = $_SESSION['userid']; // Obtém o userid da sessão

        // Verifica se o usuário existe na tabela tbl_usuarios
        $sql_check_user = "SELECT * FROM tbl_usuarios WHERE id = '$userid'";
        $result = $conn->query($sql_check_user);

        if ($result && $result->num_rows > 0) {
            // O usuário existe, insere o comentário
            $sql = "INSERT INTO comentarios (body, data, userid, idcomentario) VALUES ('$comentario', '$data', '$userid', '$postId')";
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Comentário adicionado com sucesso!');</script>";
                header ('Location: topico.php?id='.$_GET['id']);
            } else {
                echo "<script>alert('Erro ao adicionar comentário: " . $conn->error."');</script>";
                header ('Location: topico.php?id='.$_GET['id']);
            }
        } else {
            echo "<script>alert('Erro: Usuário não encontrado.');</script>";
            header ('Location: topico.php?id='.$_GET['id']);
        }
    } else {
        
        echo "<script>alert('Você precisa fazer login para comentar!');</script>";
        header ('Location: topico.php?id='.$_GET['id']);
        
        // header('Location: teste.php?id=44&mensagem=True');
    }
}

// Recupera os comentários do tópico
$sql_comentarios = "SELECT * FROM comentarios WHERE idcomentario=".$id;
$result_comentarios = $conn->query($sql_comentarios);

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../frontend/public/css/teste.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        *{text-align:center;}
    </style>
    <link rel="icon" href="../../frontend/public/imagens/recursos/logo.png">
    <title><?php echo htmlspecialchars($linha['nome']); ?></title>
    <style>
    *{
        text-align: center;
    }
    .user-post {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .input-custom-sm {
        width: 60%;
        padding: 8px 15px;
        font-size: 14px;
        border: 2px solid #ccc;
        border-radius: 8px;
        outline: none;
        transition: all 0.3s ease;
    }

    .input-custom-sm:focus {
        border-color: #81B973;
        box-shadow: 0 0 8px rgba(129, 185, 115, 0.4);
    }

    .btn-custom-sm {
        width: 60%;
        padding: 10px;
        background-color: #81B973;
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 16px;
        cursor: pointer;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s, box-shadow 0.3s;
}

  .btn-custom-sm:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
  }

  .btn-custom-sm:active {
      transform: translateY(0);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  } 

    .respostainput{
        width: 50%;
    }
    
    .post-title {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 15px;
        text-align: center;
    }

    .comentar{
        width: 110%;
        margin-top: -70px;
    }

    .responder{
        width: 110%;
        margin-top: -70px;
    }
    
    .post-image {
        display: block;
        margin: 0 auto;
        border-radius: 8px;
    }
    
    .post-text {
        font-size: 18px;
        color: #333;
        text-align: justify;
    }
    
    .divider {
        border: none;
        border-top: 2px solid #81B973;
        width: 80%;
    }

    
.topico-container {
    max-width: 1440px;    
    background-color: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}
    
    ul {
        padding-left: 0;
    }
    
    .resposta {
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    border-radius: 8px;
    padding: 10px;
    margin-top: 10px;
    word-wrap: break-word;
}

    
    .input-custom {
        width: 100%;
        padding: 12px 20px;
        font-size: 16px;
        border: 2px solid #ccc;
        border-radius: 8px;
        outline: none;
        transition: all 0.3s ease;
        margin-bottom: 10px;
    }
    
    .input-custom:focus {
        border-color: #81B973;
        box-shadow: 0 0 8px rgba(129, 185, 115, 0.4);
    }
    
    .btn-custom {
        width: 70%;
        padding: 15px;
        background-color: #81B973;
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 18px;
        cursor: pointer;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .btn-custom:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }
    
    .btn-custom:active {
        transform: translateY(0);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .container {
    max-width: 1440px;
}

.user-post {
    background-color: #ffffff;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.post-title {
    font-size: 24px;
    color: #343a40;
}

.post-image {
    border-radius: 8px;
}

.comentario {
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
}

.comentario-conteudo {
    margin-left: 10px;
}

.resposta {
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    border-radius: 8px;
    padding: 10px;
    margin-top: 10px;
}

.btn-custom {
    width: 60%;
    padding: 15px;
    background-color: #81B973;
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 18px;
    cursor: pointer;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s, box-shadow 0.3s;
}

.btn-custom:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
}

.btn-custom:active {
    transform: translateY(0);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.btn-custom-sm {
    width: 60%;
    padding: 10px;
    background-color: #81B973;
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 16px;
    cursor: pointer;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s, box-shadow 0.3s;
}

.btn-custom-sm:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
}

.btn-custom-sm:active {
    transform: translateY(0);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.input-custom {
    width: 100%;
    padding: 12px 20px;
    font-size: 16px;
    border: 2px solid #ccc;
    border-radius: 8px;
    outline: none;
    transition: all 0.3s ease;
}

.input-custom:focus {
    border-color: #81B973;
    box-shadow: 0 0 8px rgba(129, 185, 115, 0.4);
}

.input-custom-sm {
    width: 60%;
    padding: 8px 15px;
    font-size: 14px;
    border: 2px solid #ccc;
    border-radius: 8px;
    outline: none;
    transition: all 0.3s ease;
}

.input-custom-sm:focus {
    border-color: #81B973;
    box-shadow: 0 0 8px rgba(129, 185, 115, 0.4);
}

#part-1 label:after {
    content: " *";
    color: red;
}

#part-2 label:after {
    content: " *";
    color: red;
}

textarea {
    resize: none;
}

.post-text {
    line-height: 1.6;
}

@media (max-width: 576px) {

    .input-custom,
    .input-custom-sm {
        width: 100%;
    }

    .post-title {
        font-size: 20px;
    }
}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg py-3 custom-navbar">
    <div class="container">

      <a class="navbar-brand" href="../../frontend/principal/index.php">
        <img src="../../frontend/public/imagens/recursos/logo.png " height="80rem" width="80rem" alt="Logo" height="40">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <img src="../../frontend/public/imagens/recursos/menu.png" width="20px" alt="">
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
          <a class="nav-link active" aria-current="page" target="blank" href="../../frontend/public/docs/POR_2013_19_V2.pdf">Regulamento Interno</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../backend/galeria/index.php">Galeria</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../backend/Evento/index.php">Programação Semestral</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../backend/mailer/fale.php">Fale Conosco</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../backend/forum/main.php">Fórum</a>
          </li>
          <?php 
          if($logged){
            if($_SESSION['tipo']>=1){
              echo '<li class="nav-item">
                <a class="nav-link" href="../../backend/loja/loja.php">Loja</a>
              </li>';
            }
            if($_SESSION['tipo']==3){
              echo '<li class="nav-item">
                <a class="nav-link" href="../../backend/adm/index_adm.php">ADM</a>
              </li>';
            }
            echo '<li class="nav-item">
            <a class="nav-link" href="../../frontend/principal/user.php?id='.$_SESSION["userid"].'">'.$_SESSION["nome"].'</a>
                <li class="nav-item">
            <a class="nav-link" href="../../backend/notificacoes/index.php"><img src="../../frontend/public/imagens/recursos/sino.png" width="20px"></a>
              </li>';
          }
          else{
            echo '<li class="nav-item">
                <a class="nav-link" href="../../backend/login/login.php"><strong>Faça Login</strong></a>
              </li>';
          }
            ?>
        </ul>
      </div>
    </div>
  </nav>
  <section class="container topico-container my-5 d-flex flex-column align-items-center">

<div class="user-post w-100 mb-5">
    <h1 class="post-title text-center"><?php echo htmlspecialchars($linha['nome']); ?></h1>
    <?php if (!empty($linha['anexos'])): ?>
        <img src="uploads/<?php echo $linha['anexos'] ?>" alt="Imagem do tópico" class="post-image" width="40%"><br>
    <?php endif; ?>
    <p class="post-text text-center">
        <?php echo nl2br(htmlspecialchars($linha['body'])); ?>
    </p>
</div>

<hr class="divider my-5 w-100">

<h2 class="text-center mb-4">Comentários</h2>
<div class="comentar w-100 mb-5">
    <form action="" method="post" class="d-flex flex-column align-items-center">
        <input type="text" class="input-custom mb-3 w-75" name="comentario" required placeholder="Escreva seu comentário">
        <button type="submit" class="btn-custom">Comentar</button>
    </form>
</div>

<h3 class="text-center mt-5 mb-4">Comentários recentes</h3>

<ul class="list-unstyled w-100">
    <?php while ($row = $result_comentarios->fetch_assoc()): ?>
        <li class="comentario mb-4">
            <?php 
            $sql_usuario = "SELECT * FROM tbl_usuarios WHERE id=".$row['userid']; 
            $userres = $conn->query($sql_usuario);
            $user_row = $userres->fetch_assoc();
            ?>

            <div class="comentario-conteudo">
                <strong><?php echo htmlspecialchars($user_row['nome']); ?>:</strong> 
                <p class="mb-2"><?php echo nl2br(htmlspecialchars($row['body'])); ?></p>
                
                <div class="responder mt-2">
                    <form method="POST" action="responder_comentario.php" class="d-flex flex-column align-items-start">
                        <input type="hidden" name="post_id" value="<?php echo $id; ?>">
                        <input type="hidden" name="comentario_id" value="<?php echo $row['id']; ?>">
                        <input type="text" name="resposta" class="input-custom-sm mb-2" placeholder="Responder">
                        <button type="submit" class="btn-custom-sm">Responder</button>
                    </form>
                </div>

                <?php
                $respostas_sql = "SELECT * FROM respostas WHERE comentario_id = ".$row['id'];
                $respostas_result = $conn->query($respostas_sql);
                if ($respostas_result->num_rows > 0): ?>
                    <ul class="list-unstyled mt-3 ml-4">
                        <?php while ($resposta = $respostas_result->fetch_assoc()): 
                            $res_user_sql = "SELECT * FROM tbl_usuarios WHERE id=".$resposta['usuario_id']; 
                            $res_user_res = $conn->query($res_user_sql);
                            $res_user_row = $res_user_res->fetch_assoc();
                        ?>
                        <li class="resposta mb-3">
                            <strong><?php echo htmlspecialchars($res_user_row['nome']); ?>:</strong> 
                            <?php echo nl2br(htmlspecialchars($resposta['resposta'])); ?>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </li>
    <?php endwhile; ?>
</ul>
</section>

        
     


    <footer class="footer text-center text-lg-start d-flex w-100 justify-content-between align-items-center">
    <div class="container p-3 d-flex justify-content-between w-100">
      <span class="text-muted">© 2024 Grupo Escoteiro João Paulo II. Todos os direitos reservados.</span>
      <div class="social-icons">
        <a href="https://www.facebook.com/GrupoescoteirojoaopauloII?mibextid=ZbWKwL" target="_blank" class="text-muted me-3">
          <i class="bi bi-facebook"></i>
        </a>
        <a href="https://youtube.com/@gejoaopauloii?si=8nJBM0zWxWBofH_V" target="_blank" class="text-muted me-3">
          <i class="bi bi-youtube"></i>
        </a>
        <a href="https://www.instagram.com/ge_joaopaulo2?igsh=MWFiOTA2OTFxYW11" target="_blank" class="text-muted me-3">
          <i class="bi bi-instagram"></i>
        </a>
        <a href="https://www.tiktok.com/@ge._joaopauloii?_t=8nG7cVdsBGQ&_r=1" target="_blank" class="text-muted">
          <i class="bi bi-tiktok"></i>
        </a>
      </div>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>