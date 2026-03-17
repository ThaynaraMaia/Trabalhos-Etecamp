<?php 
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;
        require 'vendor/autoload.php';

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Username = 'insira seu email';
        $mail->Password = 'insira a senha de aplicação do email';
        $mail->Port = 587;
        $mail->setFrom($_POST['nome']);
        $mail->addReplyTo($_POST['email']);
        $mail->addAddress('');
        $mail->isHTML(true);
        $mail->Subject = 'Fale Conosco';
        $mail->Body    = $_POST['nome'] . ': ' . $_POST['body'];
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
        // $mail->addAttachment('/tmp/image.jpg', 'nome.jpg');

        if(!$mail->send()) {
                echo 'Não foi possível enviar a mensagem.<br>';
                echo 'Erro: ' . $mail->ErrorInfo;
            } else {
                echo 'Mensagem enviada.';
            }

        header('Location: fale.php?enviada=True')


?>