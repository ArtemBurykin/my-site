<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@EasyAdmin/page/login.html.twig', [
            'error' => $error,
            'csrf_token_intention' => 'authenticate',
            'last_username' => $lastUsername,
            'target_path' => $this->generateUrl('admin'),
            'forgot_password_enabled' => true,
            'forgot_password_path' => $this->generateUrl('app_forgot_password_request'),
            'forgot_password_label' => 'Forgot your password?',
        ]);
    }
}
