<?php
/**
 * Created by PhpStorm.
 * User: brieres
 * Date: 09/10/2018
 * Time: 22:17
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
        $this->commentManager->createComment($this->handler->formData(), $this->trick, $this->handler->getUser());
        $this->handler->setFlash('success', 'Le commentaire a été sauvegardé!');
        return $this->handler->redirect('trick_view', array(
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
        $listComments = $this->commentManager->getComments($this->trick);
        $nbPages = $this->commentManager->getNbPages($listComments);

        return $this->handler->response($this->view, array(
            "trick" => $this->trick,
            'listComments' => $listComments,
            'nbPages' => $nbPages
        ));
    }

    /**
     * @param $formType
     * @param null $comment
     * @param string $method
     * @return Response
     */
    public function handle($formType, $comment = null, $method = 'onSuccess')
    {
        $this->comment = $comment;
        if ($this->handler->isSubmitted($formType, $this->comment)) {
            if (is_callable([$this, $method])) {
                return $this->$method();
            }
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
