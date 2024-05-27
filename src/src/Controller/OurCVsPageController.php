<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/team', name: 'app_our_cvs_page')]
class OurCVsPageController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('site/our_cvs_page/our_cvs_page.html.twig');
    }
}
