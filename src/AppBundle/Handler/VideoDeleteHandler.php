<?php
/**
 * Created by PhpStorm.
 * User: brieres
 * Date: 18/10/2018
 * Time: 22:15
 */

namespace AppBundle\Handler;

use AppBundle\Entity\Trick;
use AppBundle\Service\SPHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class VideoDeleteHandler
{
    private $em;
    private $view;
    private $handler;
    private $video;

    /**
     * @var Trick
     */
    private $trick;

    /**
     * VideoUpdateHandler constructor.
     * @param SPHandler $handler
     * @param EntityManagerInterface $em
     */
    public function __construct(SPHandler $handler, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->handler = $handler;
    }

    /**
     * @return RedirectResponse
     */
    public function onSuccess()
    {
        $this->em->getRepository('AppBundle:Video');
        $this->em->remove($this->video);
        $this->em->flush();
        $this->handler->setFlash('success', 'La video a été supprimée');
        return $this->handler->redirect('trick_edit', array(
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
        return $this->handler->response($this->view, array(
            'video' => $this->video,
            'trick' => $this->trick
        ));
    }

    /**
     * @param $video
     * @return RedirectResponse|Response
     */
    public function handle($video = null)
    {
        $this->video = $video;

        if ($this->handler->isSubmitted(null, $video)) {
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
