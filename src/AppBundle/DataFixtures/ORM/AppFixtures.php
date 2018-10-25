<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Groupe;
use AppBundle\Entity\Image;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;

class AppFixtures implements FixtureInterface, ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadGroupe($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        $factory = $this->container->get('security.encoder_factory');
        $appPath = $this->container->getParameter('kernel.project_dir');
        $infoUsers = array(
            ['admin', ['ROLE_ADMIN'], 'admin@gdpweb.fr', 'avatar-1.png', 'png'],
            ['default', ['ROLE_ADMIN'], 'default@gdpweb.fr', 'avatar-2.png', 'png'],
            ['auteur', ['ROLE_ADMIN'], 'auteur@gdpweb.fr', 'avatar-3.png', 'png']
        );

        foreach ($infoUsers as $infoUser) {
            $user = new User();
            $user->setUsername($infoUser[0]);
            $password = $factory->getEncoder($user)->encodePassword($infoUser[0], $user->getSalt());
            $user->setPassword($password);
            $user->setRoles($infoUser[1]);
            $user->setEmail($infoUser[2]);
            $user->setIsActive(true);

            $image = new Image();
            $image->setFile(new File($appPath . '/src/AppBundle/DataFixtures/img/' . $infoUser[3]));
            $image->setAlt($user->getUsername());
            $image->setExt($infoUser[4]);
            $image->setType('avatar');
            $user->setImage($image);
            $manager->persist($user);
        }
        $manager->flush();
    }

    private function loadGroupe(ObjectManager $manager)
    {
        $infoGroupes = array('Les grabs', 'Les rotations', 'Les flips', 'Les rotations désaxées',
            'Les slides', 'Les one foot tricks', 'Old school');
        foreach ($infoGroupes as $infoGroupe) {
            $groupe = new Groupe();
            $groupe->setNom($infoGroupe);
            $manager->persist($groupe);
        }
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
