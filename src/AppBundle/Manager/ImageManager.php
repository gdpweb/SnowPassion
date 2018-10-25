<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
}
