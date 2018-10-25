<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var User
     */
    private $user;
    /**
     * @var FormInterface
     */
    private $form;

    public function __construct(
        FormFactoryInterface $formFactory,
        RequestStack $requestStack,
        RouterInterface $router,
        FlashBagInterface $flashBag,
        Environment $twig,
        TokenStorageInterface $tokenStorage
    ) {
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
        $this->router = $router;
        $this->flashBag = $flashBag;
        $this->twig = $twig;
        $this->setUser($tokenStorage);
    }

    /**
     * @return bool
     */
    public function isSubmitted($formType = null, $entity = null)
    {
        if (null === $formType) {
            $this->form = $this->formFactory->create();
        }
        if (null !== $formType) {
            $this->form = $this->formFactory->create($formType, $entity);
        }

        $this->form->handleRequest($this->requestStack->getCurrentRequest());

        if ($this->form->isSubmitted() and $this->form->isValid()) {
            return true;
        }

        return false;
    }

    /**
     * @param $type
     * @param $message
     */
    public function setFlash($type, $message)
    {
        $this->flashBag->add($type, $message);
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function generateRoute($name)
    {
        return $this->router->generate($name);
    }

    /**
     * @return mixed
     */
    public function formData()
    {
        return $this->form->getData();
    }

    /**
     * @param $view
     * @param $datas
     *
     * @return Response
     */
    public function response($view, $datas)
    {
        return new Response($this->twig->render(
            $view,
            ['form' => $this->form->createView()] + $datas
        ));
    }

    /**
     * @param       $name
     * @param array $parameters
     *
     * @return RedirectResponse
     */
    public function redirect($name, $parameters = [])
    {
        return new RedirectResponse($this->router->generate($name, $parameters));
    }

    public function setUser(TokenStorageInterface $tokenStorage)
    {
        $this->user = $tokenStorage->getToken()->getUser();
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
