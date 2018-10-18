<?php
/**
 * Created by PhpStorm.
 * User: brieres
 * Date: 10/10/2018
 * Time: 18:10
 */

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

class SPHandler
{
    public function __construct(FormFactoryInterface $formFactory, RequestStack $requestStack,
                                RouterInterface $router, FlashBagInterface $flashBag,
                                Environment $twig, TokenStorageInterface $tokenStorage)
    {
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
        $this->router = $router;
        $this->flashBag = $flashBag;
        $this->twig = $twig;
        $this->setAuthor($tokenStorage);
    }

    /**
     * @var FormFactoryInterface
     */
    public $formFactory;
    /**
     * @var RequestStack
     */
    public $requestStack;
    /**
     * @var RouterInterface
     */
    public $router;
    /**
     * @var Environment
     */
    public $twig;
    /**
     * @var FlashBagInterface
     */
    public $flashBag;

    /**
     * @var User
     */
    private $author;
    /**
     * @var FormInterface
     */
    public $form;

    /**
     * @param $formType
     * @param $entity
     * @return bool
     */
    public function isSubmitted($formType, $entity)
    {

        $this->form = $this->formFactory->create($formType, $entity);
        $this->form->handleRequest($this->requestStack->getCurrentRequest());

        if ($this->form->isSubmitted() and $this->form->isValid()) {
            return true;
        }
        return false;
    }

    public function setFlash($type, $message)
    {
        $this->flashBag->add($type, $message);
    }

    public function generateRoute($name)
    {
        return $this->router->generate($name);
    }

    public function formData()
    {
        return $this->form->getData();
    }

    /**
     * @param $view
     * @param $datas
     * @return Response
     */
    public function response($view, $datas)
    {
        return new Response($this->twig->render($view, ["form" => $this->form->createView()] + $datas));
    }

    public function redirect($name, $parameters = array())
    {
        return new RedirectResponse($this->router->generate($name, $parameters));
    }

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function setAuthor(TokenStorageInterface $tokenStorage)
    {
        $this->author = $tokenStorage->getToken()->getUser();
    }

    public function getAuthor()
    {
        return $this->author;
    }

}