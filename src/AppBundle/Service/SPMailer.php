<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;

class SPMailer
{
    private $mailer;
    private $message;
    private $to;
    private $subject;
    private $body;


    public function __construct($from, \Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->message = new \Swift_Message();
        $this->message->setFrom($from, 'SnowPassion');
    }

    public function resetUserMailer(User $user)
    {
        $this->subject = "Réinitialisation de votre compte";
        $this->body =
            "<html>
            <h4>Bonjour " . $user->getUsername() . ",</h4>
            <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
            <p>Merci de suivre: 
                <a href='http://127.0.0.1:8000/reset/" . $user->getToken() . "'>
            ce lien</a>
            </p>
            <p>Cordialement SnowPassion.</p>
            </html>";

        $this->to = 'stephanebriere@hotmail.fr';
        $this->sendMessage();
    }

    public function validateUserMail(User $user)
    {
        $this->subject = "Création de votre compte";
        $this->body = "<html>
            <h4>Bonjour " . $user->getUsername() . ",</h4>
            <p>Vous avez demandé la création d'un compte sur le site SnowPassion.</p>
            <p>Afin de valider votre compte, merci de suivre: 
            <a href='http://127.0.0.1:8000/validate/" . $user->getToken() . "'>ce lien</a></p>
            <p>Cordialement SnowPassion.</p>
            </html>";

        $this->to = 'stephanebriere@hotmail.fr';

        $this->sendMessage();
    }


    private function sendMessage()
    {
        $this->message
            ->setTo($this->to)
            ->setSubject($this->subject)
            ->setBody($this->body)
            ->setContentType('text/html');

        $this->mailer->send($this->message);
    }
}
