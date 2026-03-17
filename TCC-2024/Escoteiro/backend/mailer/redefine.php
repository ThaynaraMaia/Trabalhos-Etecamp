<?php 
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;
        require 'vendor/autoload.php';
        include_once '../classes/conn.php';


        $sql='SELECT * FROM tbl_usuarios WHERE email="'.$_POST['email'].'";';
        $res=$conn->query($sql);

        for($i = 0; $i < $res ->num_rows; $i++){
            $linha=$res->fetch_assoc();
            $ID=$linha['id'];
        $MESSAGE = <<<END
         Clique <a href="http://localhost/site_tcc/jpii/backend/login/redef.php?id=$ID">aqui</a> para redefinir sua senha.

         Grupo Escoteiro João Paulo II.
         END;

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Username = 'insira seu email';
        $mail->Password = 'insira a senha de aplicação do email';
        $mail->Port = 587;
        $mail->setFrom('GE João Paulo II');
        $mail->addReplyTo($_POST['email']);
        $mail->addAddress($_POST['email']);
        $mail->isHTML(true);
        $mail->Subject = 'Redefinição de senha';
        $mail -> Body = $MESSAGE;
        // $mail->addAttachment('/tmp/image.jpg', 'nome.jpg');

        if(!$mail->send()) {
                echo 'Não foi possível enviar a mensagem.<br>';
                echo 'Erro: ' . $mail->ErrorInfo;
            } else {
                echo 'Mensagem enviada.';
            }
        }
        header('Location: ../login/login.php')


//         $NAME = $_POST['nome'];
//         $EMAIL = $_GET['email'];
//         $TOKEN = $_GET['token'];
//         $SUBJECT = "ParaGames - Redefinicao de Senha";
//         
        

//         $mail = new PHPMailer(true);

//         // Creating
//         $mail -> isSMTP();
//         $mail -> isHTML(true);
//         $mail -> SMTPAuth = true;

//         // Host (gmail)
//         $mail -> Host = "smtp.gmail.com";
//         $mail -> SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
//         $mail -> Port = 587;

//         // My login
//         $mail -> Username = "";
//         $mail -> Password = "";

//         // Mail
//         $mail -> setFrom("", "");
//         $mail -> addAddress($EMAIL, $NAME);
//         $mail -> Subject = $SUBJECT;
//         

//         // Send
//         $mail -> send();
//         header("Location: ../../../public/html/indexLogin.php?2");
// ?>