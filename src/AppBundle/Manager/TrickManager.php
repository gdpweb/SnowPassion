<?php

namespace AppBundle\Manager;


use AppBundle\Entity\Image;
use AppBundle\Entity\Trick;
use AppBundle\Entity\User;
use AppBundle\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;


class TrickManager
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $path;

    /**
     * TrickManager constructor.
     * @param EntityManagerInterface $em
     * @param $image_directory
     */
    public function __construct(EntityManagerInterface $em, $image_directory)
    {
        $this->em = $em;
        $this->path = $image_directory;
    }

    /**
     * @param Trick $trick
     * @param User $user
     */
    public function createTrick(Trick $trick, User $user)
    {

        $trick->setAuteur($user);
        $trick->setPublie(true);
        $this->em->persist($trick);
        $this->em->flush();
    }

    /**
     * @param Trick $trick
     */
    public function updateTrick(Trick $trick)
    {
        $this->em->persist($trick);
        $this->em->flush();
    }

    public function deleteTrick(Trick $trick)
    {

        forEach ($trick->getImages() as $image) {
            $image->setPath($this->path);
            $this->em->remove($image);
        }
        $this->em->remove($trick);
        $this->em->flush();
    }

    public function addImage(Trick $trick, Image $image)
    {
        $trick->addImage($image);
        $this->em->persist($trick);
        $this->em->flush();
    }

    public function addVideo(Trick $trick, Video $video)
    {
        $trick->addVideo($video);
        $this->em->persist($trick);
        $this->em->flush();
    }


}