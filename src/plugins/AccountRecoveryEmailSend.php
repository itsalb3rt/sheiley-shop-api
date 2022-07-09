<?php


namespace App\plugins;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AccountRecoveryEmailSend
{
    private $emailDestinatary;
    private $token;

    public function __construct($emailDestinatary, $token)
    {
        $this->emailDestinatary = $emailDestinatary;
        $this->token = $token;
    }

    public function send()
    {
        $mail = new PHPMailer();
        try {
            $mail->IsSMTP(); // enable SMTP

            $mail->SMTPDebug = 0; //debug off = 0, debugging: 1 = errors and messages, 2 = messages only
            $mail->SMTPAuth = true; // authentication enabled

            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->Port = $_ENV["SMTP_PORT"]; // or 587
            $mail->Username = $_ENV['SMTP_EMAIL'];
            $mail->Password = $_ENV['SMTP_PASSWORD'];
            $mail->SetFrom($_ENV['SMTP_EMAIL']);

            $mail->IsHTML(true);
            $mail->Subject = "Sheiley Shop account recovery";
            $mail->Body = $this->getBodyMessage();
            $mail->AddAddress($this->emailDestinatary);
            return $mail->Send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    private function getBodyMessage()
    {
        $bodyMessage = '<div>The following link will take you to an external page to recover your password;</div>';
        $url = $_ENV["API_FRONTEND_URL"] . "/auth/recoverypassword/?token=$this->token";
        $bodyMessage .= '<div><a href=" ' . $url . ' "> ' . $url . '</a></div>';
        $bodyMessage .= '<div>This email expires in 2 hours.</div>';
        return $bodyMessage;
    }
}
