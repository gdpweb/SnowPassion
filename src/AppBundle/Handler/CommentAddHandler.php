<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Handler;

use AppBundle\Entity\Trick;
use AppBundle\Manager\CommentManager;
use AppBundle\Service\SPHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class CommentAddHandler
{
    private $commentManager;
    private $comment;
    private $view;
    private $handler;
    /**
     * @var Trick
     */
    public $trick;

    public function __construct(SPHandler $handler, CommentManager $commentManager)
    {
        $this->commentManager = $commentManager;
        $this->handler = $handler;
    }

    /**
     * @return RedirectResponse
     */
    public function onSuccess()
    {
        $this->commentManager->createComment(
            $this->handler->formData(),
            $this->trick,
            $this->handler->getUser()
        );
        $this->handler->setFlash(
            'success',
            'Le commentaire a été sauvegardé!'
        );

        return $this->handler->redirect('trick_view', [
            'slug' => $this->trick->getSlug(),
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
        $listComments = $this->commentManager->getComments($this->trick);
        $nbPages = $this->commentManager->getNbPages($listComments);

        return $this->handler->response($this->view, [
            'trick' => $this->trick,
            'listComments' => $listComments,
            'nbPages' => $nbPages,
        ]);
    }

    /**
     * @param        $formType
     * @param string $method
     *
     * @return Response
     */
    public function handle($formType, $comment = null, $method = 'onSuccess')
    {
        $this->comment = $comment;
        if ($this->handler->isSubmitted($formType, $this->comment)) {
            if (\is_callable([$this, $method])) {
                return $this->$method();
            }
        }

        return $this->getView();
    }

    public function setTrick(Trick $trick)
    {
        $this->trick = $trick;
    }
}
