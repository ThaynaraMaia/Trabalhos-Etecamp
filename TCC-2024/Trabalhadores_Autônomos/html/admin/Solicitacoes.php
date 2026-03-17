<?php
session_start();
include_once('../../backend/Conexao.php');

// Buscar atualizações pendentes
$sql = "SELECT * FROM atualizacoes_pendentes WHERE aprovado = 0";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Solicitações</title>
    <link rel="stylesheet" href="../../css/styleSolicitacoes.css">
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

<h1>Solicitações de Atualizações Pendentes</h1>

<?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="BlocoAtualizacao "style="margin:10px 20px 10px 20px;">
                <div class="row txtAtualizacao" style="margin:10px auto 10px auto;">
                    <div class="col ">
                    <p><span>ID:</span> <?= $row['id_trabalhador'] ?></p>
                    <p><span>Nome:</span> <?= $row['nome'] ?></p>
                    <p><span>emai:</span> <?= $row['email'] ?></p>
                    <p><span>Contato:</span> <?= $row['contato'] ?></p>
                    </div>
                    <div class="col">
                        <p><span>Data Nascimento:</span> <?= $row['data_nasc'] ?></p>
                        <p><span>Descrição:</span> <?= $row['descricao'] ?></p>
                        <p><span>ID area:</span> <?= $row['id_area'] ?></p>
                        <p><span>ID categoria:</span> <?= $row['id_categoria'] ?></p>
                    </div>
                </div>
                <div class="row me-0 txtIMG">
                    <div class="col d-flex justify-content-center ">
                        <div class="teste">
                            <p>Foto perfil</p>
                            <?php if (!empty($row['foto_perfil'])): ?>
                                <img src="../../uploads/<?= $row['foto_perfil'] ?>" width="200">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col d-flex justify-content-center">
                        <div class="teste">
                            <p>Foto banner</p>
                            <?php if (!empty($row['foto_banner'])): ?>
                                <img src="../../uploads/<?= $row['foto_banner'] ?>" width="200">
                                <?php endif; ?>
                        </div>
                    </div>
                </div>
            
                <div class="row testeBloco me-0 txtIMG">
                    <div class="col d-flex justify-content-center">
                        <div class="teste">
                            <p>Foto trabalho 1</p>
                            <?php if (!empty($row['foto_trabalho1'])): ?>
                                <img src="../../uploads/<?= $row['foto_trabalho1'] ?>" width="350">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col">
                        <div class="teste">
                            <p>Foto trabalho 2</p>
                            <?php if (!empty($row['foto_trabalho2'])): ?>
                                <img src="../../uploads/<?= $row['foto_trabalho2'] ?>" width="350">
                                <?php endif; ?>
                        </div>
                    </div>
                    <div class="col">
                        <div class="teste">
                            <p>Foto trabalho 3</p>
                            <?php if (!empty($row['foto_trabalho3'])): ?>
                                <img src="../../uploads/<?= $row['foto_trabalho3'] ?>" width="350">
                                <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="row me-0 mt-4">
                    <div class="col ">
                        <form action="../../backend/aprovar_rejeitar.php" method="POST">
                            <input type="hidden" name="id_atualizacao" value="<?php echo $row['id_atualizacoes_pendentes']; ?>">
                            <button type="submit" name="acao" value="aprovar">Aprovar</button>
                            <button type="submit" name="acao" value="rejeitar">Rejeitar</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
<?php else: ?>
    <div class="txtNaoEncontrado">
        <p>Não há atualizações pendentes.</p>
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

