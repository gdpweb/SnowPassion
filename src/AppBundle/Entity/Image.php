<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image.
 *
 * @ORM\Table(name="sp_image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    /**
     * @var string
     *
     * @ORM\Column(name="ext", type="string", length=255)
     */
    private $ext;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
    /**
     * @Assert\File(
     *     maxSize = "1024k",
     *     maxSizeMessage="Le fichier est trop volumineux.
     * La taille maximale autorisée est de 1024 Ko.",
     *     uploadIniSizeErrorMessage = "Le fichier est trop volumineux.
     * La taille maximale autorisée est de 1024 Ko",
     *     mimeTypes = {
     *          "image/png",
     *          "image/jpg",
     *          "image/jpeg"
     *     },
     *     mimeTypesMessage = "Le format de l'image n'est pas valide,
     * seul les formats png et jpg sont autorisés",
     * )
     */
    private $file;

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file = null)
    {
        $this->file = $file;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param string $alt
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    }

    /**
     * Get ext.
     *
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * Set ext.
     *
     * @param string $ext
     */
    public function setExt($ext)
    {
        $this->ext = $ext;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }
}
