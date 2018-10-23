<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Image;
use AppBundle\Entity\Trick;
use AppBundle\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class TrickManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * TrickManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function getAll(){

        return $this->em->getRepository('AppBundle:Trick')->getAll();

    }
    public function getListTricks()
    {
        return $this->em->getRepository('AppBundle:Trick')
            ->getListTricks(Trick::Nb_TRICKS_PAGE);
    }

    public function countTricks()
    {
        try {
            return $this->em->getRepository('AppBundle:Trick')->countTricksMax();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function saveTrick(Trick $trick, $user = null)
    {
        if ($trick->getId() === null) {
            $trick->setAuteur($user);
            $trick->setPublie(true);
            forEach ($trick->getImages() as $image) {
                $image->setType('trick');
            }
            $this->em->persist($trick);
        }
        $this->em->flush();
    }

    public function deleteTrick(Trick $trick)
    {
        $this->em->remove($trick);
        $this->em->flush();
    }

    public function addImage(Trick $trick, Image $image)
    {
        $image->setType('trick');
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