<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

function enviar_email($destinatario, $nome_destinatario, $assunto, $mensagem){

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);
//To load the French version
$mail->setLanguage('fr', 'PHPMailer\language\phpmailer.lang-pt.php');

try {
    //Server settings
   // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.sapo.pt';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'email';                     //SMTP username
    $mail->Password   = 'password';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('email', 'Alvaro');  //Set the sender of the message
    $mail->addAddress('$destinatario', '$nome_destinatario');     //Add a recipient
 
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = '$assunto';
    $mail->Body    = '$mensagem';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
// enviar_email('gomesalvarogomes@gmail.com', 'Alvaro', 'PHPMailler em teste', 'Bem vindo ao mundo do PHPmailer');
}
