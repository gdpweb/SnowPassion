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
use AppBundle\Handler\CommentHandler;
use AppBundle\Handler\TrickHandler;
use AppBundle\Manager\CommentManager;
use AppBundle\Manager\ImageManager;
use AppBundle\Manager\TrickManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends Controller
{

    /**
     * @Route("/", name="homepage")
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function indexAction(EntityManagerInterface $em)
    {   //faire une method dans repository
        $tricks = $em->getRepository('AppBundle:Trick')->findAll();

        return $this->render('Trick/index.html.twig', array(
            'tricks' => $tricks
        ));
    }

    /**
     * @Route("/trick/{id}", name="trick_view")
     * @param Trick $trick
     * @param CommentHandler $commentHandler
     * @return RedirectResponse|Response
     */
    public function viewAction(Trick $trick, CommentHandler $commentHandler)
    {
        $commentHandler->setTrick($trick);
        $commentHandler->setView('Trick/view.html.twig');
        return $commentHandler->handle(CommentType::class);
    }

    /**
     * @Route("/comments/{id}/page/{page}")
     * @param CommentManager $commentManager
     * @param Trick $trick
     * @param $page
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
     * @param TrickHandler $trickHandler
     * @return RedirectResponse|Response
     */
    public function addAction(TrickHandler $trickHandler)
    {
        $trickHandler->setView('Trick/add.html.twig');
        return $trickHandler->handle(TrickAddType::class);
    }

    /**
     * @Route("/edit/{id}", name="trick_edit")
     * @param Trick $trick
     * @param TrickHandler $trickHandler
     * @return RedirectResponse|Response
     */
    public function editAction(Trick $trick, TrickHandler $trickHandler)
    {
        $trickHandler->setView('Trick/edit.html.twig');
        return $trickHandler->handle(TrickEditType::class, $trick);
    }

    /**
     * @Route("/admin/delete/{id}", name="trick_delete")
     * @param Trick $trick
     * @param TrickHandler $trickHandler
     * @return Response
     */
    public function deleteAction(Trick $trick, TrickHandler $trickHandler)
    {
        $trickHandler->setView('Trick/delete.html.twig');
        return $trickHandler->handle(TrickEditType::class, $trick,'remove');
    }

    /**
     * @Route("/admin/add_image/{id}", name="add_image")
     * @param Request $request
     * @param Trick $trick
     * @param TrickManager $trickManager
     * @return Response
     */
    public function addImageAction(Request $request, Trick $trick, TrickManager $trickManager)
    {
        $form = $this->createForm(ImageType ::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $trickManager->addImage($trick, $form->getData());

            $this->addFlash('success', 'L\'image a été ajoutée');
        }

        return $this->render('Trick/add_image.html.twig', array(
            'form' => $form->createView(),
            'trick' => $trick
        ));
    }

    /**
     * @Route("/admin/update_image/{id}", name="update_image")
     * @param Request $request
     * @param Image $image
     * @param ImageManager $imageManager
     * @return Response
     */
    public function updateImageAction(Request $request, Image $image, ImageManager $imageManager)
    {
        $form = $this->createForm(ImageType ::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageManager->updateImageTrick($image);
            $this->addFlash('success', 'L\'image a été modifiée');
        }

        return $this->render('Trick/update_image.html.twig', array(
            'form' => $form->createView(),
            'image' => $image
        ));
    }

    /**
     * @Route("/admin/trick/{id}/delete_image/{image_id}", name="image_delete")
     * @ParamConverter("image", class="AppBundle:Image", options={"id" = "image_id"})
     * @param Request $request
     * @param Trick $trick
     * @param ImageManager $imageManager
     * @param Image $image
     * @return Response
     */
    public function deleteImageAction(Request $request, Trick $trick, ImageManager $imageManager, Image $image)
    {
        $form = $this->get('form.factory')->create();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageManager->deleteImageTrick($image);
            $this->addFlash('success', 'L\'image a été supprimée');

            return $this->redirectToRoute('trick_edit', array('id' => $trick->getId()));
        }
        return $this->render('Trick/delete_image.html.twig', array(
            'trick' => $trick,
            'image' => $image,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/admin/add_video/{id}", name="add_video")
     * @param Request $request
     * @param Trick $trick
     * @param TrickManager $trickManager
     * @return Response
     */
    public function addVideoAction(Request $request, Trick $trick, TrickManager $trickManager)
    {
        $form = $this->createForm(VideoType ::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $trickManager->addVideo($trick, $form->getData());
            $this->addFlash('success', 'La video a été ajoutée');

        }
        return $this->render('Trick/add_video.html.twig', array(
            'form' => $form->createView(),
            'trick' => $trick
        ));
    }


    /**
     * @Route("/admin/update_video/{id}", name="update_video")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Video $video
     * @return Response
     */
    public function updateVideoAction(Request $request, EntityManagerInterface $em, Video $video)
    {
        $form = $this->createForm(VideoType ::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->getRepository('AppBundle:Video');
            $em->persist($form->getData());
            $em->flush();

            $this->addFlash('success', 'La video a été modifiée');
        }

        return $this->render('Trick/update_video.html.twig', array(
            'form' => $form->createView(),
            'video' => $video
        ));
    }

    /**
     * @Route("/admin/trick/{id}/delete_video/{video_id}", name="delete_video")
     * @ParamConverter("video", class="AppBundle:Video", options={"id" = "video_id"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Trick $trick
     * @param Video $video
     * @return RedirectResponse|Response
     */

    public function deleteVideoAction(Request $request, EntityManagerInterface $em, Trick $trick, Video $video)
    {
        $form = $this->get('form.factory')->create();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->getRepository('AppBundle:Video');
            $em->remove($video);
            $em->flush();

            $this->addFlash('success', 'La video a été supprimée');

            return $this->redirectToRoute('trick_edit', array('id' => $trick->getId()));
        }
        return $this->render('Trick/delete_video.html.twig', array(
            'trick' => $trick,
            'video' => $video,
            'form' => $form->createView()
        ));
    }


}
