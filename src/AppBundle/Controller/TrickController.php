<?php

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
use AppBundle\Handler\TrickEditHandler;
use AppBundle\Handler\TrickDeleteHandler;
use AppBundle\Handler\VideoAddHandler;
use AppBundle\Handler\VideoDeleteHandler;
use AppBundle\Handler\VideoUpdateHandler;
use AppBundle\Manager\CommentManager;
use AppBundle\Manager\TrickManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param TrickManager $trickManager
     * @return Response
     */
    public function indexAction(TrickManager $trickManager)
    {
        $tricks = $trickManager->getlistTricks();
        $nbTricksMax = $trickManager->countTricks();

        return $this->render('Trick/index.html.twig', array(
            'tricks' => $tricks,
            'limit' => Trick::NB_TRICKS_PAGE,
            'nbTricksMax' => $nbTricksMax
        ));
    }

    /**
     * @Route("/listTricks", name="list_tricks")
     * @param TrickManager $trickManager
     * @return Response
     */
    public function listTricksAction(TrickManager $trickManager)
    {
        $tricks = $trickManager->getAll();
        return $this->render('Trick/listTricks.html.twig', array(
            'tricks' => $tricks
        ));
    }

    /**
     * @Route("/trick/{id}", name="trick_view")
     * @param Trick             $trick
     * @param CommentAddHandler $commentHandler
     * @return RedirectResponse|Response
     */
    public function viewAction(Trick $trick, CommentAddHandler $commentHandler)
    {
        $commentHandler->setTrick($trick);
        $commentHandler->setView('Trick/view.html.twig');
        return $commentHandler->handle(CommentType::class);
    }

    /**
     * @Route("/comments/{id}/page/{page}")
     * @param CommentManager $commentManager
     * @param Trick          $trick
     * @param                $page
     * @return Response
     */
    public function commentsAction(CommentManager $commentManager, Trick $trick, $page)
    {
        $listComments = $commentManager->getComments($trick, $page);
        return $this->render('Trick/comments.html.twig', array(
            'listComments' => $listComments
        ));
    }

    /**
     * @Route("/add", name="trick_add")
     * @param TrickAddHandler $trickHandler
     * @return RedirectResponse|Response
     */
    public function addAction(TrickAddHandler $trickHandler)
    {
        $trickHandler->setView('Trick/add.html.twig');
        return $trickHandler->handle(TrickAddType::class);
    }

    /**
     * @Route("/edit/{id}", name="trick_edit")
     * @param Trick            $trick
     * @param TrickEditHandler $trickHandler
     * @return RedirectResponse|Response
     */
    public function editAction(Trick $trick, TrickEditHandler $trickHandler)
    {
        $trickHandler->setView('Trick/edit.html.twig');
        return $trickHandler->handle(TrickEditType::class, $trick);
    }

    /**
     * @Route("/admin/delete/{id}", name="trick_delete")
     * @param Trick              $trick
     * @param TrickDeleteHandler $trickHandler
     * @return Response
     */
    public function deleteAction(Trick $trick, TrickDeleteHandler $trickHandler)
    {
        $trickHandler->setView('Trick/delete.html.twig');
        return $trickHandler->handle($trick);
    }

    /**
     * @Route("/admin/add_image/{id}", name="add_image")
     * @param Trick           $trick
     * @param ImageAddHandler $imageHandler
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
     * @param Image              $image
     * @param ImageUpdateHandler $imageHandler
     * @return Response
     */
    public function updateImageAction(Image $image, ImageUpdateHandler $imageHandler)
    {
        $imageHandler->setView('Trick/update_image.html.twig');
        return $imageHandler->handle(ImageType::class, $image);
    }

    /**
     * @Route("/admin/trick/{id}/delete_image/{image_id}", name="image_delete")
     * @ParamConverter(
     *     "image", class="AppBundle:Image",
     *     options={"id" = "image_id"}
     *     )
     * @param Trick              $trick
     * @param Image              $image
     * @param ImageDeleteHandler $imageHandler
     * @return Response
     */
    public function deleteImageAction(Trick $trick, Image $image, ImageDeleteHandler $imageHandler)
    {
        $imageHandler->setTrick($trick);
        $imageHandler->setView('Trick/delete_image.html.twig');
        return $imageHandler->handle($image);
    }

    /**
     * @Route("/admin/add_video/{id}", name="add_video")
     * @param Trick           $trick
     * @param VideoAddHandler $videoHandler
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
     * @param Video              $video
     * @param VideoUpdateHandler $videoHandler
     * @return Response
     */
    public function updateVideoAction(Video $video, VideoUpdateHandler $videoHandler)
    {
        $videoHandler->setView('Trick/update_video.html.twig');
        return $videoHandler->handle(VideoType ::class, $video);
    }

    /**
     * @Route("/admin/trick/{id}/delete_video/{video_id}", name="delete_video")
     * @ParamConverter(
     *     "video", class="AppBundle:Video", options={"id" = "video_id"})
     * @param Trick              $trick
     * @param Video              $video
     * @param VideoDeleteHandler $videoHandler
     * @return RedirectResponse|Response
     */
    public function deleteVideoAction(Trick $trick, Video $video, VideoDeleteHandler $videoHandler)
    {
        $videoHandler->setTrick($trick);
        $videoHandler->setView('Trick/delete_video.html.twig');
        return $videoHandler->handle($video);
    }
}
