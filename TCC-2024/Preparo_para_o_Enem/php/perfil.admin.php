<?php

session_start();

// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'enemplatd_bd');

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Aqui você pode obter o ID do usuário de uma sessão ou de um parâmetro GET
// $id_usuario = 1;  // Exemplo: ID do usuário

$id_usuario = $_SESSION['usuario_id'];


$sql = "SELECT * FROM usuario_form WHERE usuario_id = $id_usuario";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/perfil_teste.css">
    <link rel="shortcut icon" href="../img/logoxampp.png" type="image/x-icon">
    <title>EnemPlatD</title>
</head>
<body>
    
<div class="container light-style flex-grow-1 container-p-y">
    <h4 class="font-weight-bold py-3 mb-4">
        Perfil do Administrador
    </h4>
    <div class="card-overflow-hidden">
        <div class="row no-gutters row-bordered row-border-light">
            <div class="col-md-3 pt-0">
                <div class="menu-lateral">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active"  href="#account-general">Geral</a>
                        <a class="list-group-item list-group-item-action"  href="./admin_page.php">Home</a>
                       
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="account-general">
                        <div class="card-body media align-items-center">
                        <img id="profile-pic" src="../img/<?php echo $user['foto_perfil'] ? $user['foto_perfil'] : 'default-profile.png'; ?>" alt="Sua Foto" class="profile-pic">
                            <div class="media-body ml-4">
                            <h4 class="font-weight-bold py-3 mb-4">Perfil de <?php echo $user['nome']; ?></h4>
                                <!-- <label class="btn btn-outline-primary">
                                    Trocar de foto 
                                    <input type="file" class="account-settings-fileinput" id="file-input">
                                </label> -->
                                <!-- <div class="text-light small mt-1"><?php echo $user['foto_perfil']; ?></div> -->
                            </div>
                        </div>
                        <hr class="border-light m-0">
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">Nome: <?php echo $user['nome']; ?></label>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Sobrenome: <?php echo $user['sobrenome']; ?></label>
                            </div>
                            <div class="form-group">
                                <label class="form-label">E-mail: <?php echo $user['email']; ?></label>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Escola: <?php echo $user['nome_escola']; ?></label>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Aniversário: <?php echo $user['aniversario']; ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="account-change-password">
                        <!-- Alterar Senha -->
                    </div>
                    <div class="tab-pane fade" id="account-info">
                        <div class="card-body pb-2">
                            <div class="form-group">
                                <label class="form-label">Bio: <?php echo $user['bio']; ?></label>
                            </div>
                            
                        </div>
                        <hr class="border-light m-0">
                        <div class="card-body pb-2">
                            <h3 class="mb-4">Contato</h3>
                            <div class="form-group">
                                <label class="form-label">Telefone: <?php echo $user['telefone']; ?></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../javascript/perfil.js"></script>
</body>
</html>



<?php
} else {
    echo "Nenhum usuário encontrado.";
}
$conn->close();
?>
