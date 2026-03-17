<?php
include_once('../../backend/Conexao.php'); 

if (!$conn || !($conn instanceof mysqli)) {
    die("Erro: Conexão com o banco de dados não estabelecida ou não é uma instância de mysqli.");
}

// Função para excluir um cliente
function deleteClient($conn, $id_cliente) {
    $sql = "DELETE FROM cliente WHERE id_cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    
    if ($stmt->execute()) {
        echo "Cliente excluído com sucesso.";
    } else {
        echo "Erro ao excluir cliente: " . $stmt->error;
    }
}

// Função para editar um cliente
function editClient($conn, $id_cliente, $nome, $email, $contato, $data_nasc, $id_area) {
    $sql = "UPDATE cliente SET nome = ?, email = ?, contato = ?, data_nasc = ?, id_area = ? WHERE id_cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nome, $email, $contato, $data_nasc, $id_area, $id_cliente);
    
    if ($stmt->execute()) {
        echo "Cliente atualizado com sucesso.";
    } else {
        echo "Erro ao atualizar cliente: " . $stmt->error;
    }
}

// Processar a ação de exclusão
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id_cliente = (int)$_GET['id'];
    deleteClient($conn, $id_cliente);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Processar a ação de edição
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id_cliente = (int)$_GET['id'];
    // Buscando os dados do cliente
    $sql = "SELECT * FROM cliente WHERE id_cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenha os dados do formulário
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';
    $contato = isset($_POST['contato']) ? $_POST['contato'] : '';
    $data_nasc = isset($_POST['data_nasc']) ? $_POST['data_nasc'] : '';
    $id_area = isset($_POST['id_area']) ? (int)$_POST['id_area'] : null;
    
    // Se estiver editando, chamamos a função editClient
    if (isset($_POST['id_cliente'])) {
        $id_cliente = (int)$_POST['id_cliente'];
        editClient($conn, $id_cliente, $nome, $email, $contato, $data_nasc, $id_area);
    } else {
        // Validações básicas
        if (empty($nome) || empty($email) || empty($senha) || empty($contato) || empty($data_nasc) || empty($id_area)) {
            echo "Todos os campos são obrigatórios.";
            exit;
        }

        // Prepare a query de inserção
        $sql = "INSERT INTO cliente (nome, email, senha, contato, data_nasc, id_area) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Erro na preparação da consulta: " . $conn->error);
        }

        // Hash da senha (opcional)
        $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

        // Vincular os parâmetros
        $stmt->bind_param("sssssi", $nome, $email, $senha_hash, $contato, $data_nasc, $id_area);

        // Execute a query
        if ($stmt->execute()) {
            echo "Cliente adicionado com sucesso.";
        } else {
            echo "Erro ao adicionar cliente: " . $stmt->error;
        }
    }
}

// Selecionar as cidades para o select
$sql_cidades = "SELECT id_area, cidade FROM area_atuação";
$result_cidades = $conn->query($sql_cidades);

if ($result_cidades === false) {
    die("Erro ao buscar cidades: " . $conn->error);
}

// Obter a lista de clientes
$sql_clientes = "SELECT id_cliente, nome, email, foto_perfil FROM cliente";
$result_clientes = $conn->query($sql_clientes);

if ($result_clientes === false) {
    die("Erro ao buscar clientes: " . $conn->error);
}

$clientes = [];
while ($cliente = $result_clientes->fetch_assoc()) {
    $clientes[] = $cliente;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="../../css/stylecrudcliente.css">
</head>
<body>

<header>
    <nav class="BarraNav">
        <img src="../../img/LogoJundtaskCompleta.png" alt="Logo JundTask">
        <div class="perfil">
            <a href="./homeAdm.php">Voltar</a>
        </div>
    </nav>
</header>

<main>
    <h2><?php echo isset($cliente) ? 'Editar Cliente' : 'Adicionar Novo Cliente'; ?></h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <?php if (isset($cliente)): ?>
            <input type="hidden" name="id_cliente" value="<?php echo $cliente['id_cliente']; ?>">
        <?php endif; ?>
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo isset($cliente) ? htmlspecialchars($cliente['nome']) : ''; ?>" required>
        <br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo isset($cliente) ? htmlspecialchars($cliente['email']) : ''; ?>" required>
        <br>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
        <br>

        <label for="contato">Contato:</label>
        <input type="text" id="contato" name="contato" value="<?php echo isset($cliente) ? htmlspecialchars($cliente['contato']) : ''; ?>" required>
        <br>

        <label for="data_nasc">Data de Nascimento:</label>
        <input type="date" id="data_nasc" name="data_nasc" value="<?php echo isset($cliente) ? htmlspecialchars($cliente['data_nasc']) : ''; ?>" required>
        <br>

        <label for="id_area">Cidade:</label>
        <select name="id_area" id="id_area" required>
            <option value="">Selecione a cidade</option>
            <?php while ($cidade = $result_cidades->fetch_assoc()): ?>
                <option value="<?php echo $cidade['id_area']; ?>" <?php echo isset($cliente) && $cliente['id_area'] == $cidade['id_area'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cidade['cidade']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br>

        <button type="submit"><?php echo isset($cliente) ? 'Atualizar Cliente' : 'Adicionar Cliente'; ?></button>
    </form>

    <h2>Lista de Clientes</h2>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Foto</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($clientes)): ?>
                <tr>
                    <td colspan="4">Nenhum cliente encontrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                        <td><img src="../../uploads/<?php echo htmlspecialchars($cliente['foto_perfil']); ?>" alt="Foto" style="width: 50px;"></td>
                        <td class="actions">
                            <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?action=edit&id=<?php echo $cliente['id_cliente']; ?>" title="Editar">
                                <img src="../../img/editar-arquivo.png" alt="Editar">
                            </a>
                            <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?action=delete&id=<?php echo $cliente['id_cliente']; ?>" onclick="return confirm('Tem certeza que deseja excluir este cliente?');" title="Excluir">
                                <img src="../../img/botao-apagar.png" alt="Excluir">
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<footer>
    <p>&copy; 2024 JundTask. Todos os direitos reservados.</p>
</footer>

</body>
</html>
