<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserResetType;
use AppBundle\Form\UserRegisterType;
use AppBundle\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @param UserManager $userManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request, UserManager $userManager)
    {
        $form = $this->createForm(UserRegisterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userManager->registerMail($form->getData());
            $this->addFlash(
                'info', 'Votre compte a été créé. 
            Utiliser le lien qui vous a été envoyé par mail pour valider votre inscription.
            Le lien reste actif 20 minutes.'
            );
            return $this->redirectToRoute('homepage');
        }
        return $this->render('User/register.html.twig', array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/reset/{token}", name="reset")
     * @param Request $request
     * @param UserManager $userManager
     * @param $token
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function resetAction(Request $request, UserManager $userManager, $token)
    {
        $user = $userManager->tokenValid($token);

        if ($user === null) {
            $this->addFlash(
                'danger', 'Ce lien a expiré'
            );
            return $this->redirectToRoute('homepage');
        }
        $form = $this->createForm(UserResetType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userManager->activeAccount($form->getData());

            $this->addFlash(
                'success', 'Votre mot de passe a été réinitialisé.'
            );
            return $this->redirectToRoute('homepage');
        }
        return $this->render('User/reset.html.twig', array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/validate/{token}", name="validate_account")
     * @param UserManager $userManager
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Entity("User", expr="repository.tokenIsValid(token)")
     */
    public function validateAccountAction(UserManager $userManager, User $user)
    {
        if ($user !== null) {
            $userManager->activeAccount($user);
            $this->addFlash('info', 'Votre compte est activé.');
        }
        if ($user === null) {
            $this->addFlash('danger', 'Désolé, Ce lien a expiré, votre compte n\'a pu être activé');
        }
        return $this->redirectToRoute('homepage');
    }
}
