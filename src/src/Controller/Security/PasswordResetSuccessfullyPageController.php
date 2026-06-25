<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reset-password/successful', name: 'app_password_reset_successfully')]
class PasswordResetSuccessfullyPageController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('security/features/reset_password/password_reset_successfully.html.twig');
    }
}
