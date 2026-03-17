<?php
session_start();

// Supondo que você tenha armazenado o nome da foto na sessão ou que tenha consultado o banco de dados
$fotoPerfil = isset($_SESSION['foto_perfil']) ? $_SESSION['foto_perfil'] : 'default.jpg'; // Use uma imagem padrão se não houver foto

// Caminho para a pasta onde as fotos de perfil estão armazenadas
$caminhoFotoPerfil = "../../frontend/img/fotoPerfil" . htmlspecialchars($fotoPerfil);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/perfil.css">
    <link rel="stylesheet" href="../css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poetsen+One&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="imagex/png" href="../img/mercury.simples.ico">
    <title>Mercury</title>
</head>
<body>
<nav id="sidebar" role="navigation">
    <div id="sidebar_content">
        <div id="user">
            <img src="../img/perfil.png" id="user_avatar" alt="Avatar">

            <p id="user_infos">
                <span class="item-description perfil">
                <?php
                    echo ($_SESSION['nome']);
                ?>
                </span>
                <span class="item-description">
                <?php
                    echo ($_SESSION['email']);
                ?>
                </span>
            </p>
        </div>

        <ul id="side_items">
            <li class="side-item">
                <a href="../html/home.php">
                  <i class="fa-solid fa-house"></i>
                    <span class="item-description">
                        Home
                    </span>
                </a>
            </li>

            <li class="side-item active">
                <a href="../html/perfil.php">
                    <i class="fa-solid fa-user"></i>
                    <span class="item-description">
                        Perfil
                    </span>
                </a>
            </li>

            <li class="side-item">
                <a href="../html/meus_hobbies.php">
                  <i class="fa-solid fa-paintbrush"></i>
                    <span class="item-description">
                        Meus Hobbies
                    </span>
                </a>
            </li>
        </ul>

        <button aria-label="Abrir menu" id="open_btn">
            <i id="open_btn_icon" class="fa-solid fa-chevron-right"></i>
        </button>
    </div>

        <div id="logout">
            <button aria-label="Cadastrar" id="logout_btn" onclick="window.location.href='../../backend/login/logout.php'">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="item-description">Logout</span>
            </button>
        </div>
</nav>

<div class="container">
    <div class="header">
        <div class="row">
            <div class="col-5">
                    
            </div>
            <div class="col-4">
                <img src="../img/logo.png" alt="" class="logo">
            </div> 
            <div class="col-3">
                    
            </div>
        </div>
    </div>

    <div class="section">
        <div class="profile-card">
            <div class="profile-header">
                <img src="<?php echo ($_SESSION['foto_perfil']);?>">
                <div class="profile-info">
                    <h2><?php echo ($_SESSION['nome']); ?></h2>
                    <p class="role"><?php echo ($_SESSION['email']); ?></p>
                </div>
            </div>
        <div class="profile-actions">
            <form id="uploadForm" action="../../backend/perfil/upload.php" method="post" enctype="multipart/form-data" style="display: none;">
                <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*">
                <button type="submit">Salvar</button>
            </form>
            <button class="picture-btn" id="addPhotoBtn">Adicionar foto</button>
        </div>
    </div>
</div>

</div>
<script src="../../backend/scripts/scriptperfil.js"></script>
<script src="../../backend/scripts/script.js"></script>
</body>
</html>