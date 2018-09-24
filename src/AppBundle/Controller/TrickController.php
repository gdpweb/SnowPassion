<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/delete/{id}", name="delete")
     * @param EntityManagerInterface $em
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(EntityManagerInterface $em, $id)
    {
        $trick = $em->getRepository('AppBundle:Trick')->find($id);;

        return $this->render('Trick/delete.html.twig', array(
            'trick' => $trick
        ));
    }

    /**
     * @Route("/delete_check/{id}", name="delete_check")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteCheckAction(Request $request, EntityManagerInterface $em, $id)
    {
        $trick = $em->getRepository('AppBundle:Trick')->find($id);

        if($request->isMethod('POST')){
            $em->remove($trick);
            $em->flush();
            $this->addFlash('success','La figure de snowboard a été supprimé');
        }
       return $this->redirectToRoute('homepage');
    }
}
