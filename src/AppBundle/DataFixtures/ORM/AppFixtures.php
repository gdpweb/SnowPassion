<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Groupe;
use AppBundle\Entity\Image;
use AppBundle\Entity\Trick;
use AppBundle\Entity\User;
use AppBundle\Entity\Video;
use AppBundle\Service\SPFileSystem;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class AppFixtures extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{
    /** @var ContainerInterface */
    protected $container;
    /** @var EncoderFactory */
    private $factory;
    private $appPath;
    /**
     * @var SPFileSystem
     */
    private $managerFile;

    public function load(ObjectManager $manager)
    {
        $this->factory = $this->container->get('security.encoder_factory');
        $this->appPath = $this->container->getParameter('kernel.project_dir');
        $this->managerFile = $this->container->get('AppBundle\Service\SPFileSystem');

        $this->removeAllFiles();
        $this->loadImageAvatar($manager);
        $this->loadUsers($manager);
        $this->loadGroupe($manager);
        $this->loadImageTrick($manager);
        $this->loadVideoTrick($manager);
        $this->loadTrick($manager);
    }

    private function removeAllFiles()
    {
        $this->managerFile->removeAllFiles($this->appPath.'/web/uploads/avatar/');
        $this->managerFile->removeAllFiles($this->appPath.'/web/uploads/avatar/mini/');
        $this->managerFile->removeAllFiles($this->appPath.'/web/uploads/trick/');
        $this->managerFile->removeAllFiles($this->appPath.'/web/uploads/trick/mini/');
    }

    private function loadImageAvatar(ObjectManager $manager)
    {
        $infoImages = [
            ['avatar-1', 'admin', 'png'],
            ['avatar-2', 'default', 'png'],
            ['avatar-3', 'auteur', 'png'],
        ];
        $imagePath = '/src/AppBundle/DataFixtures/img/';
        foreach ($infoImages as $infoImage) {
            $image = new Image();
            $image->setFile(
                new File($this->appPath.$imagePath.$infoImage[0].'.'.$infoImage[2])
            );
            $image->setAlt($infoImage[1]);
            $image->setExt($infoImage[2]);
            $image->setType('avatar');
            $manager->persist($image);
            $this->addReference($infoImage[0], $image);
        }
        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {
        $infoUsers = [
            ['admin', ['ROLE_ADMIN'], 'admin@gdpweb.fr', 'avatar-1'],
            ['default', ['ROLE_ADMIN'], 'default@gdpweb.fr', 'avatar-2'],
            ['auteur', ['ROLE_ADMIN'], 'auteur@gdpweb.fr', 'avatar-3'],
        ];
        foreach ($infoUsers as $infoUser) {
            $user = new User();
            $user->setUsername($infoUser[0]);
            $password = $this->factory->getEncoder($user)
                ->encodePassword($infoUser[0], $user->getSalt());
            $user->setPassword($password);
            $user->setRoles($infoUser[1]);
            $user->setEmail($infoUser[2]);
            $user->setIsActive(true);
            $user->setImage($this->getReference($infoUser[3]));
            $manager->persist($user);
            $this->addReference($infoUser[0], $user);
        }
        $manager->flush();
    }

    private function loadGroupe(ObjectManager $manager)
    {
        $infoGroupes = [
            ['Les grabs', 'groupe1'],
            ['Les rotations', 'groupe2'],
            ['Les flips', 'groupe3'],
            ['Les rotations désaxées', 'groupe4'],
            ['Les slides', 'groupe5'],
            ['Les one foot tricks', 'groupe6'],
            ['Old school', 'groupe7'],
        ];
        foreach ($infoGroupes as $infoGroupe) {
            $groupe = new Groupe();
            $groupe->setNom($infoGroupe[0]);
            $manager->persist($groupe);
            $this->addReference($infoGroupe[1], $groupe);
        }
        $manager->flush();
    }

    private function loadImageTrick(ObjectManager $manager)
    {
        $infoImages = [
            ['img-trick-1', 'jpg'], ['img-trick-11', 'jpg'], ['img-trick-12', 'jpg'],
            ['img-trick-13', 'jpg'],
            ['img-trick-2', 'jpg'], ['img-trick-21', 'jpg'], ['img-trick-22', 'jpg'],
            ['img-trick-3', 'jpg'], ['img-trick-31', 'jpg'], ['img-trick-32', 'jpg'],
            ['img-trick-4', 'jpg'], ['img-trick-41', 'jpg'], ['img-trick-42', 'jpg'],
            ['img-trick-5', 'jpg'], ['img-trick-51', 'jpg'], ['img-trick-52', 'jpg'],
            ['img-trick-6', 'jpg'], ['img-trick-61', 'jpg'], ['img-trick-62', 'jpg'],
            ['img-trick-63', 'jpg'],
            ['img-trick-7', 'jpg'], ['img-trick-71', 'jpg'], ['img-trick-72', 'jpg'],
            ['img-trick-8', 'jpg'], ['img-trick-81', 'jpg'], ['img-trick-82', 'jpg'],
            ['img-trick-9', 'jpg'], ['img-trick-91', 'jpg'], ['img-trick-92', 'jpg'],
            ['img-trick-10', 'jpg'], ['img-trick-101', 'jpg'], ['img-trick-102', 'jpg'],
        ];
        $imagePath = '/src/AppBundle/DataFixtures/img/';
        foreach ($infoImages as $infoImage) {
            $image = new Image();
            $image->setFile(
                new File($this->appPath.$imagePath.$infoImage[0].'.'.$infoImage[1])
            );
            $image->setAlt($infoImage[0]);
            $image->setExt($infoImage[1]);
            $image->setType('trick');
            $manager->persist($image);
            $this->addReference($infoImage[0], $image);
        }
        $manager->flush();
    }

    private function loadVideoTrick(ObjectManager $manager)
    {
        $infoVideos = [
            ['video-1', 'https://www.youtube.com/embed/aZNjaV1dzKg'],
            ['video-2', 'https://www.youtube.com/embed/V9xuy-rVj9w'],
            ['video-3', 'https://www.youtube.com/embed/1BjgBoummtE'],
            ['video-4', 'https://www.youtube.com/embed/id8VKl9RVQw'],
            ['video-5', 'https://www.youtube.com/embed/mUK9hEjye3w'],
            ['video-6', 'https://www.youtube.com/embed/xhvqu2XBvI0'],
            ['video-7', 'https://www.youtube.com/embed/5Oy6g8FKESo'],
            ['video-8', 'https://www.youtube.com/embed/pxQXQNEvJbo'],
            ['video-9', 'https://www.youtube.com/embed/gV_s0_lfkgg'],
            ['video-10', 'https://www.youtube.com/embed/HRNXjMBakwM'],
        ];

        foreach ($infoVideos as $infoVideo) {
            $video = new Video();
            $video->setUrl($infoVideo[1]);
            $manager->persist($video);
            $this->addReference($infoVideo[0], $video);
        }
        $manager->flush();
    }

    private function loadTrick(ObjectManager $manager)
    {
        $infoTricks = [
            ['Mute',
                'Pendant le saut, saisir la carre frontside de la planche entre les deux 
                pieds avec la main avant',
                'admin', ['img-trick-1', 'img-trick-11', 'img-trick-12', 'img-trick-13'], 'video-1',
                'groupe1', ],
            ['Style week',
                'Pendant le saut, saisir la carre backside de la planche, entre les deux pieds, 
                avec la main avant',
                'admin', ['img-trick-2', 'img-trick-21', 'img-trick-22'], 'video-2', 'groupe1', ],
            ['Seat belt',
                'Pendant le saut, saisir la carre frontside à l\'arrière avec la main avant ',
                'admin', ['img-trick-3', 'img-trick-31', 'img-trick-32'], 'video-3', 'groupe1', ],
            ['Tail grab',
                'Saisie de la partie arrière de la planche, avec la main arrière',
                'admin', ['img-trick-4', 'img-trick-41', 'img-trick-42'], 'video-4', 'groupe1', ],
            ['Big foot',
                'On réalise trois tours complets, uniquement des rotations horizontales.',
                'admin', ['img-trick-5', 'img-trick-51', 'img-trick-52'], 'video-5', 'groupe2', ],
            ['Front flips',
                'Le Front flips est une rotation verticale vers l\'avant.',
                'admin', ['img-trick-6', 'img-trick-61', 'img-trick-62', 'img-trick-63'], 'video-6',
                'groupe3', ],
            ['Corkscrew',
                'Rotation initialement horizontale mais lancée avec un mouvement des épaules 
                particulier qui désaxe la rotation',
                'admin', ['img-trick-7', 'img-trick-71', 'img-trick-72'], 'video-7', 'groupe4', ],
            ['Nose slide',
                'On slide avec l\'avant de la planche sur la barre',
                'admin', ['img-trick-8', 'img-trick-81', 'img-trick-82'], 'video-8', 'groupe5', ],
            ['Backside Air',
                'S\'il ne devait rester qu\'un trick dans le snowboard, ce serait peut être celui là. 
                L\'occasion de commencer cette nouvelle saison des trick tips sur une bonne note ! ',
                'admin', ['img-trick-9', 'img-trick-91', 'img-trick-92'], 'video-9', 'groupe7', ],
            ['Tail slide',
                'On slide avec l\'arrière de la planche sur la barre',
                'admin', ['img-trick-10', 'img-trick-101', 'img-trick-102'], 'video-10', 'groupe5', ],
        ];
        foreach ($infoTricks as $infoTrick) {
            $trick = new Trick();
            $trick->setNom($infoTrick[0]);
            $trick->setDescription($infoTrick[1]);
            $trick->setAuteur($this->getReference($infoTrick[2]));
            $trick->setGroupe($this->getReference($infoTrick[5]));
            /** @var Image $image */
            foreach ($infoTrick[3] as $image) {
                $image = $this->getReference($image);
                $trick->addImage($image);
            }
            /** @var Video $video */
            $video = $this->getReference($infoTrick[4]);
            $trick->addVideo($video);
            $trick->setDate(new \DateTime());
            $trick->setPublie('1');
            $manager->persist($trick);
        }
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
