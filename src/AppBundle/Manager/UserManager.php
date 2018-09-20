<?php
/**
 * Created by PhpStorm.
 * User: brieres
 * Date: 20/09/2018
 * Time: 15:13
 */

namespace AppBundle\Manager;


use AppBundle\Entity\User;
use AppBundle\Service\SPMailer;
use Doctrine\ORM\EntityManagerInterface;

class UserManager{
    /**
     * @var SPMailer
     */
    private $mailer;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UserManager constructor.
     * @param SPMailer $mailer
     * @param EntityManagerInterface $em
     */
    public function __construct(SPMailer $mailer, EntityManagerInterface $em)
    {
        $this->mailer = $mailer;
        $this->em = $em;
    }

    public function activeAccount(User $user){

        $user->setIsActive(true);
        $user->setToken(NULL);
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     */
    public function resetMail(User $user)
    {
        $this->createToken($user);
        $this->mailer->resetUserMailer($user);
    }

    /**
     * @param User $user
     */
    public function registerMail(User $user)
    {
        $this->createToken($user);
        $this->mailer->validateUserMail($user);
    }

    /**
     * @param User $user
     */
    private function createToken(User $user){

            $token = md5(uniqid(rand(), true));
            $user->setToken($token);
            $date = new \DateTime('NOW', new \DateTimezone("Europe/Paris"));
            $user->setDateToken($date);
            $this->em->persist($user);
            $this->em->flush();

        }


}