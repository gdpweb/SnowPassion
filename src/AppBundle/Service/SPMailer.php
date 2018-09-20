<?php
/**
 * Created by PhpStorm.
 * User: brieres
 * Date: 18/09/2018
 * Time: 18:29
 */

namespace AppBundle\Service;


class Mail
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer    = $mailer;
    }



}
