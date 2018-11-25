<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\Trick;
use AppBundle\Entity\Video;
use AppBundle\Form\CommentType;
use AppBundle\Form\ImageType;
use AppBundle\Form\TrickAddType;
use AppBundle\Form\TrickEditType;
use AppBundle\Form\VideoType;
use AppBundle\Handler\CommentAddHandler;
use AppBundle\Handler\ImageAddHandler;
use AppBundle\Handler\ImageDeleteHandler;
use AppBundle\Handler\ImageUpdateHandler;
use AppBundle\Handler\TrickAddHandler;
use AppBundle\Handler\TrickDeleteHandler;
use AppBundle\Handler\TrickEditHandler;
use AppBundle\Handler\VideoAddHandler;
use AppBundle\Handler\VideoDeleteHandler;
use AppBundle\Handler\VideoUpdateHandler;
use AppBundle\Manager\CommentManager;
use AppBundle\Manager\TrickManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @return Response
     */
    public function indexAction(TrickManager $trickManager)
    {
        $tricks = $trickManager->getlistTricks();
        $nbTricksMax = $trickManager->countTricks();

        return $this->render('Trick/index.html.twig', [
            'tricks' => $tricks,
            'limit' => Trick::NB_TRICKS_PAGE,
            'nbTricksMax' => $nbTricksMax,
        ]);
    }

    /**
     * @Route("/listTricks", name="list_tricks")
     *
     * @return Response
     */
    public function listTricksAction(TrickManager $trickManager)
    {
        $tricks = $trickManager->getAll();

        return $this->render('Trick/listTricks.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    /**
     * @Route("/trick/{slug}", name="trick_view")
     *
     * @return RedirectResponse|Response
     */
    public function viewAction(Trick $trick, CommentAddHandler $commentHandler)
    {
        $commentHandler->setTrick($trick);
        $commentHandler->setView('Trick/view.html.twig');

        return $commentHandler->handle(CommentType::class);
    }

    /**
     * @Route("/comments/{slug}/page/{page}", name="trick_list_comments")
     *
     * @param $page
     *
     * @return Response
     */
    public function listCommentsAction(CommentManager $commentManager, Trick $trick, $page)
    {
        $listComments = $commentManager->getComments($trick, $page);

        return $this->render('Trick/comments.html.twig', [
            'listComments' => $listComments,
        ]);
    }

    /**
     * @Route("/admin/add", name="trick_add")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @return RedirectResponse|Response
     */
    public function addAction(TrickAddHandler $trickHandler)
    {
        $trickHandler->setView('Trick/add.html.twig');

        return $trickHandler->handle(TrickAddType::class);
    }

    /**
     * @Route("/admin/edit/{slug}", name="trick_edit")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Trick $trick, TrickEditHandler $trickHandler)
    {
        $trickHandler->setView('Trick/edit.html.twig');

        return $trickHandler->handle(TrickEditType::class, $trick);
    }

    /**
     * @Route("/admin/delete/{slug}", name="trick_delete")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function deleteAction(Trick $trick, TrickDeleteHandler $trickHandler)
    {
        $trickHandler->setView('Trick/delete.html.twig');

        return $trickHandler->handle($trick);
    }

    /**
     * @Route("/admin/add_image/{slug}", name="add_image")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function addImageAction(Trick $trick, ImageAddHandler $imageHandler)
    {
        $imageHandler->setTrick($trick);
        $imageHandler->setView('Trick/add_image.html.twig');

        return $imageHandler->handle(ImageType::class);
    }

    /**
     * @Route("/admin/update_image/{id}", name="update_image")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function updateImageAction(Image $image, ImageUpdateHandler $imageHandler)
    {
        $imageHandler->setView('Trick/update_image.html.twig');

        return $imageHandler->handle(ImageType::class, $image);
    }

    /**
     * @Route("/admin/trick/{slug}/delete_image/{image_id}", name="image_delete")
     * @ParamConverter(
     *     "image", class="AppBundle:Image",
     *     options={"id" = "image_id"}
     *     )
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function deleteImageAction(Trick $trick, Image $image, ImageDeleteHandler $imageHandler)
    {
        $imageHandler->setTrick($trick);
        $imageHandler->setView('Trick/delete_image.html.twig');

        return $imageHandler->handle($image);
    }

    /**
     * @Route("/admin/add_video/{slug}", name="add_video")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function addVideoAction(Trick $trick, VideoAddHandler $videoHandler)
    {
        $videoHandler->setTrick($trick);
        $videoHandler->setView('Trick/add_video.html.twig');

        return $videoHandler->handle(VideoType ::class);
    }

    /**
     * @Route("/admin/update_video/{id}", name="update_video")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function updateVideoAction(Video $video, VideoUpdateHandler $videoHandler)
    {
        $videoHandler->setView('Trick/update_video.html.twig');

        return $videoHandler->handle(VideoType ::class, $video);
    }

    /**
     * @Route("/admin/trick/{slug}/delete_video/{video_id}", name="delete_video")
     * @ParamConverter(
     *     "video", class="AppBundle:Video", options={"id" = "video_id"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @return RedirectResponse|Response
     */
    public function deleteVideoAction(Trick $trick, Video $video, VideoDeleteHandler $videoHandler)
    {
        $videoHandler->setTrick($trick);
        $videoHandler->setView('Trick/delete_video.html.twig');

        return $videoHandler->handle($video);
    }
}
