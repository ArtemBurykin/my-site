<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OurCasesPageController extends AbstractController
{
    #[Route('/our-cases', name: 'app_our_cases_page')]
    public function index(): Response
    {
        return $this->render('site/our_cases_page/our_cases_page.html.twig');
    }
}
