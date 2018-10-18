<?php
/**
 * Created by PhpStorm.
 * User: brieres
 * Date: 18/10/2018
 * Time: 22:15
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

        return $this->handler->redirect('trick_edit',array(
            'id' => $this->trick->getId()
        ));
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
        return $this->handler->response(
            $this->view, array(
                'image' => $this->image,
                'trick' => $this->trick
            )
        );
    }

    /**
     * @param null $image
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

    /**
     * @param Trick $trick
     */
    public function setTrick(Trick $trick)
    {
        $this->trick = $trick;
    }
}