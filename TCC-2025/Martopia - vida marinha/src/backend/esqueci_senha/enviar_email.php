<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'SEUEMAIL@gmail.com'; 
    $mail->Password   = 'mpzn peze emyw jnuw'; // Aqui vai a senha de app
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('SEUEMAIL@gmail.com', 'Martopia Suporte');
    $mail->addAddress('EMAIL_DO_USUARIO');

    $mail->isHTML(true);
    $mail->Subject = 'Recuperação de senha - Martopia';
    $mail->Body    = 'Seu token para recuperar a senha é: <b>'.$token.'</b><br> Clique aqui:<br> https://seusite.com/redefinir.php?token='.$token;

    $mail->send();
    echo 'Email enviado com sucesso!';
} catch (Exception $e) {
    echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
}
