<?php
// Conexão com o banco de dados
$pdo = new PDO("mysql:host=localhost;dbname=sistema_quiz", "usuario", "senha");

$id = $_GET['id'];

// Buscar a prova específica
$sql = "SELECT * FROM provas WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$prova = $stmt->fetch(PDO::FETCH_ASSOC);

if ($prova):
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Prova</title>
</head>
<body>
    <h2><?php echo htmlspecialchars($prova['titulo']); ?> (<?php echo $prova['ano']; ?>)</h2>

    <h3>Prova</h3>
    <embed src="<?php echo htmlspecialchars($prova['arquivo_prova']); ?>" type="application/pdf" width="100%" height="600px" />

    <h3>Gabarito</h3>
    <embed src="<?php echo htmlspecialchars($prova['arquivo_gabarito']); ?>" type="application/pdf" width="100%" height="600px" />

</body>
</html>

<?php else: ?>
    <p>Prova não encontrada.</p>
<?php endif; ?>
