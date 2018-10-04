<?php

namespace AppBundle\Manager;


use AppBundle\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;


class ImageManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $path;

    /**
     * @param EntityManagerInterface $em
     * @param $image_directory
     */
    public function __construct(EntityManagerInterface $em, $image_directory)
    {
        $this->em = $em;
        $this->path = $image_directory;
    }

    public function updateImageTrick(Image $image)
    {
        $image->setPath($this->path . 'trick');
        $this->em->getRepository('AppBundle:Image');
        $this->em->persist($image);
        $this->em->flush();

    }

    public function deleteImageTrick(Image $image)
    {
        $image->setPath($this->path . 'trick');
        $this->em->getRepository('AppBundle:Image');
        $this->em->remove($image);
        $this->em->flush();

    }

}