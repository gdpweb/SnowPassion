<?php

namespace AppBundle\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SPFileSystem
{
    const NEW_HEIGHT = 200;

    private $pathDirectory;

    /**
     * @param File|UploadedFile $file
     * @param                   $fileName
     */
    public function upload($file, $fileName)
    {
        if ($file instanceof UploadedFile) {
            $file->move($this->pathDirectory, $fileName);
        } else {
            $fileSystem = new Filesystem();
            $fileSystem->copy(
                $file->getPath() . '/' . $file->getFilename(),
                $this->pathDirectory . '/' . $fileName
            );
        }
    }

    public function resizeThumbnail($filename, $fileResize, $ext, $newHeight = self::NEW_HEIGHT)
    {
        list($width, $height) = getimagesize($filename);
        if ($newHeight >= $height) {
            copy($filename, $fileResize);
        }
        $newWidth = $newHeight * 3 / 2;

        $thumb = imagecreatetruecolor($newWidth, $newHeight);

        switch ($ext) {
            case 'jpg':
                $source = imagecreatefromjpeg($filename);
                imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagejpeg($thumb, $fileResize);
                break;
            case 'png':
                $source = imagecreatefrompng($filename);
                imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagepng($thumb, $fileResize);
                break;
        }
    }

    public function remove($filename)
    {
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    /**
     * @return mixed
     */
    public function getPathDirectory()
    {
        return $this->pathDirectory;
    }

    /**
     * @param mixed $pathDirectory
     */
    public function setPathDirectory($pathDirectory)
    {
        $this->pathDirectory = $pathDirectory;
    }
}
