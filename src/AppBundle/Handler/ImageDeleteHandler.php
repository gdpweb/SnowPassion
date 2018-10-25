<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Handler;

use AppBundle\Entity\Trick;
use AppBundle\Manager\ImageManager;
use AppBundle\Service\SPHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ImageDeleteHandler
{
    private $imageManager;
    /**
     * @var Trick
     */
    private $trick;
    private $image;
    private $view;
    private $handler;

    public function __construct(SPHandler $handler, ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
        $this->handler = $handler;
    }

    /**
     * @return RedirectResponse
     */
    public function onSuccess()
    {
        $this->imageManager->deleteImageTrick($this->image);
        $this->handler->setFlash('success', 'L\'image a été supprimée');

        return $this->handler->redirect('trick_edit', [
            'id' => $this->trick->getId(),
        ]);
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
            'trick' => $this->trick,
        ]);
    }

    /**
     * @return Response
     */
    public function handle($image = null)
    {
        $this->image = $image;

        if ($this->handler->isSubmitted(null, $this->image)) {
            return $this->onSuccess();
        }

        return $this->getView();
    }

    public function setTrick(Trick $trick)
    {
        $this->trick = $trick;
    }
}
