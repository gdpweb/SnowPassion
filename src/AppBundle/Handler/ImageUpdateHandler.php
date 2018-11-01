<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Handler;

use AppBundle\Entity\Image;
use AppBundle\Entity\Trick;
use AppBundle\Manager\ImageManager;
use AppBundle\Service\SPHandler;
use Symfony\Component\HttpFoundation\Response;

class ImageUpdateHandler
{
    private $imageManager;
    /**
     * @var Image
     */
    private $image;
    /**
     * @var Trick
     */
    private $trick;
    private $view;
    private $handler;

    public function __construct(SPHandler $handler, ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
        $this->handler = $handler;
    }

    public function onSuccess()
    {
        $this->imageManager->updateImageTrick($this->image);
        $this->handler->setFlash('success', 'L\'image a été modifiée');
    }

    /**
     * @param $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * @return Response
     */
    public function getView()
    {
        return $this->handler->response($this->view, [
            'image' => $this->image,
        ]);
    }

    /**
     * @param $formType
     * @param $image
     *
     * @return Response
     */
    public function handle($formType, $image)
    {
        $this->image = $image;

        if ($this->handler->isSubmitted($formType, $image)) {
            $this->onSuccess();
        }

        return $this->getView();
    }

    public function setTrick(Trick $trick)
    {
        $this->trick = $trick;
    }
}
