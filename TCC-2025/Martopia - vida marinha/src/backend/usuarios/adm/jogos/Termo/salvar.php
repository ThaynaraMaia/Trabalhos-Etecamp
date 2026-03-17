<?php
include_once '../../../../classes/class_Conexao.php'; // ajuste o caminho conforme sua estrutura

// Criar conexão
$conexao = new Conexao("localhost", "root", "", "vidamarinha"); // ajuste usuário/senha/banco
if (!$conexao->conectar()) {
    die("<script>
        alert('Erro ao conectar ao banco de dados!');
        window.history.back();
    </script>");
}

$conn = $conexao->getConnection();

// Recebe os dados do formulário
$dificuldade = $_POST['dificuldade'];
$palavra = strtoupper(trim($_POST['pergunta'])); // coloca em maiúsculo
$dica = isset($_POST['dica']) && $_POST['dica'] !== '' ? trim($_POST['dica']) : null;

// Validação de comprimento conforme dificuldade
$letrasEsperadas = [
    1 => 5, // fácil
    2 => 6, // médio
    3 => 7  // difícil
];

if (mb_strlen($palavra) != $letrasEsperadas[$dificuldade]) {
    die("<script>
        alert('Erro: A palavra deve ter {$letrasEsperadas[$dificuldade]} letras para esse nível!');
        window.history.back();
    </script>");
}

// Salvar no banco
$sql = "INSERT INTO termo (palavra, dificuldade, dica) VALUES (?, ?, ?)";
$resultado = $conexao->executarQuery($sql, [$palavra, (int)$dificuldade, $dica]);

if ($resultado) {
    echo "<script>
        alert('Palavra adicionada com sucesso!');
        window.location.href='termoAdm.php';
    </script>";
} else {
    echo "<script>
        alert('Erro ao salvar a palavra!');
        window.history.back();
    </script>";
}
?>
