<?php

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use AppBundle\Service\SPMailer;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
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
    private $container;
    private $encoderFactory;

    /**
     * @param SPMailer $mailer
     * @param EntityManagerInterface $em
     * @param EncoderFactoryInterface $encoderFactory
     * @param ContainerInterface $container
     */
    public function __construct(SPMailer $mailer, EntityManagerInterface $em, EncoderFactoryInterface $encoderFactory,
                                ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->container = $container;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param $token
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function tokenValid($token)
    {
        return $this->em->getRepository('AppBundle:User')->tokenIsValid($token);
    }

    public function activeAccount(User $user)
    {
        $user->setIsActive(true);
        $password = $this->encoderFactory->getEncoder($user)->encodePassword($user->getPassword(), $user->getSalt());
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
    private function createToken(User $user)
    {
        $token = md5(uniqid(rand(), true));
        $user->setToken($token);
        $date = new \DateTime();
        $user->setDateToken($date);
    }

    /**
     * @param User $user
     */
    public function registerMail(User $user)
    {
        $user->getImage()->setType('avatar');
        $this->createToken($user);
        $password = $this->encoderFactory->getEncoder($user)->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($password);
        $this->em->persist($user);
        $this->em->flush();
        $this->mailer->validateUserMail($user);
    }

    /**
     * @param EncoderFactoryInterface $encoderFactory
     * @return UserManager
     */
    public function setEncoderFactory($encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
        return $this;
    }
}
