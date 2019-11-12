<?php

use PHPMailer\PHPMailer\PHPMailer;
class Mail 
{
    public static function sendRegularMail($to, $subject, $message)
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = MAIL_SERVER;
        $mail->SMTPAuth   = true;                               // Enable SMTP authentication
        $mail->Username   = MAIL_USERNAME;                      // SMTP username
        $mail->Password   = MAIL_PASSWORD;                      // SMTP password
        $mail->SMTPSecure = 'ssl';                              // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = MAIL_PORT;

        $mail->setFrom(MAIL_USERNAME, 'WordLearner');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = $message;
        $mail->send();
    }
}