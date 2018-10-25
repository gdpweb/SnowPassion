<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Handler;

use AppBundle\Manager\TrickManager;
use AppBundle\Service\SPHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class TrickDeleteHandler
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
        $this->trickManager->deleteTrick($this->trick);
        $this->handler->setFlash('success', 'La figure de snowboard a été supprimé');

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
        return $this->handler->response($this->view, [
            'trick' => $this->trick,
        ]);
    }

    /**
     * @return Response
     */
    public function handle($trick = null)
    {
        $this->trick = $trick;

        if ($this->handler->isSubmitted(null, $this->trick)) {
            return $this->onSuccess();
        }

        return $this->getView();
    }
}
