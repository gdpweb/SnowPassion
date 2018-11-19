<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Comment;
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
        $this->loadComment($manager);
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
            ['avatar-1', 'admin56'],
            ['avatar-2', 'utilisateur1'],
            ['avatar-3', 'utilisateur2'],
            ['avatar-4', 'utilisateur3'],
        ];
        $imagePath = '/src/AppBundle/DataFixtures/img/';
        foreach ($infoImages as $infoImage) {
            $image = new Image();
            $image->setFile(
                new File($this->appPath.$imagePath.$infoImage[0].'.jpg')
            );
            $image->setAlt($infoImage[1]);
            $image->setExt('jpg');
            $image->setType('avatar');
            $manager->persist($image);
            $this->addReference($infoImage[0], $image);
        }
        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {
        $infoUsers = [
            ['admin56', ['ROLE_ADMIN'], 'admin56@gdpweb.fr', 'avatar-1'],
            ['utilisateur1', ['ROLE_USER'], 'utilisateur2@gdpweb.fr', 'avatar-2'],
            ['utilisateur2', ['ROLE_USER'], 'utilisateur3@gdpweb.fr', 'avatar-3'],
            ['utilisateur3', ['ROLE_USER'], 'utilisateur4@gdpweb.fr', 'avatar-4'],
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
            'img-trick-1', 'img-trick-11', 'img-trick-12', 'img-trick-13',
            'img-trick-2', 'img-trick-21', 'img-trick-22',
            'img-trick-3', 'img-trick-31', 'img-trick-32',
            'img-trick-4', 'img-trick-41', 'img-trick-42',
            'img-trick-5', 'img-trick-51', 'img-trick-52',
            'img-trick-6', 'img-trick-61', 'img-trick-62', 'img-trick-63',
            'img-trick-7', 'img-trick-71', 'img-trick-72',
            'img-trick-8', 'img-trick-81', 'img-trick-82',
            'img-trick-9', 'img-trick-91', 'img-trick-92',
            'img-trick-10', 'img-trick-101', 'img-trick-102',
        ];
        $imagePath = '/src/AppBundle/DataFixtures/img/';
        foreach ($infoImages as $infoImage) {
            $image = new Image();
            $image->setFile(
                new File($this->appPath.$imagePath.$infoImage.'.jpg')
            );
            $image->setAlt($infoImage);
            $image->setExt('jpg');
            $image->setType('trick');
            $manager->persist($image);
            $this->addReference($infoImage, $image);
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
                'utilisateur1', ['img-trick-1', 'img-trick-11', 'img-trick-12', 'img-trick-13'],
                'video-1', 'groupe1', ],
            ['Style week',
                'Pendant le saut, saisir la carre backside de la planche, entre les deux pieds, 
                avec la main avant',
                'admin56', ['img-trick-2', 'img-trick-21', 'img-trick-22'], 'video-2', 'groupe1', ],
            ['Seat belt',
                'Pendant le saut, saisir la carre frontside à l\'arrière avec la main avant ',
                'utilisateur2', ['img-trick-3', 'img-trick-31', 'img-trick-32'], 'video-3', 'groupe1', ],
            ['Tail grab',
                'Saisie de la partie arrière de la planche, avec la main arrière',
                'admin56', ['img-trick-4', 'img-trick-41', 'img-trick-42'], 'video-4', 'groupe1', ],
            ['Big foot',
                'On réalise trois tours complets, uniquement des rotations horizontales.',
                'utilisateur3', ['img-trick-5', 'img-trick-51', 'img-trick-52'], 'video-5', 'groupe2', ],
            ['Front flips',
                'Le Front flips est une rotation verticale vers l\'avant.',
                'admin56', ['img-trick-6', 'img-trick-61', 'img-trick-62', 'img-trick-63'], 'video-6',
                'groupe3', ],
            ['Corkscrew',
                'Rotation initialement horizontale mais lancée avec un mouvement des épaules 
                particulier qui désaxe la rotation',
                'utilisateur2', ['img-trick-7', 'img-trick-71', 'img-trick-72'], 'video-7', 'groupe4', ],
            ['Nose slide',
                'On slide avec l\'avant de la planche sur la barre',
                'admin56', ['img-trick-8', 'img-trick-81', 'img-trick-82'], 'video-8', 'groupe5', ],
            ['Backside Air',
                'S\'il ne devait rester qu\'un trick dans le snowboard, ce serait peut être celui là. 
                L\'occasion de commencer cette nouvelle saison des trick tips sur une bonne note ! ',
                'utilisateur2', ['img-trick-9', 'img-trick-91', 'img-trick-92'], 'video-9', 'groupe7', ],
            ['Tail slide',
                'On slide avec l\'arrière de la planche sur la barre',
                'admin56', ['img-trick-10', 'img-trick-101', 'img-trick-102'], 'video-10', 'groupe5', ],
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
            $this->addReference($infoTrick[0], $trick);
        }
        $manager->flush();
    }

    private function loadComment(ObjectManager $manager)
    {
        $comments = [
            ['utilisateur1', 'Merci, pour cet article.'],
            ['utilisateur2', 'Super les photos!'],
            ['utilisateur3', 'Je débute, merci pour l\'article'],
            ['admin56', 'Bonjour, il manque des informations et des photos!'],
            ['utilisateur1', 'Merci pour ces conseils très clairs et très utiles.'],
            ['utilisateur3', 'Bon article!'],
            ['utilisateur2', 'En tant que débutant, je suis très content de trouver des informations 
            sur le snow trick'],
            ['utilisateur3', 'Je viens de me mettre au snowboard cette année, je vais essayer cette 
            figure rapidement'],
            ['utilisateur2', 'Cette figure est ma préféré, merci pour la vidéo'],
            ['utilisateur1', 'Bonjour, bonne sensation, simple agréable à réaliser'],
        ];
        $infoComments = [
            ['Mute', $comments],
            ['Style week', [$comments[1], $comments[2]]],
            ['Seat belt', [$comments[2]]],
            ['Tail grab', [$comments[3]]],
            ['Big foot', [$comments[4]]],
            ['Big foot', [$comments[5]]],
            ['Front flips', [$comments[6]]],
            ['Corkscrew', [$comments[7]]],
            ['Nose slide', [$comments[8]]],
            ['Backside Air', [$comments[9]]],
        ];

        foreach ($infoComments as $infoComment) {
            foreach ($infoComment[1] as $message) {
                $comment = new Comment();
                $comment->setTrick($this->getReference($infoComment[0]));
                $comment->setMessage($message[1]);
                $comment->setAuteur($this->getReference($message[0]));
                $manager->persist($comment);
            }
        }
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
