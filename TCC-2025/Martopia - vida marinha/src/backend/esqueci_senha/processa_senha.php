<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "../classes/class_Conexao.php";
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/src/Exception.php';

// Conexão com banco
$con = new Conexao("localhost", "root", "", "vidamarinha");
$con->conectar();
$conn = $con->getConnection();

$email = $_POST['emailRecu'];
$senhaApp = $_POST['senha_app'] ?? '';

// Validar se os campos de configuração foram preenchidos
if (empty($email) || empty($senhaApp)) {
    $_SESSION['mensagem'] = "Por favor, preencha o email e a senha de app!";
    $_SESSION['tipo'] = "erro";
    header("Location: ../login/login.php");
    exit;
}

// Gerar token e expiração
$token = bin2hex(random_bytes(32));
$expira = date("Y-m-d H:i:s", strtotime("+30 minutes"));

$sql = "UPDATE usuarios SET token_recuperacao = ?, token_expira = ? WHERE email = ?";
$result = $con->executarQuery($sql, [$token, $expira, $email]);

// Verificar se o email existe no banco
if ($result) {

    // Link para redefinir senha
    $link = "http://localhost/Martopia_Final3/backend/esqueci_senha/resetar_senha.php?token=$token";

    // PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $email; // Email informado no formulário
        $mail->Password   = $senhaApp; // Senha de app informada no formulário
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($email, 'Projeto Martopia');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Recuperação de Senha - Projeto Martopia';

        $mail->Body = "
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
                    .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                    .header { background: #045a94; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                    .content { padding: 30px 20px; }
                    .button { display: inline-block; padding: 15px 30px; background: #045a94; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                    .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2> Projeto Martopia</h2>
                    </div>
                    <div class='content'>
                        <h3>Recuperação de Senha</h3>
                        <p>Olá,</p>
                        <p>Recebemos uma solicitação para redefinir a senha da sua conta no Projeto Martopia.</p>
                        <p>Clique no botão abaixo para criar uma nova senha:</p>
                        <center>
                            <a href='$link' class='button' style='color:white; '>Redefinir Senha</a>
                        </center>
                        <p style='color: #666; font-size: 14px;'>Ou copie e cole este link no seu navegador:</p>
                        <p style='word-break: break-all; color: #045a94;'>$link</p>
                        <p style='color: #d32f2f; font-weight: bold;'>⚠️ Este link expira em 30 minutos.</p>
                        <p>Se você não solicitou esta recuperação, ignore este e-mail.</p>
                    </div>
                    <div class='footer'>
                        <p>© 2025 Projeto Martopia - Todos os direitos reservados</p>
                        <p>Este é um e-mail automático, não responda.</p>
                    </div>
                </div>
            </body>
            </html>
        ";

        $mail->AltBody = "Recuperação de Senha\n\nClique no link abaixo para criar uma nova senha:\n$link\n\nEste link expira em 30 minutos.";

        $mail->send();
        $_SESSION['mensagem'] = "Link de recuperação enviado! Verifique seu e-mail.";
        $_SESSION['tipo'] = "sucesso";
    
    } catch (Exception $e) {
        $_SESSION['mensagem'] = " Erro ao enviar e-mail. Verifique suas credenciais do Gmail.";
        $_SESSION['tipo'] = "erro";
        
        // Log do erro para debug (remover em produção)
        error_log("Erro PHPMailer: " . $mail->ErrorInfo);
    }

} else {
    $_SESSION['mensagem'] = " E-mail não encontrado !";
    $_SESSION['tipo'] = "erro";
}

header("Location: ../login/login.php");
exit;
?>