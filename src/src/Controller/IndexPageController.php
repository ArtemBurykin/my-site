<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_index_page')]
class IndexPageController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('site/index_page/index_page.html.twig');
    }
}
