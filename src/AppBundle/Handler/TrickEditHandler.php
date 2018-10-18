<?php
/**
 * Created by PhpStorm.
 * User: brieres
 * Date: 09/10/2018
 * Time: 22:15
 */

namespace AppBundle\Handler;

use AppBundle\Manager\TrickManager;
use AppBundle\Service\SPHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;


class TrickEditHandler
{
    private $trickManager;
    private $trick;
    private $view;
    private $handler;

    public function __construct(SPHandler $handler, TrickManager $trickManager)
    {
        $this->trickManager = $trickManager;
        $this->handler = $handler;
    }

    /**
     * @return RedirectResponse
     */
    public function onSuccess()
    {
        $this->trickManager->saveTrick($this->handler->formData(), $this->handler->getUser());
        $this->handler->setFlash('success', 'La figure a été sauvegardée');
        return $this->handler->redirect('homepage');
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
                "trick" => $this->trick
            )
        );
    }

    /**
     * @param $formType
     * @param null $trick
     * @return Response
     */

    public function handle($formType, $trick = null)
    {
        $this->trick = $trick;
        if ($this->handler->isSubmitted($formType, $this->trick)) {
            return $this->onSuccess();
        }
        return $this->getView();
    }
}