<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render('Security/login.html.twig', [
            'last_username' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/forgot", name="forgot")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function forgotAction(
        Request $request,
        EntityManagerInterface $em,
        UserManager $userManager
    ) {
        if ($request->isMethod('POST')) {
            $username = $request->get('_username');
            $user = $em->getRepository('AppBundle:User')
                ->findOneBy(['username' => $username]);

            if (null === $user) {
                $this->addFlash('danger',
                    'Ce nom d\'utilisateur n\'hésite pas!');

                return $this->redirectToRoute('forgot');
            }

            $userManager->resetMail($user);

            $this->addFlash(
                'success',
                'Votre demande a été enregistrée. Consultez 
                vos mails '.$user->getUsername().'!'
            );

            return $this->redirectToRoute('homepage');
        }

        return $this->render('Security/forgot.html.twig');
    }

    /**
     * @Route("/login_check", name="login_check")
     *
     * @throws \Exception
     */
    public function loginCheckAction()
    {
        throw new \Exception('Action impossible!');
    }

    /**
     * @Route("/connexion/logout", name="logout")
     */
    public function logoutAction()
    {
    }
}
