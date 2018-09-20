<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {


        return $this->render('Tricks/index.html.twig');
    }


}
