<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use AppBundle\Service\SPMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserManager
{
    /**
     * @var SPMailer
     */
    private $mailer;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    public function __construct(
        SPMailer $mailer,
        EntityManagerInterface $em,
        EncoderFactoryInterface $encoderFactory
    ) {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->setEncoderFactory($encoderFactory);
    }

    /**
     * @param $token
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function tokenValid($token)
    {
        return $this->em->getRepository('AppBundle:User')
            ->tokenIsValid($token);
    }

    public function activeAccount(User $user)
    {
        $user->setIsActive(true);
        $password = $this->encoderFactory->getEncoder($user)
            ->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($password);
        $user->setToken(null);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function resetMail(User $user)
    {
        $this->createToken($user);
        $this->em->persist($user);
        $this->em->flush();
        $this->mailer->resetUserMailer($user);

        return $user;
    }

    private function createToken(User $user)
    {
        $token = md5(uniqid(rand(), true));
        $user->setToken($token);
        $date = new \DateTime();
        $user->setDateToken($date);
    }

    public function registerMail(User $user)
    {
        $user->getImage()->setType('avatar');
        $this->createToken($user);
        $password = $this->encoderFactory->getEncoder($user)
            ->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($password);
        $this->em->persist($user);
        $this->em->flush();
        $this->mailer->validateUserMail($user);
    }

    /**
     * @param EncoderFactoryInterface $encoderFactory
     *
     * @return UserManager
     */
    public function setEncoderFactory($encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;

        return $this;
    }
}
