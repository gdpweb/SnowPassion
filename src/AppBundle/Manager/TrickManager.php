<?php

namespace AppBundle\Manager;


use AppBundle\Entity\Image;
use AppBundle\Entity\Trick;
use AppBundle\Entity\User;
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
        forEach ($trick->getImages() as $image) {
            $image->setPath($this->path);
        }
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
        forEach ($trick->getImages() as $image) {
            $image->setPath($this->path);
        }
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

    public function deleteImage(Image $image)
    {
        $image->setPath($this->path);
        $this->em->remove($image);
        $this->em->flush();
    }
}