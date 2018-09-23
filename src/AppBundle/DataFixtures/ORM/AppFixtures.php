<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Groupe;
use AppBundle\Entity\Image;
use AppBundle\Entity\Trick;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppFixtures implements FixtureInterface, ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;
    private $users;
    private $groupes;
    private $tricks;

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadGroupe($manager);
        $this->loadTrick($manager);
        $this->loadComment($manager);

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

        $user->setImage(new Image());
        $user->getImage()->setAlt($user->getUsername());
        $user->getImage()->setExt('jpg');

        $manager->persist($user);
        $this->users[] = $user;


        $user = new User();
        $user->setUsername('auteur');
        $password = $factory->getEncoder($user)->encodePassword('auteur', $user->getSalt());
        $user->setPassword($password);
        $user->setRoles(['ROLE_AUTEUR']);
        $user->setEmail('auteur@gdpweb.fr');
        $user->setIsActive(true);

        $user->setImage(new Image());
        $user->getImage()->setAlt($user->getUsername());
        $user->getImage()->setExt('jpg');

        $manager->persist($user);
        $this->users[] = $user;
        $user = new User();
        $user->setUsername('default');
        $password = $factory->getEncoder($user)->encodePassword('user', $user->getSalt());
        $user->setPassword($password);
        $user->setEmail('default@gdpweb.fr');
        $user->setIsActive(true);

        $user->setImage(new Image());
        $user->getImage()->setAlt($user->getUsername());
        $user->getImage()->setExt('jpg');

        $manager->persist($user);
        $manager->flush();
        $this->users[] = $user;
    }

    private function loadGroupe(ObjectManager $manager)
    {
        foreach ($this->users as $key => $user) {

            $groupe = new Groupe();
            $groupe->setNom('Groupe' . $key);
            $manager->persist($groupe);
            $manager->flush();
            $this->groupes[] = $groupe;
        }
    }
    private function loadComment(ObjectManager $manager)
    {
        foreach ($this->users as $key => $user) {

            $comment = new Comment();
            $comment->setAuteur($user);
            $comment->setMessage('Bon Article!');
            $comment->setDate(new \DateTime());
            $manager->persist($comment);
            $manager->flush();
            }
    }

    private function loadTrick(ObjectManager $manager)
    {
        foreach ($this->users as $key => $user) {
            $trick = new Trick();

            $trick->setNom('Triks'.$key);
            $trick->setDate(new \DateTime());
            $trick->setDescription(
                'Un grab consiste à attraper la planche avec la main pendant le saut. Le verbe anglais 
                 to grab signifie « attraper. » Il existe plusieurs types de grabs selon la position de la saisie
                 et la main choisie pour l\'effectuer, avec des difficultés variables'
            );
            $trick->setPublie(true);

            $image= new Image();
            $image->setAlt($trick->getNom());
            $image->setExt('jpg');
            $trick->addImage($image);

            $trick->setAuteur($this->users[$key]);
            $trick->setGroupe($this->groupes[$key]);

            $manager->persist($trick);
            $manager->flush();
            $this->tricks[]=$trick;
        }
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
