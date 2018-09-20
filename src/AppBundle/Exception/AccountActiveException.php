<?php

namespace AppBundle\Exception;


use Symfony\Component\Security\Core\Exception\AccountStatusException;

class AccountActiveException extends  AccountStatusException
{
    /**
     * @return string
     */
    public function getMessageKey()
    {
        return 'Le compte n\'est pas actif';
    }
}