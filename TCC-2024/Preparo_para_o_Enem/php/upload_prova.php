
<?php

$dsn = 'mysql:host=localhost;dbname=enemplatd_bd';
$usuario = 'usuario';
$senha = 'senha';

try {
    $pdo = new PDO($dsn, $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erro ao conectar com o banco de dados: ' . $e->getMessage();
}


// Conexão com o banco de dados (ajuste conforme suas credenciais)
$pdo = new PDO('mysql:host=localhost;dbname=enemplatd', 'usuario_id', 'senha');

// Verifica se o diretório uploads/ existe, se não, cria o diretório
if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true); // Cria o diretório com permissões de leitura/escrita
}

// Caminho para onde os arquivos serão movidos
$provaDestino = 'uploads/' . $_FILES['prova']['name'];
$gabaritoDestino = 'uploads/' . $_FILES['gabarito']['name'];

// Move o arquivo de prova
if (move_uploaded_file($_FILES['prova']['tmp_name'], $provaDestino)) {
    echo "Arquivo de prova enviado com sucesso!";
} else {
    echo "Erro ao enviar o arquivo de prova.";
}

// Move o arquivo de gabarito
if (move_uploaded_file($_FILES['gabarito']['tmp_name'], $gabaritoDestino)) {
    echo "Arquivo de gabarito enviado com sucesso!";
} else {
    echo "Erro ao enviar o arquivo de gabarito.";
}

// Inserir os caminhos no banco de dados
$stmt = $pdo->prepare("INSERT INTO provas (nome_prova, caminho_prova, caminho_gabarito) VALUES (:nome_prova, :caminho_prova, :caminho_gabarito)");
$stmt->execute([
    ':nome_prova' => $_FILES['prova']['name'],
    ':caminho_prova' => $provaDestino,
    ':caminho_gabarito' => $gabaritoDestino
]);

echo "Prova registrada no banco de dados com sucesso!";
?>
