<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Inclua o caminho correto para o autoload do Composer

session_start(); // Inicie a sessão se ainda não estiver iniciada


$para = $_GET['para'];
$id = $_GET['id'];


$body = <<<END
<h1>Olá,</h1>
<h3>Redefina a sua senha clicando <a href="http://localhost/teamplay/public/html/redef.php?id=$id">aqui</a>.</h3>

<p>Se você não solicitou uma redefinição de senha, ignore este e-mail.</p><br>


<strong>Atenciosamente,</strong><br>
Suporte TeamPlay
END;




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
    // $mail->addReplyTo($_SESSION['email'], 'Suporte TeamPlay'); // Usa o e-mail da sessão
    $mail->addAddress($_GET['para'], 'Usuário Teamplay'); 
    $mail->isHTML(true);
    
    // Define o assunto do e-mail com o username
    $mail->Subject = 'Redefinição de Senha - TeamPlay'; 

    // Define o corpo do e-mail
    $mail->Body = $body; // Usa nl2br para manter quebras de linha


    // Tenta enviar o e-mail
    if (!$mail->send()) {
        echo 'Não foi possível enviar a mensagem.<br>';
        echo 'Erro: ' . $mail->ErrorInfo;
    } else {
        header('Location: ../../../public/html/login.php');
        echo 'Feito';
        exit; 
    }

