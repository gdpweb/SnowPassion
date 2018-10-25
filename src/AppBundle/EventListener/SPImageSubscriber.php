<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Image;
use AppBundle\Service\SPFileSystem;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SPImageSubscriber implements EventSubscriber
{
    private $fileSystem;
    private $targetDirectory;

    public function __construct(SPFileSystem $fileSystem, $target_directory)
    {
        $this->fileSystem = $fileSystem;
        $this->targetDirectory = $target_directory;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
            Events::postPersist,
            Events::preRemove,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Image) {
            return;
        }

        $this->setFileUpload($entity);
    }

    public function postUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Image) {
            return;
        }

        $this->setFileUpload($entity);
        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Image) {
            return;
        }

        $this->setFileUpload($entity);
        $this->uploadFile($entity);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Image) {
            return;
        }
        $directory = $this->targetDirectory.$entity->getType();
        $filename = $directory.'/'.$entity->getId().'.'.$entity->getExt();
        $fileResize = $directory.'/mini/'.$entity->getId().'.'.$entity->getExt();
        $this->fileSystem->remove($filename);
        $this->fileSystem->remove($fileResize);
    }

    public function setFileUpload(Image $entity)
    {
        $file = $entity->getFile();
        if ($file instanceof UploadedFile) {
            $this->fileSystem->setPathDirectory($this->targetDirectory.$entity->getType());
            $entity->setExt($file->getClientOriginalExtension());
            $entity->setAlt(basename($file->getClientOriginalName(), '.'.$entity->getExt()));
        }
        if ($file instanceof File) {
            $this->fileSystem->setPathDirectory($this->targetDirectory.$entity->getType());
        }
    }

    public function uploadFile($entity)
    {
        if (!$entity instanceof Image) {
            return;
        }
        $file = $entity->getFile();

        if ($file instanceof UploadedFile | $file instanceof File) {
            $this->fileSystem->upload(
                $file,
                $entity->getId().'.'.$entity->getExt()
            );
        }

        $directory = $this->targetDirectory.$entity->getType();
        $filename = $directory.'/'.$entity->getId().'.'.$entity->getExt();
        $fileResize = $directory.'/mini/'.$entity->getId().'.'.$entity->getExt();

        $this->fileSystem->resizeThumbnail($filename, $fileResize, $entity->getExt());
    }
}
