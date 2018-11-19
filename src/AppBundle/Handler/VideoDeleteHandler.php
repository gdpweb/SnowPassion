<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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

        return $this->handler->redirect('trick_edit', [
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
        return $this->handler->response($this->view, [
            'video' => $this->video,
            'trick' => $this->trick,
        ]);
    }

    /**
     * @param $video
     *
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

    public function setTrick(Trick $trick)
    {
        $this->trick = $trick;
    }
}
