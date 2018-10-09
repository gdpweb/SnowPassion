<?php

namespace AppBundle\Manager;


use AppBundle\Entity\Image;
use AppBundle\Service\SPFileSystem;
use Doctrine\ORM\EntityManagerInterface;


class ImageManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $fileSystem;


    /**
     * @param EntityManagerInterface $em
     * @param $image_directory
     */
    public function __construct(SPFileSystem $fileSystem, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->fileSystem = $fileSystem;
    }

    public function updateImageTrick(Image $image)
    {
        $image->setAlt(null);
        $this->em->flush();

    }

    public function deleteImageTrick(Image $image)
    {

        $this->em->remove($image);
        $this->em->flush();

    }

    public function uploadImageTrick(Image $image){

        //$this->fileSystem->upload($image->getFile());

    }

}