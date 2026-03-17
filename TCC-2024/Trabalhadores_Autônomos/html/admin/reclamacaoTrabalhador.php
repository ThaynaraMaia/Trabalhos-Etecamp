<?php
session_start();
include_once('../../backend/Conexao.php');

// Buscar atualizações pendentes
$sql = "SELECT * FROM reclamacao_trabalhador ORDER BY id_reclamacao_trabalhador DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FeedBacks dos trabalhadores</title>
    <link rel="stylesheet" href="../../css/styleReclamacao.css">
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.min.css">
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
<body>

<h1>FeedBacks dos trabalhadores</h1>

<?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="blocoGeral">
                <div class="row me-0 ">
                    <div class="col d-flex justify-content-center">
                        <p><span>nome:</span> <?php echo $row['nome']?></p>
                        <p><span>email:</span> <?php echo $row['email']?></p>
                    </div>
                </div>
                <div class="row me-0">
                    <div class="col d-flex align-items-center flex-column">
                    <p><span>feedback:</span></p>
                    <p id="txtReclamacao"> <?php echo $row['reclamacao']?></p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
<?php else: ?>
    <div class="txtNaoEncontrado">
        <p>Não há FeedBacks</p>
    </div>
<?php endif; ?>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="../../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>

