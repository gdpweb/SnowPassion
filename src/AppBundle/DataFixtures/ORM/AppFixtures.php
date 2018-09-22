<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppFixtures implements FixtureInterface, ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        $factory = $this->container->get('security.encoder_factory');

        $user = new User();
        $user->setUsername('admin');
        $password = $factory->getEncoder($user)->encodePassword('admin', $user->getSalt());
        $user->setPassword($password);
        $user->setRoles(['ROLE_AUTEUR', 'ROLE_ADMIN']);
        $user->setEmail('admin@gdpweb.fr');
        $user->setIsActive(true);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('auteur');
        $password = $factory->getEncoder($user)->encodePassword('auteur', $user->getSalt());
        $user->setPassword($password);
        $user->setRoles(['ROLE_AUTEUR']);
        $user->setEmail('auteur@gdpweb.fr');
        $user->setIsActive(true);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('default');
        $password = $factory->getEncoder($user)->encodePassword('user', $user->getSalt());
        $user->setPassword($password);
        $user->setEmail('default@gdpweb.fr');
        $user->setIsActive(true);
        $manager->persist($user);
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
