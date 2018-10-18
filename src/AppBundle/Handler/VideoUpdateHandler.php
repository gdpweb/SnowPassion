<?php
/**
 * Created by PhpStorm.
 * User: brieres
 * Date: 09/10/2018
 * Time: 22:15
 */

namespace AppBundle\Handler;

use AppBundle\Service\SPHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;


class VideoUpdateHandler
{
    private $em;
    private $view;
    private $handler;
    private $video;

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

    public function onSuccess()
    {
        $this->em->getRepository('AppBundle:Video');
        $this->em->persist($this->handler->formData());
        $this->em->flush();
        $this->handler->setFlash('success', 'La video a été modifiée');
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
                "video" => $this->video
            )
        );
    }

    /**
     * @param $formType
     * @param $video
     * @return RedirectResponse|Response
     */
    public function handle($formType, $video)
    {
        $this->video = $video;

        if ($this->handler->isSubmitted($formType,$video)) {
           $this->onSuccess();
        }
        return $this->getView();
    }
}