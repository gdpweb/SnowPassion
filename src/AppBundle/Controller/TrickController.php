<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\Trick;
use AppBundle\Form\CommentType;
use AppBundle\Form\TrickAddType;
use AppBundle\Form\TrickEditType;
use AppBundle\Manager\CommentManager;
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
     * @param Request $request
     * @param CommentManager $commentManager
     * @param Trick $trick
     * @return RedirectResponse|Response
     */
    public function viewAction(Request $request, CommentManager $commentManager, Trick $trick)
    {
        $listComments = $commentManager->getComments($trick);

        $nbPages = $commentManager->getNbPages($listComments);

        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $commentManager->createComment($form->getData(), $trick, $this->getUser());

            $this->addFlash(
                'success', 'Le commentaire a été sauvegardé'
            );
            return $this->redirectToRoute('trick_view', array(
                'id' => $trick->getId()
            ));
        }
        return $this->render('Trick/view.html.twig', array(
            'trick' => $trick,
            'form' => $form->createView(),
            'listComments' => $listComments,
            'nbPages' => $nbPages
        ));
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
     * @param Request $request
     * @param TrickManager $trickManager
     * @return RedirectResponse|Response
     */
    public function addAction(Request $request, TrickManager $trickManager)
    {
        $form = $this->createForm(TrickAddType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $trickManager->createTrick($form->getData(), $this->getUser());
            $this->addFlash(
                'success', 'La figure a été sauvegardée'
            );
            return $this->redirectToRoute('homepage');
        }
        return $this->render('Trick/add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/edit/{id}", name="trick_edit")
     * @param Request $request
     * @param TrickManager $trickManager
     * @param Trick $trick
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, TrickManager $trickManager, Trick $trick)
    {
        $form = $this->createForm(TrickEditType ::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $trickManager->updateTrick($form->getData());
            $this->addFlash(
                'success', 'La figure a été sauvegardée'
            );
            return $this->redirectToRoute('homepage');
        }
        return $this->render('Trick/edit.html.twig', array(
            'form' => $form->createView(),
            'trick' => $trick
        ));
    }

    /**
     * @Route("/admin/delete/{id}", name="trick_delete")
     * @param Trick $trick
     * @return Response
     */
    public function deleteAction(Trick $trick)
    {
        return $this->render('Trick/delete.html.twig', array(
            'trick' => $trick
        ));
    }

    /**
     * @Route("/admin/delete/{id}/check", name="trick_delete_check")
     * @param Request $request
     * @param TrickManager $trickManager
     * @param Trick $trick
     * @return RedirectResponse
     */
    public function deleteCheckAction(Request $request, TrickManager $trickManager, Trick $trick)
    {
        if ($request->isMethod('POST')) {

            $trickManager->deleteTrick($trick);
            $this->addFlash('success', 'La figure de snowboard a été supprimé');
        }
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/admin/trick/{id}/delete/{image_id}", name="image_delete")
     * @ParamConverter("image", class="AppBundle:Image", options={"id" = "image_id"})
     * @param Trick $trick
     * @param Image $image
     * @return Response
     */
    public function deleteImageAction(Trick $trick, Image $image)
    {
        return $this->render('Trick/delete_image.html.twig', array(
            'trick' => $trick,
            'image' => $image
        ));
    }

    /**
     * @Route("/admin/trick/{id}/delete/check/{image_id}", name="image_delete_check")
     * @ParamConverter("image", class="AppBundle:Image", options={"id" = "image_id"})
     * @param Request $request
     * @param TrickManager $trickManager
     * @param Trick $trick
     * @param Image $image
     * @return RedirectResponse
     */
    public function deleteImageCheckAction(Request $request, TrickManager $trickManager, Trick $trick, Image $image)
    {
        if ($request->isMethod('POST')) {

            $trickManager->deleteImage($image);

            $this->addFlash(
                'success', 'L\'image a été supprimée'
            );
        }
        return $this->redirectToRoute('trick_edit', array('id' => $trick->getId()));
    }

}
