<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="app_login")
     */
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render('@App/Security/login.html.twig',
            array(
                // last username entered by the user (if any)
                'last_username' => $helper->getLastUsername(),
                // last authentication error (if any)
                'error' => $helper->getLastAuthenticationError(),
            ));
    }

    /**
     * @Route("/forgot", name="app_forgot")
     */
    public function forgotAction(Request $request)
    {


        if ($request->isMethod('POST')) {

            $username = $request->get('_username');
            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository('AppBundle:User')->findOneBy(
                array('username' => $username)
            );
            $token = md5(uniqid(rand(), true));
            var_dump($token);

            if (null === $user) {

                $this->addFlash(
                    'danger',
                    'It\'s username is wrong!'
                );

                return $this->render('@App/Security/forgot.html.twig');

            }
            $mailer =null;
            //$mailer = $this->get('AppBundle.mailer')->resetMailer($user);

            if (null !== $mailer) {

                $this->addFlash(
                    'success',
                    'Your request has been registered. Consult your mails ' . $user->getUsername() . '!'
                );
                return $this->render('@App/Security/login.html.twig', array(
                        'last_username' => $username,
                        'error' => null)
                );
            }
        }
        return $this->render('@App/Security/forgot.html.twig');
    }

    /**
     * @Route("/login_check", name="app_login_check")
     * @throws \Exception
     */
    public function loginCheckAction()
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/logout", name="app_logout")
     * @throws \Exception
     */
    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }
}
