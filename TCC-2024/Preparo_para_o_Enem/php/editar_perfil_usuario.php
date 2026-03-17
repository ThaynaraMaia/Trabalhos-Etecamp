<?php
session_start();

// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'enemplatd_bd');

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obtendo o ID do usuário da sessão
$id_usuario = $_SESSION['usuario_id'];

$sql = "SELECT * FROM usuario_form WHERE usuario_id = $id_usuario";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Nenhum usuário encontrado.";
    exit;
}

// Se o formulário foi enviado, atualizar os dados do usuário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = $_POST['email'];
    $escola = $_POST['escola'];
    $aniversario = $_POST['aniversario'];
    $bio = $_POST['bio'];
    $telefone = $_POST['telefone'];

    // Verificar se uma nova foto foi enviada
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        $foto_perfil = $_FILES['foto_perfil'];
        $nome_arquivo = $foto_perfil['name'];
        $caminho_temporario = $foto_perfil['tmp_name'];
        $pasta_destino = "../img/";

        // Gerar um nome único para a imagem
        $extensao = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
        $novo_nome = uniqid() . "." . $extensao;

        // Mover o arquivo para a pasta de destino
        if (move_uploaded_file($caminho_temporario, $pasta_destino . $novo_nome)) {
            // Atualizar o caminho da foto de perfil no banco de dados
            $sql_update_foto = "UPDATE usuario_form SET foto_perfil='$novo_nome' WHERE usuario_id = $id_usuario";
            if ($conn->query($sql_update_foto) === TRUE) {
                echo "Foto de perfil atualizada com sucesso!";
                $user['foto_perfil'] = $novo_nome; // Atualizar o array do usuário com o novo nome da imagem
            } else {
                echo "Erro ao atualizar foto de perfil: " . $conn->error;
            }
        } else {
            echo "Erro ao fazer upload da foto.";
        }
    }

    // Atualizar os outros campos do perfil
    $sql_update = "UPDATE usuario_form SET 
        nome='$nome', 
        sobrenome='$sobrenome', 
        email='$email', 
        nome_escola='$escola', 
        aniversario='$aniversario',
        bio='$bio',
        telefone='$telefone' 
        WHERE usuario_id = $id_usuario";

    if ($conn->query($sql_update) === TRUE) {
        echo "Perfil atualizado com sucesso!";
        $user = array_merge($user, $_POST);
    } else {
        echo "Erro ao atualizar perfil: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/perfil_teste.css">
    <link rel="shortcut icon" href="../img/logoxampp.png" type="image/x-icon">
    <title>Editar Perfil - EnemPlatD</title>
</head>
<body>
    
<div class="container light-style flex-grow-1 container-p-y">
    <h4 class="font-weight-bold py-3 mb-4">
        Editar Perfil
    </h4>
    <div class="card-overflow-hidden">
        <div class="row no-gutters row-bordered row-border-light">
            <div class="col-md-3 pt-0">
                <div class="menu-lateral">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" href="../php/perfil.php">Perfil Atualizado</a>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="editar-perfil">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="card-body media align-items-center">
                                <img id="profile-pic" src="../img/<?php echo $user['foto_perfil'] ? $user['foto_perfil'] : 'default-profile.png'; ?>" alt="Sua Foto" class="profile-pic">
                                <div class="media-body ml-4">
                                    <h4 class="font-weight-bold py-3 mb-4">Editar Perfil de <?php echo $user['nome']; ?></h4>
                                    <label class="btn btn-outline-primary">
                                        Trocar de foto 
                                        <input type="file" name="foto_perfil" class="account-settings-fileinput">
                                    </label>
                                </div>
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">Nome</label>
                                    <input type="text" name="nome" class="form-control" value="<?php echo $user['nome']; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Sobrenome</label>
                                    <input type="text" name="sobrenome" class="form-control" value="<?php echo $user['sobrenome']; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">E-mail</label>
                                    <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Escola</label>
                                    <input type="text" name="escola" class="form-control" value="<?php echo $user['nome_escola']; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Aniversário</label>
                                    <input type="date" name="aniversario" class="form-control" value="<?php echo $user['aniversario']; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Bio</label>
                                    <textarea name="bio" class="form-control"><?php echo $user['bio']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Telefone</label>
                                    <input type="text" name="telefone" class="form-control" value="<?php echo $user['telefone']; ?>">
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../javascript/perfil.js"></script>
</body>
</html>

<?php
$conn->close();
?>
