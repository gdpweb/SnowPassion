<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Image
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
     * @Assert\File(
     *     maxSize = "1024k",
     *     maxSizeMessage="Le fichier est trop volumineux. La taille maximale autorisée est de 1024 Ko.",
     *     uploadIniSizeErrorMessage = "Le fichier est trop volumineux. La taille maximale autorisée est de 1024 Ko",
     *     mimeTypes = {
     *          "image/png",
     *          "image/jpg",
     *          "image/jpeg"
     *     },
     *     mimeTypesMessage = "Le format de l'image n'est pas valide, seul les formats png et jpg sont autorisés",
     * )
     */
    private $file;

    private $tempFilename;

    private $path;


    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        // On vérifie si on avait déjà un fichier pour cette entité
        if (null !== $this->ext) {
            // On sauvegarde l'extension du fichier pour le supprimer plus tard
            $this->tempFilename = $this->id . '.' . $this->ext;

            // On réinitialise les valeurs des attributs url et alt
            $this->ext = null;
            $this->alt = null;
        }
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

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function PreUpload()
    {
        if (null === $this->file) {
            return false;
        }

        $fileName = $this->file->getClientOriginalName();

        $this->ext = $this->file->getClientOriginalExtension();

        $this->alt = basename($fileName, '.' . $this->ext);

        return $fileName;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */

    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // Si on avait un ancien fichier, on le supprime
        if (null !== $this->tempFilename) {
            $oldFile = $this->getPath() . '/' . $this->tempFilename;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $this->file->move($this->getPath(), $this->id . "." . $this->ext);

        $this->resizeThumbnail();

    }

    public function resizeThumbnail($newHeight = 200)
    {

        $filename = $this->getPath() . '/' . $this->id . "." . $this->ext;
        $newFilename = $this->getPath() . '/mini/' . $this->id . '.' . $this->ext;
        list($width, $height) = getimagesize($filename);

        if ($newHeight >= $height) {

            copy($filename, $newFilename);
            return true;

        }

        $newWidth = $newHeight * 3 / 2;
        $thumb = imagecreatetruecolor($newWidth, $newHeight);

        switch ($this->ext) {
            case 'jpg':
                $source = imagecreatefromjpeg($filename);
                imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagejpeg($thumb, $newFilename );
                break;
            case 'png':
                $source = imagecreatefrompng($filename);
                imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagepng($thumb, $newFilename );

        }


    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemove()
    {

        $this->tempFilename = $this->id . '.' . $this->ext;
    }

    /**
     * @ORM\PostRemove()
     */
    public function PostRemove()
    {


        if (file_exists($this->getPath() . '/' . $this->tempFilename)) {

            unlink($this->getPath() . '/' . $this->tempFilename);
            unlink($this->getPath() . '/mini/' . $this->tempFilename);
        }

    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

}

