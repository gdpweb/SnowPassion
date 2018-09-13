<?php

namespace CFA\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 *
 * @ORM\Table(name="sp_image")
 * @ORM\Entity(repositoryClass="CFA\AppBundle\Repository\ImageRepository")
 */
class Image
{
    /**
     * @ORM\ManyToOne(targetEntity="CFA\AppBundle\Entity\Figure", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $figure;
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


    private $file;

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    public function setFigure(Figure $figure)
    {
        $this->figure = $figure;

        return $this;
    }

    public function getFigure()
    {
        return $this->figure;
    }

    /**
     * Get id
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
     * Set ext
     *
     * @param string $ext
     *
     * @return Image
     */

    public function setExt($ext)
    {
        $this->ext = $ext;

        return $this;
    }

    /**
     * Get ext
     *
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }
}

