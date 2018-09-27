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

    private $path;


    private $container;

    /**
     * UserManager constructor.
     * @param SPMailer $mailer
     * @param EntityManagerInterface $em
     * @param ContainerInterface $container
     * @param $image_directory
     */
    public function __construct(SPMailer $mailer, EntityManagerInterface $em, ContainerInterface $container, $image_directory)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->container = $container;
        $this->path = $image_directory;
    }

    public function tokenValid($token)
    {

        return $this->em->getRepository('AppBundle:User')->tokenIsValid($token);
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

        $user->getImage()->setPath($this->path);
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