<?php
session_start();
$logged = isset($_SESSION['on']);

if (isset($_GET['enviada'])) {
    echo '<script>alert("Mensagem Enviada.");</script>';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/global.css">
    <link rel="stylesheet" href="../../css/styleSign.css">
    <title>Denúncia</title>
</head>
<body>
<form method="POST" action="sendmail.php?offender=<?php echo $_GET['offender'] ?>" enctype="multipart/form-data">
    <div class="main">
        <div class="content">
            <div class="toolbar">
                <div class="logo">
                    <a href="../index.php">
                        <img src="../../assets/Logo_Full.png" style="width: 10vw;" alt="TeamPlay">
                    </a>
                </div>
                <div class="userarea">
                    
                </div>
            </div>

            <div class="page">
                <div class="con1">
                    <div class="con2">
                        <h2>Denúnciar</h2><br>
                        <div id="part-1">
                            <label for="body" style="font-size: 20px;">Insira o conteúdo do e-mail</label><br>
                            <textarea class="inp" name="body" rows="5" cols="50" id="body" required></textarea><br><br>
                            <label style="font-size: 20px;">Insira os anexos do e-mail:</label>
                            <input type="file" name="anexos"><br/><br/>
                            <input name="next" type="submit" value="Enviar" class="inp btn" style="padding: 10px 20px; font-size: 30px; height: auto; display: block; margin: 0 auto; width: 200px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>
