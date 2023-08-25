<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/our-cases/{slug}', name: 'app_case_page')]
class CasePageController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('site/case_page/case_page.html.twig');
    }
}
