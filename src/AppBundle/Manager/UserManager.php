<?php

namespace AppBundle\Manager;


use AppBundle\Entity\User;
use AppBundle\Service\SPMailer;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

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


    private $container;

    /**
     * UserManager constructor.
     * @param SPMailer $mailer
     * @param EntityManagerInterface $em
     * @param ContainerInterface $container
     */
    public function __construct(SPMailer $mailer, EntityManagerInterface $em, ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->container = $container;
    }

    public function activeAccount(User $user)
    {
        $user->setIsActive(true);

        $factory = $this->container->get('security.encoder_factory');
        $password = $factory->getEncoder($user)->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($password);

        $user->setToken(null);

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     */
    public function resetMail(User $user)
    {
        $this->createToken($user);

        $this->em->persist($user);
        $this->em->flush();

        $this->mailer->resetUserMailer($user);
    }

    /**
     * @param User $user
     */
    public function registerMail(User $user)
    {
        $this->createToken($user);

        $factory = $this->container->get('security.encoder_factory');
        $password = $factory->getEncoder($user)->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($password);

        $this->em->persist($user);
        $this->em->flush();

        $this->mailer->validateUserMail($user);
    }

    /**
     * @param User $user
     */
    private function createToken(User $user)
    {
        $token = md5(uniqid(rand(), true));
        $user->setToken($token);
        $date = new \DateTime();
        $user->setDateToken($date);
    }


}