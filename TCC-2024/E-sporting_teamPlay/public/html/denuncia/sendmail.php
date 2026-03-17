<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Inclua o caminho correto para o autoload do Composer

session_start(); // Inicie a sessão se ainda não estiver iniciada


$off = $_GET['offender'];

// Verifique se o usuário está logado e o e-mail está na sessão
if (!isset($_SESSION['email'])) {
    // Substitua 'users' e 'email' pelos nomes corretos da sua tabela e coluna
    include 'conn.php'; // Inclua seu arquivo de conexão com o banco de dados

    $username = $_SESSION['username']; // Supondo que você armazena o username na sessão
    $sql = "SELECT email FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email']; // Armazena o e-mail na sessão
    } else {
        echo 'Usuário não encontrado.';
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = new PHPMailer();
    $mail->isSMTP();
    // ATENÇÃO: MUDAR "XXXXXXXXXXX" para seus próprios dados.
    $mail->Host = 'XXXXXXXXXXX';
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Username = 'XXXXXXXXXXX'; 
    $mail->Password = 'XXXXXXXXXXX'; 
    $mail->Port = 587;

    // Adiciona o charset UTF-8 para suportar caracteres especiais
    $mail->CharSet = 'UTF-8';

    $mail->setFrom('XXXXXXXXXXX', 'Suporte TeamPlay');
    $mail->addReplyTo($_SESSION['email'], 'Suporte TeamPlay'); // Usa o e-mail da sessão
    $mail->addAddress('XXXXXXXXXXX'); 
    $mail->isHTML(true);
    
    // Define o assunto do e-mail com o username
    $mail->Subject = 'Denúncia do usuário: @' . $_SESSION['username'] . ', Para: @' . $off; 

    // Define o corpo do e-mail
    $mail->Body = nl2br(htmlspecialchars($_POST['body'])); // Usa nl2br para manter quebras de linha

    // Verifica se anexos foram enviados
    if (isset($_FILES['anexos']) && $_FILES['anexos']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['anexos']['tmp_name'];
        $fileName = $_FILES['anexos']['name'];
        $fileSize = $_FILES['anexos']['size'];
        $fileType = $_FILES['anexos']['type'];

        // Especifica o caminho para o arquivo anexado
        $uploadFileDir = './uploads/';
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true); // Cria o diretório se não existir
        }
        $dest_path = $uploadFileDir . basename($fileName);

        // Move o arquivo para o diretório especificado
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $mail->addAttachment($dest_path); // Anexa o arquivo
        } else {
            echo 'Houve um erro ao mover o arquivo enviado.';
        }
    }

    // Tenta enviar o e-mail
    if (!$mail->send()) {
        echo 'Não foi possível enviar a mensagem.<br>';
        echo 'Erro: ' . $mail->ErrorInfo;
    } else {
        header('Location: ../user.php');
        exit; 
    }
} else {
    echo 'Método de requisição inválido.';
}
?>
