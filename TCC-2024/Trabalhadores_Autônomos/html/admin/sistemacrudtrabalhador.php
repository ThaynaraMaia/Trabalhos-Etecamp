<?php
include_once('../../backend/Conexao.php'); 

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $descricao = $_POST['descricao'];
    $contato = $_POST['contato'];
    $data_nasc = $_POST['data_nasc'];
    $id_categoria = $_POST['id_categoria'];
    $id_area = $_POST['id_area'];
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    
    if (empty($nome) || empty($email) || empty($descricao) || empty($contato) || empty($data_nasc) || empty($id_categoria) || empty($id_area)) {
        echo "Todos os campos são obrigatórios.";
        exit;
    }

    $fotoPerfil = ''; 

    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../uploads/';
        $fileName = basename($_FILES['foto_perfil']['name']);
        $uploadFile = $uploadDir . $fileName;

      
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

       
        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $uploadFile)) {
            $fotoPerfil = $fileName;  
        } else {
            echo "Erro ao fazer upload da foto.";
            exit;
        }
    }

    if ($action === 'update' && $id) {
        
        $sql = "UPDATE trabalhador SET nome = ?, email = ?, senha = ?, descricao = ?, contato = ?, data_nasc = ?, id_categoria = ?, id_area = ?, foto_perfil = ? WHERE id_trabalhador = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Erro na preparação da consulta: " . $conn->error);
        }

        $senhaHash = !empty($senha) ? password_hash($senha, PASSWORD_DEFAULT) : $_POST['senha_atual']; // Mantém a senha atual se não for alterada
        $stmt->bind_param('sssssssssi', $nome, $email, $senhaHash, $descricao, $contato, $data_nasc, $id_categoria, $id_area, $fotoPerfil, $id);

      
        if ($stmt->execute()) {
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Erro ao atualizar trabalhador: " . $stmt->error;
        }
    } else if ($action === 'create') {
      
        $sql = "INSERT INTO trabalhador (nome, email, senha, descricao, contato, data_nasc, id_categoria, id_area, foto_perfil) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Erro na preparação da consulta: " . $conn->error);
        }

        $stmt->bind_param('sssssssis', $nome, $email, password_hash($senha, PASSWORD_DEFAULT), $descricao, $contato, $data_nasc, $id_categoria, $id_area, $fotoPerfil);

        if ($stmt->execute()) {
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Erro ao adicionar trabalhador: " . $stmt->error;
        }
    }
}


if ($action === 'delete' && $id) {
    $sql = "DELETE FROM trabalhador WHERE id_trabalhador = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }
    
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        die("Erro ao excluir trabalhador: " . $stmt->error);
    }
}


$trabalhador = [];
if ($action === 'edit' && $id) {
    $sql = "SELECT * FROM trabalhador WHERE id_trabalhador = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $trabalhador = $result->fetch_assoc();
    if ($trabalhador === false) {
        die("Erro ao buscar trabalhador: " . $stmt->error);
    }
}


$sql = "SELECT trabalhador. *,area_atuação.cidade FROM trabalhador 
LEFT JOIN area_atuação ON trabalhador.id_area = area_atuação.id_area";
$result = $conn->query($sql);
if ($result === false) {
    die("Erro na consulta: " . $conn->error);
}
$trabalhadores = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento Trabalhadores</title>
    <link rel="stylesheet" href="../../css/stylecrudtrabalhador.css">
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap-grid.min.css">
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

    <h2><?php echo $action === 'edit' ? 'Editar Trabalhador' : 'Adicionar Novo Trabalhador'; ?></h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?action=<?php echo $action === 'edit' ? 'update' : 'create'; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($trabalhador['id_trabalhador'] ?? ''); ?>">

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($trabalhador['nome'] ?? ''); ?>" required>
        <br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($trabalhador['email'] ?? ''); ?>" required>
        <br>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha">
        <br>

        <label for="foto_perfil">Foto do Perfil:</label>
        <input type="file" name="foto_perfil" id="foto_perfil">
        <?php if (!empty($trabalhador['foto_perfil'])): ?>
            <br>
            <img src="../../uploads/<?php echo htmlspecialchars($trabalhador['foto_perfil']); ?>" alt="Foto de Perfil" width="100">
            <br>
        <?php endif; ?>
        <br>
        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required><?php echo htmlspecialchars($trabalhador['descricao'] ?? ''); ?></textarea>
        <br>

        <label for="contato">Contato:</label>
        <input type="text" id="contato" name="contato" value="<?php echo htmlspecialchars($trabalhador['contato'] ?? ''); ?>" required>
        <br>

        <label for="data_nasc">Data de Nascimento:</label>
        <input type="date" id="data_nasc" name="data_nasc" value="<?php echo htmlspecialchars($trabalhador['data_nasc'] ?? ''); ?>" required>
        <br>

        <label for="id_categoria">Categoria:</label>
        <div class="box">
            <select name="id_categoria" id="id_categoria">
                <option value="">Selecione uma categoria</option>
               
            </select>
        </div>
        <br>

        <label for="id_area">Cidade:</label>
        <div class="box">
            <select name="id_area" id="id_area">
                <option value="">Selecione uma área</option>
               
            </select>
        </div>
        <br>

        <button type="submit"><?php echo $action === 'edit' ? 'Atualizar Trabalhador' : 'Adicionar Trabalhador'; ?></button>
    </form>

    <h2>Lista de Trabalhadores</h2>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Foto</th>
                <th>Cidade</th>
                <th>Descrição</th>
                <th>Contato</th>
                <th>Data de Nascimento</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($trabalhadores as $trabalhador): ?>
                <tr>
                 
                    <td><?php echo htmlspecialchars($trabalhador['nome']); ?></td>
                    <td><?php echo htmlspecialchars($trabalhador['email']); ?></td>
                    <td><img src="../../uploads/<?php echo htmlspecialchars($trabalhador['foto_perfil']); ?>" alt="Foto" style="width: 50px;"></td>
                    <td><?php echo htmlspecialchars($trabalhador['cidade']); ?></td>

                    <td><?php echo htmlspecialchars($trabalhador['descricao']); ?></td>
                    <td><?php echo htmlspecialchars($trabalhador['contato']); ?></td>
                    <td><?php echo htmlspecialchars($trabalhador['data_nasc']); ?></td>
                    <td class="actions">
                        <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?action=edit&id=<?php echo $trabalhador['id_trabalhador']; ?>" title="Editar">
                            <img src="../../img/editar-arquivo.png" alt="Editar">
                        </a>
                        <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?action=delete&id=<?php echo $trabalhador['id_trabalhador']; ?>" title="Excluir" onclick="return confirm('Você tem certeza que deseja excluir?');">
                            <img src="../../img/botao-apagar.png" alt="Excluir">
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <script src="../../js/funcaoMenuLateral.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const areaSelect = document.getElementById('id_area');
        const categoriaSelect = document.getElementById('id_categoria');


        fetch('../getcidades.php')
            .then(response => response.json())
            .then(areas => {
                console.log(areas); 
                areaSelect.innerHTML = '<option value="">Selecione uma área</option>'; 
                areas.forEach(area => {
                    const option = document.createElement('option');
                    option.value = area.id_area; 
                    option.textContent = area.cidade; 
                    areaSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Erro ao carregar áreas:', error));

        fetch('../getcategoriacadastro.php')
            .then(response => response.json())
            .then(categorias => {
                console.log(categorias); 
                categoriaSelect.innerHTML = '<option value="">Selecione uma categoria</option>'; 
                categorias.forEach(categoria => {
                    const option = document.createElement('option');
                    option.value = categoria.id_categoria; 
                    option.textContent = categoria.nome_cat; 
                    categoriaSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Erro ao carregar categorias:', error));
      });
    </script>

    <footer class="d-flex justify-content-center ">
        <p>N</p>
        <p>Terms of Service</p>
        <p>Privacy Policy</p>
        <p>@2024nerisdesign</p>
    </footer>
</body>
</html>
