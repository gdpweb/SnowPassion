<?php

namespace AppBundle\Security;

use AppBundle\Exception\AccountActiveException;
use AppBundle\entity\User as AppUser;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user account is not active, the user may be notified
        if ($user->isActive() == false) {
            throw new AccountActiveException('....');
        }
    }
}