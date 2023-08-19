<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OurCVsPageController extends AbstractController
{
    #[Route('/our-cvs', name: 'app_our_cvs_page')]
    public function index(): Response
    {
        return $this->render('site/our_cvs_page/our_cvs_page.html.twig');
    }
}
