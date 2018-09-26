<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Image;
use AppBundle\Entity\Trick;
use AppBundle\Form\CommentType;
use AppBundle\Form\TrickAddType;
use AppBundle\Form\TrickEditType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends Controller
{

    /**
     * @Route("/", name="homepage")
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(EntityManagerInterface $em)
    {
        $tricks = $em->getRepository('AppBundle:Trick')->findAll();;
        return $this->render('Trick/index.html.twig', array(
            'tricks' => $tricks
        ));
    }

    /**
     * @Route("/trick/{id}", name="trick_view")
     * @ParamConverter("trick", class="AppBundle:Trick")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Trick $trick
     * @param Image $image
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request, EntityManagerInterface $em, Trick $trick)
    {
        $comment = new Comment();
        $form = $this->get('form.factory')->create(CommentType::class, $comment);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $comment->setAuteur($this->getUser());
            $trick->addComment($comment);

            $em->flush();

            $this->addFlash(
                'success', 'Le commentaire a été sauvegardé'
            );
            return $this->redirectToRoute('trick_view', array('id' => $trick->getId()));

        }
        return $this->render('Trick/view.html.twig', array(
            'trick' => $trick,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/add", name="trick_add")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request, EntityManagerInterface $em)
    {
        $trick = new Trick();
        $trick->setAuteur($this->getUser());
        $form = $this->get('form.factory')->create(TrickAddType::class, $trick);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            forEach ($trick->getImages() as $image) {
                $image->setPath($this->getParameter('trick_directory'));
            }
            $trick->setPublie(true);
            $em->persist($trick);
            $em->flush();

            $this->addFlash(
                'success', 'La figure a été sauvegardée'
            );
            return $this->redirectToRoute('trick_edit', array('id' => $trick->getId()));
        }

        return $this->render('Trick/add.html.twig', array(
            'form' => $form->createView(),
            'trick' => $trick
        ));
    }

    /**
     * @Route("/edit/{id}", name="trick_edit")
     * @ParamConverter("trick", class="AppBundle:Trick")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Trick $trick
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, EntityManagerInterface $em, Trick $trick)
    {
        $form = $this->get('form.factory')->create(TrickEditType ::class, $trick);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            forEach ($trick->getImages() as $image) {
                $image->setPath($this->getParameter('trick_directory'));
            }
            $trick->setPublie(true);

            $em->persist($trick);
            $em->flush();

            $this->addFlash(
                'success', 'La figure a été sauvegardée'
            );
            return $this->redirectToRoute('trick_edit', array('id' => $trick->getId()));
        }
        return $this->render('Trick/edit.html.twig', array(
            'form' => $form->createView(),
            'trick' => $trick
        ));
    }

    /**
     * @Route("/admin/delete/{id}", name="trick_delete")
     * @ParamConverter("trick", class="AppBundle:Trick")
     * @param Trick $trick
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Trick $trick)
    {
        return $this->render('Trick/delete.html.twig', array(
            'trick' => $trick
        ));

    }

    /**
     * @Route("/admin/delete/{id}/check", name="trick_delete_check")
     * @ParamConverter("trick", class="AppBundle:Trick")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Trick $trick
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteCheckAction(Request $request, EntityManagerInterface $em, Trick $trick)
    {
        if ($request->isMethod('POST')) {

            forEach ($trick->getImages() as $image) {
                $image->setPath($this->getParameter('trick_directory'));
                $em->remove($image);
            }
            $em->remove($trick);
            $em->flush();
            $this->addFlash('success', 'La figure de snowboard a été supprimé');
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/admin/trick/{trick_id}/image/delete/{image_id}", name="image_delete")
     * @ParamConverter("trick", class="AppBundle:Trick", options={"id" = "trick_id"})
     * @ParamConverter("image", class="AppBundle:Image", options={"id" = "image_id"})
     * @param Trick $trick
     * @param Image $image
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteImageAction(Trick $trick, Image $image)
    {
        return $this->render('Trick/delete_image.html.twig', array(
            'trick' => $trick,
            'image' => $image
        ));
    }

    /**
     * @Route("/admin/trick/{trick_id}/image/delete/check/{image_id}", name="image_delete_check")
     * @ParamConverter("trick", class="AppBundle:Trick", options={"id" = "trick_id"})
     * @ParamConverter("image", class="AppBundle:Image", options={"id" = "image_id"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Trick $trick
     * @param Image $image
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteImageCheckAction(Request $request, EntityManagerInterface $em, Trick $trick, Image $image)
    {
        if ($request->isMethod('POST')) {

            $image->setPath($this->getParameter('trick_directory'));
            $trick->removeImage($image);
            $em->remove($image);
            $em->flush();

            $this->addFlash(
                'success', 'L\'image a été supprimée'

            );
        }
        return $this->redirectToRoute('trick_edit', array('id' => $trick->getId()));
    }

    /**
     * @Route("/comments/{id}/limit/{limit}")
     * @ParamConverter("trick", class="AppBundle:Trick")
     */
    public function commentsAction(EntityManagerInterface $em, Trick $trick, $limit = 0)
    {
        $comments = $em->getRepository('AppBundle:Comment')->findBy(
            array('trick' => $trick),
            array('date' => 'Desc'),
            $limit,
            0
        );
        return $this->render('Trick/comments.html.twig', array(
            'comments' => $comments
        ));
    }

}
