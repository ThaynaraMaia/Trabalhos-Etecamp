<?php
include_once('../../backend/Conexao.php'); 

if (!$conn || !($conn instanceof mysqli)) {
    die("Erro: Conexão com o banco de dados não estabelecida ou não é uma instância de mysqli.");
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if (empty($nome)) {
        echo "O campo de nome é obrigatório.";
        exit;
    }

    $imagem = ''; 
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../uploads/categorias/';
        $fileName = basename($_FILES['imagem']['name']);
        $uploadFile = $uploadDir . $fileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadFile)) {
            $imagem = $fileName;
        } else {
            echo "Erro ao fazer upload da imagem.";
            exit;
        }
    }

    if ($action === 'update' && $id) {
        $sql = "UPDATE categorias SET nome_cat= ?, imagem = IFNULL(?, imagem) WHERE id_categoria = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Erro na preparação da consulta: " . $conn->error);
        }

        $stmt->bind_param('ssi', $nome, $imagem, $id);

        if ($stmt->execute()) {
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Erro ao atualizar categoria: " . $stmt->error;
        }
    } else if ($action === 'create') {
        $sql = "INSERT INTO categorias (nome, imagem) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Erro na preparação da consulta: " . $conn->error);
        }

        $stmt->bind_param('ss', $nome, $imagem);

        if ($stmt->execute()) {
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Erro ao adicionar categoria: " . $stmt->error;
        }
    }
}

if ($action === 'delete' && $id) {
    $sql = "DELETE FROM categorias WHERE id_categoria = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        die("Erro ao excluir categoria: " . $stmt->error);
    }
}

$categoria = [];
if ($action === 'edit' && $id) {
    $sql = "SELECT * FROM categorias WHERE id_categoria = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $categoria = $result->fetch_assoc();
    if ($categoria === false) {
        die("Erro ao buscar categoria: " . $stmt->error);
    }
}

$sql = "SELECT * FROM categorias";
$result = $conn->query($sql);
if ($result === false) {
    die("Erro na consulta: " . $conn->error);
}
$categorias = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Categorias</title>
    <link rel="stylesheet" href="../../css/stylecrudcategoria.css">
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap-grid.min.css">
    <link rel="shortcut icon" href="../../img/logo@2x.png" type="image/x-icon">
</head>
<body>

<header>
    <nav class="BarraNav">
        <img src="../../img/LogoJundtaskCompleta.png" alt="Logo JundTask">
        <div class="perfil">
            <a href="./homeAdm.php">
                Voltar
            </a>
        </div>
    </nav>
</header>

<main class=""> 

<h2><?php echo $action === 'edit' ? 'Editar Categoria' : 'Adicionar Nova Categoria'; ?></h2>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?action=<?php echo $action === 'edit' ? 'update' : 'create'; ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($categoria['id_categoria'] ?? ''); ?>">

    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($categoria['nome_cat'] ?? ''); ?>" required>
    <br>

    <label for="imagem">Imagem da Categoria:</label>
    <input type="file" name="imagem" id="imagem">
    <?php if (!empty($categoria['imagem'])): ?>
        <br>
        <img src="uploads/categorias/<?php echo htmlspecialchars($categoria['imagem']); ?>" alt="Imagem da Categoria" width="100">
        <br>
    <?php endif; ?>
    <br>

    <button type="submit"><?php echo $action === 'edit' ? 'Atualizar Categoria' : 'Adicionar Categoria'; ?></button>
</form>

<h2>Lista de Categorias</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Imagem</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categorias as $categoria): ?>
            <tr>
                <td><?php echo htmlspecialchars($categoria['id_categoria']); ?></td>
                <td><?php echo htmlspecialchars($categoria['nome_cat']); ?></td>
                <td><img src="../../uploads/categorias/<?php echo htmlspecialchars($categoria['imagem']); ?>" alt="Imagem" style="width: 50px;"></td>
                <td>
                    <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?action=edit&id=<?php echo $categoria['id_categoria']; ?>" title="Editar">
                    <img src="../../img/editar-arquivo.png" alt="Editar" style="width: 25px; height: auto;">
                    </a>
                    <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?action=delete&id=<?php echo $categoria['id_categoria']; ?>" title="Excluir" onclick="return confirm('Você tem certeza que deseja excluir?');">
                    <img src="../../img/botao-apagar.png" alt="Excluir" style="width: 25px; height: auto;">
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script src="../../js/funcaoMenuLateral.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
