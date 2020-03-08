<?php


namespace App\plugins;

use PHPMailer\PHPMailer\PHPMailer;

class AccountRecoveryEmailSend
{
    private $emailDestinatary;
    private $token;

    public function __construct($emailDestinatary,$token)
    {
        $this->emailDestinatary = $emailDestinatary;
        $this->token = $token;
    }

    public function send(){
        $mail = new PHPMailer();
        $mail->IsSMTP(); // enable SMTP

        $mail->SMTPDebug = 0; //debug off = 0, debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        $mail->Host = "mail.gibucket.a2hosted.com";
        $mail->Port = 465; // or 587
        $mail->IsHTML(true);
        $mail->Username = "security@gibucket.a2hosted.com";
        $mail->Password = "!qLIT=qlc4c_";
        $mail->SetFrom("security@gibucket.a2hosted.com");
        $mail->Subject = "Sheiley Shop account recovery";
        $mail->Body = $this->getBodyMessage();
        $mail->AddAddress($this->emailDestinatary);
        return $mail->Send();
    }

    private function getBodyMessage(){
        $bodyMessage = '<div>The following link will take you to an external page to recover your password;</div>';
        $url = "https://gibucket.a2hosted.com/sheiley_shop/auth/recoverypassword/?token=$this->token";
        $bodyMessage .= '<div><a href=" '. $url .' "> ' . $url . '</a></div>';
        $bodyMessage .= '<div>This email expires in 2 hours.</div>';
        return $bodyMessage;
    }
}