<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserRegisterType;
use AppBundle\Form\UserResetType;
use AppBundle\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/register", name="register")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request, UserManager $userManager)
    {
        $form = $this->createForm(UserRegisterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->registerMail($form->getData());
            $this->addFlash('info', 'Votre compte a été créé. 
            Utiliser le lien qui vous a été envoyé par mail pour valider 
            votre inscription. Le lien reste actif 20 minutes.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('User/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset/{token}", name="reset")
     *
     * @param $token
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function resetAction(Request $request, UserManager $userManager, $token)
    {
        $user = $userManager->tokenValid($token);

        if (null === $user) {
            $this->addFlash('danger', 'Ce lien a expiré');

            return $this->redirectToRoute('homepage');
        }
        $form = $this->createForm(UserResetType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->activeAccount($form->getData());
            $this->addFlash('success',
                'Votre mot de passe a été réinitialisé.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('User/reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/validate/{token}", name="validate_account")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Entity("User", expr="repository.tokenIsValid(token)")
     */
    public function validateAccountAction(UserManager $userManager, User $user)
    {
        if (null !== $user) {
            $userManager->activeAccount($user);
            $this->addFlash('info', 'Votre compte est activé.');
        }
        if (null === $user) {
            $this->addFlash(
                'danger',
                'Désolé, Ce lien a expiré, votre 
                compte n\'a pu être activé'
            );
        }

        return $this->redirectToRoute('homepage');
    }
}
