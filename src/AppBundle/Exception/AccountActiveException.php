<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Exception;

use Symfony\Component\Security\Core\Exception\AccountStatusException;

class AccountActiveException extends AccountStatusException
{
    /**
     * @return string
     */
    public function getMessageKey()
    {
        return 'Le compte n\'est pas actif';
    }
}
